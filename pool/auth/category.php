<?php
session_start();
$path = $_SERVER['DOCUMENT_ROOT'];
include $path . '/pool/libs/controllers/LogsController.php';

$settings = new LogsController();
//$settings->authCheck();

$uid = $_SESSION['user_id'];



include $path . '/pool/assets/layouts/style.php';
?>
<style>
topic {
    border: 1px solid #ddd;
    padding: 10px;
    margin-bottom: 10px;
}

.topic input[type="checkbox"] {
    margin-right: 5px;
}

/* Add more styling as needed */
</style>


<div class="container pt-3">
    <!-- ... Other parts of your form ... -->

    <!-- Search Box -->
    <input type="text" id="searchBox" onkeyup="filterTopics()" placeholder="Search topics..." class="form-control mb-3">

    <!-- Topics Container -->
    <div class="row" id="topicsContainer">
        <!-- Topics will be dynamically inserted here -->
    </div>

    <!-- Submit Button -->
    <button type="submit" class="btn btn-primary" name="select_topics">Save</button>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
    // Example data structure for topics
    var topics = {
        "Physics": ["Quantum Mechanics", "Relativity", "Astrophysics"],
        "Biology": ["Genetics", "Ecology", "Microbiology"],
        "Chemistry": ["Organic Chemistry", "Inorganic Chemistry", "Physical Chemistry"],
        // Add more topics and subtopics here
    };

    var container = document.getElementById("topicsContainer");

    for (var topic in topics) {
        var topicDiv = document.createElement("div");
        topicDiv.classList.add("topic");
        topicDiv.innerHTML = `<strong>${topic}</strong>`;

        topics[topic].forEach(function(subtopic) {
            var checkbox = document.createElement("input");
            checkbox.type = "checkbox";
            checkbox.id = subtopic;
            checkbox.name = subtopic;

            var label = document.createElement("label");
            label.htmlFor = subtopic;
            label.textContent = subtopic;

            topicDiv.appendChild(checkbox);
            topicDiv.appendChild(label);
        });

        container.appendChild(topicDiv);
    }
});

function filterTopics() {
    var input = document.getElementById("searchBox");
    var filter = input.value.toLowerCase();
    var nodes = document.getElementsByClassName("topic");

    Array.from(nodes).forEach(function(node) {
        if (node.textContent.toLowerCase().includes(filter)) {
            node.style.display = "";
        } else {
            node.style.display = "none";
        }
    });
}
</script>





