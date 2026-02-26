<?php
// WHY: Diagnose why releases are not showing on the page
require_once 'apps/edu/controllers/ReleaseController.php';

$rc = new ReleaseController();

// WHY: Check all releases (including unpublished)
$allReleases = $rc->getAllReleasesForCMS();
echo "Total releases in database: " . count($allReleases) . "\n";

if (count($allReleases) > 0) {
    echo "\nAll releases:\n";
    foreach ($allReleases as $r) {
        echo "  ID: " . $r['id'] . "\n";
        echo "  Title: " . $r['title'] . "\n";
        echo "  Version: " . $r['version'] . "\n";
        echo "  Published: " . ($r['is_published'] ? 'YES' : 'NO') . "\n";
        echo "  Created: " . $r['created_at'] . "\n";
        echo "\n";
    }
} else {
    echo "No releases found in database\n";
}

// WHY: Check published releases only
$publishedReleases = $rc->getAllReleases();
echo "\nPublished releases: " . count($publishedReleases) . "\n";
?>
