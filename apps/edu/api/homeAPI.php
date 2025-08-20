<?php
session_start();
$path = $_SERVER['DOCUMENT_ROOT'];
include $path.'/apps/work/controllers/ExternalProjects.php';

// Create an instance of the Bidding class
$bid = new Bidding();

// Array of queries
$queries = ["php", "javascript", "html", "css", "mysql", "jquery", "vuejs", /* ... */];
$limit = $_GET['limit'] ?? 4;
$result = [];

foreach ($queries as $query) {
    $data = $bid->feeder_home($query, $limit); // Assuming feeder_home is the method you want to use
    $result[$query] = $data;
}

header('Content-Type: application/json');
echo json_encode($result);
?>
