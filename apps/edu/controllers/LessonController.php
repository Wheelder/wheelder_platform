<?php
// LessonController — manages tech lesson CRUD, mirroring BlogController exactly.
// Kept separate so lesson data never pollutes the blogs table.
// Use __DIR__ relative path so this file works when included from any context
// (e.g. cms2/ajax.php, lessons/app.php, etc.)
require_once __DIR__ . '/Controller.php';

class LessonController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    // Redirect unauthenticated users to home — same guard used by BlogController
    public function check_auth()
    {
        if (!isset($_SESSION['user_id'])) {
            // Save the requested URL so we can redirect back after login
            $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
            // Use url() so the redirect respects the project subdirectory (e.g. /wheelder/)
            // Hardcoding '/' would send the user to Apache root on localhost, not the app home
            header('Location: ' . url('/'));
            exit;
        }
    }

    // Render sidebar links for published lessons only — drafts stay hidden from the reader
    public function titles()
    {
        $sql = "SELECT DISTINCT title, id FROM lessons WHERE status = 'published' ORDER BY id DESC";
        $stmt = $this->run_query($sql);

        if (!$stmt) {
            // DB error — fail silently in the sidebar so the page still loads
            return;
        }

        while ($row = $stmt->fetch_array()) {
            // Build a URL-safe slug from the title for the ?t= query param
            $slug = str_replace(' ', '_', strtolower($row['title']));

            echo '<div class="toc">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="?t=' . urlencode($slug) . '">
                        <span data-feather="book" class="align-text-bottom"></span>'
                        . htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') . '
                    </a>
                </li>
            </div>';
        }
    }

    // Show the newest published lesson when no title is selected, or a specific published lesson by title
    public function get_default_lesson($title = null)
    {
        if ($title === null) {
            // No title given — show the most recently published lesson
            $sql = "SELECT * FROM lessons WHERE status = 'published' ORDER BY id DESC LIMIT 1";
        } else {
            // Reconstruct the human-readable title from the URL slug
            $title = ucwords(str_replace('_', ' ', $title));
            $title = $this->connectDb()->real_escape_string($title);
            $sql = "SELECT * FROM lessons WHERE title = '$title' AND status = 'published'";
        }

        $stmt = $this->run_query($sql);

        if (!$stmt) {
            echo "<p>Error loading lesson. Please try again.</p>";
            return;
        }

        while ($row = $stmt->fetch_assoc()) {
            $content = $row['content'];

            echo "<div class='content' id='contentDiv'>";
            echo htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') !== '' ?
                "<h4>" . htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') . "</h4><br>" : '';
            // Content is stored as plain text; preserve line breaks for readability
            echo nl2br(htmlspecialchars($content, ENT_QUOTES, 'UTF-8'));
            echo "</div>";
        }
    }

    // Fetch a single lesson row by ID for the edit form
    public function get_lesson_edit($id)
    {
        // Cast to int to prevent SQL injection on this numeric parameter
        $id = (int) $id;
        if ($id <= 0) {
            return null;
        }
        $sql = "SELECT * FROM lessons WHERE id = '$id'";
        $stmt = $this->run_query($sql);
        if (!$stmt) {
            return null;
        }
        return $stmt->fetch_assoc();
    }

    // Return all lessons ordered newest first — used by the CMS list view
    public function list_lessons()
    {
        $sql = "SELECT * FROM lessons ORDER BY id DESC";
        $stmt = $this->run_query($sql);
        $lessons = [];
        if ($stmt) {
            while ($row = $stmt->fetch_assoc()) {
                $lessons[] = $row;
            }
        }
        return $lessons;
    }

    // Insert a new lesson with explicit status — classic CMS uses 'published' by default
    public function insert($title, $category, $content, $image_url = '', $code_block = '', $status = 'published')
    {
        // Whitelist status to prevent injection via this parameter
        $status = in_array($status, ['draft', 'published', 'archived'], true) ? $status : 'published';

        // Use connectDb()->prepare() with ? placeholders — AI content contains single quotes
        // and apostrophes that break string interpolation. run_query_prepared() only exists
        // in pool/libs Controller, not here, so we use the DB connection directly (same
        // pattern as Database::query() in pool/config/database.php).
        try {
            $conn = $this->connectDb();
            $sql  = "INSERT INTO lessons (title, category, content, image_url, code_block, status) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                error_log("LessonController::insert prepare failed: " . $conn->error);
                return false;
            }
            $stmt->bind_param('ssssss', $title, $category, $content, $image_url, $code_block, $status);
            $ok = $stmt->execute();
            if (!$ok) {
                error_log("LessonController::insert execute failed: " . $stmt->error);
            }
            $stmt->close();
            return $ok;
        } catch (Exception $e) {
            error_log("LessonController::insert exception: " . $e->getMessage());
            return false;
        }
    }

    // Insert an AI-generated lesson as a draft — used by cms2 so it never auto-publishes
    public function insert_draft($title, $category, $content, $image_url = '', $code_block = '')
    {
        return $this->insert($title, $category, $content, $image_url, $code_block, 'draft');
    }

    // Publish a draft lesson by ID — makes it visible on /lesson
    public function publish($id)
    {
        $id = (int) $id;
        if ($id <= 0) { return false; }
        $sql = "UPDATE lessons SET status = 'published' WHERE id = '$id'";
        $stmt = $this->run_query($sql);
        return $stmt ? true : false;
    }

    // Archive a lesson by ID — hides it from both /lesson and cms2 sidebar
    public function archive($id)
    {
        $id = (int) $id;
        if ($id <= 0) { return false; }
        $sql = "UPDATE lessons SET status = 'archived' WHERE id = '$id'";
        $stmt = $this->run_query($sql);
        return $stmt ? true : false;
    }

    // Return all draft lessons ordered newest first — used by cms2 sidebar
    public function list_drafts()
    {
        $sql = "SELECT id, title, category, created_at FROM lessons WHERE status = 'draft' ORDER BY id DESC";
        $stmt = $this->run_query($sql);
        $rows = [];
        if ($stmt) {
            while ($row = $stmt->fetch_assoc()) { $rows[] = $row; }
        }
        return $rows;
    }

    // Update an existing lesson by ID
    public function update($id, $title, $category, $content, $image_url = '', $code_block = '')
    {
        $id       = (int) $id; // Numeric cast — no escape needed
        $title    = $this->connectDb()->real_escape_string($title);
        $category = $this->connectDb()->real_escape_string($category);
        $content  = $this->connectDb()->real_escape_string($content);
        $image_url  = $this->connectDb()->real_escape_string($image_url);
        $code_block = $this->connectDb()->real_escape_string($code_block);

        if ($id <= 0) {
            return false;
        }

        $sql = "UPDATE lessons SET title='$title', category='$category', content='$content', image_url='$image_url', code_block='$code_block' WHERE id='$id'";
        $stmt = $this->run_query($sql);

        return $stmt ? true : false;
    }

    // Delete a lesson by ID
    public function delete($id)
    {
        $id = (int) $id; // Numeric cast prevents injection
        if ($id <= 0) {
            return false;
        }
        $sql = "DELETE FROM lessons WHERE id = '$id'";
        $stmt = $this->run_query($sql);
        return $stmt ? true : false;
    }
}
