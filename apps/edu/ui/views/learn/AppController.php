<?php
$path = $_SERVER['DOCUMENT_ROOT'];
include $path . '/pool/libs/controllers/Controller.php';
define('OPENAI_API_ENDPOINT', 'https://api.openai.com/v1/chat/completions');
define('OPENAI_API_KEY', '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'); // Replace 'YOUR_OPENAI_API_KEY' with your actual API key

//$admin_key="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"



class AppController extends Controller
{
    private $open_ai = "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";

    public function check_auth()
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
    
    public function generateResponse($userInput)
    {
        $data = [
            'model' => 'gpt-4o',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $userInput
                ]
            ],
            'temperature' => 1,
            'max_tokens' => 10000,
            'top_p' => 1,
            'frequency_penalty' => 0,
            'presence_penalty' => 0
        ];

        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . OPENAI_API_KEY
        ];

        $ch = curl_init(OPENAI_API_ENDPOINT);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            return json_encode(['error' => curl_error($ch)]);
        }

        curl_close($ch);

        $responseData = json_decode($response, true);

        if (isset($responseData['choices'][0]['message']['content'])) {
            return $responseData['choices'][0]['message']['content'];
        } else {
            return json_encode(['error' => 'Content not found in the response']);
        }
    }
 
    public function generateImage($prompt)
    {
        // Set the API endpoint URL
        $url = "https://api.openai.com/v1/images/generations";

        // Set the headers
        $headers = array(
            "Content-Type: application/json",
            "Authorization: Bearer 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" // Replace with your OpenAI API key
        );

        // Set the data as an array
        $data = array(
            "model"=>"dall-e-3",
            "prompt" => $prompt,
            "n" => 1,
            "size" => "1024x1024"
        );

        // Initialize cURL session
        $ch = curl_init($url);

        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        // Execute the cURL request
        $response = curl_exec($ch);

        // Check for cURL errors
        if (curl_errno($ch)) {
            echo 'cURL Error: ' . curl_error($ch);
        }

        // Close cURL session
        curl_close($ch);

        // Decode the JSON response
        $res = json_decode($response, true);

        // Check if the response contains 'data' and is not empty
        if (isset($res['data']) && !empty($res['data'])) {
            // Get the URL from the response
            $img_url = $res['data'][0]['url'];
            return $img_url;
        } else {
            return "No image URL found in the response.";
        }
    }

    //store data in the MySQL database in questions table 
    





    function storeData($questions, $answers, $images) {
        try {
            // Connect to the SQLite database
            $db = new PDO('sqlite:database.sqlite');
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            // Escape and quote strings for safe use with exec (not recommended for user input)
            $questions = $db->quote($questions);
            $answers = $db->quote($answers);
            $images = $db->quote($images);
    
            // Build and execute the query
            $query = "INSERT INTO ans_data (questions, answers, images) VALUES ($questions, $answers, $images)";
            $db->exec($query);
    
           // echo "Data inserted successfully!";
        } catch (PDOException $e) {
            echo "Insert failed: " . $e->getMessage();
        }
    }

    function getAllData() {
        try {
            // Connect to the SQLite database
            $db = new PDO('sqlite:database.sqlite');
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            // Query to select all data
            $query = "SELECT * FROM ans_data";
            $stmt = $db->query($query);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            // Display the data
            if ($results) {
                foreach ($results as $row) {
                    echo "ID: " . $row['id'] . "<br>";
                    echo "Questions: " . $row['questions'] . "<br>";
                    echo "Answers: " . $row['answers'] . "<br>";
                    echo "Images: " . $row['images'] . "<br>";
                    echo "<hr>";
                }
            } else {
                echo "No data found.";
            }
        } catch (PDOException $e) {
            echo "Select failed: " . $e->getMessage();
        }
    }


}