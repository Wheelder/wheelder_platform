<?php
//session_start();
$path = $_SERVER['DOCUMENT_ROOT'];
require $path . '/pool/config/database.php';
include 'StripeController.php';
include 'CrudController.php';
include 'Metadata.php';
//namespace Pool\Libs\Controllers;

class Controller extends Database
{

    use StripeController, CrudController, Metadata;

    public function __construct()
    {

        parent::__construct();
    }

    // Encryption function
    function encrypt($data, $key)
    {
        // Generate a random IV (Initialization Vector)
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));

        // Encrypt the data
        $encrypted = openssl_encrypt($data, 'aes-256-cbc', $key, 0, $iv);

        // Combine IV and encrypted data and encode in base64
        return base64_encode($iv . $encrypted);
    }

    // Decryption function
    function decrypt($data, $key)
    {
        // Decode the base64 encoded data
        $data = base64_decode($data);

        // Extract IV and encrypted data
        $iv = substr($data, 0, openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = substr($data, openssl_cipher_iv_length('aes-256-cbc'));

        // Decrypt the data
        $decrypted = openssl_decrypt($encrypted, 'aes-256-cbc', $key, 0, $iv);

        return $decrypted;
    }


    public function popup($message)
    {
        echo '<div id="customPopup" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Custom Popup</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>' . $message . '</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>';
        echo '<script>
        $(document).ready(function() {
            $("#customPopup").modal("show");
            setTimeout(function() {
                $("#customPopup").modal("hide");
            }, 3000); // Close the popup after 3 seconds
        });
    </script>';
    }


    //write a function to store the user, ip address, country, city, latitude, longitude, timezone, and date of visit into the database website_traffic table

    public function store_website_traffic($ip, $country, $city, $latitude, $longitude, $timezone)
    {
        $sql = "INSERT INTO website_traffic (ip_address, country, city, latitude, longitude, timezone) VALUES ('$ip', '$country', '$city', '$latitude', '$longitude', '$timezone')";
        $this->run_query($sql);
    }

    //write a function to parse the IP into

    public function get_ip_info($ip, $type)
    {
        $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
        return $ipdat->{'geoplugin_' . $type};
    }

    //write a function that returns the whole the industries as select list

    function get_industries()
    {
        $industries = [
            'Automotive',
            'Banking',
            'Construction',
            'Education',
            'Energy',
            'Fashion',
            'Food and Beverage',
            'Healthcare',
            'Information Technology',
            'Manufacturing',
            'Media and Entertainment',
        ];

        $selectList = '<select name="industry" class="bg-white w-100 border border-secondary border-opacity-25 p-2 fs-14 rounded-2">';
        foreach ($industries as $industry) {

            $selectList .= '<option value="' . $industry . '">' . $industry . '</option>';
        }
        $selectList .= '</select>';

        return $selectList;
    }

    //write to display all the data from website_traffic table

    public function display_website_traffic()
    {
        $sql = "SELECT * FROM website_traffic ORDER BY id DESC";
        $results = $this->run_query($sql);
        $count = 0;
        if ($results) {
            while ($row = $results->fetch_assoc()) {
                $count++;
                echo '<tr>
                <td>' . $count . '</td>
                <td>' . $row['ip_address'] . '</td>
                <td>' . $row['country'] . '</td>
                <td>' . $row['city'] . '</td>
                <td>' . $row['latitude'] . '</td>
                <td>' . $row['longitude'] . '</td>
                <td>' . $row['timezone'] . '</td>
                <td>' . $row['created_at'] . '</td>
                </tr>';
            }
        }
    }


    //write a function that returns the whole the sub-industries as select list
    function get_sub_industries()
    {
        $sub_industries = [
            'Automotive' => ['Car Manufacturing', 'Car Dealerships', 'Auto Parts'],
            'Banking' => ['Retail Banking', 'Investment Banking', 'Mortgage Lending'],
            'Construction' => ['Residential Construction', 'Commercial Construction', 'Civil Engineering'],
            'Education' => ['Primary Education', 'Secondary Education', 'Higher Education'],
            'Energy' => ['Renewable Energy', 'Oil and Gas', 'Utilities'],
            'Fashion' => ['Apparel', 'Accessories', 'Footwear'],
            'Food and Beverage' => ['Restaurants', 'Beverage Production', 'Food Processing'],
            'Healthcare' => ['Hospitals', 'Pharmaceuticals', 'Medical Devices'],
            'Information Technology' => ['Software Development', 'Hardware Manufacturing', 'IT Consulting'],
            'Manufacturing' => ['Automobiles', 'Electronics', 'Textiles'],
            'Media and Entertainment' => ['Film and Television', 'Music', 'Gaming'],
        ];

        $selectList = '<select name="subindustry" class="bg-white w-100 border border-secondary border-opacity-25 p-2 fs-14 rounded-2">';
        foreach ($sub_industries as $industry => $sub_industry_list) {
            $selectList .= '<optgroup label="' . $industry . '">';
            foreach ($sub_industry_list as $sub_industry) {
                $selectList .= '<option value="' . $sub_industry . '">' . $sub_industry . '</option>';
            }
            $selectList .= '</optgroup>';
        }
        $selectList .= '</select>';

        return $selectList;
    }


    function getCountriesList()
    {
        $apiUrl = 'https://restcountries.com/v3.1/all'; // API endpoint to retrieve all countries

        // Make an HTTP GET request
        $response = file_get_contents($apiUrl);

        if ($response === false) {
            // Error handling
            return 'Unable to retrieve countries.';
        }

        // Decode the JSON response
        $countriesData = json_decode($response, true);

        if (empty($countriesData)) {
            // Error handling
            return 'Unable to parse country data.';
        }

        // Extract country names from the response
        $countries = array_column($countriesData, 'name', 'name');

        // HTML select list
        $html = '<select name="country">';
        foreach ($countries as $country) {
            $html .= '<option value="' . $country . '">' . $country . '</option>';
        }
        $html .= '</select>';

        // Return the HTML select list
        return $html;
    }





    //Write a function that automatically get the user timezone based on their IP address
    public function getTimezone($ip)
    {
        $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
        return $ipdat->geoplugin_timezone;
    }

    //Write a function that automatically get the user country based on their IP address
    public function getCountry($ip)
    {
        $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
        return $ipdat->geoplugin_countryName;
    }
    //Get viewer IP address
    public function getIP()
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        return $ip;
    }

    //write function that returns the country codes
    public function getCountryCode($ip)
    {
        $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
        return $ipdat->geoplugin_countryCode;
    }

    //write function that get the user latitude and longitude

    public function getLatLong($ip)
    {
        $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
        return $ipdat->geoplugin_latitude . "," . $ipdat->geoplugin_longitude;
    }

    //write function that get the user latitude

    public function getLatitude($ip)
    {
        $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
        return $ipdat->geoplugin_latitude;
    }

    //Write a function that turns the user latitude and longitude into a physical address on google map

    public function getPhysicalAddress($lat, $long)
    {
        $url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=" . $lat . "," . $long . "&sensor=false";
        $data = @file_get_contents($url);
        $jsondata = json_decode($data, true);
        $address = $jsondata["results"][0]["formatted_address"];
        if ($address) {
            return $address;
        } else {
            return "Not found";
        }
    }

    //Write a function that checks html pages find and remove duplicate words, tags, attributes, etc.

    public function removeDuplicate($html)
    {
        $dom = new DOMDocument();
        $dom->loadHTML($html);
        $xpath = new DOMXPath($dom);
        $nodes = $xpath->query('//@*');
        $nodeMap = array();
        foreach ($nodes as $node) {
            $nodeMap[$node->nodeName][] = $node;
        }
        foreach ($nodeMap as $attrName => $attrNodes) {
            $duplicates = array();
            foreach ($attrNodes as $attrNode) {
                $value = $attrNode->nodeValue;
                if (isset($duplicates[$value])) {
                    $duplicates[$value][] = $attrNode;
                } else {
                    $duplicates[$value] = array($attrNode);
                }
            }
            foreach ($duplicates as $attrNodes) {
                $firstAttrNode = array_shift($attrNodes);
                foreach ($attrNodes as $attrNode) {
                    $firstAttrNode->parentNode->removeAttributeNode($attrNode);
                }
            }
        }
        return $dom->saveHTML();
    }



    public function printIterable(iterable $myIterable)
    {
        foreach ($myIterable as $item) {
            echo $item;
        }
    }

    //Write to function to search for a word in all files and replace it with another word

    public function searchAndReplace($search, $replace, $file)
    {
        $file = fopen($file, "r") or die("Unable to open file!");
        $content = fread($file, filesize($file));
        fclose($file);
        $content = str_replace($search, $replace, $content);
        $file = fopen($file, "w") or die("Unable to open file!");
        fwrite($file, $content);
        fclose($file);
    }
    //test this function
    public function searchAndReplaceAll($search, $replace, $dir)
    {
        $files = scandir($dir);
        foreach ($files as $file) {
            $file = fopen($file, "r") or die("Unable to open file!");
            $content = fread($file, filesize($file));
            fclose($file);
            $content = str_replace($search, $replace, $content);
            $file = fopen($file, "w") or die("Unable to open file!");
            fwrite($file, $content);
            fclose($file);
        }
    }

    //Write a function that aviod showing error on null value of the URL variable

    public function url_var($var)
    {

        if (isset($_GET[$var]) && !empty($_GET[$var])) {

            $url_var = $_GET[$var];

            //sanizite the url variable

            //$url_var = filter_var($url_var, FILTER_SANITIZE_URL);
            return $url_var;

        } else {
            return "";
        }


    }


    public function checkSessionAndRedirect()
    {


        // Check if the session is not set
        if (!isset($_SESSION['user_id'])) {
            // Store the current page URL in a session variable
            $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];



            // Redirect the user to the login page or any other desired page
            header('Location: /index.php'); // Replace "login.php" with the desired URL
            exit;
        }
    }



    //Write a function that clear null value from an a variable

    public function var ($var)
    {
        if (isset($var) && !empty($var)) {
            return $var;
        } elseif ($var = null || $var = "" || $var = 0 || $var = false) {
            $var = "";
            return $var;

        } else {
            $var = "";
            return $var;
        }
    }

    //Write a function to pass data to html elements using JavaScript

    public function passData($data)
    {
        echo "<script> var data = " . $data . "</script>";
    }


    public function createTable($table, $columns)
    {

        $conn = $this->run_query;

        $sql = "CREATE TABLE IF NOT EXISTS " . $table . " (" . $columns . ")";

        if ($conn->query($sql) === TRUE) {
            echo "Table " . $table . " created successfully</br>";
        } else {
            echo "Error creating table: " . $conn->error;
        }

        $conn->close();

    }

    public function deleteTable($table)
    {

        $conn = $this->$this->run_query;

        $sql = "DROP TABLE IF EXISTS " . $table;

        if ($conn->query($sql) === TRUE) {
            echo "Table " . $table . " deleted successfully</br>";
        } else {
            echo "Error deleting table: " . $conn->error;
        }

        $conn->close();
    }

    public function createAll($input)
    {
        $data_arr = explode("&", $input);
        foreach ($data_arr as $d) {
            $d_arr = explode("=", $d);


            for ($i = 0; $i < count($d_arr); $i++) {
                return array($d_arr[$i]);
            }


        }
    }

    function store_file($file_name, $target_dir)
    {
        // Ensure the target directory ends with a slash
        if (substr($target_dir, -1) !== '/') {
            $target_dir .= '/';
        }

        $target_file = $target_dir . basename($file_name);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if file already exists
        if (file_exists($target_file)) {
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            return "Upload Failed";
        } else {
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                return $target_file; // Return the full path to the uploaded file
            } else {
                return "Upload Failed";
            }
        }
    }


    public function notications()
    {

        $sql = "SELECT * FROM projects  ORDER BY id DESC";
        $results = $this->run_query($sql);
        $count = 0;
        if ($results) {
            while ($row = $results->fetch_assoc()) {
                echo '<script>alert(' . $row['project_title'] . ');</script>';
            }


        }

    }

    public function alert_redirect($message, $url)
    {
        echo '<script>alert("' . $message . '");window.location.href = "' . $url . '";</script>';
    }

    public function redirect($url)
    {
        echo '<script>window.location.href = "' . $url . '";</script>';
    }



    public function authCheck()
    {

        $auth = $_SESSION['user_id'] = $_SESSION['id'];



        if (!isset($auth)) {

            echo '<script>window.location.href = "/index.php";</script>';


        }

    }





    public function requestedPage()
    {


        $url = parse_url($_SERVER['REQUEST_URI']);
        if ($url != '/') {
            parse_str($url['query']);
            echo $id;
            echo $othervar;
        }

    }
    /*
    public function sanitize($value)
    {
        $value = trim($value);
        $value = stripslashes($value);
        $value = htmlspecialchars($value);
        $value = strip_tags($value);
        $value = htmlentities($value);
        return $value;
    }
    */

    public function secure_login($uid)
    {
        $sql = "SELECT * FROM  users WHERE id='$uid'";
        $result = $this->run_query($sql);
        return $result;
    }

    public function users_verifications($uid, $email, $first_name, $last_name, $role, $phone)
    {

        $sql = "SELECT * FROM users WHERE id='$uid' AND email='$email' AND first_name='$first_name' AND last_name='$last_name' AND role='$role' AND phone='$phone'";
        $result = $this->run_query($sql);
        if ($result) {
            return true;
        } else {
            return false;
        }


    }
    public function update_role($id, $role)
    {
        $sql = "UPDATE users SET role='$role' WHERE id='$id'";
        $result = $this->run_query($sql);
        if ($result) {
            return true;
        } else {
            return false;
        }

    }



    public function inc($file)
    {
        $path = $_SERVER['DOCUMENT_ROOT'];
        $res = include($path . '/' . $file);
        return $res;
    }

    public function timeZone()
    {
        date_default_timezone_set('Canada/Pacific');
    }


    public function checkHost()
    {
        $url = $_SERVER['HTTP_HOST'];
        if ($url == "localhost:3000") {
            return true;
        } else {
            return false;
        }
    }

    public function autoCreate($input)
    {

        $input = file_get_contents("php://input");

        $data_arr = explode("&", $input);

        foreach ($data_arr as $d) {
            $d_arr = explode("=", $d);

            $d_arr = array($d_arr);

            $darr = explode(" ", $d_arr);

            //$d_arr=array($d_arr);
            for ($i = 0; $i < count($darr); $i++) {
                echo $darr[0] . "<br>";
            }


        }

    }





    //create a PDO version of the following function
    public function run_query_pdo($sql, $params = [])
    {
        $result = null;
        try {
            $stmt = $this->connectDbPDO()->prepare($sql);
            $result = $stmt->execute($params);
            return $result;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function run_query($sql)
    {
        try {
            $result = $this->connectDb()->query($sql);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return $result;
    }





    function getData()
    {

        $input = file_get_contents("php://input");

        $data_arr = explode("&", $input);

        foreach ($data_arr as $d) {
            $d_arr = explode("=", $d);

            $d_arr = array($d_arr);

            $darr = explode(" ", $d_arr);

            //$d_arr=array($d_arr);
            for ($i = 0; $i < count($darr); $i++) {
                echo $darr[0] . "<br>";
            }


        }

    }

    //Function to store files 

    public function handleFileUpload($directory, $uploaderId)
    {
        if (isset($_FILES['file'])) {
            $file = $_FILES['file'];

            // Extract file information
            $fileName = $file['name'];
            $fileTmp = $file['tmp_name'];

            // Generate a unique key ID for the file
            $key = uniqid();

            // Get the document root path
            $path = $_SERVER['DOCUMENT_ROOT'];

            // Build the file destination path
            $destination = $path . '/' . $directory . '/' . $fileName;

            // Move the uploaded file to the destination
            if (move_uploaded_file($fileTmp, $destination)) {
                // Save file information to the database
                $this->saveFileToDatabase($destination, $key, $uploaderId);

                // File upload success
                return $key;

            } else {
                // File upload failed
                echo "File upload failed.";
            }
        } else {
            // No file was uploaded
            echo "No file selected.";
        }
    }



    public function saveFileToDatabase($filePath, $key, $uploaderId)
    {
        $query = "INSERT INTO files (file_path, key_id, user_id) VALUES (?, ?, ?)";
        $stmt = $this->run_query->prepare($query);
        $stmt->bind_param("ssi", $filePath, $key, $uploaderId);
        $stmt->execute();
        $stmt->close();
    }
    /*
    public function sending_email($to_email, $from,$message,$subject)
    {

        $to = "$to_email";
      

        $message = "
                   <html>
                   <head>
                  
                   </head>
                   <body>
                   ".$message."
                    </body>
                   </html>
                   ";

        // Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";


        // More headers
        $headers .= 'From: <'.$from.'>' . "\r\n";
    

       $res=mail($to, $subject, $message, $headers);
       if($res){
        return true;
       }else{
        return false;
       }

    }
    */
    public function send_email($to_email,$message,$sub)
    {

        $to = "$to_email";
        $subject = "$sub";

        $message = "
                   <html>
                   <head>
                   <title>".$subject."</title>
                   </head>
                   <body>
                   ".$message."
              
                   
                    </body>
                   </html>
                   ";

        // Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";


        // More headers
        $headers .= 'From: <bafakhri@wheeleder.com>' . "\r\n";
       // $headers .= 'Cc: contact@wheeleder.com' . "\r\n";

        mail($to, $subject, $message, $headers);

    }










}