<?php
// Shared bootstrap for legacy 2024 views so they can run inside whichever repo (wheelder or wheeleder)
if (!isset($LEGACY_BASE_PATH)) {
    $candidates = [];

    $docRoot = $_SERVER['DOCUMENT_ROOT'] ?? '';
    if (!empty($docRoot)) {
        $candidates[] = rtrim($docRoot, '/\\');
    }

    $candidates[] = realpath(__DIR__ . '/../../../../..');

    // Allow "wheelder" → "wheeleder" mismatch (and vice versa)
    if (!empty($docRoot)) {
        if (str_contains($docRoot, 'wheelder')) {
            $candidates[] = str_replace('wheelder', 'wheeleder', $docRoot);
        }
        if (str_contains($docRoot, 'wheeleder')) {
            $candidates[] = str_replace('wheeleder', 'wheelder', $docRoot);
        }
    }

    foreach ($candidates as $candidate) {
        if (!$candidate) {
            continue;
        }
        $candidate = rtrim($candidate, '/\\');
        if (file_exists($candidate . '/pool/libs/controllers/Controller.php')) {
            $LEGACY_BASE_PATH = $candidate;
            break;
        }
    }

    if (!isset($LEGACY_BASE_PATH)) {
        $LEGACY_BASE_PATH = __DIR__;
    }
}
