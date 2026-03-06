<?php
// Wheelder Circular - Meta-AI Orchestrator
include __DIR__ . '/CircularController.php';

// Include top.php for url() helper
require_once dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/top.php';
require_once dirname(__DIR__, 2) . '/layouts/legacy_split.php';

$circular = new CircularController();
$note = $circular;
$blog = $circular;

// Auth is intentionally disabled for demo mode — anyone with the URL can use the app.
// API costs are protected by: CSRF tokens (no forged requests) + rate limiting (10 req/60s per session).
// To require login, uncomment the line below:
// $note->check_auth();

// Fetch all past conversations for the sidebar (newest first)
$conversations = [];
if (method_exists($circular, 'getConversations')) {
    $conversations = $circular->getConversations();
}

// Pre-compute URLs for Circular
$centerBaseUrl      = url('/circular');
$centerAjaxEndpoint = url('/circular/ajax');
$centerTtsEndpoint  = url('/circular/tts');

// Load session history if viewing a past conversation
$viewConversation = null;
if (!empty($_GET['view'])) {
    $requestedSessionId = htmlspecialchars($_GET['view'], ENT_QUOTES, 'UTF-8');
    if (method_exists($circular, 'getSessionHistory')) {
        $sessionHistory = $circular->getSessionHistory($requestedSessionId);
        // Convert to format expected by UI
        if (!empty($sessionHistory)) {
            $viewConversation = [$sessionHistory];
        }
    }
}

// Circular doesn't need image regeneration - focuses on tool recommendations
$lastEntry = null;

// Only start a session if one isn't already active (avoids Notice when the router starts one)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Public center experience — no access key required. Rate limiting + CSRF still
// protect API usage, so we just keep URLs clean.
$keyParam = '';
$newResearchUrl = $centerBaseUrl;

// Generate a CSRF token once per session — sent with every AJAX POST
// so the backend can verify the request came from our page, not a forged form
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wheelder</title>

    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

    <!-- All custom styles are defined inline below (no external style.css needed) -->
    <style>
        /* Textarea shadow — matches the panels below */
        #queryInput {
            box-shadow: 8px 8px 8px #ccc;
        }

        /* Scrollable text panel — holds Q&A pairs in questionnaire style */
        .content {
            /* Relative so the text-fullscreen-btn can be positioned inside */
            position: relative;
            width: 100%;
            padding: 10px;
            height: 600px;
            overflow-y: auto;
            overflow-x: hidden;
            background-color: #fff;
            /* normal wrapping so numbered lists and bullet points don't break mid-line */
            white-space: normal;
            border: 2px solid #ccc;
            font-family: Verdana, sans-serif;
            font-size: 17px;
            font-weight: 400;
            border-radius: 10px;
            box-shadow: 8px 8px 8px #ccc;
        }

        /* Each question inside the answer panel — prominent bordered black box */
        .qa-question {
            font-weight: 700;
            font-size: 15px;
            color: #212529;
            margin-top: 12px;
            margin-bottom: 8px;
            padding: 10px 14px;
            border: 2px solid #212529;
            border-radius: 6px;
            background-color: #f8f9fa;
        }

        /* Each answer block inside the answer panel */
        .qa-answer {
            margin-bottom: 16px;
            padding-bottom: 8px;
            border-bottom: 1px dashed #ddd;
            /* Let formatted HTML (lists, paragraphs) breathe with normal line-height */
            line-height: 1.7;
        }

        /* Numbered and bullet lists inside answers — indent so they don't hug the left edge */
        .qa-answer ol,
        .qa-answer ul {
            padding-left: 28px;
            margin: 8px 0;
        }
        .qa-answer li {
            margin-bottom: 4px;
        }

        /* Paragraphs inside answers — compact spacing between consecutive <p> tags */
        .qa-answer p {
            margin: 6px 0;
        }

        /* Headings inside answers — scale down so they fit the panel context */
        .qa-answer h3 { font-size: 1.15em; margin: 12px 0 6px; }
        .qa-answer h4 { font-size: 1.05em; margin: 10px 0 4px; }
        .qa-answer h5 { font-size: 0.95em; margin: 8px 0 4px; }

        /* Inline code — subtle background to distinguish from prose */
        .qa-answer code {
            background-color: #f0f0f0;
            padding: 1px 5px;
            border-radius: 3px;
            font-size: 0.92em;
        }

        /* Fenced code blocks — scrollable, dark background */
        .qa-answer pre {
            background-color: #1e1e1e;
            color: #d4d4d4;
            padding: 12px 14px;
            border-radius: 6px;
            overflow-x: auto;
            margin: 10px 0;
            font-size: 0.88em;
            line-height: 1.5;
        }
        /* Reset inline-code style when inside a code block */
        .qa-answer pre code {
            background: none;
            padding: 0;
            font-size: inherit;
        }

        /* Horizontal rules inside answers */
        .qa-answer hr {
            border: none;
            border-top: 1px solid #ddd;
            margin: 12px 0;
        }

        /* Bold and italic inherit the answer font but stand out */
        .qa-answer strong { font-weight: 700; }
        .qa-answer em { font-style: italic; }

        /* Dark mode overrides for code blocks and inline code */
        .dark-mode .qa-answer pre {
            background-color: #333;
            color: #fff;
        }
        .dark-mode .qa-answer code {
            background-color: #444;
            color: #fff;
        }

        /* Image panel — same height and border style as text panel */
        .contentImage {
            /* Relative so the fullscreen button can be positioned inside */
            position: relative;
            width: 100%;
            height: 600px;
            overflow: hidden;
            border: 2px solid #ccc;
            border-radius: 10px;
            box-shadow: 8px 8px 8px #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f9f9f9;
        }

        /* Make the image fill the panel without distortion */
        .contentImage img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Fullscreen button — top-right corner of the image panel */
        .img-fullscreen-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 10;
            background: rgba(0,0,0,0.6);
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 6px 10px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.2s;
        }
        .img-fullscreen-btn:hover {
            background: rgba(0,0,0,0.85);
        }

        /* Fullscreen button — stays pinned at top-right of the scrollable answer panel.
           position:sticky keeps it visible even when the user scrolls down.
           float:right removes it from the normal block flow so it doesn't push content down. */
        .text-fullscreen-btn {
            position: sticky;
            top: 10px;
            float: right;
            z-index: 10;
            background: rgba(0,0,0,0.6);
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 6px 10px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.2s;
        }
        .text-fullscreen-btn:hover {
            background: rgba(0,0,0,0.85);
        }

        /* Text zoom overlay — full-viewport readable view of the answer text */
        .text-overlay {
            display: none;
            position: fixed;
            inset: 0;
            top: 0; left: 0; right: 0; bottom: 0;
            width: 100vw;
            height: 100vh;
            height: 100dvh;
            z-index: 99999;
            background: rgba(0,0,0,0.95);
            /* Scrollable so long answers don't get cut off */
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
        }
        .text-overlay.active {
            display: block;
        }
        /* The text container inside the overlay — centered, readable width */
        .text-overlay-content {
            max-width: 800px;
            margin: 60px auto 40px;
            padding: 30px;
            color: #fff;
            font-family: Verdana, sans-serif;
            font-size: 18px;
            line-height: 1.7;
        }
        /* Inherit answer formatting inside the overlay */
        .text-overlay-content .qa-question {
            border: 2px solid #fff;
            padding: 8px 12px;
            margin-bottom: 16px;
            font-weight: 700;
            font-size: 1.1em;
            color: #fff;
            background: transparent;
        }
        .text-overlay-content .qa-answer {
            margin-bottom: 24px;
        }
        .text-overlay-content .qa-answer strong { font-weight: 700; }
        .text-overlay-content .qa-answer em { font-style: italic; }
        .text-overlay-content .qa-depth-label {
            color: #aaa;
            font-size: 0.85em;
            margin-bottom: 6px;
        }
        .text-overlay-content .qa-answer pre {
            background-color: #333;
            color: #d4d4d4;
            padding: 12px 14px;
            border-radius: 6px;
            overflow-x: auto;
        }
        .text-overlay-content .qa-answer code {
            background-color: #444;
            color: #fff;
            padding: 1px 5px;
            border-radius: 3px;
        }
        .text-overlay-content .qa-answer pre code {
            background: none;
            padding: 0;
        }
        /* Close button on the text overlay — same style as image overlay */
        .text-overlay-close {
            position: fixed;
            top: calc(18px + env(safe-area-inset-top, 0px));
            right: calc(24px + env(safe-area-inset-right, 0px));
            background: none;
            border: none;
            color: #fff;
            font-size: 32px;
            cursor: pointer;
            z-index: 100000;
            min-width: 44px;
            min-height: 44px;
        }
        .text-overlay-close:hover {
            color: #ccc;
        }

        /* Fullscreen image overlay — covers the entire viewport above all layers */
        .img-overlay {
            display: none;
            position: fixed;
            inset: 0;
            top: 0; left: 0; right: 0; bottom: 0;
            width: 100vw;
            height: 100vh;
            height: 100dvh;
            z-index: 99999;
            background: rgba(0,0,0,0.95);
            align-items: center;
            justify-content: center;
            touch-action: none;
            -webkit-overflow-scrolling: touch;
            overflow: hidden;
        }
        .img-overlay.active {
            display: flex;
        }
        /* The image inside the overlay — fit within viewport with padding */
        .img-overlay img {
            max-width: 95vw;
            max-height: 90vh;
            max-height: 90dvh;
            object-fit: contain;
            border-radius: 8px;
            touch-action: pinch-zoom;
        }
        /* Close button on the overlay */
        .img-overlay-close {
            position: absolute;
            top: calc(18px + env(safe-area-inset-top, 0px));
            right: calc(24px + env(safe-area-inset-right, 0px));
            background: none;
            border: none;
            color: #fff;
            font-size: 32px;
            cursor: pointer;
            z-index: 100000;
            min-width: 44px;
            min-height: 44px;
        }
        .img-overlay-close:hover {
            color: #ccc;
        }

        /* --- Print styles: only show answer text + image, hide all UI chrome --- */
        @media print {
            /* Hide everything that isn't the answer or image */
            header, .navbar, .sidebar, .controls,
            #sidebarMenu, #questionInput, #askBtn, #deepenBtn, #clearBtn,
            #depthBadge, #loadingSpinner, #errorMsg,
            .img-fullscreen-btn, .text-fullscreen-btn,
            .img-overlay, .text-overlay,
            .mb-5, .d-flex.gap-2,
            .row.justify-content-center {
                display: none !important;
            }

            /* Remove sidebar column width so content fills the page */
            .col-md-10 {
                width: 100% !important;
                max-width: 100% !important;
                flex: 0 0 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            /* Answer panel — remove fixed height, border, shadow for clean print */
            .content {
                height: auto !important;
                overflow: visible !important;
                border: none !important;
                box-shadow: none !important;
                font-size: 12pt !important;
                padding: 0 !important;
            }

            /* Image panel — auto height, no border, centered */
            .contentImage {
                height: auto !important;
                max-height: 400px !important;
                overflow: visible !important;
                border: none !important;
                box-shadow: none !important;
                page-break-before: always;
            }
            .contentImage img {
                max-width: 100% !important;
                height: auto !important;
                object-fit: contain !important;
            }

            /* Make panels stack vertically for print (not side by side) */
            .col-md-6 {
                width: 100% !important;
                max-width: 100% !important;
                flex: 0 0 100% !important;
            }

            /* Clean question boxes for print */
            .qa-question {
                border: 1px solid #000 !important;
                color: #000 !important;
                background: #fff !important;
            }

            body {
                background: #fff !important;
                color: #000 !important;
            }
        }

        /* Dark mode — toggled by the moon/sun icons in the toolbar */
        .dark-mode {
            background-color: #000;
            color: #fff;
        }
        .dark-mode .content {
            background-color: #1a1a1a;
            color: #fff;
            border-color: #444;
        }
        .dark-mode .qa-question {
            color: #eee;
            border-color: #888;
            background-color: #2a2a2a;
        }
        .dark-mode .qa-answer {
            border-color: #444;
        }
        .dark-mode .contentImage {
            background-color: #1a1a1a;
            border-color: #444;
        }
        .dark-mode .form-control {
            background-color: #1a1a1a;
            color: #fff;
            border-color: #444;
        }
        /* Dark mode toolbar — matches blog so icons stay visible */
        .dark-mode .controls {
            background-color: #000;
            color: #fff;
        }
        .dark-mode #start, .dark-mode #pause, .dark-mode #resume,
        .dark-mode #fullscreen, .dark-mode #copyToClipboard, .dark-mode #printContent,
        .dark-mode #increaseFontSize, .dark-mode #decreaseFontSize,
        .dark-mode #darkMode, .dark-mode #lightMode {
            color: #fff;
        }

        /* Navbar brand — seamless with the dark navbar, no separate tint */
        .navbar-brand {
            padding-top: .75rem;
            padding-bottom: .75rem;
        }

        /* Toolbar controls — dark background, standard padding to match blog */
        .navbar .form-control {
            padding: .75rem 1rem;
            background-color: #212529;
            color: #fff;
            border-color: transparent;
        }
        /* Toolbar icons — cursor pointer, spacing matches blog */
        .controls i {
            cursor: pointer;
        }
        #resume, #fullscreen, #copyToClipboard, #printContent,
        #increaseFontSize, #decreaseFontSize, #lightMode, #shareButton {
            margin: 3px;
        }

        /* Highlighted word — used by the speech reader to mark the current word */
        .tts-word-highlight {
            background-color: #ffe066;
            border-radius: 3px;
            padding: 0 2px;
            transition: background-color 0.15s ease;
        }
        .dark-mode .tts-word-highlight {
            background-color: #665500;
            color: #fff;
        }

        /* Sidebar — same width and padding as the blog app for a seamless look */
        .sidebar {
            position: fixed;
            top: 0;
            margin: auto;
            bottom: 0;
            left: 0;
            width: 260px;
            z-index: 100;
            padding: 40px 0 0;
            /* Show scrollbar only when items overflow — avoids an empty white gap when they don't */
            overflow-y: auto;
            overflow-x: hidden;
            background-color: white;
            color: #000;
            border: 1px solid #ccc;
            border-radius: 7px;
            box-shadow: 8px 8px 8px #ccc;
        }
        /* Visible thin scrollbar — light track + darker thumb so it's never invisible */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        .sidebar::-webkit-scrollbar-track {
            /* Light tint so the track is distinguishable from the white sidebar */
            background: #f0f0f0;
            border-radius: 3px;
        }
        .sidebar::-webkit-scrollbar-thumb {
            /* Dark enough to see clearly against the light track */
            background: #999;
            border-radius: 3px;
        }
        .sidebar::-webkit-scrollbar-thumb:hover {
            background: #666;
        }
        /* Firefox thin scrollbar — same colour scheme */
        .sidebar {
            scrollbar-width: thin;
            scrollbar-color: #999 #f0f0f0;
        }
        .sidebar::-webkit-scrollbar-track {
            background: #f0f0f0;
        }
        .sidebar::-webkit-scrollbar-thumb {
            background: #999;
        }
        .sidebar::-webkit-scrollbar-thumb:hover {
            background: #666;
        }
        .sidebar a {
            color: #000;
            background-color: #fff;
        }
        .sidebar .nav-item {
            margin: 3px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 3px 3px 3px #ccc;
            /* Row layout: link text on left, action icons on right, vertically centered */
            display: flex;
            align-items: center;
            /* Stay within sidebar width — forces flex children to respect boundaries */
            max-width: 100%;
            overflow: hidden;
        }
        .sidebar .nav-link {
            color: #000;
            font-size: 13px;
            font-weight: 400;
            text-decoration: none;
            /* Truncate long titles with ellipsis — conv-date overrides this to wrap below */
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            /* Take remaining space so action icons stay flush right */
            flex: 1;
            min-width: 0;
            /* Compact vertical padding so items don't get too tall */
            padding: 6px 8px;
            line-height: 1.3;
        }
        .sidebar .nav-link:hover {
            background-color: #f0f0f0;
        }
        .sidebar .nav-link.active {
            background-color: #212529;
            color: #fff;
            border-radius: 5px;
        }
        /* Conversation date label — sits below the title on its own line */
        .sidebar .conv-date {
            font-size: 10px;
            color: #999;
            /* Force date onto a new line below the truncated title */
            display: block;
            /* Reset nowrap inherited from parent so the date can wrap if needed */
            white-space: normal;
            transition: color 0.15s;
            margin-top: 1px;
        }
        /* Archive + Delete action icons — small, right-aligned inside each sidebar item */
        .conv-actions {
            display: flex;
            align-items: center;
            gap: 6px;
            /* Keep icons snug against the sidebar's right edge */
            padding-right: 6px;
            flex-shrink: 0;
            /* Push icons to the far right of the flex row */
            margin-left: auto;
        }
        .conv-actions i {
            font-size: 11px;
            color: #999;
            cursor: pointer;
            transition: color 0.15s;
        }
        /* Archive icon turns blue on hover */
        .conv-actions .conv-archive:hover {
            color: #0d6efd;
        }
        /* Delete icon turns red on hover */
        .conv-actions .conv-delete:hover {
            color: #dc3545;
        }
        /* Dark mode — make the icons visible against dark background */
        .dark-mode .conv-actions i {
            color: #666;
        }
        .dark-mode .conv-actions .conv-archive:hover {
            color: #6ea8fe;
        }
        .dark-mode .conv-actions .conv-delete:hover {
            color: #ea868f;
        }

        /* Remove link underlines globally — matches blog */
        a { text-decoration: none; }

        /* Dark mode sidebar — matches blog */
        .dark-mode .sidebar {
            color: #fff;
            background-color: #000;
            box-shadow: 10px 10px 10px #ccc;
            /* Dark mode scrollbar colours */
            scrollbar-color: #555 #1a1a1a;
        }
        .dark-mode .sidebar::-webkit-scrollbar-track {
            background: #1a1a1a;
        }
        .dark-mode .sidebar::-webkit-scrollbar-thumb {
            background: #555;
        }
        .dark-mode .sidebar::-webkit-scrollbar-thumb:hover {
            background: #888;
        }
        .dark-mode .sidebar a {
            color: #fff;
            background-color: #000;
        }
        .dark-mode #sidebarMenu {
            color: #fff;
            background-color: #000;
        }

        /* Navbar branding — right-aligned in the top bar */
        .navbar-branding {
            /* Keep it from shrinking so it stays visible next to the controls */
            flex-shrink: 0;
            white-space: nowrap;
            padding: 0 16px;
            font-family: Verdana, sans-serif;
            font-size: 15px;
            color: #cc0000;
        }
        .navbar-branding .footer-heart {
            color: #cc0000;
            margin-right: 4px;
            font-size: 17px;
        }
        /* Dark mode — brighten the red for contrast on dark navbar */
        .dark-mode .navbar-branding {
            color: #ff4444;
        }
        .dark-mode .navbar-branding .footer-heart {
            color: #ff4444;
        }
        /* Print — hide branding, it's not content */
        @media print {
            .navbar-branding {
                display: none !important;
            }
        }

/* RESPONSIVE — mobile-first breakpoints
   Fixes: toolbar overflow, sidebar overlap, panel
   stacking, font sizing, and touch-friendly spacing
   ==================================================== */

/* --- Small screens (phones, up to 767px) --- */
@media (max-width: 767.98px) {

    /* Sidebar overlays content when toggled — not pushing it down */
    .sidebar {
        top: 3.5rem;
        bottom: auto;
        left: 0;
        right: 0;
        width: 100% !important;
        max-width: 100%;
        max-height: 60vh;
        border-radius: 0;
        box-shadow: 0 4px 12px rgba(0,0,0,.3);
        padding-top: 10px;
        z-index: 1050;
        position: fixed !important;
    }
    /* Remove Bootstrap grid sizing so sidebar doesn't occupy row space */
    #sidebarMenu {
        flex: none !important;
        width: 100% !important;
    }

    /* Main content takes full width — no sidebar offset on mobile */
    main.col-md-10 {
        flex: 0 0 100% !important;
        max-width: 100% !important;
        margin-left: 0 !important;
        padding-left: 12px !important;
        padding-right: 12px !important;
    }

    /* Toolbar: shrink pipe separators to zero so only icons show */
    .controls {
        display: flex;
        flex-wrap: wrap;
        gap: 2px;
        padding: 6px 8px !important;
        font-size: 0;
        justify-content: center;
        /* Center all icons vertically — prevents taller glyphs (moon, play) from misaligning */
        align-items: center;
        line-height: 1;
    }
    /* Restore icon size — parent font-size:0 hides pipe text nodes */
    .controls i {
        font-size: 14px;
        padding: 4px;
        /* Force uniform height so all icons align on the same row */
        line-height: 1;
        height: 22px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    /* Page title — smaller on phones */
    main h2 {
        font-size: 1.3rem;
        margin-bottom: 0.75rem !important;
        padding-top: 0.5rem !important;
    }

    /* Textarea fills full width on mobile — no col-md-8 constraint */
    #queryInput {
        font-size: 14px;
        box-shadow: 4px 4px 4px #ccc;
    }

    /* Buttons wrap neatly and stay touch-friendly (min 44px tap target) */
    #askBtn, #deepenBtn, #clearBtn {
        font-size: 13px;
        padding: 8px 14px;
        min-height: 44px;
    }
    /* Deepen button text is long — allow it to shrink on mobile */
    #deepenBtn {
        font-size: 12px;
        white-space: normal;
        text-align: center;
    }

    /* Answer panel — shorter height so it doesn't push image off-screen */
    .content {
        height: 350px;
        font-size: 14px;
        box-shadow: 4px 4px 4px #ccc;
    }

    /* Image panel — shorter on mobile, stacks below answer panel */
    .contentImage {
        height: 250px;
        margin-top: 12px;
        box-shadow: 4px 4px 4px #ccc;
    }

    /* Depth badge — smaller on mobile */
    #depthBadge {
        font-size: 0.75rem !important;
    }

    /* Navbar brand — smaller on mobile to leave room for hamburger */
    .navbar-brand {
        font-size: 0.85rem;
        max-width: 140px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* Hamburger toggler — pin to top-right of navbar instead of default left */
    .navbar-toggler {
        right: 0.5rem !important;
        left: auto !important;
        top: 0.4rem;
        z-index: 1060;
    }

    /* Dark/light mode icons — match size and spacing of other toolbar icons */
    #darkMode, #lightMode {
        font-size: 14px;
        padding: 4px;
        vertical-align: middle;
    }
}

/* --- Medium screens (tablets, 768px – 991px) --- */
@media (min-width: 768px) and (max-width: 991.98px) {
            /* Sidebar narrower on tablets to give more room to content */
            .sidebar {
                width: 200px;
            }

            /* Answer + image panels — moderate height for tablet */
            .content {
                height: 450px;
                font-size: 15px;
            }
            .contentImage {
                height: 450px;
            }

            /* Toolbar icons — slightly smaller on tablets */
            .controls {
                font-size: 13px;
            }
        }

        /* --- Large screens (desktops, 992px+) — defaults apply --- */

        /* =========================================================
           Prompt Modal — full-screen overlay with spinning open animation
           WHY: Forces the user to pay ultimate attention to writing their prompt
           by expanding into a large, focused writing area.
           ========================================================= */

        /* Backdrop — semi-transparent dark overlay behind the modal */
        #promptModalBackdrop {
            display: none;
            position: fixed;
            inset: 0;
            /* WHY: darker overlay grabs user attention and forces focus on the modal */
            background: rgba(0,0,0,0.75);
            z-index: 1050;
            /* WHY: fade-in backdrop so the transition feels intentional, not jarring */
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        #promptModalBackdrop.show {
            opacity: 1;
        }

        /* Modal container — centered, responsive width */
        #promptModal {
            display: none;
            position: fixed;
            z-index: 1060;
            /* WHY: center vertically and horizontally regardless of viewport size */
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(0deg) scale(0);
            /* WHY: mobile-first — nearly full width on small screens, capped on desktop */
            width: 92vw;
            max-width: 720px;
            max-height: 90vh;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 12px 40px rgba(0,0,0,0.3);
            padding: 24px;
            overflow-y: auto;
            /* WHY: will-change hints the GPU to prepare for the transform animation */
            will-change: transform, opacity;
        }

        /* WHY: spin-in keyframes — the modal rotates 720° (two full spins) while
           scaling from 0 to 1, creating a fun "circulating" entrance effect */
        @keyframes promptSpinIn {
            0%   { transform: translate(-50%, -50%) rotate(0deg)   scale(0);   opacity: 0; }
            60%  { transform: translate(-50%, -50%) rotate(540deg) scale(1.05); opacity: 1; }
            80%  { transform: translate(-50%, -50%) rotate(700deg) scale(0.97); opacity: 1; }
            100% { transform: translate(-50%, -50%) rotate(720deg) scale(1);    opacity: 1; }
        }

        /* WHY: spin-out keyframes — reverse animation so the close feels symmetrical */
        @keyframes promptSpinOut {
            0%   { transform: translate(-50%, -50%) rotate(0deg)   scale(1);  opacity: 1; }
            100% { transform: translate(-50%, -50%) rotate(-360deg) scale(0); opacity: 0; }
        }

        #promptModal.spin-in {
            display: block;
            animation: promptSpinIn 0.8s cubic-bezier(0.22, 1, 0.36, 1) forwards;
        }
        #promptModal.spin-out {
            display: block;
            animation: promptSpinOut 0.45s ease-in forwards;
        }

        /* Modal header */
        #promptModal .prompt-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }
        #promptModal .prompt-modal-header h4 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 700;
        }
        /* WHY: close button uses a clean × character — no icon library dependency */
        #promptModalClose {
            background: none;
            border: none;
            font-size: 1.6rem;
            cursor: pointer;
            color: #333;
            line-height: 1;
            padding: 4px 8px;
        }
        #promptModalClose:hover {
            color: #000;
        }

        /* WHY: large textarea inside modal gives the user room to think and write */
        #promptModalTextarea {
            width: 100%;
            min-height: 220px;
            max-height: 55vh;
            border: 2px solid #dee2e6;
            border-radius: 10px;
            padding: 14px;
            font-size: 16px;
            font-family: inherit;
            resize: vertical;
            outline: none;
            transition: border-color 0.2s;
        }
        #promptModalTextarea:focus {
            border-color: #333;
            box-shadow: 0 0 0 3px rgba(0,0,0,0.08);
        }

        /* Modal footer — action buttons */
        #promptModal .prompt-modal-footer {
            display: flex;
            gap: 10px;
            margin-top: 16px;
            flex-wrap: wrap;
        }
        #promptModal .prompt-modal-footer .btn {
            min-height: 44px;
            font-size: 15px;
        }

        /* Character counter — subtle, right-aligned below the textarea */
        #promptCharCount {
            text-align: right;
            font-size: 0.8rem;
            color: #888;
            margin-top: 4px;
        }

        /* WHY: dark mode support — match the existing dark mode toggle in the app */
        .dark-mode #promptModal {
            background: #1e1e1e;
            color: #e0e0e0;
        }
        .dark-mode #promptModalTextarea {
            background: #2a2a2a;
            color: #e0e0e0;
            border-color: #555;
        }
        .dark-mode #promptModalTextarea:focus {
            border-color: #aaa;
            box-shadow: 0 0 0 3px rgba(255,255,255,0.08);
        }
        .dark-mode #promptModalClose {
            color: #ccc;
        }
        .dark-mode #promptModalClose:hover {
            color: #fff;
        }

        /* WHY: reduce animation duration on mobile to feel snappier on slower GPUs */
        @media (max-width: 767.98px) {
            #promptModal {
                width: 96vw;
                padding: 16px;
                border-radius: 12px;
            }
            #promptModal.spin-in {
                animation-duration: 0.6s;
            }
            #promptModalTextarea {
                min-height: 180px;
                font-size: 15px;
            }
            #promptModal .prompt-modal-header h4 {
                font-size: 1.1rem;
            }
        }

        /* WHY: respect user preference for reduced motion (accessibility) */
        @media (prefers-reduced-motion: reduce) {
            #promptModal.spin-in {
                animation: none;
                transform: translate(-50%, -50%) scale(1);
                opacity: 1;
            }
            #promptModal.spin-out {
                animation: none;
                display: none;
            }
        }

    </style>

</head>

<body>
    <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">

        <!-- Brand — col-md-2 matches blog layout -->
        <a class="navbar-brand col-md-2 col-lg-2 me-0 px-3 fs-6" href="<?php echo $centerBaseUrl; ?>">Wheelder</a>

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

        <!-- Branding — right-aligned in the navbar so it's always visible -->
        <span class="navbar-branding d-none d-md-inline">
            <span class="footer-heart">&#9829;</span>Proudly made in Canada.
        </span>

    </header>

    <!-- Blog-style grid: sidebar (col-md-2) + main content (col-md-10) -->
    <div class="container-fluid">
        <div class="row">

            <!-- Sidebar — matches blog's nav structure -->
            <nav id="sidebarMenu" class="col-md-2 col-lg-2 d-md-block sidebar collapse">
                <!-- Scrollable nav list — flex-grow so it fills available space above the branding -->
                <!-- Removed position-sticky — it conflicts with flex column; the inline flex styles handle scrolling -->
                <div class="pt-3 sidebar-sticky" style="flex:1 1 auto; overflow-y:auto; overflow-x:hidden; min-height:0;">
                    <ul class="nav flex-column">

                        <!-- "New Research" link resets the page to a fresh state -->
                        <li class="nav-item">
                            <a href="<?php echo htmlspecialchars($newResearchUrl, ENT_QUOTES, 'UTF-8'); ?>" class="nav-link <?php echo empty($_GET['view']) && empty($_POST['ask']) && empty($_POST['deepen']) ? 'active' : ''; ?>">
                                <i class="fas fa-plus"></i> New Circle
                            </a>
                        </li>

                        <!-- "Latest Features" link to releases page -->
                        <li class="nav-item">
                            <a href="/releases" class="nav-link">
                                <i class="fas fa-rocket"></i> Latest Features
                            </a>
                        </li>

                        <?php
                        // Loop through all past conversations and display them as clickable nav items
                        foreach ($conversations as $conv):
                            // Truncate long questions for the sidebar label
                            $label = mb_substr($conv['question'], 0, 30);
                            if (mb_strlen($conv['question']) > 30) $label .= '...';
                            // Highlight the currently viewed conversation
                            $isActive = (!empty($_GET['view']) && $_GET['view'] === $conv['session_id']) ? 'active' : '';
                        ?>
                        <li class="nav-item d-flex align-items-center" data-session="<?php echo htmlspecialchars($conv['session_id']); ?>">
                            <a href="<?php echo $centerBaseUrl; ?>?view=<?php echo urlencode($conv['session_id']); ?><?php echo $keyParam ? str_replace('&', '&amp;', $keyParam) : ''; ?>" class="nav-link flex-grow-1 <?php echo $isActive; ?>" title="<?php echo htmlspecialchars($conv['question']); ?>">
                                <?php echo htmlspecialchars($label); ?>
                                <span class="conv-date"><?php echo $conv['created_at']; ?></span>
                            </a>
                            <!-- Archive + Delete buttons — appear on the right side of each sidebar item -->
                            <span class="conv-actions">
                                <i class="fas fa-box-archive conv-archive" title="Archive this research" data-session="<?php echo htmlspecialchars($conv['session_id']); ?>"></i>
                                <i class="fas fa-trash conv-delete" title="Delete this research" data-session="<?php echo htmlspecialchars($conv['session_id']); ?>"></i>
                            </span>
                        </li>
                        <?php endforeach; ?>

                    </ul>
                </div>

            </nav>

            <!-- Main content area — col-md-10 matches blog layout -->
            <main class="col-md-10 ms-sm-auto col-lg-10 px-md-4">

                <!-- Page title — centered -->
                <h2 class="text-center mb-4 pt-3">Ask to Reach</h2>

                <!-- Prompt trigger — centered on the page.
                     WHY: clicking here opens the full prompt modal for focused writing. -->
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <textarea class="form-control mb-3" id="queryInput" rows="2"
                            readonly
                            style="overflow-y:hidden; resize:none; min-height:60px; max-height:240px; cursor:pointer;"
                            placeholder="Type your question here..."><?php
                            // Pre-fill from viewed conversation so the user sees what was asked
                            if (!empty($viewConversation)) {
                                $lastEntry = end($viewConversation);
                                echo htmlspecialchars($lastEntry['question'] ?? '');
                            }
                        ?></textarea>
                    </div>
                </div>

                <!-- Buttons row: Deepen + Depth badge only.
                     WHY: Ask and Clear are removed from here because they live inside the prompt modal now.
                     The hidden askBtn and clearBtn are kept in the DOM (display:none) so existing JS handlers still work.
                     Deepen button is shown after first successful query via JavaScript. -->
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="d-flex gap-2 mb-3 flex-wrap" id="controlsRow">
                            <!-- WHY: hidden but still in DOM so modal Ask can trigger askBtn.click() -->
                            <button type="button" id="askBtn" class="btn btn-dark" style="display:none;">Ask</button>
                            <!-- WHY: Deepen button appears after first successful query. Shows step-by-step workflow for current question -->
                            <button type="button" id="deepenBtn" class="btn btn-outline-dark" style="display:none;">🔍 Deepen Circle</button>
                            <!-- WHY: hidden but still in DOM so modal Clear and page Clear logic still works -->
                            <button type="button" id="clearBtn" class="btn btn-dark" style="display:none;">Clear</button>
                            <!-- WHY: depth badge shows current depth level (1-7) when deepening -->
                            <span id="depthBadge" class="badge bg-dark fs-6 ms-auto align-self-center" style="display:none;"></span>
                        </div>
                    </div>
                </div>

                <!-- Loading spinner — shown while AJAX request is in progress -->
                <div id="loadingSpinner" class="text-center mb-3" style="display:none;">
                    <div class="spinner-border text-dark" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Generating response...</p>
                </div>

                <!-- Error message — shown if AJAX request fails -->
                <div id="errorMsg" class="alert alert-danger mb-3" style="display:none;" role="alert"></div>

                <!-- Two panels side by side: Answer panel (left) + Image panel (right) -->
                <div class="row mt-2" id="resultsRow">
                    <?php
                    // If viewing a past conversation, pre-fill both panels with Q&A history
                    if (!empty($viewConversation)):
                    ?>
                    <div class="col-md-6">
                        <div class="content" id="answerPanel">
                            <!-- Focus button — opens the answer text in a full-viewport overlay for reading -->
                            <button class="text-fullscreen-btn" title="Focus view" onclick="openTextOverlay()">
                                <i class="fas fa-expand"></i>
                            </button>
                            <?php
                            // Render all Q&A pairs in questionnaire style inside the answer panel
                            foreach ($viewConversation as $entry):
                            ?>
                                <?php if (!empty($entry['depth_level']) && $entry['depth_level'] > 0): ?>
                                    <div class="qa-depth-label">Depth Level <?php echo (int)$entry['depth_level']; ?>/7</div>
                                <?php endif; ?>
                                <div class="qa-question"><?php echo htmlspecialchars($entry['question'] ?? ''); ?></div>
                                <div class="qa-answer"><?php echo $entry['answer']; ?></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="contentImage" id="imagePanel">
                            <?php if (!empty($lastEntry['image'])): ?>
                                <!-- Fullscreen button — opens the image in a full-viewport overlay -->
                                <button class="img-fullscreen-btn" title="View fullscreen" onclick="openImageOverlay(this)">
                                    <i class="fas fa-expand"></i>
                                </button>
                                <!-- WHY: lets the user fetch a different image if the current one is irrelevant -->
                                <button class="img-fullscreen-btn" id="regenerateImageBtn" title="Regenerate image" style="right:60px;" onclick="regenerateImage()">
                                    <i class="fas fa-redo"></i>
                                </button>
                                <img src="<?php echo htmlspecialchars($lastEntry['image']); ?>" alt="Generated image"/>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

            </main> <!-- close main col-md-10 -->

        </div> <!-- close row -->
    </div> <!-- close container-fluid -->

    <!-- Fullscreen image overlay — hidden by default, shown when the expand button is clicked -->
    <div class="img-overlay" id="imgOverlay">
        <button class="img-overlay-close" title="Close" onclick="closeImageOverlay()">&times;</button>
        <img id="imgOverlayImg" src="" alt="Fullscreen image"/>
    </div>

    <!-- Text focus overlay — hidden by default, shown when the text expand button is clicked -->
    <div class="text-overlay" id="textOverlay">
        <button class="text-overlay-close" title="Close" onclick="closeTextOverlay()">&times;</button>
        <div class="text-overlay-content" id="textOverlayContent"></div>
    </div>

    <!-- Prompt Modal — spins open when user clicks the prompt trigger.
         WHY: large focused writing area forces the user to craft thoughtful prompts.
         spellcheck + lang="en" enable browser-native spell/grammar checking.
    -->
    <div id="promptModalBackdrop"></div>
    <div id="promptModal" role="dialog" aria-labelledby="promptModalTitle" aria-modal="true">
        <div class="prompt-modal-header">
            <h4 id="promptModalTitle">Write Your Prompt</h4>
            <button type="button" id="promptModalClose" aria-label="Close">&times;</button>
        </div>
        <!-- WHY: spellcheck + lang enable browser-native spell and grammar checking -->
        <textarea id="promptModalTextarea"
            spellcheck="true"
            lang="en"
            autocomplete="off"
            autocorrect="on"
            placeholder="Take your time... write a clear, detailed prompt to get the best answer."
        ></textarea>
        <div id="promptCharCount">0 characters</div>
        <div class="prompt-modal-footer">
            <button type="button" id="promptModalAskBtn" class="btn btn-dark">Ask</button>
            <button type="button" id="promptModalClearBtn" class="btn btn-outline-secondary">Clear</button>
            <button type="button" id="promptModalCancelBtn" class="btn btn-outline-dark">Cancel</button>
        </div>
    </div>

    <!-- Bottom spacing so content doesn't crowd the page end -->
    <div class="mb-5"></div>

    <!-- Include Bootstrap JS -->
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

    <!-- ============================================================
         AJAX + Toolbar JavaScript
         ============================================================ -->
    <script>
        // ===========================================
        // AJAX State — tracks the current conversation so deepen can chain from it
        // ===========================================
        var ajaxState = {
            sessionId:        '',   // Groups all entries in one conversation thread
            originalQuestion: '',   // The first question the user asked
            prevAnswer:       '',   // Raw answer from the last call (fed into the next deepen prompt)
            depthLevel:       0,    // Current depth (0 = initial ask, 1-7 = deepen levels)
            lastRowId:        0     // DB row id of the latest entry — used to persist regenerated images
        };

        // CSRF token — generated server-side, sent with every AJAX POST
        // so the backend can reject forged cross-site requests
        var csrfToken = <?php echo json_encode($_SESSION['csrf_token']); ?>;

        // Access key — sent with every AJAX POST so the backend can re-validate
        // even if the PHP session expired (session garbage-collected after inactivity).
        // Without this, AJAX calls fail with 403 after the session dies.
        var accessKey = <?php echo json_encode($_SESSION['demo_access_key'] ?? ''); ?>;

        <?php
        // If viewing a past conversation, seed the JS state so deepen works immediately
        if (!empty($viewConversation) && !empty($lastEntry)):
        ?>
        ajaxState.sessionId        = <?php echo json_encode($_GET['view'] ?? ''); ?>;
        ajaxState.originalQuestion = <?php echo json_encode($lastEntry['question'] ?? ''); ?>;
        ajaxState.prevAnswer       = <?php echo json_encode($lastEntry['answer'] ?? ''); ?>;
        ajaxState.depthLevel       = 0;
        ajaxState.lastRowId        = <?php echo (int)($lastEntry['id'] ?? 0); ?>;
        <?php endif; ?>

        // --- DOM references (cached once so we don't query the DOM repeatedly) ---
        var sidebarNav     = document.querySelector('#sidebarMenu .nav.flex-column');
        var askBtn         = document.getElementById('askBtn');
        var deepenBtn      = document.getElementById('deepenBtn');
        var clearBtn       = document.getElementById('clearBtn');
        var depthBadge     = document.getElementById('depthBadge');
        var queryInput     = document.getElementById('queryInput');
        var resultsRow     = document.getElementById('resultsRow');
        var loadingSpinner = document.getElementById('loadingSpinner');
        var errorMsg       = document.getElementById('errorMsg');

        // Ensure the two panels exist — creates them if this is the first AJAX call
        // (On page load with ?view=, they already exist from PHP)
        function ensurePanels() {
            if (!document.getElementById('answerPanel')) {
                resultsRow.innerHTML =
                    '<div class="col-md-6">' +
                    '    <div class="content" id="answerPanel">' +
                    '        <button class="text-fullscreen-btn" title="Focus view" onclick="openTextOverlay()">' +
                    '            <i class="fas fa-expand"></i>' +
                    '        </button>' +
                    '    </div>' +
                    '</div>' +
                    '<div class="col-md-6">' +
                    '    <div class="contentImage" id="imagePanel"></div>' +
                    '</div>';
            }
        }

        // Scroll the answer panel so the latest question is visible at the top
        function scrollAnswerPanel() {
            var panel = document.getElementById('answerPanel');
            if (!panel) return;
            // Find the last .qa-question element and scroll it to the top of the panel
            var questions = panel.querySelectorAll('.qa-question');
            if (questions.length > 0) {
                questions[questions.length - 1].scrollIntoView({ block: 'start', behavior: 'smooth' });
            } else {
                panel.scrollTop = panel.scrollHeight;
            }
        }

        // --- Image overlay helpers ---
        // Track scroll position so we can restore it when the overlay closes
        var _overlayScrollY = 0;

        function openImageOverlay(btn) {
            // Find the <img> sibling inside the same .contentImage panel
            var panel = btn.closest('.contentImage');
            var img = panel ? panel.querySelector('img') : null;
            if (!img || !img.src) return;
            document.getElementById('imgOverlayImg').src = img.src;
            document.getElementById('imgOverlay').classList.add('active');

            // Lock page scroll — must lock BOTH html and body for iOS Safari.
            // position:fixed prevents rubber-band overscroll on iOS.
            // Save scroll position so we can restore it on close.
            _overlayScrollY = window.scrollY;
            document.body.style.position = 'fixed';
            document.body.style.top = '-' + _overlayScrollY + 'px';
            document.body.style.left = '0';
            document.body.style.right = '0';
            document.body.style.overflow = 'hidden';
            document.documentElement.style.overflow = 'hidden';
            // Jump back to where the user was before opening the overlay
            window.scrollTo(0, _overlayScrollY);
        }
        function closeImageOverlay() {
            document.getElementById('imgOverlay').classList.remove('active');
            document.getElementById('imgOverlayImg').src = '';

            // Restore page scroll — undo the position:fixed lock
            document.body.style.position = '';
            document.body.style.top = '';
            document.body.style.left = '';
            document.body.style.right = '';
            document.body.style.overflow = '';
            document.documentElement.style.overflow = '';
            // Jump back to where the user was before opening the overlay
            window.scrollTo(0, _overlayScrollY);
        }

        // --- Text overlay helpers ---
        // Opens a full-viewport overlay with the answer panel's content for focused reading.
        // Copies the innerHTML (excluding the focus button) so the overlay always
        // reflects the latest Q&A content, even after AJAX updates.
        function openTextOverlay() {
            var panel = document.getElementById('answerPanel');
            if (!panel) return;

            // Clone the panel's content but skip the focus button itself
            var overlayContent = document.getElementById('textOverlayContent');
            overlayContent.innerHTML = '';
            var children = panel.children;
            for (var i = 0; i < children.length; i++) {
                if (children[i].classList.contains('text-fullscreen-btn')) continue;
                overlayContent.innerHTML += children[i].outerHTML;
            }

            document.getElementById('textOverlay').classList.add('active');

            // Lock page scroll (same pattern as image overlay)
            _overlayScrollY = window.scrollY;
            document.body.style.position = 'fixed';
            document.body.style.top = '-' + _overlayScrollY + 'px';
            document.body.style.left = '0';
            document.body.style.right = '0';
            document.body.style.overflow = 'hidden';
            document.documentElement.style.overflow = 'hidden';
        }
        function closeTextOverlay() {
            document.getElementById('textOverlay').classList.remove('active');
            document.getElementById('textOverlayContent').innerHTML = '';

            // Restore page scroll
            document.body.style.position = '';
            document.body.style.top = '';
            document.body.style.left = '';
            document.body.style.right = '';
            document.body.style.overflow = '';
            document.documentElement.style.overflow = '';
            window.scrollTo(0, _overlayScrollY);
        }

        // Close either overlay with Escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeImageOverlay();
                closeTextOverlay();
            }
        });

        // ===========================================
        // regenerateImage — fetches a new image for the current conversation
        // WHY: lets the user get a different image if the auto-generated one is irrelevant
        // Mirrors the lessons/cms2 regenerate_image pattern but adapted for the center app
        // ===========================================
        function regenerateImage() {
            // WHY: need a question to derive image keywords from
            if (!ajaxState.originalQuestion) {
                alert('No question available to regenerate image for.');
                return;
            }

            var imgPanel = document.getElementById('imagePanel');
            var regenBtn = document.getElementById('regenerateImageBtn');
            if (!imgPanel) return;

            // WHY: swap icon to spinner so user knows something is happening
            if (regenBtn) {
                regenBtn.disabled = true;
                regenBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            }

            var formData = new FormData();
            formData.append('regenerate_image', '1');
            formData.append('original_question', ajaxState.originalQuestion);
            formData.append('session_id', ajaxState.sessionId);
            formData.append('row_id', ajaxState.lastRowId || 0);
            formData.append('csrf_token', csrfToken);
            if (accessKey) formData.append('access_key', accessKey);

            fetch('<?php echo $centerAjaxEndpoint; ?>', {
                method: 'POST',
                body: formData
            })
            .then(function (response) {
                return response.text().then(function (text) {
                    return { text: text, status: response.status, ct: response.headers.get('content-type') || '' };
                });
            })
            .then(function (res) {
                if (!res.text || !res.text.trim()) {
                    throw new Error('Server returned an empty response (HTTP ' + res.status + ').');
                }
                if (res.ct.indexOf('application/json') === -1) {
                    throw new Error('Server returned HTML instead of JSON (HTTP ' + res.status + ').');
                }
                try {
                    return JSON.parse(res.text);
                } catch (e) {
                    throw new Error('Server returned invalid JSON (HTTP ' + res.status + ').');
                }
            })
            .then(function (data) {
                if (data.error) {
                    alert('Image regeneration failed: ' + data.error);
                    return;
                }
                // WHY: swap the <img> src directly so the fullscreen + regenerate buttons stay intact
                if (data.image) {
                    var imgEl = imgPanel.querySelector('img');
                    if (imgEl) {
                        imgEl.src = data.image;
                    } else {
                        // WHY: image element missing (edge case) — rebuild the full panel
                        imgPanel.innerHTML =
                            '<button class="img-fullscreen-btn" title="View fullscreen" onclick="openImageOverlay(this)">' +
                            '    <i class="fas fa-expand"></i>' +
                            '</button>' +
                            '<button class="img-fullscreen-btn" id="regenerateImageBtn" title="Regenerate image" style="right:60px;" onclick="regenerateImage()">' +
                            '    <i class="fas fa-redo"></i>' +
                            '</button>' +
                            '<img src="' + data.image + '" alt="Generated image"/>';
                    }
                }
            })
            .catch(function (err) {
                alert('Image regeneration error: ' + err.message);
            })
            .finally(function () {
                // WHY: restore the redo icon and re-enable the button regardless of outcome
                var btn = document.getElementById('regenerateImageBtn');
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-redo"></i>';
                }
            });
        }

        // Auto-scroll on page load if viewing a past conversation
        scrollAnswerPanel();

        // Show the deepen button if we already have a conversation loaded (from ?view=)
        <?php if (!empty($viewConversation)): ?>
        deepenBtn.style.display = 'inline-block';
        <?php endif; ?>

        // ===========================================
        // Auto-expand textarea — grows/shrinks as the user types
        // Resets to auto first so shrinking works, then sets to scrollHeight.
        // ===========================================
        function autoResizeTextarea() {
            queryInput.style.height = 'auto';
            // Clamp to max-height so the page doesn't grow unbounded
            queryInput.style.height = Math.min(queryInput.scrollHeight, 240) + 'px';
        }
        queryInput.addEventListener('input', autoResizeTextarea);
        // Run once on load in case the textarea is pre-filled (viewing past conversation)
        autoResizeTextarea();

        // ===========================================
        // Prompt Modal — spinning overlay for focused prompt writing
        // WHY: forces user to pay attention to crafting a quality prompt
        // ===========================================
        var promptBackdrop  = document.getElementById('promptModalBackdrop');
        var promptModal     = document.getElementById('promptModal');
        var promptTextarea  = document.getElementById('promptModalTextarea');
        var promptCharCount = document.getElementById('promptCharCount');
        var promptCloseBtn  = document.getElementById('promptModalClose');
        var promptAskBtn    = document.getElementById('promptModalAskBtn');
        var promptClearBtn  = document.getElementById('promptModalClearBtn');
        var promptCancelBtn = document.getElementById('promptModalCancelBtn');

        // WHY: track whether modal is open to prevent double-open and stale state
        var _promptModalOpen = false;

        function openPromptModal() {
            if (_promptModalOpen) return;
            _promptModalOpen = true;

            // WHY: sync current queryInput value into the modal textarea so prior text carries over
            promptTextarea.value = queryInput.value;
            updatePromptCharCount();

            // Show backdrop with fade-in, then trigger the spin-in animation
            promptBackdrop.style.display = 'block';
            // WHY: rAF ensures display:block paints before adding the class triggers the CSS transition
            requestAnimationFrame(function () {
                promptBackdrop.classList.add('show');
            });

            // Remove any leftover spin-out class, then add spin-in
            promptModal.classList.remove('spin-out');
            promptModal.classList.add('spin-in');

            // WHY: lock body scroll so the page doesn't move behind the modal
            document.body.style.overflow = 'hidden';

            // WHY: focus the textarea after animation finishes so the cursor is ready
            setTimeout(function () {
                promptTextarea.focus();
            }, 850);
        }

        function closePromptModal() {
            if (!_promptModalOpen) return;
            _promptModalOpen = false;

            // WHY: sync modal text back to the hidden queryInput so the Ask flow still works
            queryInput.value = promptTextarea.value;
            autoResizeTextarea();

            // WHY: fade out backdrop by removing .show (CSS transition handles opacity → 0)
            promptBackdrop.classList.remove('show');

            // WHY: spin-out animation plays before hiding the modal
            promptModal.classList.remove('spin-in');
            promptModal.classList.add('spin-out');

            // WHY: hide elements after animations complete (matched to CSS durations).
            // Also reset inline styles to prevent stale display:block from keeping
            // the backdrop visible if the timeout was somehow interrupted on a previous close.
            setTimeout(function () {
                promptModal.classList.remove('spin-out');
                // WHY: removeAttribute clears any inline display left from openPromptModal,
                // letting the CSS default (display:none) take effect cleanly
                promptModal.removeAttribute('style');
                promptBackdrop.removeAttribute('style');
                document.body.style.overflow = '';
            }, 500);
        }

        function updatePromptCharCount() {
            var len = promptTextarea.value.length;
            promptCharCount.textContent = len + ' character' + (len !== 1 ? 's' : '');
        }

        // WHY: clicking the readonly trigger textarea opens the modal instead of typing inline
        queryInput.addEventListener('click', openPromptModal);
        queryInput.addEventListener('focus', function () {
            // WHY: keyboard-tab focus also opens the modal for accessibility
            openPromptModal();
            // WHY: blur immediately so the cursor doesn't flash in the readonly field
            queryInput.blur();
        });

        // WHY: the Ask button on the page also opens the modal if the queryInput is empty,
        // so the user is guided to the focused writing experience
        askBtn.addEventListener('click', function (e) {
            if (!queryInput.value.trim() && !_promptModalOpen) {
                e.stopImmediatePropagation();
                openPromptModal();
            }
        }, true);

        // Modal close triggers
        promptCloseBtn.addEventListener('click', closePromptModal);
        promptCancelBtn.addEventListener('click', closePromptModal);
        promptBackdrop.addEventListener('click', closePromptModal);

        // WHY: Escape closes the prompt modal (extends existing Escape handler)
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && _promptModalOpen) {
                closePromptModal();
            }
        });

        // WHY: live character counter helps user gauge prompt length
        promptTextarea.addEventListener('input', updatePromptCharCount);

        // Modal Clear — empties only the modal textarea
        promptClearBtn.addEventListener('click', function () {
            promptTextarea.value = '';
            promptTextarea.focus();
            updatePromptCharCount();
        });

        // Modal Ask — copies text to queryInput, closes modal, then triggers the real Ask
        promptAskBtn.addEventListener('click', function () {
            var text = promptTextarea.value.trim();
            if (!text) {
                // WHY: shake the textarea briefly to indicate empty input
                promptTextarea.style.borderColor = '#dc3545';
                setTimeout(function () { promptTextarea.style.borderColor = ''; }, 600);
                promptTextarea.focus();
                return;
            }
            // WHY: sync text back to the main queryInput before closing
            queryInput.value = text;
            autoResizeTextarea();
            closePromptModal();
            // WHY: small delay lets close animation start before the AJAX call takes over the UI
            setTimeout(function () {
                askBtn.click();
            }, 150);
        });

        // WHY: Ctrl+Enter / Cmd+Enter submits from inside the modal (power-user shortcut)
        promptTextarea.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && (e.ctrlKey || e.metaKey)) {
                e.preventDefault();
                promptAskBtn.click();
            }
        });

        // ===========================================
        // addSidebarThread — inserts a new conversation into the sidebar immediately
        // so the user doesn't have to refresh to see it.
        // Only called for brand-new threads (not follow-ups in existing threads).
        // ===========================================
        function addSidebarThread(sessionId, questionText) {
            if (!sidebarNav) return;

            // Truncate label to 30 chars to match the PHP sidebar rendering
            var label = questionText.length > 30 ? questionText.substring(0, 30) + '...' : questionText;

            // Escape HTML in label to prevent XSS from user input
            var safeLabel = label.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
            var safeTitle = questionText.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
            var safeSession = sessionId.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
            // Use URL key first, then fall back to the accessKey JS variable
            // (set from session/cookie). Without this fallback, sidebar links
            // lose the key when the user arrived via cookie (no ?key= in URL).
            var keyParam = '';
            var urlParams = new URLSearchParams(window.location.search);
            var sidebarKey = urlParams.get('key') || accessKey;
            if (sidebarKey) {
                keyParam = '&key=' + encodeURIComponent(sidebarKey);
            }

            // Format today's date as YYYY-MM-DD to match the PHP conv-date display
            var today = new Date();
            var dateStr = today.getFullYear() + '-' +
                String(today.getMonth() + 1).padStart(2, '0') + '-' +
                String(today.getDate()).padStart(2, '0');

            // Build the same HTML structure as the PHP loop generates
            var li = document.createElement('li');
            li.className = 'nav-item d-flex align-items-center';
            li.setAttribute('data-session', sessionId);

            li.innerHTML =
                '<a href="' + '<?php echo $centerBaseUrl; ?>?view=' + encodeURIComponent(sessionId) + keyParam + '"' +
                '   class="nav-link flex-grow-1 active" title="' + safeTitle + '">' +
                    safeLabel +
                    '<span class="conv-date">' + dateStr + '</span>' +
                '</a>' +
                '<span class="conv-actions">' +
                    '<i class="fas fa-box-archive conv-archive" title="Archive this research" data-session="' + safeSession + '"></i>' +
                    '<i class="fas fa-trash conv-delete" title="Delete this research" data-session="' + safeSession + '"></i>' +
                '</span>';

            // Remove 'active' class from all other sidebar links so only the new one is highlighted
            sidebarNav.querySelectorAll('.nav-link').forEach(function (link) {
                link.classList.remove('active');
            });

            // Insert after the "+ New Research" item (first <li>)
            var firstItem = sidebarNav.querySelector('li.nav-item');
            if (firstItem && firstItem.nextSibling) {
                sidebarNav.insertBefore(li, firstItem.nextSibling);
            } else {
                sidebarNav.appendChild(li);
            }
        }

        // ===========================================
        // sendAjax — core function that POSTs to ajax_handler.php and renders the result
        // ===========================================
        function sendAjax(formData, questionText) {
            // Show spinner, hide previous errors
            loadingSpinner.style.display = 'block';
            errorMsg.style.display = 'none';

            // Disable buttons while request is in flight to prevent double-clicks
            askBtn.disabled = true;
            deepenBtn.disabled = true;

            // Attach CSRF token so the backend can verify this request came from our page
            formData.append('csrf_token', csrfToken);
            // Attach access key so backend can re-validate even if session expired
            if (accessKey) formData.append('access_key', accessKey);

            // Use fetch API — modern, clean, no jQuery needed
            fetch('<?php echo $centerAjaxEndpoint; ?>', {
                method: 'POST',
                body: formData
            })
            .then(function (response) {
                // Read body as text first — this lets us detect empty responses
                // (PHP can die mid-execution after sending headers, leaving an empty body
                // that causes "Unexpected end of JSON input" when parsed directly)
                return response.text().then(function (text) {
                    return { text: text, status: response.status, ct: response.headers.get('content-type') || '' };
                });
            })
            .then(function (res) {
                // Guard 1: empty body — PHP died before writing any output
                if (!res.text || !res.text.trim()) {
                    throw new Error('Server returned an empty response (HTTP ' + res.status + '). The request may have timed out. Please try again.');
                }
                // Guard 2: non-JSON content type (e.g. PHP fatal error page, nginx 404)
                if (res.ct.indexOf('application/json') === -1) {
                    throw new Error('Server returned HTML instead of JSON (HTTP ' + res.status + '). Please reload the page.');
                }
                // Guard 3: parse JSON safely — catch malformed/truncated JSON
                try {
                    return JSON.parse(res.text);
                } catch (e) {
                    throw new Error('Server returned invalid JSON (HTTP ' + res.status + '). Please try again.');
                }
            })
            .then(function (data) {
                if (data.error) {
                    // Hide spinner and show error — no panels to render
                    loadingSpinner.style.display = 'none';
                    errorMsg.textContent = data.error;
                    errorMsg.style.display = 'block';
                    askBtn.disabled = false;
                    deepenBtn.disabled = false;
                    return;
                }

                // --- Hold both panels: preload the image BEFORE rendering anything ---
                // Problem: text renders instantly but Pollinations image takes 3-8s,
                // so the user sees a blank right panel while the image generates.
                // Solution: keep the spinner visible, preload the image in a hidden
                // Image() object, then render BOTH panels simultaneously once loaded.

                // renderBothPanels — called once the image is ready (or on fallback)
                // Renders the answer text, tools panel, and image at the same time
                function renderBothPanels(imageUrl) {
                    // Hide spinner now that everything is ready
                    loadingSpinner.style.display = 'none';

                    ensurePanels();
                    var answerPanel = document.getElementById('answerPanel');
                    var imagePanel  = document.getElementById('imagePanel');

                    // Build depth label if this is a deepen response
                    var depthHtml = '';
                    if (data.depth_level >= 1) {
                        depthHtml = '<div class="qa-depth-label">Depth Level ' + data.depth_level + '/7</div>';
                    }

                    // Append the question + answer pair into the answer panel (questionnaire style)
                    answerPanel.innerHTML += depthHtml +
                        '<div class="qa-question">' + questionText.replace(/</g, '&lt;').replace(/>/g, '&gt;') + '</div>' +
                        '<div class="qa-answer">' + data.answer + '</div>';
                    scrollAnswerPanel();

                    // Display recommended tools in the image panel if available
                    // WHY: Circular's main feature is tool recommendations, so show them prominently
                    if (data.recommended_tools && data.recommended_tools.length > 0) {
                        // WHY: determine if deepen button should be visible based on depth level
                        var showDeepenBtn = data.depth_level < 7 ? 'block' : 'none';
                        
                        var toolsHtml = '<div style="padding: 15px; overflow-y: auto; height: 100%;">' +
                            '<div style="margin-bottom: 15px;">' +
                            '<button id="deepenBtnPanel" style="display: ' + showDeepenBtn + '; width: 100%; padding: 10px 16px; background: #dc3545; color: white; border: none; border-radius: 6px; font-size: 13px; font-weight: bold; cursor: pointer; transition: all 0.3s ease;">🔍 Deepen Circle</button>' +
                            '</div>' +
                            '<h3 style="margin-top: 0; color: #333;">🔧 Recommended Tools</h3>' +
                            '<p style="color: #666; font-size: 13px;">For: <strong>' + (data.query_type || 'general') + '</strong></p>';
                        
                        // WHY: iterate through recommended tools and create clickable cards
                        data.recommended_tools.forEach(function(tool, idx) {
                            toolsHtml += '<div style="margin: 12px 0; padding: 12px; border: 1px solid #ddd; border-radius: 6px; background: #f9f9f9;">' +
                                '<div style="font-weight: bold; color: #007bff; margin-bottom: 4px;">' + 
                                (tool.name || 'Tool ' + (idx + 1)) + 
                                '</div>' +
                                '<div style="font-size: 12px; color: #666; margin-bottom: 6px;">' + 
                                (tool.description || 'AI tool for ' + (tool.category || 'general') + ' tasks') + 
                                '</div>';
                            
                            // WHY: provide direct link to tool if URL available
                            if (tool.url) {
                                toolsHtml += '<a href="' + tool.url + '" target="_blank" style="display: inline-block; padding: 6px 12px; background: #007bff; color: white; border-radius: 4px; font-size: 12px; text-decoration: none; margin-right: 6px;">Visit Tool →</a>';
                            }
                            
                            // WHY: show how to use this tool
                            if (tool.best_for) {
                                toolsHtml += '<div style="font-size: 11px; color: #999; margin-top: 6px;">Best for: ' + tool.best_for + '</div>';
                            }
                            
                            toolsHtml += '</div>';
                        });
                        
                        toolsHtml += '<div style="margin-top: 15px; padding: 12px; background: #e7f3ff; border-radius: 6px; font-size: 12px; color: #0c5460;">' +
                            '<strong>💡 Tip:</strong> Click the "Deepen" button in the top bar to get a step-by-step workflow using these tools.' +
                            '</div>' +
                            '</div>';
                        
                        imagePanel.innerHTML = toolsHtml;
                    } else if (imageUrl) {
                        // Fallback: show image if no tools available
                        imagePanel.innerHTML =
                            '<button class="img-fullscreen-btn" title="View fullscreen" onclick="openImageOverlay(this)">' +
                            '    <i class="fas fa-expand"></i>' +
                            '</button>' +
                            '<button class="img-fullscreen-btn" id="regenerateImageBtn" title="Regenerate image" style="right:60px;" onclick="regenerateImage()">' +
                            '    <i class="fas fa-redo"></i>' +
                            '</button>' +
                            '<img src="' + imageUrl + '" alt="Generated image"/>';
                    } else {
                        // No tools and no image: show empty state
                        imagePanel.innerHTML = '<div style="padding: 20px; text-align: center; color: #999;">No tools or images available</div>';
                    }

                    // --- Add new thread to sidebar instantly (only for brand-new conversations) ---
                    // Must check BEFORE updating ajaxState, because once sessionId is set
                    // follow-up asks within the same thread should NOT create duplicate entries.
                    if (!ajaxState.sessionId && data.session_id) {
                        addSidebarThread(data.session_id, data.original_question || questionText);
                    }

                    // --- Update AJAX state so the next deepen call chains correctly ---
                    ajaxState.sessionId        = data.session_id;
                    ajaxState.originalQuestion = data.original_question;
                    ajaxState.prevAnswer       = data.prev_answer;
                    ajaxState.depthLevel       = data.depth_level;

                    // Show the deepen button (hidden until first successful ask)
                    // Hide it if we've hit max depth (7)
                    if (data.depth_level < 7) {
                        deepenBtn.style.display = 'inline-block';
                        // Also show the deepen button in the right panel
                        var deepenBtnPanel = document.getElementById('deepenBtnPanel');
                        if (deepenBtnPanel) {
                            deepenBtnPanel.style.display = 'block';
                        }
                    } else {
                        deepenBtn.style.display = 'none';
                        var deepenBtnPanel = document.getElementById('deepenBtnPanel');
                        if (deepenBtnPanel) {
                            deepenBtnPanel.style.display = 'none';
                        }
                    }

                    // Show or update the depth badge
                    if (data.depth_level >= 1) {
                        depthBadge.textContent = 'Depth Level ' + data.depth_level + '/7';
                        depthBadge.style.display = 'inline-block';
                    } else {
                        depthBadge.style.display = 'none';
                    }

                    // Attach click handler to the deepen button in the right panel
                    var deepenBtnPanel = document.getElementById('deepenBtnPanel');
                    if (deepenBtnPanel && !deepenBtnPanel._listenerAttached) {
                        deepenBtnPanel.addEventListener('click', function () {
                            deepenBtn.click();
                        });
                        deepenBtnPanel._listenerAttached = true;
                    }

                    // Re-enable buttons now that both panels are visible
                    askBtn.disabled = false;
                    deepenBtn.disabled = false;
                }

                // --- Preload the image before rendering ---
                // If there's an image URL, load it in a hidden Image() object.
                // Once loaded (or on error/timeout), call renderBothPanels().
                if (data.image) {
                    var preloader = new Image();
                    var imageLoaded = false;

                    // Guard: prevent renderBothPanels from firing twice
                    // (e.g. if timeout fires right after onload)
                    function onImageReady() {
                        if (imageLoaded) return;
                        imageLoaded = true;
                        clearTimeout(imageTimeout);
                        renderBothPanels(data.image);
                    }

                    // Image loaded successfully — render both panels
                    preloader.onload = onImageReady;

                    // Image failed to load — render anyway with the URL
                    // (browser will show broken-image icon, but text still appears)
                    preloader.onerror = onImageReady;

                    // Safety timeout: if Pollinations takes longer than 15s,
                    // render both panels anyway so the user isn't stuck waiting forever
                    var imageTimeout = setTimeout(onImageReady, 15000);

                    // Start loading — this triggers the Pollinations generation
                    preloader.src = data.image;
                } else {
                    // No image URL — render the answer panel immediately
                    renderBothPanels('');
                }
            })
            .catch(function (err) {
                // Network error or JSON parse failure
                loadingSpinner.style.display = 'none';
                errorMsg.textContent = 'Request failed: ' + err.message;
                errorMsg.style.display = 'block';
                askBtn.disabled = false;
                deepenBtn.disabled = false;
            });
        }

        // ===========================================
        // ASK button — sends a new question via AJAX
        // ===========================================
        askBtn.addEventListener('click', function () {
            var query = queryInput.value.trim();
            if (!query) {
                errorMsg.textContent = 'Please enter a question.';
                errorMsg.style.display = 'block';
                return;
            }

            // Clear the textarea after sending and reset its height
            queryInput.value = '';
            autoResizeTextarea();

            // Build form data for the Ask request
            var formData = new FormData();
            formData.append('ask', '1');
            formData.append('query', query);

            // If we already have an active conversation, pass its session_id
            // so the backend appends this follow-up to the same thread.
            // If no session_id exists, the backend will create a new thread.
            if (ajaxState.sessionId) {
                formData.append('session_id', ajaxState.sessionId);
            }

            // Reset depth state — a new Ask is not a deepen, even within the same thread
            ajaxState.depthLevel = 0;
            depthBadge.style.display = 'none';

            // Pass the question text so sendAjax can render it in the answer panel
            sendAjax(formData, query);
        });

        // ===========================================
        // DEEPEN button — opens workflow in new tab with Bootstrap nav-tabs
        // ===========================================
        deepenBtn.addEventListener('click', function () {
            // Safety: can't deepen without a prior conversation
            if (!ajaxState.originalQuestion) {
                errorMsg.textContent = 'Ask a question first before deepening.';
                errorMsg.style.display = 'block';
                return;
            }

            // Show spinner while generating workflow
            loadingSpinner.style.display = 'block';
            errorMsg.style.display = 'none';
            deepenBtn.disabled = true;

            // Build form data for the Deepen request
            var formData = new FormData();
            formData.append('deepen', '1');
            formData.append('depth_level', ajaxState.depthLevel + 1);
            formData.append('original_question', ajaxState.originalQuestion);
            formData.append('prev_answer', ajaxState.prevAnswer);
            formData.append('session_id', ajaxState.sessionId);
            formData.append('csrf_token', csrfToken);
            if (accessKey) formData.append('access_key', accessKey);

            // WHY: fetch workflow data via AJAX, then open in new tab
            fetch('<?php echo $centerAjaxEndpoint; ?>', {
                method: 'POST',
                body: formData
            })
            .then(function (response) {
                return response.text().then(function (text) {
                    return { text: text, status: response.status, ct: response.headers.get('content-type') || '' };
                });
            })
            .then(function (res) {
                // Guard: check for valid JSON response
                if (!res.text || !res.text.trim()) {
                    throw new Error('Server returned an empty response');
                }
                if (res.ct.indexOf('application/json') === -1) {
                    throw new Error('Server returned HTML instead of JSON');
                }
                try {
                    return JSON.parse(res.text);
                } catch (e) {
                    throw new Error('Server returned invalid JSON');
                }
            })
            .then(function (data) {
                loadingSpinner.style.display = 'none';
                
                if (data.error) {
                    errorMsg.textContent = data.error;
                    errorMsg.style.display = 'block';
                    deepenBtn.disabled = false;
                    return;
                }

                // WHY: store workflow data in session via AJAX call to deepen.php
                // This prepares the data for the new tab
                var deepenData = new FormData();
                deepenData.append('store_workflow', '1');
                deepenData.append('answer', data.answer || '');
                deepenData.append('recommended_tools', JSON.stringify(data.recommended_tools || []));
                deepenData.append('query_type', data.query_type || 'general');
                deepenData.append('original_question', data.original_question || ajaxState.originalQuestion);
                deepenData.append('depth_level', data.depth_level || ajaxState.depthLevel + 1);
                deepenData.append('csrf_token', csrfToken);

                // WHY: send workflow data to backend to store in session
                return fetch('<?php echo url('/circular/deepen-store'); ?>', {
                    method: 'POST',
                    body: deepenData
                }).then(function(storeRes) {
                    return storeRes.json();
                }).then(function(storeData) {
                    if (storeData.success) {
                        // WHY: open deepen.php in new tab after data is stored
                        window.open('<?php echo url('/circular/deepen'); ?>', 'deepen_workflow', 'width=1200,height=800');
                        deepenBtn.disabled = false;
                    } else {
                        throw new Error('Failed to store workflow data');
                    }
                });
            })
            .catch(function (err) {
                loadingSpinner.style.display = 'none';
                errorMsg.textContent = 'Deepen failed: ' + err.message;
                errorMsg.style.display = 'block';
                deepenBtn.disabled = false;
            });
        });

        // ===========================================
        // CLEAR button — resets the UI to fresh state
        // ===========================================
        clearBtn.addEventListener('click', function () {
            queryInput.value = '';
            // WHY: also clear the modal textarea so re-opening shows a blank slate
            promptTextarea.value = '';
            updatePromptCharCount();
            // Reset textarea height back to default after clearing
            autoResizeTextarea();
            resultsRow.innerHTML = '';
            deepenBtn.style.display = 'none';
            depthBadge.style.display = 'none';
            errorMsg.style.display = 'none';
            loadingSpinner.style.display = 'none';
            // Reset AJAX state
            ajaxState = { sessionId: '', originalQuestion: '', prevAnswer: '', depthLevel: 0, lastRowId: 0 };
        });

        // ===========================================
        // Allow Enter key to submit the Ask (Shift+Enter for newline)
        // ===========================================
        queryInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                askBtn.click();
            }
        });

        // ===========================================
        // ARCHIVE / DELETE — sidebar action icons
        // Uses event delegation so we don't attach listeners to each icon individually
        // ===========================================
        document.getElementById('sidebarMenu').addEventListener('click', function (e) {
            var target = e.target;

            // Determine which action was clicked — archive or delete
            var isArchive = target.classList.contains('conv-archive');
            var isDelete  = target.classList.contains('conv-delete');
            if (!isArchive && !isDelete) return;

            // Prevent the click from navigating to the conversation link
            e.preventDefault();
            e.stopPropagation();

            var sessionId = target.getAttribute('data-session');
            if (!sessionId) return;

            // Confirm before deleting — archive is silent, delete asks the user
            if (isDelete && !confirm('Delete this research? It will be moved to archive.')) {
                return;
            }

            // Build the AJAX request — reuses the same /center/ajax endpoint
            var formData = new FormData();
            formData.append(isArchive ? 'archive' : 'delete', '1');
            formData.append('session_id', sessionId);
            // Attach CSRF token for backend validation
            formData.append('csrf_token', csrfToken);
            // Attach access key so backend can re-validate even if session expired
            if (accessKey) formData.append('access_key', accessKey);

            fetch('<?php echo $centerAjaxEndpoint; ?>', {
                method: 'POST',
                body: formData
            })
            .then(function (response) {
                // Read body as text first to detect empty/truncated responses
                return response.text().then(function (text) {
                    return { text: text, status: response.status, ct: response.headers.get('content-type') || '' };
                });
            })
            .then(function (res) {
                if (!res.text || !res.text.trim()) {
                    throw new Error('Server returned an empty response (HTTP ' + res.status + ').');
                }
                if (res.ct.indexOf('application/json') === -1) {
                    throw new Error('Server returned non-JSON response. Please reload the page.');
                }
                try {
                    return JSON.parse(res.text);
                } catch (e) {
                    throw new Error('Server returned invalid JSON (HTTP ' + res.status + ').');
                }
            })
            .then(function (data) {
                if (data.error) {
                    alert('Error: ' + data.error);
                    return;
                }

                // Remove the sidebar item from the DOM so it disappears instantly
                var listItem = target.closest('li.nav-item');
                if (listItem) listItem.remove();

                // If the user was viewing this conversation, redirect to fresh state
                // so the main area doesn't show stale data for a now-archived thread
                if (ajaxState.sessionId === sessionId) {
                    window.location.href = '<?php echo $centerBaseUrl; ?>';
                }
            })
            .catch(function (err) {
                alert('Request failed: ' + err.message);
            });
        });

        // ===========================================
        // Toolbar JavaScript — ported from the original app.php
        // ===========================================

        // --- Share Button ---
        document.getElementById('shareButton').addEventListener('click', function () {
            if (navigator.share) {
                navigator.share({ title: 'Check out this link', url: window.location.href })
                    .then(function () { console.log('URL shared'); })
                    .catch(function (error) { console.error('Error sharing URL', error); });
            } else {
                // Fallback for browsers that don't support Web Share API
                prompt('Copy this URL and share it manually', window.location.href);
            }
        });

        // --- Dark Mode / Light Mode ---
        document.getElementById('darkMode').addEventListener('click', function () {
            document.body.classList.add('dark-mode');
        });
        document.getElementById('lightMode').addEventListener('click', function () {
            document.body.classList.remove('dark-mode');
        });

        // --- Font Size (increase / decrease on the text panel) ---
        document.getElementById('increaseFontSize').addEventListener('click', function () {
            var contentDiv = document.querySelector('.content');
            if (!contentDiv) return; // No answer panel yet
            var currentSize = window.getComputedStyle(contentDiv).fontSize;
            contentDiv.style.fontSize = (parseFloat(currentSize) * 1.2) + 'px';
        });
        document.getElementById('decreaseFontSize').addEventListener('click', function () {
            var contentDiv = document.querySelector('.content');
            if (!contentDiv) return;
            var currentSize = window.getComputedStyle(contentDiv).fontSize;
            contentDiv.style.fontSize = (parseFloat(currentSize) / 1.2) + 'px';
        });

        // --- Print Content ---
        document.getElementById('printContent').addEventListener('click', function () {
            var contentDiv = document.querySelector('.content');
            if (!contentDiv) { alert('No content to print yet.'); return; }
            window.print();
        });

        // --- Copy to Clipboard ---
        document.getElementById('copyToClipboard').addEventListener('click', function () {
            var contentDiv = document.querySelector('.content');
            if (!contentDiv) { alert('No content to copy yet.'); return; }
            var textToCopy = contentDiv.innerText;
            var textarea = document.createElement('textarea');
            textarea.value = textToCopy;
            textarea.setAttribute('readonly', '');
            textarea.style.position = 'absolute';
            textarea.style.left = '-9999px';
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);
            alert('Content copied to clipboard!');
        });

        // ===========================================
        // Text-to-Speech — Edge TTS (natural neural voice)
        // Uses a server-side PHP proxy (/center/tts) that calls
        // Microsoft Edge's free neural TTS service. Voice: en-US-GuyNeural.
        // No API key required. Returns MP3 audio + word boundary timestamps.
        // ===========================================
        var ttsAudio = null;
        var ttsPlainText = '';
        var ttsIsSpeaking = false;
        var ttsBoundaries = [];
        var ttsHighlightTimer = null;
        var ttsIsLoading = false;

        function ttsWrapWords() {
            var cd = document.querySelector('.content');
            if (!cd) return;
            ttsPlainText = cd.innerText;
            var wi = 0;
            var wk = document.createTreeWalker(cd, NodeFilter.SHOW_TEXT, null, false);
            var nodes = [];
            while (wk.nextNode()) nodes.push(wk.currentNode);
            nodes.forEach(function (nd) {
                var parts = nd.textContent.split(/(\s+)/);
                if (parts.length <= 1 && !nd.textContent.trim()) return;
                var fr = document.createDocumentFragment();
                parts.forEach(function (p) {
                    if (!p) return;
                    if (/^\s+$/.test(p)) {
                        fr.appendChild(document.createTextNode(p));
                    } else {
                        var sp = document.createElement('span');
                        sp.setAttribute('data-tts-idx', wi);
                        sp.textContent = p;
                        fr.appendChild(sp);
                        wi++;
                    }
                });
                nd.parentNode.replaceChild(fr, nd);
            });
        }

        function ttsUnwrapWords() {
            var cd = document.querySelector('.content');
            if (!cd) return;
            cd.querySelectorAll('.tts-word-highlight').forEach(function (el) {
                el.classList.remove('tts-word-highlight');
            });
            cd.querySelectorAll('span[data-tts-idx]').forEach(function (sp) {
                sp.parentNode.replaceChild(document.createTextNode(sp.textContent), sp);
            });
            cd.normalize();
        }

        function ttsHighlightLoop() {
            if (!ttsAudio || ttsAudio.paused || !ttsBoundaries.length) return;
            var cd = document.querySelector('.content');
            if (!cd) return;
            var ms = ttsAudio.currentTime * 1000;
            var idx = -1;
            for (var i = 0; i < ttsBoundaries.length; i++) {
                if (ttsBoundaries[i].offset <= ms) idx = i; else break;
            }
            if (idx >= 0) {
                var pv = cd.querySelector('.tts-word-highlight');
                var spans = cd.querySelectorAll('span[data-tts-idx]');
                if (idx < spans.length) {
                    if (pv && pv !== spans[idx]) pv.classList.remove('tts-word-highlight');
                    if (!spans[idx].classList.contains('tts-word-highlight')) {
                        spans[idx].classList.add('tts-word-highlight');
                        spans[idx].scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    }
                }
            }
            ttsHighlightTimer = requestAnimationFrame(ttsHighlightLoop);
        }

        function ttsStart() {
            var cd = document.querySelector('.content');
            if (!cd || !cd.innerText.trim()) { alert('No content to read yet.'); return; }
            if (ttsIsLoading) return;
            ttsStop();
            ttsWrapWords();
            ttsIsLoading = true;
            var fd = new FormData();
            fd.append('text', ttsPlainText);
            fd.append('csrf_token', csrfToken);
            fetch('<?php echo $centerTtsEndpoint; ?>', { method: 'POST', body: fd })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                ttsIsLoading = false;
                if (data.error) {
                    console.error('TTS error: ' + data.error);
                    alert('Text-to-speech failed: ' + data.error);
                    ttsUnwrapWords();
                    return;
                }
                ttsBoundaries = data.boundaries || [];
                ttsAudio = new Audio('data:audio/mp3;base64,' + data.audio);
                ttsIsSpeaking = true;
                ttsAudio.onplay = function () {
                    ttsHighlightTimer = requestAnimationFrame(ttsHighlightLoop);
                };
                ttsAudio.onended = function () {
                    ttsIsSpeaking = false;
                    if (ttsHighlightTimer) cancelAnimationFrame(ttsHighlightTimer);
                    ttsUnwrapWords();
                };
                ttsAudio.onerror = function () {
                    ttsIsSpeaking = false;
                    if (ttsHighlightTimer) cancelAnimationFrame(ttsHighlightTimer);
                    ttsUnwrapWords();
                };
                ttsAudio.play().catch(function (e) {
                    ttsIsSpeaking = false;
                    ttsUnwrapWords();
                });
            })
            .catch(function (e) {
                ttsIsLoading = false;
                alert('Text-to-speech request failed.');
                ttsUnwrapWords();
            });
        }

        function ttsStop() {
            if (ttsAudio) { ttsAudio.pause(); ttsAudio.currentTime = 0; ttsAudio = null; }
            ttsIsSpeaking = false;
            ttsIsLoading = false;
            if (ttsHighlightTimer) cancelAnimationFrame(ttsHighlightTimer);
            ttsUnwrapWords();
        }

        document.getElementById('start').addEventListener('click', function () { ttsStart(); });
        document.getElementById('pause').addEventListener('click', function () {
            if (ttsAudio && ttsIsSpeaking) { ttsAudio.pause(); if (ttsHighlightTimer) cancelAnimationFrame(ttsHighlightTimer); }
        });
        document.getElementById('resume').addEventListener('click', function () {
            if (ttsAudio && ttsAudio.paused && ttsIsSpeaking) { ttsAudio.play(); ttsHighlightTimer = requestAnimationFrame(ttsHighlightLoop); }
        });
        window.onbeforeunload = function () { ttsStop(); };

        // --- Fullscreen Toggle ---
        document.getElementById('fullscreen').addEventListener('click', function () {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().then(function () {
                    /* Resize both panels to same height so they align in fullscreen */
                    var txt = document.querySelector('.content');
                    var img = document.querySelector('.contentImage');
                    if (txt) txt.style.height = '80vh';
                    if (img) img.style.height = '80vh';
                }).catch(function (e) {
                    console.error('Failed to enter fullscreen mode: ', e);
                });
            } else {
                document.exitFullscreen().then(function () {
                    /* Restore both panels to default height on exit */
                    var txt = document.querySelector('.content');
                    var img = document.querySelector('.contentImage');
                    if (txt) txt.style.height = '600px';
                    if (img) img.style.height = '600px';
                }).catch(function (e) {
                    console.error('Failed to exit fullscreen mode: ', e);
                });
            }
        });
    </script>

</body>

</html>