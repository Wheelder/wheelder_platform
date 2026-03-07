<?php
/**
 * Vackup Database Setup / Migration Script
 * Creates all required tables in the Vackup SQLite database.
 * Access via: /vackup/setup?action=cr
 */

require_once __DIR__ . '/database.php';

class VackupMigration
{
    private $db;

    public function __construct()
    {
        $this->db = VackupDatabase::getInstance();
    }

    private function tableExists($table)
    {
        $result = $this->db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='$table'");
        $row = $result->fetch();
        return $row !== false;
    }

    private function createIfNotExists($table, $sql)
    {
        if ($this->tableExists($table)) {
            echo "Table <strong>$table</strong> already exists, skipping<br>";
            return;
        }
        try {
            $this->db->exec($sql);
            echo "Table <strong>$table</strong> created successfully<br>";
        } catch (Exception $e) {
            echo "Error creating table $table: " . $e->getMessage() . "<br>";
        }
    }

    public function projects_table()
    {
        $this->createIfNotExists('projects', "
            CREATE TABLE projects (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                slug TEXT UNIQUE,
                project_path TEXT NOT NULL,
                description TEXT,
                current_version TEXT DEFAULT '0.0',
                github_repo TEXT,
                github_token TEXT,
                local_storage_path TEXT,
                onedrive_path TEXT,
                google_drive_path TEXT,
                exclude_patterns TEXT,
                auto_push_github INTEGER DEFAULT 0,
                auto_copy_onedrive INTEGER DEFAULT 1,
                auto_copy_gdrive INTEGER DEFAULT 0,
                status TEXT DEFAULT 'active',
                created_at TEXT DEFAULT CURRENT_TIMESTAMP,
                updated_at TEXT DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }

    public function vackups_table()
    {
        $this->createIfNotExists('vackups', "
            CREATE TABLE vackups (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                project_id INTEGER NOT NULL,
                version TEXT NOT NULL,
                label TEXT NOT NULL,
                description TEXT,
                notes TEXT,
                zip_filename TEXT,
                zip_size INTEGER,
                zip_path TEXT,
                github_commit_sha TEXT,
                github_tag TEXT,
                github_pushed INTEGER DEFAULT 0,
                onedrive_copied INTEGER DEFAULT 0,
                gdrive_copied INTEGER DEFAULT 0,
                local_copied INTEGER DEFAULT 0,
                files_count INTEGER,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
            )
        ");
    }

    public function settings_table()
    {
        $this->createIfNotExists('settings', "
            CREATE TABLE settings (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                key TEXT UNIQUE NOT NULL,
                value TEXT,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP,
                updated_at TEXT DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }

    public function notes_table()
    {
        $this->createIfNotExists('release_notes', "
            CREATE TABLE release_notes (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                vackup_id INTEGER NOT NULL,
                content TEXT,
                format TEXT DEFAULT 'markdown',
                created_at TEXT DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (vackup_id) REFERENCES vackups(id) ON DELETE CASCADE
            )
        ");
    }

    /**
     * WHY: Add column for storing the GitHub release asset download URL.
     * The github_commit_sha column already exists from the original schema.
     * SQLite doesn't have IF NOT EXISTS for ALTER TABLE, so we check first.
     */
    public function add_github_asset_column()
    {
        try {
            $result = $this->db->query("PRAGMA table_info(vackups)");
            $columns = [];
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $columns[] = $row['name'];
            }

            if (!in_array('github_asset_url', $columns)) {
                $this->db->exec("ALTER TABLE vackups ADD COLUMN github_asset_url TEXT");
                echo "Column <strong>github_asset_url</strong> added to vackups table<br>";
            } else {
                echo "Column <strong>github_asset_url</strong> already exists, skipping<br>";
            }
        } catch (Exception $e) {
            echo "Error adding github_asset_url column: " . $e->getMessage() . "<br>";
        }
    }

    public function runAll()
    {
        echo "<h3>Vackup Database Migration</h3>";
        echo "<p>Database file: " . realpath(__DIR__ . '/vackup.db') . "</p>";
        echo "<hr>";

        $this->projects_table();
        $this->vackups_table();
        $this->settings_table();
        $this->notes_table();
        $this->add_github_asset_column();

        echo "<hr>";
        echo "<p><strong>Migration complete.</strong></p>";
        echo "<p><a href='/vackup'>Go to Vackup Dashboard</a></p>";
    }
}

// Run migration when accessed with ?action=cr
$action = $_GET['action'] ?? '';

if ($action === 'cr') {
    $migration = new VackupMigration();
    $migration->runAll();
} else {
    echo "<p>Vackup Setup — use <code>?action=cr</code> to create tables.</p>";
}
