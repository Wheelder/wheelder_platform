<?php
$path = $_SERVER['DOCUMENT_ROOT'];
include $path . '/apps/edu/controllers/NoteController.php';
$noteController = new NoteController();
$noteController->check_auth();

include $path . '/apps/edu/ui/layouts/nav_bl.php';
?>

<style>
    /* Add spacing between button icons */
/* Light mode styles */
/* ... (your existing styles) */

/* Dark mode styles for control buttons */
body.dark-mode #start,
body.dark-mode #pause,
body.dark-mode #resume,
body.dark-mode #fullscreen,
body.dark-mode #copyToClipboard,
body.dark-mode #printContent,
body.dark-mode #increaseFontSize,
body.dark-mode #decreaseFontSize,
body.dark-mode #darkMode,
body.dark-mode #lightMode {
    color: #fff; /* Change the color to white */
}

#resume,
#fullscreen,
#copyToClipboard,
#printContent,
#increaseFontSize,
#decreaseFontSize,
#darkMode,
#lightMode {
    margin-right: 10px; /* Adjust the margin as needed */
}

    .toc {
        width: 100%;
        position: sticky;
        top: 10px;
        height: 600px;
        overflow-y: auto;
    }

    .content {
        width: 100%;
        padding: 0px;
        height: 600px;
        overflow-y: auto;
        overflow-x: hidden;
        background-color: #f1f1f1;
        /* Disable horizontal scroll */
        white-space: pre-wrap;
        /* Handle line breaks */
    }

    .footer-controls {
        position: relative;
        bottom: 0;
        width: 100%;
        background-color: #fff;
    }

    .highlighted {
        background-color: #ccc;
    }

    .content,
    .toc {
        padding: 2px;
    }

    .toc a {
        display: block;
        margin-bottom: 10px;
        text-decoration: none;
        color: #333;
    }

    .toc a:hover {
        text-decoration: underline;
    }

    /* Move progress bar to the footer-controls and set its position */
    #progressBarContainer {
        position: absolute;
        right: 30px;
        top: 11%;
        width: 60px;
        height: 90%;
        z-index: 1000;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    #progressBar {
        width: 20px;
        height: 0;
        background: #FF5733;
    }

    #progressPercentage {
        font-size: 14px;
    }


    .section {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 20px;
    }

    .highlighted {
        background-color: yellow;
    }

    .double-highlight {
        background-color: orange;
        border: 2px solid red;
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
    background-color: #222;
    color: #fff;
}

.dark-mode .toc a {
    color: #fff;
}

.dark-mode .highlighted {
    background-color: #444;
}

/* Add more dark mode styles as needed */

    /*make the tabs responsive to the screens */


    @media (max-width: 768px) {
        .toc {
            width: 100%;
            position: sticky;
            top: 10px;
            height: 600px;
            overflow-y: auto;
        }

        .content {
            width: 100%;
            padding: 0px;
            height: 600px;
            overflow-y: auto;
            overflow-x: hidden;
            background-color: #f1f1f1;
            /* Disable horizontal scroll */
            white-space: pre-wrap;
            /* Handle line breaks */
        }

        .footer-controls {
            position: relative;
            bottom: 0;
            width: 100%;
            background-color: #fff;
        }

        .highlighted {
            background-color: #ccc;
        }

        .content,
        .toc {
            padding: 2px;
        }

        .toc a {
            display: block;
            margin-bottom: 10px;
            text-decoration: none;
            color: #333;
        }

        .toc a:hover {
            text-decoration: underline;
        }

        /* Move progress bar to the footer-controls and set its position */
        #progressBarContainer {
            position: absolute;
            right: 30px;
            top: 11%;
            width: 60px;
            height: 90%;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        #progressBar {
            width: 20px;
            height: 0;
            background: #FF5733;
        }

        #progressPercentage {
            font-size: 14px;
        }
    }
    


</style>

<div class="container">
    <div class="row">
        <div class="col-2 " style="">
            <div id="progressBarContainer">
                <div id="progressBar"></div>
                <div id="progressPercentage">0%</div>
            </div>
            <div class="toc">

                <?php
                echo $noteController->titles();
                ?>
            </div>
        </div>
        <div class="col-10">
            <div class="content" id="contentDiv">
                <?php
                $title = $_GET['t'] ?? Null;
                if ($title) {

                    echo $noteController->getNote($title);
                } else {
                    echo $noteController->getNote("Home");
                }


                ?>
                 <!-- Clipboard Button -->
                
                <!-- Footer Controls -->

            </div>
        
            <div class="footer-controls">
            <!-- Icons with IDs -->
<i id="start" class="fas fa-play"></i>
<i id="pause" class="fas fa-pause"></i>
<i id="resume" class="fas fa-step-forward"></i>
<i id="fullscreen" class="fas fa-expand"></i>
<i id="copyToClipboard" class="far fa-copy"></i>
<i id="printContent" class="fas fa-print"></i>
<i id="increaseFontSize" class="fas fa-search-plus"></i>
<i id="decreaseFontSize" class="fas fa-search-minus"></i>
<i id="darkMode" class="fas fa-moon"></i>
<i id="lightMode" class="fas fa-sun"></i>

            </div>




        </div>
    
    </div>
</div>
<!-- ... existing PHP and HTML ... -->

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
        const contentToPrint = content.cloneNode(true); // Clone the content div
        const printWindow = window.open('', '_blank'); // Open a new window for printing
        printWindow.document.open();
        printWindow.document.write('<html><head><title>Print</title></head><body>');
        printWindow.document.write(contentToPrint.outerHTML); // Add the cloned content to the new window
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print(); // Open the print dialog
        printWindow.close(); // Close the new window after printing
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

    let currentSentence = 0;
    let currentWord = 0;
    let sentences = contentDiv.innerText.split('. ');

    function speakText(startIndex) {
        if (startIndex >= sentences.length) return;

        // Cancel any previously running synthesis
        synth.cancel();

        let utterance = new SpeechSynthesisUtterance(sentences[startIndex]);
        utterance.onend = function () {
            currentSentence = startIndex + 1;
            speakText(currentSentence);
        };
        utterance.onboundary = function (event) {
            if (event.name === 'word') {
                highlightWord(event.offset, event.length);
            }
        };

        highlightSentence(startIndex);
        synth.speak(utterance);
    }

    function highlightSentence(index) {
        sentences.forEach((sentence, idx) => {
            // Your logic to unhighlight previous sentence and highlight the new one
        });
    }

    function highlightWord(offset, length) {
        // Your logic to highlight the word that is being spoken
    }

    document.getElementById('start').addEventListener('click', function () {
        speakText(currentSentence);
    });

    document.getElementById('pause').addEventListener('click', function () {
        synth.pause();
    });

    document.getElementById('resume').addEventListener('click', function () {
        synth.resume();
    });


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
    document.getElementById('fullscreen').addEventListener('click', function () {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen();
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            }
        }
    });
</script>

<?php include $path . '/apps/edu/ui/layouts/footer.php'; ?>