<?php
$path = $_SERVER['DOCUMENT_ROOT'];
include $path . '/pool/config/database.php';



class Migration extends Database
{
  public $table;



  public function createFilesTable($table = "files")
  {
    $this->deleteTable($table);
    $this->createTable(
      $table,
      '
          id INT AUTO_INCREMENT PRIMARY KEY,
          file_path TEXT,
          key_id VARCHAR(50),
          user_id INT,
          created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        
          '
    );
  }

  public function users_table($table = "users")
  {

    $this->deleteTable($table);
    //$this->copyTable($table, $table . "2");
    $this->createTable(
      $table,
      '
      id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
      subscription_id VARCHAR(255) NULL,
      phone VARCHAR(255) NULL,
      first_name VARCHAR(255) NULL,
      last_name VARCHAR(255) NULL,
      selected_topics TEXT NULL,
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


  public function storage_table($table = "storage")
  {

    $this->deleteTable($table);
    $this->createTable(
      $table,
      '
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
user_id INT(6) NULL,
title TEXT NULL,
file_name varchar(255) NULL,
file_mime varchar(255) NULL,
file_data longblob NULL,
date_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
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
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        '
    );
  }


  // Function to create the cities table
  public function create_cities_table($table = "cities")
  {
    $this->deleteTable($table);
    $this->createTable(
      $table,
      '
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    city_name VARCHAR(255) NOT NULL,
    date_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
    );

    // Call the fill_cities_table function to populate the table
    $this->fill_cities_table($table);
  }

  // Function to fill the cities table
  public function fill_cities_table($table)
  {
    // Initialize cURL
    $ch = curl_init();

    // API Endpoint for fetching cities
    $api_url = 'http://geodb-free-service.wirefreethought.com/v1/geo/cities?limit=1000'; // Replace with the actual API endpoint
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute cURL request and decode the response
    $response = curl_exec($ch);
    $response_data = json_decode($response, true);

    // Close the cURL resource
    curl_close($ch);

    // Use your connectDb method to get the database connection
    $conn = $this->connectDb();

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    if (!empty($response_data['data'])) {
      foreach ($response_data['data'] as $city) {
        $city_name = $city['city'];

        // SQL to insert city
        $sql = "INSERT INTO $table (city_name) VALUES ('" . mysqli_real_escape_string($conn, $city_name) . "')";

        if ($conn->query($sql) === FALSE) {
          echo "Error: " . $sql . "<br>" . $conn->error;
        }
      }
    }

    // Close connection
    $conn->close();
  }


}
$action = $_GET['action'];

if ($action == 'cr') {

  $db = new Migration();



  $db->users_table($table = "users");

  $db->createFilesTable($table = "files");


  $db->storage_table();
  
  
  $db->website_traffic($table = "website_traffic");
}






