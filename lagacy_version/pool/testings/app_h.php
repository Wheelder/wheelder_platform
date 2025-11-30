<?php
// Function to decode the code and retrieve original parameters
function decodeAndRedirect($code) {
    // Implement your decoding logic here
    // For this example, we assume the code contains encoded parameters
    $decoded_params = decodeParameters($code);

    // Construct the original URL
    $original_url = "https://wheeleder.com/blog?" . http_build_query($decoded_params);

    // Redirect to the original URL
    header("Location: $original_url");
    exit();
}

// Function to decode the parameters from the code
function decodeParameters($code) {
    // Implement your decoding logic here
    // For this example, we assume the code represents parameters as key-value pairs
    $decoded_params = array();

    // Decode the code and populate the $decoded_params array

    return $decoded_params;
}

// Extract the rewritten URL, e.g., "/8n23$5"
$request_uri = $_SERVER['REQUEST_URI'];

// Extract the code from the rewritten URL
$code = trim($request_uri, '/');

if ($code) {
    // A code is present, decode it and redirect to the original URL
    decodeAndRedirect($code);
} else {
    // No code is present, serve your regular content here
    // This is where you can include your existing styles and scripts
    // include your existing styles
    // include your existing scripts
    echo "This is your main content.";
}
?>
