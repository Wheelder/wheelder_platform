<?php
$path = $_SERVER['DOCUMENT_ROOT'];
include $path . '/apps/lib/controllers/FeedController.php';
$feed = new FeedController();
$feed->checkSessionAndRedirect();
include $path . '/apps/lib/ui/layouts/nav.php';
?>


<div class="container-fluid">
    <div class="row">


        <div class="col-md-2 col-lg-2 px-md-1 pt-5">
            <div class="left_panel">
            <ul class="nav ">

            <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/lab">
                            <span data-feather="home" class="align-text-bottom">vLab</span>
                        </a>
                    </li>

                    </ul>
            </div>
            

        </div>

        <main class="col-md-8 ms-sm-auto col-lg-8 px-md-4 pt-3">


            <div class="controls  form-control w-100  border-2 shadow">
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
                &nbsp;
                <i id="decreaseFontSize" class="fas fa-search-minus"></i>
                |
                <i id="darkMode" class="fas fa-moon"></i>

                &nbsp;

                <i id="lightMode" class="fas fa-sun"></i>
                |
                <i id="dynamicSectionList" class="fas fa-list"></i>


            </div>



            <div class="content" id="contentDiv">


                <?php

                $title = $_GET['t'] ?? Null;
                $feed->topics_url($title);
                ?>





            </div>
        </main>
        <nav id="sidebarMenu" class="col-md-2 col-lg-2 d-md-block  collapse">
            <div class="pt-3">
                <ul class="nav flex-column">


                    <div class="toc ">

                        <h6 class="text-center">Selected Topics</h6>
                        <hr>

                        <?php

                        $feed->topics($_SESSION['user_id']);


                        ?>




                    </div>
                </ul>
            </div>
        </nav>


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


<?php
include $path . '/apps/lib/ui/layouts/footer.php';
?>