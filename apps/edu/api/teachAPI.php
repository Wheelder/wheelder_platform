<?php
$path = $_SERVER['DOCUMENT_ROOT'];
include $path . '/wheelder/apps/edu/controllers/NoteController.php';

$note = new NoteController();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the request is a POST request

    if (isset($_POST['search'])) {
        // Get the query from the POST data
        $q = $_POST['query'];
        
        // For image generation, remove words like "what is," "like," "so," etc.
        $imgq = str_replace("what is", "", $q);

        $imgurl = $note->generateImage($imgq);
        $answer = $note->generateResponse($q);

        // Split the sentences, preserving periods at the beginning of sentences
        $sentences = preg_split('/(?<=[.?!])\s+(?=[A-Z])/', $answer);
        $formattedAnswer = implode("<br></br>", $sentences);
        $formattedAnswer = trim($formattedAnswer);

        // Prepare the response as JSON
        $response = array(
            'answer' => $formattedAnswer,
            'imgurl' => $imgurl
        );

        // Set response headers to indicate JSON content
        header('Content-Type: application/json');

        // Output the JSON response
        echo json_encode($response);
    } else {
        // If the 'query' parameter is not set, return an error response
        header('HTTP/1.1 400 Bad Request');
        echo json_encode(array('error' => 'Query parameter missing'));
    }
} else {
    // If it's not a POST request, return an error response
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(array('error' => 'Method not allowed'));
}

?>



