<?php
$path = $_SERVER['DOCUMENT_ROOT'];
require_once $path . '/apps/edu/controllers/Controller.php';

class BlogController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getBlogByTitle($title)
    {
        $sql = "SELECT * FROM blogs WHERE title = '$title'";
        $stmt = $this->run_query($sql);
        $blog = $stmt->fetch_assoc();
        return $blog;
    }

    public function check_auth()
    {
        // Check if the session is not set
        if (!isset($_SESSION['user_id'])) {
            // Store the current page URL in a session variable
            $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];

            // Redirect the user to the login page or any other desired page
            header('Location: /'); // Replace "login.php" with the desired URL
            exit;
        }
    }

    public function titles()
    {
        $sql = "SELECT DISTINCT title FROM blogs ORDER BY id DESC";
        $stmt = $this->run_query($sql);
        while ($row = $stmt->fetch_array()) {
            $titles[] = $row;
            
            $title = $row["title"];
            //make to lower case and use _ inplace of + to
            $title = str_replace(" ", "_", strtolower($title));
            //encode the url and
            
            if ($row["title"] != "Home" ) {
                //convert the title to short encrpted code 
                echo
                    '<div class="toc">
                <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="?t=' .urldecode($title) . '">
                    <span data-feather="home" class="align-text-bottom"></span>' .
                    $row["title"] . '
                </a>
            </li>
            </div>';

            }

        }

    }

    public function get_default_blog($title)
    {
        $sql = "SELECT * FROM blogs WHERE title = '$title'";
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
        $sql = "SELECT * FROM blogs WHERE id = '$id'";
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
            echo "<h4>{$row['content']}</h4><br>";
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
    
    public function insert($title, $content)
    {
        $content = $this->connectDb()->real_escape_string($content);
        $title = $this->connectDb()->real_escape_string($title);
        $sql = "INSERT INTO blogs (title, content) VALUES ('$title', '$content')";
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
