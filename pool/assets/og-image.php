<?php
// WHY: Generates og-image.png dynamically using PHP GD
// Social media crawlers need a real PNG/JPG, not SVG
// Cache the result so it's only generated once

$cachePath = __DIR__ . '/og-image.png';

// Serve cached version if it exists
if (file_exists($cachePath)) {
    header('Content-Type: image/png');
    header('Cache-Control: public, max-age=86400');
    readfile($cachePath);
    exit;
}

// Generate the image
$w = 1200;
$h = 630;
$img = imagecreatetruecolor($w, $h);

// Colors
$bgColor     = imagecolorallocate($img, 10, 10, 10);
$white       = imagecolorallocate($img, 224, 224, 224);
$gray        = imagecolorallocate($img, 136, 136, 136);
$darkGray    = imagecolorallocate($img, 51, 51, 51);
$accent      = imagecolorallocate($img, 13, 110, 253);
$gold        = imagecolorallocate($img, 255, 215, 0);
$green       = imagecolorallocate($img, 95, 208, 126);
$blue        = imagecolorallocate($img, 111, 173, 255);
$purple      = imagecolorallocate($img, 199, 125, 255);

// Background
imagefilledrectangle($img, 0, 0, $w, $h, $bgColor);

// Top accent line
imagefilledrectangle($img, 0, 0, $w, 4, $accent);

// Bottom accent line
imagefilledrectangle($img, 0, $h - 4, $w, $h, $accent);

// Title - WHEELDER
$titleSize = 60;
$titleFont = 5; // Built-in font
// Use built-in fonts since we can't guarantee TTF availability
$title = 'WHEELDER';
$titleWidth = strlen($title) * imagefontwidth(5);
$titleX = ($w - $titleWidth * 4) / 2;

// Draw title using imagestring (basic but reliable)
for ($i = 0; $i < strlen($title); $i++) {
    $char = $title[$i];
    $x = ($w / 2) - (strlen($title) * 12 / 2) + ($i * 12);
    imagechar($img, 5, $x, 190, $char, $white);
}

// Better approach: use large text via imagestring for basic text
// Title
$text = 'W H E E L D E R';
$textWidth = strlen($text) * imagefontwidth(5);
imagestring($img, 5, ($w - $textWidth) / 2, 170, $text, $white);

// Subtitle
$sub = 'RESEARCH PLATFORM';
$subWidth = strlen($sub) * imagefontwidth(4);
imagestring($img, 4, ($w - $subWidth) / 2, 210, $sub, $gray);

// Timeline line
imageline($img, 200, 320, 1000, 320, $darkGray);

// Timeline dots
$dots = [
    [250, '2023', $gold],
    [400, '2024', $green],
    [575, 'Aug 2025', $blue],
    [725, 'Nov 2025', $purple],
    [900, 'Feb 2026', $accent],
];

foreach ($dots as $dot) {
    imagefilledellipse($img, $dot[0], 320, 16, 16, $dot[2]);
    $labelWidth = strlen($dot[1]) * imagefontwidth(3);
    imagestring($img, 3, $dot[0] - $labelWidth / 2, 295, $dot[1], $dot[2]);
}

// Labels under dots
$labels = [
    [250, 'Genesis'],
    [400, 'First Commit'],
    [575, 'Rewrite'],
    [725, 'Deployed'],
    [900, 'Current'],
];

foreach ($labels as $label) {
    $lw = strlen($label[1]) * imagefontwidth(2);
    imagestring($img, 2, $label[0] - $lw / 2, 340, $label[1], $gray);
}

// Tagline
$tagline = 'Innovating Since 2023';
$tagWidth = strlen($tagline) * imagefontwidth(4);
imagestring($img, 4, ($w - $tagWidth) / 2, 420, $tagline, $white);

// Sub-tagline
$subTag = 'Circular Search - Deep Research - AI-Powered Learning';
$subTagWidth = strlen($subTag) * imagefontwidth(3);
imagestring($img, 3, ($w - $subTagWidth) / 2, 450, $subTag, $gray);

// Stats at bottom
$stats = ['166+ Commits', '45 Archives', '7 Repos', 'MD5 Verified'];
$statColors = [$green, $gold, $blue, $purple];
$statX = 280;
foreach ($stats as $i => $stat) {
    $sw = strlen($stat) * imagefontwidth(3);
    imagestring($img, 3, $statX, 530, $stat, $statColors[$i]);
    $statX += 180;
}

// Save and serve
imagepng($img, $cachePath);
header('Content-Type: image/png');
header('Cache-Control: public, max-age=86400');
imagepng($img);
imagedestroy($img);
