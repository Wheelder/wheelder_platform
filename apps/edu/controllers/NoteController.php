<?php
$path = $_SERVER['DOCUMENT_ROOT'];
include $path . '/pool/libs/controllers/Controller.php';


define('OPENAI_API_ENDPOINT', 'https://api.openai.com/v1/chat/completions');
define('OPENAI_API_KEY', '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'); // Replace 'YOUR_OPENAI_API_KEY' with your actual API key



class NoteController extends Controller
{
    private $open_ai = "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";

    public function __construct()
    {
        parent::__construct();
    }

    public function getNotesByTitle($title)
    {
        $sql = "SELECT * FROM notes_data WHERE title = '$title'";
        $stmt = $this->run_query($sql);
        $notes = $stmt->fetch_assoc();
        return $notes;
    }

    public function get_note_edit($id)
    {
        $sql = "SELECT * FROM notes_data WHERE id = '$id'";
        $stmt = $this->run_query($sql);
        $notes = $stmt->fetch_assoc();
        return $notes;
    }

    public function check_auth()
    {
        // Check if the session is not set
        if (!isset($_SESSION['user_id'])) {
            // Store the current page URL in a session variable
            $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];

            // Redirect the user to the login page or any other desired page
            header('Location: /login?dp=2'); // Replace "login.php" with the desired URL
            exit;
        }
    }
    public function getNote($title)
    {
        $sql = "SELECT * FROM notes_data WHERE title LIKE '%$title%' AND status = 1";
        $stmt = $this->run_query($sql);
        while ($row = $stmt->fetch_assoc()) {
            $notes[] = $row;
            $content = $row['content'];
            $img = $row['image'];

            if ($img == "" or $img == null) {
                $img = "/storage/notes/technology.png";
            }

            $content = str_replace('$title', '', $content);

            // Use regular expressions to find code snippets and wrap them in a textarea, here remove the bash and the $title


            $content = preg_replace('/```(.*?)```/s', '<textarea id="codearea" class="codetextarea" rows="5" cols="65">$1</textarea>', $content);
            $content = str_replace('bash', '', $content);
            //$content = preg_replace('/```(?!bash)(.*?)```/s', '<div class="codeblock"><textarea class="codetextarea" rows="7" cols="70">'.strtolower('$1').'</textarea><button class="copybutton">Copy</button></div>', $content);
            //get section titles list and replace the section titles with the section titles list assigned to the variable $section_titles

            $content = preg_replace('/\*\*(.*?)\*\*/', '<a href="#$1">$1</a>', $content);

            echo "<div class='content' id='contentDiv'>";

            echo "<div class='content-details'>";
            echo "<h4>{$row['title']}</h4><br>";

            echo "{$content}";
            echo "</div>";
            // Add your content diagram
            echo "</div>";
            echo "&nbsp";
            echo "<div class='content-diagram'><img  src='{$img}' alrt='Diagram'/></div>";
        }
    }

    public function get_default_note($title)
    {
        $sql = "SELECT * FROM notes_data WHERE title = '$title'";
        $stmt = $this->run_query($sql);
        while ($row = $stmt->fetch_assoc()) {
            $notes[] = $row;
            $content = $row['content'];
            //$content = preg_replace('/(\S+\s*){10}/', '$0<br/>', $content);

            echo "<div class='content' id='contentDiv'>";

            echo "<div class='content-details'>";

            echo "{$content}";
            echo "</div>";
            // Add your content diagram
            echo "</div>";
            echo "&nbsp";
            echo "<div class='content-diagram'><img  src='' alrt='Diagram'/></div>";
            

        }
    }



    public function list_notes()
    {
        $sql = "SELECT * FROM notes_data ORDER BY id DESC";
        $stmt = $this->run_query($sql);
        return $stmt;
    }

    /*

    public function titles()
    {
        $sql = "SELECT title FROM notes_data WHERE status = 1 ORDER BY id DESC";
        $stmt = $this->run_query($sql);
        while ($row = $stmt->fetch_array()) {
            $titles[] = $row;
            //echo "<a href='?t=" . urlencode($row['title']) . "'>{$row['title']}</a><br>";
            $title = $row["title"];
            //trime the Mastering from the title
            //$title = preg_replace('/Mastering/', '', $title);

            //make to lower case and use _ inplace of + to
            $title = str_replace(" ", "_", strtolower($title));

            if ($row["title"] != "Home") {
                echo
                    '
                <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="?t=' . urldecode($title) . '">
                    <span data-feather="home" class="align-text-bottom"></span><i class="fas fa-star"></i>&nbsp' .
                    $row["title"] . '
                </a>
            </li>
            ';

            }
        }
    }
    */

    public function titles($category)
    {
        $sql = "SELECT title FROM notes_data WHERE status = 1 AND category='$category' ORDER BY id DESC";
        $stmt = $this->run_query($sql);
        while ($row = $stmt->fetch_array()) {
            $titles[] = $row;
            //echo "<a href='?t=" . urlencode($row['title']) . "'>{$row['title']}</a><br>";
            $title = $row["title"];
            //trime the Mastering from the title
            //$title = preg_replace('/Mastering/', '', $title);

            //make to lower case and use _ inplace of + to
            $title = str_replace(" ", "_", strtolower($title));

            if ($row["title"] != "Home") {
                echo
                    '
                <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="?t=' . urldecode($title) . '">
                    <span data-feather="home" class="align-text-bottom"></span><i class="fas fa-star"></i>&nbsp' .
                    $row["title"] . '
                </a>
            </li>
            ';

            }
        }
    }



    //write insert function without PDO

    public function insert($title, $category, $img_path, $content)
    {
        $title = $this->connectDb()->real_escape_string($title);
        //$category = $this->connectDb()->real_escape_string($category);
        $content = $this->connectDb()->real_escape_string($content);

        // Insert note into database, for demonstration assuming there is a table 'notes'
        $sql = "INSERT INTO notes (title, category,image,content) 
        VALUES ('$title','$category','$img_path','$content')";
        $stmt = $this->run_query($sql);
        if ($stmt) {
            return true;
        } else {
            return false;
        }
    }

    public function insert_data($title, $img_path,$category,$content,$status)
    {
        $title = $this->connectDb()->real_escape_string($title);
        
        $content = $this->connectDb()->real_escape_string($content);

        // Insert note into database, for demonstration assuming there is a table 'notes'
        $sql = "INSERT INTO notes_data(title,image,category,content,status) 
        VALUES ('$title','$img_path','$category','$content','$status')";
        $stmt = $this->run_query($sql);
        if ($stmt) {
            return true;
        } else {
            return false;
        }
    }
    //update the 



    //write update function like the insert function with prepared statement, pdo

    public function update($id, $title, $category,$image,$content,$status)
    {
        $title = $this->connectDb()->real_escape_string($title);
        //$category = $this->connectDb()->real_escape_string($category);
        $content = $this->connectDb()->real_escape_string($content);

        //update the notes

        $sql = "UPDATE notes_data SET title='$title',content='$content',status='$status',category='$category',image='$image' where id='$id'";

        $stmt = $this->run_query($sql);
        if ($stmt) {
            return true;
        } else {
            return false;
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


    public function generateResponse($userInput)
    {
        $data = [
            'model' => 'gpt-3.5-turbo-16k-0613',
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


    public function delete($id)
    {
        // Delete note from the database
        $sql = "DELETE FROM notes_data WHERE id = '$id'";
        $stmt = $this->run_query($sql);
        if ($stmt) {
            return true;
        } else {
            return false;
        }
    }

}