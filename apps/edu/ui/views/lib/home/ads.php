<?php
$path = $_SERVER['DOCUMENT_ROOT'];
include $path . '/apps/lib/controllers/FeedController.php';
$feed = new FeedController();
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

            .feedpost {
                border: 1px solid #ccc;
                border-radius: 5px;
                box-shadow: 3px 3px 3px #ccc;
                margin: 5px;
                padding: 5px;
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

        .feedpost {
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 3px 3px 3px #ccc;
            margin: 5px;
            padding: 5px;
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


    <div class="container-fluid">
        <div class="row">


            <main class="col-md-12 ms-sm-auto col-lg-12 px-md-4">
                <br></br>
                <div class="content" id="contentDiv">


                    <?php

                    $lnk = $_GET['l'] ?? Null;
                    if ($lnk ) {

                        $feed->redirectAfterDelay($lnk ,4);

                    }
                    ?>

                </div>
            </main>


        </div>
    </div>

    <!-- Existing scripts... -->
</body>

</html>