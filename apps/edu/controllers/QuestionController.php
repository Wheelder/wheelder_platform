<?php
$path = $_SERVER['DOCUMENT_ROOT'];
require_once $path . '/apps/edu/controllers/Controller.php';

define('OPENAI_API_ENDPOINT', 'https://api.openai.com/v1/chat/completions');
define('OPENAI_API_KEY', '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'); // Replace 'YOUR_OPENAI_API_KEY' with your actual API key


class QuestionController extends Controller
{
    private $open_ai = "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";

    public function __construct()
    {
        parent::__construct();
    }

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
            'model' => 'gpt-4.1',
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
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . OPENAI_API_KEY
        ];
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

    public function getBlogByTitle($title)
    {
        $sql = "SELECT * FROM questions WHERE title = '$title'";
        $stmt = $this->run_query($sql);
        $blog = $stmt->fetch_assoc();
        return $blog;
    }

      public function titles()
    {
        $sql = "SELECT DISTINCT question FROM questions ORDER BY id DESC";
        $stmt = $this->run_query($sql);
        while ($row = $stmt->fetch_array()) {
            $titles[] = $row;
            
            $title = $row["question"];
            //make to lower case and use _ inplace of + to
            $title = str_replace(" ", "_", strtolower($title));
            //encode the url and
            
            if ($row["question"] != "Home" ) {
                //convert the title to short encrpted code 
                echo
                    '<div class="toc">
                <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="?t=' .urldecode($title) . '">
                    <span data-feather="home" class="align-text-bottom"></span>' .
                    $row["question"] . '
                </a>
            </li>
            </div>';

            }

        }

    }

    public function get_default_blog($title)
    {
        $sql = "SELECT * FROM questions WHERE question = '$title'";
        $stmt = $this->run_query($sql);
        while ($row = $stmt->fetch_assoc()) {
            $notes[] = $row;
            $content = $row['answer'];
            //$content = preg_replace('/(\S+\s*){10}/', '$0<br/>', $content);

            echo "<div class='content' id='contentDiv'>";


            echo "{$content}";


            echo "</div>";

        }
    }


    public function get_blog_edit($id)
    {
        $sql = "SELECT * FROM questions WHERE id = '$id'";
        $stmt = $this->run_query($sql);
        $blog = $stmt->fetch_assoc();
        return $blog;
    }

    

    public function getBlog($title)
    {
        $title = $this->connectDb()->real_escape_string($title);

        //return back the title to original form
        $title = str_replace("_", " ", $title);
        //camel case the title
        $title = ucwords($title);

        $sql = "SELECT * FROM questions WHERE question = '$title'";
        $stmt = $this->run_query($sql);
        while ($row = $stmt->fetch_assoc()) {
            $blog[] = $row;
            $content = $row['answer'];
            $content=str_replace("*", "", $content);
            $content=str_replace("`", "", $content);
            $content=str_replace("~", "", $content);
            $content=str_replace("#", "", $content);
            $content=str_replace("---", "", $content);
            $content=str_replace("`", "", $content);
            $content=str_replace("**", "", $content);
            $content=str_replace("Certainly!", "", $content);
            $image = $row['filepath'];
            $content = trim($content);

            echo "<div class='content' id='contentDiv'>";
            echo "<h4>{$row['question']}</h4><br>";
            echo "{$content}";
            echo "&nbsp;";
            //echo "<img src='$image' alt='Image' style='width: 100%; height: auto;'>";
            echo "<br>";
            echo "</div>";
        }
    }

    //get image from the database by title
    public function get_image($title)
    {
        $title = $this->connectDb()->real_escape_string($title);

        $sql = "SELECT * FROM questions WHERE question = '$title'";
        $stmt = $this->run_query($sql);
        while ($row = $stmt->fetch_assoc()) {
            $image = $row['filepath'];
            echo "<img src='.$image.' alt='Image' style='width: 100%; height: auto;'>";
        }
    }



    public function list_suggestions()
    {
        $sql = "SELECT * FROM questions ORDER BY id DESC LIMIT 5";
        $stmt = $this->run_query($sql);
        return $stmt;
    }


    public function list_blogs()
    {
        $sql = "SELECT * FROM questions ORDER BY id DESC";
        $stmt = $this->run_query($sql);
        return $stmt;
    }
    /*
    public function insert($question, $answer)
    {
        $answer = $this->connectDb()->real_escape_string($answer);
        $question = $this->connectDb()->real_escape_string($question);
        $sql = "INSERT INTO questions (question, answer) VALUES ('$question','$answer')";
        $stmt = $this->run_query($sql);
        if ($stmt) {
            return true;
        } else {
            return false;
        }
    }
        */

    //write a new insert function for the questions table, insert($question,$unf_answer,$answer,$deep_answer,$option,$image );

    public function insert($question, $unf_answer, $answer, $deep_answer, $option, $image)
    {
        $answer = $this->connectDb()->real_escape_string($answer);
        $question = $this->connectDb()->real_escape_string($question);
        $unf_answer = $this->connectDb()->real_escape_string($unf_answer);
        $deep_answer = $this->connectDb()->real_escape_string($deep_answer);
        $option = $this->connectDb()->real_escape_string($option);
        $image = $this->connectDb()->real_escape_string($image);

        $sql = "INSERT INTO questions (question, unf_answer, answer, deep_answer, option, image) 
        VALUES ('$question','$unf_answer','$answer','$deep_answer','$option','$image')";
        $stmt = $this->run_query($sql);
        if ($stmt) {
            return true;
        } else {
            return false;
        }
    }
    
    /*
    public function insert($title, $category, $content) {
        // Create a prepared statement
        $stmt = $this->connectDb()->prepare("INSERT INTO notes (title, category, content) VALUES (?, ?, ?)");
        
        if ($stmt) {
            // Bind parameters to the placeholders
            $stmt->bind_param("sss", $title, $category, $content);
    
            // Execute the statement
            if ($stmt->execute()) {
                $stmt->close();
                return true;
            }
            $stmt->close();
        }
        return false;
    }
    */
    

    public function update($id, $title, $content)
    {
        $title = $this->connectDb()->real_escape_string($title);
        $content = $this->connectDb()->real_escape_string($content);
        $sql = "UPDATE blogs SET title='$title', content='$content' where id='$id'";
        $stmt = $this->run_query($sql);
        if ($stmt) {
            return true;
        } else {
            return false;
        }
    }

    public function delete($id)
    {
        $sql = "DELETE FROM blogs WHERE id = '$id'";
        $stmt = $this->run_query($sql);
        if ($stmt) {
            return true;
        } else {
            return false;
        }
    }
}
