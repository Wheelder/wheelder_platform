<?php
$path = $_SERVER['DOCUMENT_ROOT'];
include $path . '/apps/edu/controllers/BlogController.php';
$blog = new BlogController();

//$blog->checkSessionAndRedirect();

$ip = $_SERVER['REMOTE_ADDR'];

$apiKey = '278eec80f63acc'; // Replace with your actual API key from IPinfo
$url = "http://ipinfo.io/$ip/json?token=$apiKey";

$response = file_get_contents($url);
$data = json_decode($response, true);

$country = isset($data['country']) ? $data['country'] : "";
$city = isset($data['city']) ? $data['city'] : "";
$latitude = isset($data['loc']) ? explode(',', $data['loc'])[0] : "";
$longitude = isset($data['loc']) ? explode(',', $data['loc'])[1] : "";
$timezone = isset($data['timezone']) ? $data['timezone'] : "";
$date = date("Y-m-d H:i:s");

$blog->store_website_traffic($ip, $country, $city, $latitude, $longitude, $timezone);


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>
        Wheeleder
    </title>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <script>
        //reload the page again when its opened in mobile in-app broweser

        if (window.matchMedia('(display-mode: standalone)').matches) {
            window.location.reload();
        }
    </script>
    <style>
        @media (max-width: 768px) {

            /* Dark mode styles */
            .dark-mode {
                background-color: #000;
                color: #fff;
            }

            .dark-mode .nav-item {
                color: #fff;
            }

            .tl {
                font-family: 'Courier New', Courier, monospace;
                font-size: 10px;
                font-weight: 300;
            }


            .dark-mode .toc {
                color: #fff;
            }

            .dark-mode .btn {
                color: #fff;
            }

            .dark-mode .sidebar a {
                color: #fff;
                background-color: #000;
            }

            .dark-mode .sidebar {
                color: #fff;
                background-color: #000;
                box-shadow: 10px 10px 10px #ccc;
            }

            .dark-mode .header {
                color: #fff;
                background-color: #000;
                box-shadow: 10px 10px 10px #fff;
            }


            .dark-mode #sidebarMenu {
                color: #fff;
                background-color: #000;
            }

            .challenge {
                background-color: #000;
                color: #fff;
            }



            .dark-mode .content {
                background-color: #000;
                color: #fff;
            }

            .dark-mode .controls {
                background-color: #000;
                color: #fff;
            }



            .dark-mode .highlighted {
                background-color: #444;
            }

            .dark-mode #start,
            .dark-mode #pause,
            .dark-mode #resume,
            .dark-mode #fullscreen,
            .dark-mode #copyToClipboard,
            .dark-mode #printContent,
            .dark-mode #increaseFontSize,
            .dark-mode #decreaseFontSize,
            .dark-mode #darkMode,
            .dark-mode #lightMode {
                color: #fff;
                /* Change the color to white */
            }

            .nav-item {
                color: #000;
            }


            .btn {
                font-size: 20px;
            }


            .content {
                width: 100%;
                padding: 0px;
                height: auto;
                /* Remove fixed height for responsiveness */
                overflow-y: auto;
                overflow-x: hidden;
                border: 1px solid #ccc;
                background-color: #f1f1f1;
                /* Disable horizontal scroll */
                white-space: pre-wrap;
                font-family: Verdana, sans-serif;
                font-size: 17px;
                font-weight: 400;
                /* Handle line breaks */
                border: 2px solid #ccc;
                border-radius: 10px;
                box-shadow: 8px 8px 8px #ccc;
            }

            .tl {
                display: none;
            }

            .controls {
                position: relative;
                bottom: 0;
                width: 100%;
                background-color: #fff;
            }

            .highlighted {
                background-color: #ccc;
            }



            /* Move progress bar to the footer-controls and set its position */
            #progressBarContainer {
                position: absolute;
                right: 10px;
                top: 10%;
                width: 20px;
                border-radius: 3px;
                height: 90%;
                z-index: 100;
                display: flex;
                flex-direction: column;
                align-items: flex-start;
            }

            #progressBar {
                width: 5px;
                height: 0;
                background: #727272;
            }

            .nav-scroller {
                position: relative;
                z-index: 2;
                height: 2.75rem;
                overflow-y: hidden;
            }

            .nav-scroller .nav {
                display: flex;
                flex-wrap: nowrap;
                padding-bottom: 1rem;
                margin-top: -1px;
                overflow-x: auto;
                text-align: center;
                white-space: nowrap;
                -webkit-overflow-scrolling: touch;
            }

            body {
                font-size: .875rem;
            }

            .feather {
                width: 16px;
                height: 16px;
            }


            .sidebar {
                position: relative;
                top: 0;
                bottom: 0;
                /* rtl:remove */
                left: 0;
                z-index: 100;
                /* Behind the navbar */
                padding: 48px 0 0;
                background-color: white;

                /* Height of navbar */
                box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
            }

            .sidebar-sticky {
                height: calc(100vh - 48px);
                overflow-x: hidden;
                overflow-y: auto;
                /* Scrollable contents if viewport is shorter than content. */
            }

            .sidebar .nav-link {
                font-weight: 500;
                color: #fff;
            }

            .sidebar .nav-link .feather {
                margin-right: 4px;
                color: #fff;
            }

            .dark-mode .sidebar .nav-link.active {
                color: #fff;
            }

            .sidebar .nav-link.active {
                color: #000;
            }

            .sidebar .nav-link:hover .feather,
            .sidebar .nav-link.active .feather {
                color: inherit;
            }

            .sidebar-heading {
                font-size: .30rem;
            }

            .navbar-brand {
                padding-top: .75rem;
                padding-bottom: .75rem;
                background-color: rgba(0, 0, 0, .25);
                box-shadow: inset -1px 0 0 rgba(0, 0, 0, .25);
            }

            .navbar .navbar-toggler {
                top: .25rem;
                right: 1rem;
            }

            .navbar .form-control {
                padding: .75rem 1rem;
            }

            .form-control-dark {
                color: #fff;
                background-color: rgba(255, 255, 255, .1);
                border-color: rgba(255, 255, 255, .1);
            }

            .form-control-dark:focus {
                border-color: transparent;
                box-shadow: 0 0 0 3px rgba(255, 255, 255, .25);
            }




        }

        html,
        body {
            height: 100%;
            padding: 0;
            position: relative;
        }

        .btn {
            font-size: 20px;
        }

        .challenge {
            background-color: #000;
            color: #fff;
        }


        #resume,
        #fullscreen,
        #copyToClipboard,
        #printContent,
        #increaseFontSize,
        #decreaseFontSize,

        #lightmode,
        #shareButton {
            margin: 3px;
        }


        .header {
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 8px 8px 8px #ccc;
        }

        .toc {

            overflow-y: auto;
            overflow-x: hidden;

        }

        .tl {
            width: 100%;
            font-size: 25px;
            font-weight: 600;
        }




        .content {
            width: 100%;
            padding: 0px;
            height: 640px;
            overflow-y: auto;
            overflow-x: hidden;
            background-color: #fff;
            white-space: pre-wrap;
            border: 2px solid #ccc;
            font-family: Verdana, sans-serif;
            font-size: 17px;
            font-weight: 400;
            border-radius: 10px;
            box-shadow: 8px 8px 8px #ccc;
        }



        .highlighted {
            background-color: #ccc;
        }

        .content {
            padding: 2px;
        }



        .sidebar {
            position: fixed;
            top: 0;
            margin: auto;
            bottom: 0;
            left: 0;
            width: 260px;
            z-index: 100;
            padding: 40px 0 0;
            overflow-y: auto;
            overflow-x: hidden;
            background-color: white;
            color: #000;
            border: 1px solid #ccc;
            border-radius: 7px;
            box-shadow: 8px 8px 8px #ccc;
        }


        /* Move progress bar to the footer-controls and set its position */
        #progressBarContainer {
            position: absolute;
            left: 255px;
            top: 10%;
            width: 20px;
            border-radius: 3px;
            height: 90%;
            z-index: 100;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        #progressBar {
            width: 15px;
            height: 0;
            background: #727272;
        }

        #progressPercentage {
            font-size: 14px;
        }

        .nav-item,
        .nav-link,
        .nav-link.active {
            color: #000;
            font-size: 15px;
            font-weight: 400;
        }

        .nav-item {
            margin: 3px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 3px 3px 3px #ccc;
        }




        .highlightSentence {
            background-color: yellow;
        }


        .highlighted {
            background-color: yellow;
        }

        .word-highlight {
            background-color: orange;
            border: 2px solid red;
        }

        .sentence-highlight {
            background-color: yellow;
        }

        .word-highlight {
            background-color: orange;
            border: 2px solid red;
        }

        a {
            text-decoration: none;
            class: btn-link;
        }

        /* Add this to your existing CSS or style block */
        .scrollable-content {
            max-height: 600px;
            /* Adjust the height as per your requirement */
            overflow-y: auto;
        }

        /* Dark mode styles */
        .dark-mode {
            background-color: #000;
            color: #fff;
        }

        .dark-mode .nav-item {
            color: #fff;
        }

        .dark-mode .btn {
            color: #fff;
        }

        .dark-mode .sidebar a {
            color: #fff;
            background-color: #000;
        }

        .dark-mode .sidebar {
            color: #fff;
            background-color: #000;
            box-shadow: 10px 10px 10px #ccc;
        }

        .dark-mode .header {
            color: #fff;
            background-color: #000;
            box-shadow: 10px 10px 10px #fff;
        }


        .dark-mode #sidebarMenu {
            color: #fff;
            background-color: #000;
        }



        .dark-mode .content {
            background-color: #000;
            color: #fff;
        }

        .dark-mode .controls {
            background-color: #000;
            color: #fff;
        }

        .dark-mode .highlighted {
            background-color: #444;
        }

        .dark-mode #start,
        .dark-mode #pause,
        .dark-mode #resume,
        .dark-mode #fullscreen,
        .dark-mode #copyToClipboard,
        .dark-mode #printContent,
        .dark-mode #increaseFontSize,
        .dark-mode #decreaseFontSize,
        .dark-mode #darkMode,
        .dark-mode #lightMode {
            color: #fff;
            /* Change the color to white */
        }


        @media (max-width: 767.98px) {
            .sidebar {
                top: 5rem;
            }
        }
    </style>

</head>

<body>

    <header class="navbar navbar-dark sticky-top bg-dark  flex-md-nowrap p-0 shadow">

        <a class="navbar-brand col-md-2 col-lg-2 me-0 px-3 fs-6" href="/lab">Wheeleder's Lab</a>

        <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>


        <div class="controls  form-control w-100 rounded-0 border-0">
            <!-- Icons with IDs -->
            

            <i id="start" class="fas fa-play"></i>

            <i id="pause" class="fas fa-pause"></i>

            <i id="resume" class="fas fa-step-forward"></i>
            |
            <i id="fullscreen" class="fas fa-expand"></i>
            |
            <i id="copyToClipboard" class="far fa-copy"></i>
            |
            <i id="printContent" class="fas fa-print"></i>
            |
            <i class="fas fa-share-alt" id="shareButton"></i>
            |
            <i id="increaseFontSize" class="fas fa-search-plus"></i>
            <i id="decreaseFontSize" class="fas fa-search-minus"></i>
            |
            <i id="darkMode" class="fas fa-moon"></i>

            <i id="lightMode" class="fas fa-sun"></i>

        
        </div>


    </header>



    <div class="container-fluid">
        <div class="row">

            <nav id="sidebarMenu" class="col-md-2 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3 sidebar-sticky">


                    <ul class="nav flex-column">

                        <?php
                        $blog->titles();
                        ?>

                    </ul>
                </div>


            </nav>

            <main class="col-md-10 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 ">


                    <?php
                    $title = $_GET['t'] ?? Null;
                    if ($title) {

                        $blog->getBlog($title);
                    } else {

                        $blog->get_default_blog("Home");
                    }


                    ?>



                </div>


            </main>
        </div>
    </div>

    <script>
        document.getElementById('shareButton').addEventListener('click', function () {
            if (navigator.share) {
                navigator.share({
                    title: 'Check out this link',
                    url: window.location.href
                })
                    .then(() => console.log('URL shared'))
                    .catch((error) => console.error('Error sharing URL', error));
            } else {
                // Fallback for browsers that don't support the Web Share API

                prompt('Copy this URL and share it manually', window.location.href);
                //alert('Web Share API is not supported in this browser. You can manually copy the URL.');
            }
        });
    </script>



    <script>
        $('#myModal').on('shown.bs.modal', function () {
            $('#myInput').trigger('focus')
        })

        document.getElementById('shareButton').addEventListener('click', function () {
            if (navigator.share) {
                navigator.share({
                    title: 'Check out this link',
                    url: window.location.href
                })
                    .then(() => console.log('URL shared'))
                    .catch((error) => console.error('Error sharing URL', error));
            } else {
                // Fallback for browsers that don't support the Web Share API
                alert('Web Share API is not supported in this browser. You can manually copy the URL.');
            }
        });
    </script>
    <!-- JavaScript Code -->
    <script>



        // Function to enable dark mode
        function enableDarkMode() {
            document.body.classList.add('dark-mode');
        }

        // Function to enable light mode
        function enableLightMode() {
            document.body.classList.remove('dark-mode');
        }

        // Add click event listeners to the buttons
        document.getElementById('darkMode').addEventListener('click', enableDarkMode);
        document.getElementById('lightMode').addEventListener('click', enableLightMode);

        // Function to increase font size
        function increaseFontSize() {
            const contentDiv = document.querySelector('.content');
            const currentSize = window.getComputedStyle(contentDiv).fontSize;
            const newSize = parseFloat(currentSize) * 1.2; // Increase by 20%
            contentDiv.style.fontSize = newSize + 'px';
        }

        // Function to decrease font size
        function decreaseFontSize() {
            const contentDiv = document.querySelector('.content');
            const currentSize = window.getComputedStyle(contentDiv).fontSize;
            const newSize = parseFloat(currentSize) / 1.2; // Decrease by 20%
            contentDiv.style.fontSize = newSize + 'px';
        }

        // Add click event listeners to the buttons
        document.getElementById('increaseFontSize').addEventListener('click', increaseFontSize);
        document.getElementById('decreaseFontSize').addEventListener('click', decreaseFontSize);

        // Print Button Click Event
        const printButton = document.getElementById('printContent');
        printButton.addEventListener('click', function () {
            const content = document.querySelector('.content');

            if (window.print) {
                // Modern browsers supporting window.print()
                window.print();
            } else {
                // For browsers that don't support window.print(), open a new window
                const printWindow = window.open('', '_blank');

                // Write the content to the new window
                printWindow.document.open();
                printWindow.document.write('<html><head><title>Print</title></head><body>');
                printWindow.document.write('<div class="content">' + content.innerHTML + '</div>');
                printWindow.document.write('</body></html>');
                printWindow.document.close();

                // Call the print function in the new window
                printWindow.print();

                // Close the new window after printing
                printWindow.close();
            }
        });


        // Clipboard Button Click Event
        const copyButton = document.getElementById('copyToClipboard');
        copyButton.addEventListener('click', function () {
            const content = document.querySelector('.content');
            const textToCopy = content.innerText;

            // Create a textarea element to hold the text
            const textarea = document.createElement('textarea');
            textarea.value = textToCopy;
            textarea.setAttribute('readonly', '');
            textarea.style.position = 'absolute';
            textarea.style.left = '-9999px';

            // Append the textarea to the document
            document.body.appendChild(textarea);

            // Select and copy the text
            textarea.select();
            document.execCommand('copy');

            // Remove the textarea from the document
            document.body.removeChild(textarea);

            // Provide user feedback (you can customize this)
            alert('Content copied to clipboard!');
        });




        const synth = window.speechSynthesis;
        const contentDiv = document.querySelector('.content');
        const sentences = contentDiv.innerText.split('. ');

        let currentSentence = 0;

        function speakText(startIndex) {
            if (startIndex >= sentences.length) return;

            // Cancel any previously running synthesis
            synth.cancel();

            let utterance = new SpeechSynthesisUtterance(sentences[startIndex]);
            utterance.onend = function () {
                currentSentence = startIndex + 1;
                // Remove the highlight when the sentence ends
                unhighlightSentence();
                speakText(currentSentence);
            };

            // Highlight the sentence that is currently being read
            highlightSentence(startIndex);

            synth.speak(utterance);
        }

        // Function to highlight a sentence
        function highlightSentence(index) {
            // Add your logic to highlight the sentence here
            let highlightedSentence = sentences[index];
            // You can apply your preferred styling to the highlightedSentence
            // For example, you can set the background color or border to indicate the highlighted sentence
            contentDiv.innerHTML = contentDiv.innerHTML.replace(highlightedSentence, `<span class="highlighted">${highlightedSentence}</span>`);
        }

        // Function to remove the sentence highlight
        function unhighlightSentence() {
            // Remove the 'highlighted' class or any applied styling to the highlighted sentence
            contentDiv.innerHTML = contentDiv.innerHTML.replace(/<span class="highlighted">/g, '');
            contentDiv.innerHTML = contentDiv.innerHTML.replace(/<\/span>/g, '');
        }

        // Add a click event listener to start reading the text
        document.getElementById('start').addEventListener('click', function () {
            speakText(currentSentence);
        });

        // Add a click event listener to pause speech synthesis
        document.getElementById('pause').addEventListener('click', function () {
            synth.pause();
        });

        // Add a click event listener to resume speech synthesis
        document.getElementById('resume').addEventListener('click', function () {
            synth.resume();
        });

        // Ensure synthesis is canceled when the page is unloaded
        onbeforeunload = function () {
            synth.cancel();
        }


        function generateSectionList() {
            const contentDiv = document.getElementById('contentDiv');
            const sectionListDiv = document.getElementById('dynamicSectionList');
            const sections = contentDiv.querySelectorAll('.section');

            sections.forEach((section, index) => {
                const sectionEntry = document.createElement('a');
                sectionEntry.href = `#section-${index}`;
                sectionEntry.innerText = section.innerText.substring(0, 30) + '...'; // Show first 30 characters as the name
                section.id = `section-${index}`; // Added an id to the section for linking

                sectionListDiv.appendChild(sectionEntry);
            });
        }

        generateSectionList(); // Call this function on page load to populate the section list


        // Target the content div for scroll events
        const content = document.querySelector('.content');

        // Updated updateProgressBar function
        function updateProgressBar() {
            const totalHeight = content.scrollHeight - content.clientHeight;
            const progressHeight = (content.scrollTop / totalHeight) * 100;
            document.getElementById('progressBar').style.height = progressHeight + '%';
            document.getElementById('progressPercentage').textContent = Math.floor(progressHeight) + '%';
        }

        // Update event listener to listen to scroll events on the .content div
        content.addEventListener('scroll', updateProgressBar);

        // Fullscreen toggle
        //here increase the height of the div content to be 680px when the fullscreen button is clicked
        document.getElementById('fullscreen').addEventListener('click', function () {

            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().then(function () {
                    // Increase the height of the 'content' div to 680px
                    document.querySelector('.content').style.height = '670px';
                }).catch(function (err) {
                    console.error('Failed to enter fullscreen mode: ', err);
                });
            } else {

                if (document.exitFullscreen) {
                    document.exitFullscreen().then(function () {
                        // Restore the height of the 'content' div to its original value
                        document.querySelector('.content').style.height = 'initial';
                    }).catch(function (err) {
                        console.error('Failed to exit fullscreen mode: ', err);
                    });
                }
            }
        });

    </script>


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
        integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"
        integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V"
        crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js"
        integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"
        integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha"
        crossorigin="anonymous"></script>

</body>

</html>