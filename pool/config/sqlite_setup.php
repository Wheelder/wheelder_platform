<?php
/**
 * SQLite Database Setup / Migration Script
 * 
 * Creates all required tables in the central SQLite database.
 * Access via: /sqlite_setup?action=cr
 * 
 * This replaces the MySQL-based db_setup.php for SQLite.
 */

require_once __DIR__ . '/db_config.php';
require_once __DIR__ . '/sqlite_wrapper.php';

class SQLiteMigration
{
    private $conn;

    public function __construct()
    {
        $config = new config();
        $this->conn = $config->connectDb();
    }

    private function tableExists($table)
    {
        $result = $this->conn->query("SELECT name FROM sqlite_master WHERE type='table' AND name='$table'");
        return $result && $result->num_rows > 0;
    }

    private function createIfNotExists($table, $sql)
    {
        if ($this->tableExists($table)) {
            echo "Table $table already exists, skipping</br>";
            return;
        }
        $result = $this->conn->query($sql);
        if ($result) {
            echo "Table $table created successfully</br>";
        } else {
            echo "Error creating table $table: " . $this->conn->error . "</br>";
        }
    }

    private function dropAndCreate($table, $sql)
    {
        $this->conn->query("DROP TABLE IF EXISTS $table");
        $result = $this->conn->query($sql);
        if ($result) {
            echo "Table $table created successfully</br>";
        } else {
            echo "Error creating table $table: " . $this->conn->error . "</br>";
        }
    }

    public function users_table()
    {
        $this->createIfNotExists('users', "
            CREATE TABLE users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                subscription_id TEXT,
                phone TEXT,
                first_name TEXT,
                last_name TEXT,
                selected_categories TEXT,
                selected_topics TEXT,
                dob TEXT,
                state TEXT,
                city TEXT,
                default_app TEXT,
                zip_code TEXT,
                address TEXT,
                role TEXT,
                sub_role TEXT,
                rating TEXT,
                avatar TEXT,
                current_session TEXT,
                online TEXT,
                otp TEXT,
                email TEXT,
                country TEXT,
                currency TEXT,
                tax_id TEXT,
                gst TEXT,
                pst TEXT,
                vat_no TEXT,
                language TEXT,
                business_type TEXT,
                user_type TEXT,
                created TEXT,
                modified TEXT,
                last_login TEXT,
                email_verified INTEGER,
                last_login_ip TEXT,
                last_login_device TEXT,
                last_logout TEXT,
                referral_code TEXT,
                user_status TEXT,
                created_teams TEXT,
                invited_teams TEXT,
                joined_teams TEXT,
                profile_status TEXT,
                time_zone TEXT,
                profile_image TEXT,
                password TEXT,
                bio TEXT,
                date_created TEXT DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }

    public function financial_profile_table()
    {
        $this->createIfNotExists('financial_profile', "
            CREATE TABLE financial_profile (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                user_id INTEGER,
                email TEXT,
                stripe_connect_id TEXT,
                stripe_customer_id TEXT,
                stripe_source_id TEXT,
                card_token TEXT,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }

    public function books_table()
    {
        $this->createIfNotExists('books', "
            CREATE TABLE books (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title TEXT NOT NULL,
                filepath TEXT,
                content TEXT,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }

    public function questions_table()
    {
        $this->dropAndCreate('questions', "
            CREATE TABLE questions (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                question TEXT,
                unf_answer TEXT,
                answer TEXT,
                deep_answer TEXT,
                options TEXT,
                filepath TEXT,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }

    public function notes_table()
    {
        $this->dropAndCreate('notes', "
            CREATE TABLE notes (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title TEXT,
                user_id INTEGER,
                section_title TEXT,
                category TEXT,
                image TEXT,
                example TEXT,
                content TEXT,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }

    public function notes_data_table()
    {
        $this->dropAndCreate('notes_data', "
            CREATE TABLE notes_data (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title TEXT,
                user_id INTEGER,
                section_title TEXT,
                category TEXT,
                image TEXT,
                example TEXT,
                content TEXT,
                status INTEGER,
                delete_status INTEGER,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }

    public function suggested_notes_table()
    {
        $this->dropAndCreate('suggested_notes', "
            CREATE TABLE suggested_notes (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title TEXT,
                content TEXT,
                user_details TEXT,
                deadline TEXT,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }

    public function blogs_table()
    {
        $this->dropAndCreate('blogs', "
            CREATE TABLE blogs (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title TEXT,
                section_title TEXT,
                deadline TEXT,
                content TEXT,
                file_name TEXT,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }

    public function lessons_table()
    {
        // Separate table for tech lessons so blog data is never mixed with lesson data
        $this->createIfNotExists('lessons', "
            CREATE TABLE lessons (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title TEXT NOT NULL,
                category TEXT,
                content TEXT,
                image_url TEXT,
                code_block TEXT,
                status TEXT DEFAULT 'draft',
                created_at TEXT DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }

    public function lessons_migrate_columns()
    {
        // ALTER TABLE is used (not DROP/CREATE) to preserve existing lesson rows
        // when adding new columns to an already-created lessons table
        $db = $this->connectDb();
        // status column: 'draft' (AI-generated, unpublished) | 'published' | 'archived'
        $cols = ['image_url TEXT', 'code_block TEXT', "status TEXT DEFAULT 'draft'"];
        foreach ($cols as $col) {
            $name = explode(' ', $col)[0];
            // Check if column already exists before attempting to add it
            $check = $db->query("PRAGMA table_info(lessons)");
            $exists = false;
            if ($check) {
                while ($row = $check->fetch_assoc()) {
                    if ($row['name'] === $name) { $exists = true; break; }
                }
            }
            if (!$exists) {
                $db->query("ALTER TABLE lessons ADD COLUMN $col");
            }
        }
    }

    public function website_traffic_table()
    {
        $this->dropAndCreate('website_traffic', "
            CREATE TABLE website_traffic (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                ip_address TEXT,
                user_agent TEXT,
                page_url TEXT,
                country TEXT,
                city TEXT,
                latitude TEXT,
                longitude TEXT,
                timezone TEXT,
                referrer TEXT,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }

    public function files_table()
    {
        $this->createIfNotExists('files', "
            CREATE TABLE files (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                file_path TEXT,
                key_id TEXT,
                user_id INTEGER,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }

    public function profiles_table()
    {
        $this->createIfNotExists('profiles', "
            CREATE TABLE profiles (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                user_id INTEGER,
                profile_status INTEGER DEFAULT 0,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }

    public function magic_links_table()
    {
        // Magic links for passwordless authentication — replaces password-based login
        $this->createIfNotExists('magic_links', "
            CREATE TABLE magic_links (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                user_id INTEGER,
                email TEXT NOT NULL,
                token TEXT NOT NULL UNIQUE,
                expires_at TEXT NOT NULL,
                used INTEGER DEFAULT 0,
                used_at TEXT,
                ip_address TEXT,
                user_agent TEXT,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }

    public function runAll()
    {
        echo "<h3>SQLite Migration — Creating Tables</h3>";
        echo "<p>Database file: " . realpath(__DIR__ . '/wheelder.db') . "</p>";
        echo "<hr>";

        $this->users_table();
        $this->financial_profile_table();
        $this->books_table();
        $this->questions_table();
        $this->notes_table();
        $this->notes_data_table();
        $this->suggested_notes_table();
        $this->blogs_table();
        $this->lessons_table();
        $this->lessons_migrate_columns();
        $this->website_traffic_table();
        $this->files_table();
        $this->profiles_table();
        $this->magic_links_table();

        echo "<hr>";
        echo "<p><strong>Migration complete.</strong></p>";
    }
}

// Run migration when accessed with ?action=cr
$action = $_GET['action'] ?? '';

if ($action === 'cr') {
    $migration = new SQLiteMigration();
    $migration->runAll();
} else {
    echo "<p>SQLite Setup — use <code>?action=cr</code> to create tables.</p>";
}
