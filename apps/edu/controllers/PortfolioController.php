<?php
/**
 * PortfolioController — Manages all portfolio CRUD and public display logic
 * 
 * WHY: Centralizes portfolio data access so both admin CMS and public view
 *      share the same safe query methods. Follows the BlogController pattern.
 */
require_once __DIR__ . '/Controller.php';

class PortfolioController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    // ---------------------------------------------------------------
    // Auth gate — reuse the same pattern as BlogController
    // ---------------------------------------------------------------
    public function check_auth()
    {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
            header('Location: /');
            exit;
        }
    }

    // ---------------------------------------------------------------
    //  PORTFOLIO SECTIONS  (about, skills, contact, hero, etc.)
    // ---------------------------------------------------------------

    /**
     * WHY: Fetch a single section row by its unique section_key.
     * Each section_key (e.g. "hero", "about", "contact") has exactly one row.
     */
    public function get_section($section_key)
    {
        $section_key = $this->connectDb()->real_escape_string($section_key);
        $sql = "SELECT * FROM portfolio_sections WHERE section_key = '$section_key' LIMIT 1";
        $result = $this->run_query($sql);
        if (!$result) {
            return null;
        }
        return $result->fetch_assoc();
    }

    /** WHY: List every section for the admin CMS overview table. */
    public function list_sections()
    {
        $sql = "SELECT * FROM portfolio_sections ORDER BY sort_order ASC, id ASC";
        $result = $this->run_query($sql);
        $rows = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
        }
        return $rows;
    }

    /** WHY: Insert or update a section. Upsert by section_key keeps data clean. */
    public function upsert_section($section_key, $title, $content, $sort_order = 0)
    {
        $conn = $this->connectDb();
        $section_key = $conn->real_escape_string($section_key);
        $title       = $conn->real_escape_string($title);
        $content     = $conn->real_escape_string($content);
        $sort_order  = (int) $sort_order;

        // WHY: Check if row exists to decide INSERT vs UPDATE
        $existing = $this->get_section($section_key);
        if ($existing) {
            $sql = "UPDATE portfolio_sections 
                    SET title = '$title', content = '$content', sort_order = $sort_order 
                    WHERE section_key = '$section_key'";
        } else {
            $sql = "INSERT INTO portfolio_sections (section_key, title, content, sort_order) 
                    VALUES ('$section_key', '$title', '$content', $sort_order)";
        }
        return $this->run_query($sql);
    }

    /** WHY: Retrieve a section by its numeric id for the edit form. */
    public function get_section_by_id($id)
    {
        $id = (int) $id;
        $sql = "SELECT * FROM portfolio_sections WHERE id = $id LIMIT 1";
        $result = $this->run_query($sql);
        if (!$result) {
            return null;
        }
        return $result->fetch_assoc();
    }

    /** WHY: Update section by id — used from the edit form. */
    public function update_section($id, $title, $content, $sort_order = 0)
    {
        $conn = $this->connectDb();
        $id         = (int) $id;
        $title      = $conn->real_escape_string($title);
        $content    = $conn->real_escape_string($content);
        $sort_order = (int) $sort_order;

        $sql = "UPDATE portfolio_sections 
                SET title = '$title', content = '$content', sort_order = $sort_order 
                WHERE id = $id";
        return $this->run_query($sql);
    }

    // ---------------------------------------------------------------
    //  PORTFOLIO SKILLS
    // ---------------------------------------------------------------

    /** WHY: List all skills grouped by category for public display. */
    public function list_skills()
    {
        $sql = "SELECT * FROM portfolio_skills ORDER BY category ASC, sort_order ASC, id ASC";
        $result = $this->run_query($sql);
        $rows = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
        }
        return $rows;
    }

    /** WHY: Group skills by their category column for rendering badges per group. */
    public function list_skills_grouped()
    {
        $skills = $this->list_skills();
        $grouped = [];
        foreach ($skills as $s) {
            $cat = $s['category'] ?: 'Other';
            $grouped[$cat][] = $s;
        }
        return $grouped;
    }

    public function get_skill($id)
    {
        $id = (int) $id;
        $sql = "SELECT * FROM portfolio_skills WHERE id = $id LIMIT 1";
        $result = $this->run_query($sql);
        return $result ? $result->fetch_assoc() : null;
    }

    public function insert_skill($name, $category, $sort_order = 0)
    {
        $conn = $this->connectDb();
        $name       = $conn->real_escape_string($name);
        $category   = $conn->real_escape_string($category);
        $sort_order = (int) $sort_order;
        $sql = "INSERT INTO portfolio_skills (name, category, sort_order) 
                VALUES ('$name', '$category', $sort_order)";
        return $this->run_query($sql);
    }

    public function update_skill($id, $name, $category, $sort_order = 0)
    {
        $conn = $this->connectDb();
        $id         = (int) $id;
        $name       = $conn->real_escape_string($name);
        $category   = $conn->real_escape_string($category);
        $sort_order = (int) $sort_order;
        $sql = "UPDATE portfolio_skills 
                SET name = '$name', category = '$category', sort_order = $sort_order 
                WHERE id = $id";
        return $this->run_query($sql);
    }

    public function delete_skill($id)
    {
        $id = (int) $id;
        $sql = "DELETE FROM portfolio_skills WHERE id = $id";
        return $this->run_query($sql);
    }

    // ---------------------------------------------------------------
    //  PORTFOLIO PROJECTS
    // ---------------------------------------------------------------

    public function list_projects()
    {
        $sql = "SELECT * FROM portfolio_projects ORDER BY sort_order ASC, id DESC";
        $result = $this->run_query($sql);
        $rows = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
        }
        return $rows;
    }

    /** WHY: Public view only shows visible projects. */
    public function list_published_projects()
    {
        $sql = "SELECT * FROM portfolio_projects WHERE is_visible = 1 ORDER BY sort_order ASC, id DESC";
        $result = $this->run_query($sql);
        $rows = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
        }
        return $rows;
    }

    public function get_project($id)
    {
        $id = (int) $id;
        $sql = "SELECT * FROM portfolio_projects WHERE id = $id LIMIT 1";
        $result = $this->run_query($sql);
        return $result ? $result->fetch_assoc() : null;
    }

    public function insert_project($title, $description, $technologies, $role_text, $problem, $solution, $result_text, $demo_url, $github_url, $image_url, $sort_order, $is_visible)
    {
        $conn = $this->connectDb();
        $title        = $conn->real_escape_string($title);
        $description  = $conn->real_escape_string($description);
        $technologies = $conn->real_escape_string($technologies);
        $role_text    = $conn->real_escape_string($role_text);
        $problem      = $conn->real_escape_string($problem);
        $solution     = $conn->real_escape_string($solution);
        $result_text  = $conn->real_escape_string($result_text);
        $demo_url     = $conn->real_escape_string($demo_url);
        $github_url   = $conn->real_escape_string($github_url);
        $image_url    = $conn->real_escape_string($image_url);
        $sort_order   = (int) $sort_order;
        $is_visible   = (int) $is_visible;

        $sql = "INSERT INTO portfolio_projects 
                (title, description, technologies, role_text, problem, solution, result_text, demo_url, github_url, image_url, sort_order, is_visible) 
                VALUES ('$title', '$description', '$technologies', '$role_text', '$problem', '$solution', '$result_text', '$demo_url', '$github_url', '$image_url', $sort_order, $is_visible)";
        return $this->run_query($sql);
    }

    public function update_project($id, $title, $description, $technologies, $role_text, $problem, $solution, $result_text, $demo_url, $github_url, $image_url, $sort_order, $is_visible)
    {
        $conn = $this->connectDb();
        $id           = (int) $id;
        $title        = $conn->real_escape_string($title);
        $description  = $conn->real_escape_string($description);
        $technologies = $conn->real_escape_string($technologies);
        $role_text    = $conn->real_escape_string($role_text);
        $problem      = $conn->real_escape_string($problem);
        $solution     = $conn->real_escape_string($solution);
        $result_text  = $conn->real_escape_string($result_text);
        $demo_url     = $conn->real_escape_string($demo_url);
        $github_url   = $conn->real_escape_string($github_url);
        $image_url    = $conn->real_escape_string($image_url);
        $sort_order   = (int) $sort_order;
        $is_visible   = (int) $is_visible;

        $sql = "UPDATE portfolio_projects 
                SET title='$title', description='$description', technologies='$technologies', 
                    role_text='$role_text', problem='$problem', solution='$solution', 
                    result_text='$result_text', demo_url='$demo_url', github_url='$github_url', 
                    image_url='$image_url', sort_order=$sort_order, is_visible=$is_visible 
                WHERE id = $id";
        return $this->run_query($sql);
    }

    public function delete_project($id)
    {
        $id = (int) $id;
        $sql = "DELETE FROM portfolio_projects WHERE id = $id";
        return $this->run_query($sql);
    }

    // ---------------------------------------------------------------
    //  CONTACT MESSAGES (submitted by visitors)
    // ---------------------------------------------------------------

    /** WHY: Visitors can send a message without needing an account. */
    public function insert_contact($name, $email, $subject, $message)
    {
        $conn = $this->connectDb();
        $name    = $conn->real_escape_string($name);
        $email   = $conn->real_escape_string($email);
        $subject = $conn->real_escape_string($subject);
        $message = $conn->real_escape_string($message);

        $sql = "INSERT INTO portfolio_contacts (name, email, subject, message) 
                VALUES ('$name', '$email', '$subject', '$message')";
        return $this->run_query($sql);
    }

    public function list_contacts()
    {
        $sql = "SELECT * FROM portfolio_contacts ORDER BY id DESC";
        $result = $this->run_query($sql);
        $rows = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
        }
        return $rows;
    }

    public function delete_contact($id)
    {
        $id = (int) $id;
        $sql = "DELETE FROM portfolio_contacts WHERE id = $id";
        return $this->run_query($sql);
    }
}
