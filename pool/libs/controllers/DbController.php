<?php
$path = $_SERVER['DOCUMENT_ROOT'];
include $path . '/pool/config/database.php';

class Db extends Database
{

    public function profiles_table($table = "users")
    {
        $this->deleteTable($table);
        $this->createTable(
            $table,
            '
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
            subscription_id VARCHAR(255) NULL,
            phone VARCHAR(255) NULL,
            first_name VARCHAR(255) NULL,
            last_name VARCHAR(255) NULL,
            selected_categories TEXT NULL,
            dob DATE NULL,
            state VARCHAR(255) NULL,
            city VARCHAR(255) NULL,
            default_app VARCHAR(255) NULL,
            zip_code VARCHAR(255) NULL,
            address VARCHAR(255) NULL,
            role VARCHAR(255) NULL,
            sub_role VARCHAR(255) NULL,
            rating VARCHAR(255) NULL,
            avatar VARCHAR(255) NULL,
            current_session VARCHAR(255) NULL,
            online VARCHAR(255) NULL,
            otp VARCHAR(255) NULL,
            email VARCHAR(255) NULL,
            country VARCHAR(255) NULL,
            currency VARCHAR(255) NULL,
            tax_id VARCHAR(255) NULL,
            gst VARCHAR(255) NULL,
            pst VARCHAR(255) NULL,
            vat_no VARCHAR(255) NULL,
            language VARCHAR(255) NULL,
            business_type VARCHAR(255) NULL,
            user_type VARCHAR(255) NULL,
            created datetime NULL ,
            modified datetime NULL ,
            last_login datetime NULL ,
            email_verified INT(10) NULL,
            last_login_ip VARCHAR(255) NULL,
            last_login_device VARCHAR(255) NULL,
            last_logout datetime NULL ,
            referral_code VARCHAR(255) NULL,
            user_status VARCHAR(255) NULL,
            created_teams VARCHAR(255) NULL,
            invited_teams varchar(255) NULL,
            joined_teams varchar(255) NULL,
            profile_status VARCHAR(255) NULL,
            time_zone VARCHAR(255) NULL,
            profile_image TEXT NULL,
            password VARCHAR(230) NULL,
            bio TEXT NULL,
            date_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
        );
    }

    public function books($table = "books")
    {
        $this->createTable(
            $table,
            '
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            filepath TEXT NULL,
            content TEXT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
        );
    }

    public function notes($table = "notes")
    {
        $this->copyTable($table, $table . "2");
        $this->deleteTable($table);
        $this->createTable(
            $table,
            '
            id INT AUTO_INCREMENT PRIMARY KEY,
            title TEXT  NULL,
            user_id INT(6) UNSIGNED NULL,
            section_title JSON NULL,
            category VARCHAR(255) NULL,
            image TEXT NULL,
            example TEXT NULL,
            content TEXT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
        );
    }

    public function notes_data($table = "notes_data")
    {
        //$this->copyTable($table, $table . "2");
        $this->deleteTable($table);
        $this->createTable(
            $table,
            '
            id INT AUTO_INCREMENT PRIMARY KEY,
            title TEXT  NULL,
            user_id INT(6) UNSIGNED NULL,
            section_title JSON NULL,
            category VARCHAR(255) NULL,
            image TEXT NULL,
            example TEXT NULL,
            content TEXT NULL,
            status INT(6) NULL,
            delete_status INT(6) NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
        );
    }

    public function notes_suggested($table = "suggested_notes")
    {
        $this->deleteTable($table);
        $this->createTable(
            $table,
            '
            id INT AUTO_INCREMENT PRIMARY KEY,
            title TEXT  NULL,
            content TEXT NULL,
            user_details JSON NULL,
            deadline VARCHAR(255) NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
        );
    }

    public function blogs($table = "blogs")
    {
        $this->copyTable($table, $table . "2");
        //$this->deleteTable($table);
        $this->createTable(
            $table,
            '
            id INT AUTO_INCREMENT PRIMARY KEY,
            title TEXT  NULL,
            section_title VARCHAR(255) NULL,
            deadline VARCHAR(255) NULL,
            content TEXT NULL,
            file_name varchar(255) NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
        );
    }

    public function website_traffic($table = "website_traffic")
    {
        $this->deleteTable($table);
        $this->createTable(
            $table,
            '
            id INT AUTO_INCREMENT PRIMARY KEY,
            ip_address VARCHAR(50),
            user_agent TEXT,
            page_url TEXT,
            country VARCHAR(50),
            city VARCHAR(50),
            latitude VARCHAR(50),
            longitude VARCHAR(50),
            timezone VARCHAR(50),
            referrer TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
        );
    }
}


?>