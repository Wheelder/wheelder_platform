            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .btn {
            font-size: 20px;
        }

        .searchDiv {
            margin: 3px;
            border-radius: 10px;
            box-shadow: 3px 3px 3px #ccc;
            border: 1px solid #ccc;

        }

        #answerDiv {
            border: 1px solid #ddd;
            padding: 1px;
            height: 630px;
            width: 100%;
            margin-bottom: 2px;
            border-radius: 10px;
            box-shadow: 10px 10px 10px #eee;
            overflow: hidden;
            overflow-y: scroll;
            overflow-x: hidden;
        }

        #imageDiv {
            border: 1px solid #ddd;
            padding: 11px;
            height: 630px;
            width: 100%;
            margin-bottom: 2px;
            box-shadow: 10px 10px 10px #eee;
            border-radius: 10px;
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

        .form-control input[type=text] {
            width: 500px;
            height: 50px;
            border-radius: 10px;
            box-shadow: 3px 3px 3px #ccc;
            border: 1px solid #ccc;
        }

        .dark-mode .form-control {
            background-color: #000;
            color: #fff;
        }




        .sidebar {
            position: fixed;
            top: 0;
            margin: auto;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 48px 0 0;
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

        .content-details {
            width: 55%;
            /* Adjust the width as needed */
            padding: 10px;
            /* Add padding for spacing */
        }

        .content-diagram {
            width: 45%;
            padding: 0px;
            height: 640px;
            display: flex;
            overflow-y: auto;
            overflow-x: hidden;
            background-color: #fff;
            white-space: pre-wrap;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 8px 8px 8px #ccc;
        }

        .content-diagram img {
            max-width: 100%;
            /* Make the image responsive */
            max-height: 100%;
            /* Make the image responsive */
        }



        .dark .content-diagram {
            background-color: #000;
            color: #fff;
        }

        .dark .content-details {
            background-color: #000;
            color: #fff;
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

        <a class="navbar-brand col-md-2 col-lg-2 me-0 px-3 fs-6" href="/learn">Wheeleder</a>

        <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>


        <div class="controls  form-control w-100 rounded-0 border-0">
            <!-- Icons with IDs -->
            <i id="start" class="fas fa-play"></i>
            &nbsp;
            <i id="pause" class="fas fa-pause"></i>
            &nbsp;
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
            &nbsp;
            <i id="lightMode" class="fas fa-sun"></i>

            </button>

        </div>

    </header>
    <div class="container mt-2">
        <div id="loadingScreen">
            <!-- You can add a loading spinner or text here -->
            Generating The Answer...
        </div>

        <div class="row">
            <div class="col-md-12 ">
                <div class="searchDiv">
                    <form action="" method="get">
                        <div class="input-group">
                            <input type="text" class="form-control" id="query" name="query"
                                placeholder="Enter your query" style="width:500px;"
                                value="<?php echo $_GET['query'] ?? null; ?>">
                            &nbsp;
                            <button type="submit" class="btn btn-dark">Ask</button>
                            &nbsp;
                            <a href="/learn" class="btn btn-dark">Reset</a>
                        </div>

                </div>
                </form>
            </div>
        </div>
        <div class="row mt-1">
            <?php

            if (isset($_GET['query'])) {
                $q = $_GET['query'];
                //$qm= $_GET['img'];
            
                // Convert $q to lowercase
                $qm = strtolower($q);

                // Define an array of words and phrases to remove, including common words
            
                $toRemove = array(
                    '/what is/',
                    '/what/',
                    '/whats/',
                    '/what was/',
                    '/what will/',
                    '/what can/',
                    '/what could/',
                    '/what should/',
                    '/what are/',
                    '/like/',
                    '/so/',
                    '/how/',
                    '/when/',
                    '/where/',
                    '/why/',
                    '/who/',
                    '/which/',
                    '/whom/',
                    '/whose/',
                    '/whither/',
                    '/whence/',
                    '/how many/',
                    '/how much/',
                    '/how long/',
                    '/how often/',
                    '/how far/',
                    '/how old/',
                    '/how come/',
                    '/how well/',
                    '/how many/',
                    '/\./',
                    '/\,/',
                    '/\!/',
                    '/\?/',
                    '/what\'s/i',
                    '/the/',
                    '/an/',
                    '/a/',
                    '/in/',
                    '/of/',
                    '/on/',
                    '/at/',
                    '/by/',
                    '/with/'
                );

                // Remove the defined words and phrases from $q
                $imgq = preg_replace($toRemove, ' ', $qm); // Replace removed words with a space
            
                // Remove extra spaces and punctuation
                $imgq = preg_replace('/\s+/', ' ', $imgq);
                $imgq = preg_replace('/[.,!?]/', '', $imgq);
                //$imgq = trim($qm); // Remove leading/trailing spaces
            
                $image = $note->generateImage($imgq);
                $answer = $note->generateResponse($q);

                // Split the sentences, preserving periods at the beginning of sentences
                $sentences = preg_split('/(?<=[.?!])\s+(?=[A-Z])/', $answer);
                //$sentences = preg_replace('','', $sentences);
                $formattedAnswer = implode("<br></br>", $sentences);
                $formattedAnswer = trim($formattedAnswer);
                $status = 1;

                // Strip HTML tags from $formattedAnswer
                //$content = strip_tags($formattedAnswer);
                $content = trim($formattedAnswer);
                //$category= $_GET['category'];
                //$title= $_GET['title'];
            
                //make the $formattedAnswer a string don't include it here the tags, just the text without losing the format
                $note->insert_data($title, $image, $category, $content, $status);

                echo '<div class="col-md-6 ">
        <div class="content" id="answerDiv">
        ' . $content . '
        </div>
    </div>
    <div class="col-md-6 ">
        <div class="contentImage" id="imageDiv">
            <img src=' . $image . ' alt="failed" style="width: 100%; height: 630px; object-fit: contain;"/>
        </div>
    </div>
</div>';
            }
            ?>




        </div>
    </div>

    <!-- Include Bootstrap JS and jQuery -->

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

    <!-- JavaScript Code -->
    <script>
    
        // Add a listener to hide the loading screen when the page is fully loaded
        window.addEventListener('load', function () {
            // Hide the loading screen
            document.getElementById('loadingScreen').style.display = 'none';
        });


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
                    document.querySelector('.contentImage').style.height = '670px';

                }).catch(function (err) {
                    console.error('Failed to enter fullscreen mode: ', err);
                });
            } else {

                if (document.exitFullscreen) {
                    document.exitFullscreen().then(function () {
                        // Restore the height of the 'content' div to its original value
                        document.querySelector('.content').style.height = 'initial';
                        document.querySelector('.contentImage').style.height = 'initial';
                    }).catch(function (err) {
                        console.error('Failed to exit fullscreen mode: ', err);
                    });
                }
            }
        });

    </script>
</body>

</html>
