<?php
/**
 * Dashboard — Generate shareable access links for the Learn app.
 * Each link contains a unique code in the URL (?key=CODE) that grants access.
 * Codes are stored in the local SQLite DB so they can be listed, revoked, etc.
 */

// Only start a session if one isn't already active (avoids Notice when the router starts one)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// --- CSRF token for form submissions ---
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrfToken = $_SESSION['csrf_token'];

// --- Database helper: connect to the same SQLite file used by the learn app ---
function getDashboardDb() {
    $db = new PDO('sqlite:' . __DIR__ . '/database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Auto-create the access_codes table on first run
    $db->exec("CREATE TABLE IF NOT EXISTS access_codes (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        code TEXT NOT NULL UNIQUE,
        label TEXT DEFAULT '',
        is_active INTEGER DEFAULT 1,
        created_at TEXT DEFAULT CURRENT_TIMESTAMP
    )");
    // Index on code for fast lookups when the learn app validates a key
    $db->exec("CREATE INDEX IF NOT EXISTS idx_access_code ON access_codes(code)");

    return $db;
}

// --- Generate a cryptographically random 12-char alphanumeric code ---
function generateUniqueCode() {
    // 8 random bytes → 16 hex chars, then trim to 12 for a clean URL-safe code
    return substr(bin2hex(random_bytes(8)), 0, 12);
}

// --- Detect the base URL so generated links work on any host/path ---
function getBaseUrl() {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host   = $_SERVER['HTTP_HOST'];
    // Derive the project base path from SCRIPT_NAME (e.g. /wheelder)
    $base   = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
    return $scheme . '://' . $host . $base;
}

$baseUrl  = getBaseUrl();
$message  = '';
$msgType  = '';

// --- Handle POST actions (generate / toggle / delete) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // CSRF check — every POST must include the token
    $postedToken = $_POST['csrf_token'] ?? '';
    if (empty($postedToken) || !hash_equals($_SESSION['csrf_token'], $postedToken)) {
        $message = 'Invalid or missing CSRF token. Please reload and try again.';
        $msgType = 'error';
    } else {

        try {
            $db = getDashboardDb();

            // --- Generate a new access code ---
            if (isset($_POST['generate'])) {
                $label = trim($_POST['label'] ?? '');
                // Sanitize label — plain text only, max 100 chars
                $label = htmlspecialchars(mb_substr($label, 0, 100), ENT_QUOTES, 'UTF-8');

                $code = generateUniqueCode();

                $stmt = $db->prepare("INSERT INTO access_codes (code, label) VALUES (?, ?)");
                $stmt->execute([$code, $label]);

                $message = 'New access code generated successfully!';
                $msgType = 'success';
            }

            // --- Toggle active/inactive ---
            if (isset($_POST['toggle'])) {
                $id = (int)$_POST['code_id'];
                // Flip is_active: 1→0, 0→1
                $stmt = $db->prepare("UPDATE access_codes SET is_active = CASE WHEN is_active = 1 THEN 0 ELSE 1 END WHERE id = ?");
                $stmt->execute([$id]);

                $message = 'Code status updated.';
                $msgType = 'success';
            }

            // --- Delete a code permanently ---
            if (isset($_POST['delete'])) {
                $id = (int)$_POST['code_id'];
                $stmt = $db->prepare("DELETE FROM access_codes WHERE id = ?");
                $stmt->execute([$id]);

                $message = 'Code deleted.';
                $msgType = 'success';
            }

        } catch (PDOException $e) {
            error_log("Dashboard DB error: " . $e->getMessage());
            $message = 'Database error. Please try again.';
            $msgType = 'error';
        }
    }
}

// --- Fetch all codes for display ---
try {
    $db    = getDashboardDb();
    $codes = $db->query("SELECT * FROM access_codes ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Dashboard fetch error: " . $e->getMessage());
    $codes = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wheelder — Access Code Dashboard</title>
    <!-- Bootstrap 5 — same version used by the learn app -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome — same icon library used by the learn app -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Dark theme matching the learn app's navbar */
        body {
            background: #f5f5f5;
            font-family: 'Arial', sans-serif;
            min-height: 100vh;
        }

        /* Top navbar — same dark style as the learn app */
        .navbar-top {
            background-color: #212529;
            padding: 12px 24px;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .navbar-top a {
            color: #fff;
            text-decoration: none;
            font-size: 1.1rem;
            font-weight: 600;
        }
        .navbar-top .nav-links a {
            font-size: 0.9rem;
            font-weight: 400;
            margin-left: 18px;
            opacity: 0.8;
            transition: opacity 0.2s;
        }
        .navbar-top .nav-links a:hover {
            opacity: 1;
        }

        /* Main card container */
        .dashboard-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            padding: 30px;
            margin-top: 30px;
        }

        /* Section headings */
        .section-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 16px;
            color: #212529;
        }

        /* Generate form */
        .generate-form {
            display: flex;
            gap: 10px;
            align-items: flex-end;
            flex-wrap: wrap;
        }
        .generate-form .form-group {
            flex: 1;
            min-width: 200px;
        }
        .generate-form label {
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 4px;
            display: block;
        }
        .generate-form input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 0.95rem;
        }
        .generate-form input:focus {
            outline: none;
            border-color: #212529;
        }

        /* Buttons — dark theme to match learn app */
        .btn-generate {
            background: #212529;
            color: #fff;
            border: none;
            padding: 9px 20px;
            border-radius: 6px;
            font-size: 0.95rem;
            cursor: pointer;
            transition: background 0.2s;
            white-space: nowrap;
        }
        .btn-generate:hover {
            background: #343a40;
        }

        /* Alert messages */
        .alert-msg {
            padding: 10px 16px;
            border-radius: 6px;
            margin-bottom: 16px;
            font-size: 0.9rem;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Codes table */
        .codes-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }
        .codes-table th {
            background: #f8f9fa;
            padding: 10px 12px;
            text-align: left;
            font-size: 0.85rem;
            color: #666;
            border-bottom: 2px solid #dee2e6;
        }
        .codes-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #eee;
            font-size: 0.9rem;
            vertical-align: middle;
        }
        .codes-table tr:hover {
            background: #f8f9fa;
        }

        /* URL display — monospace so it's easy to read */
        .url-cell {
            font-family: 'Courier New', monospace;
            font-size: 0.82rem;
            color: #0d6efd;
            word-break: break-all;
        }

        /* Status badge */
        .badge-active {
            background: #198754;
            color: #fff;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 0.78rem;
        }
        .badge-inactive {
            background: #dc3545;
            color: #fff;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 0.78rem;
        }

        /* Action buttons inside the table */
        .btn-sm-action {
            border: none;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 0.8rem;
            cursor: pointer;
            transition: opacity 0.2s;
        }
        .btn-sm-action:hover {
            opacity: 0.8;
        }
        .btn-copy {
            background: #0d6efd;
            color: #fff;
        }
        .btn-toggle {
            background: #ffc107;
            color: #212529;
        }
        .btn-delete {
            background: #dc3545;
            color: #fff;
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #999;
        }
        .empty-state i {
            font-size: 2.5rem;
            margin-bottom: 12px;
            display: block;
        }

        /* Copy tooltip */
        .copy-tooltip {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #198754;
            color: #fff;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 0.85rem;
            opacity: 0;
            transition: opacity 0.3s;
            z-index: 9999;
            pointer-events: none;
        }
        .copy-tooltip.show {
            opacity: 1;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .generate-form {
                flex-direction: column;
            }
            .codes-table {
                font-size: 0.8rem;
            }
            /* Hide the label column on mobile — not critical info */
            .hide-mobile {
                display: none;
            }
        }
    </style>
</head>
<body>

    <!-- Top navbar — matches learn app style -->
    <div class="navbar-top">
        <a href="/wheelder/dashboard">Wheelder Dashboard</a>
        <div class="nav-links">
            <a href="/wheelder/learn"><i class="fas fa-flask"></i> Learn App</a>
            <a href="/wheelder/"><i class="fas fa-home"></i> Home</a>
        </div>
    </div>

    <div class="container" style="max-width: 960px;">

        <!-- Flash message -->
        <?php if (!empty($message)): ?>
            <div class="alert-msg alert-<?php echo $msgType; ?>" style="margin-top: 20px;">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <!-- Generate new code -->
        <div class="dashboard-card">
            <div class="section-title"><i class="fas fa-key"></i> Generate Access Link</div>
            <p style="color: #666; font-size: 0.9rem; margin-bottom: 16px;">
                Create a unique access code for the Learn app. Share the generated URL with anyone to give them access.
            </p>
            <form method="POST" class="generate-form">
                <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                <div class="form-group">
                    <label for="label">Label (optional) — e.g. "For John", "Demo link"</label>
                    <input type="text" id="label" name="label" placeholder="Who is this link for?" maxlength="100">
                </div>
                <button type="submit" name="generate" value="1" class="btn-generate">
                    <i class="fas fa-plus"></i> Generate Link
                </button>
            </form>
        </div>

        <!-- List of generated codes -->
        <div class="dashboard-card">
            <div class="section-title"><i class="fas fa-list"></i> Generated Access Codes</div>

            <?php if (empty($codes)): ?>
                <div class="empty-state">
                    <i class="fas fa-link"></i>
                    <p>No access codes yet. Generate one above to get started.</p>
                </div>
            <?php else: ?>
                <table class="codes-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th class="hide-mobile">Label</th>
                            <th>Shareable URL</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($codes as $i => $row): ?>
                            <?php
                                // Build the full shareable URL for this code
                                $shareUrl = $baseUrl . '/learn?key=' . urlencode($row['code']);
                            ?>
                            <tr>
                                <td><?php echo $i + 1; ?></td>
                                <td class="hide-mobile"><?php echo htmlspecialchars($row['label'] ?: '—'); ?></td>
                                <td class="url-cell">
                                    <span id="url-<?php echo $row['id']; ?>"><?php echo htmlspecialchars($shareUrl); ?></span>
                                </td>
                                <td>
                                    <?php if ($row['is_active']): ?>
                                        <span class="badge-active">Active</span>
                                    <?php else: ?>
                                        <span class="badge-inactive">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td style="white-space: nowrap; font-size: 0.82rem; color: #666;">
                                    <?php echo htmlspecialchars($row['created_at']); ?>
                                </td>
                                <td style="white-space: nowrap;">
                                    <!-- Copy URL button -->
                                    <button type="button" class="btn-sm-action btn-copy" onclick="copyUrl('url-<?php echo $row['id']; ?>')" title="Copy URL">
                                        <i class="fas fa-copy"></i>
                                    </button>

                                    <!-- Toggle active/inactive -->
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                                        <input type="hidden" name="code_id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" name="toggle" value="1" class="btn-sm-action btn-toggle"
                                                title="<?php echo $row['is_active'] ? 'Deactivate' : 'Activate'; ?>">
                                            <i class="fas fa-<?php echo $row['is_active'] ? 'pause' : 'play'; ?>"></i>
                                        </button>
                                    </form>

                                    <!-- Delete code -->
                                    <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this access code permanently?');">
                                        <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                                        <input type="hidden" name="code_id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" name="delete" value="1" class="btn-sm-action btn-delete" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

    </div>

    <!-- Copy-to-clipboard toast notification -->
    <div class="copy-tooltip" id="copyToast">Copied to clipboard!</div>

    <script>
        /**
         * Copy the shareable URL to the clipboard and show a brief toast.
         * Falls back to document.execCommand for older browsers.
         */
        function copyUrl(elementId) {
            var urlText = document.getElementById(elementId).textContent;

            if (navigator.clipboard && navigator.clipboard.writeText) {
                // Modern browsers — async clipboard API
                navigator.clipboard.writeText(urlText).then(function() {
                    showToast();
                }).catch(function() {
                    fallbackCopy(urlText);
                });
            } else {
                // Fallback for older browsers
                fallbackCopy(urlText);
            }
        }

        /** Fallback copy using a temporary textarea */
        function fallbackCopy(text) {
            var ta = document.createElement('textarea');
            ta.value = text;
            ta.style.position = 'fixed';
            ta.style.left = '-9999px';
            document.body.appendChild(ta);
            ta.select();
            document.execCommand('copy');
            document.body.removeChild(ta);
            showToast();
        }

        /** Show a brief "Copied!" toast in the top-right corner */
        function showToast() {
            var toast = document.getElementById('copyToast');
            toast.classList.add('show');
            setTimeout(function() {
                toast.classList.remove('show');
            }, 1500);
        }
    </script>

</body>
</html>
