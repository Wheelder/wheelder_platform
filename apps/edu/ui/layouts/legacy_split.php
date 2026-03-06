<?php
if (!function_exists('renderLegacySplitLayout')) {
    function renderLegacySplitLayout(array $payload): void
    {
        $title            = $payload['title'] ?? 'Wheelder';
        $createdLabel     = $payload['created_label'] ?? 'Created · Aug 2023';
        $toolbarHtml      = $payload['toolbar'] ?? '';
        $sidebarHtml      = $payload['sidebar'] ?? '';
        $leftPaneHtml     = $payload['left'] ?? '';
        $rightPaneHtml    = $payload['right'] ?? '';
        $abovePanelsHtml  = $payload['above_panels'] ?? '';
        $extraHead        = $payload['head'] ?? '';
        $extraScripts     = $payload['scripts'] ?? '';
        $bodyClass        = trim(($payload['body_class'] ?? '') . ' legacy-shell');
        $sidebarHidden    = $payload['sidebar_hidden'] ?? true;
        $brandLabel       = $payload['brand_label'] ?? 'Wheelder';

        $cssBase = function_exists('url') ? url('/apps/edu/ui/assets/css/legacy-split.css') . '?v=20260306b' : '/apps/edu/ui/assets/css/legacy-split.css?v=20260306b';
        $jsBase  = function_exists('url') ? url('/apps/edu/ui/assets/js/legacy-split.js') . '?v=20260306b' : '/apps/edu/ui/assets/js/legacy-split.js?v=20260306b';

        ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="<?php echo $cssBase; ?>">
    <?php echo $extraHead; ?>
</head>
<body class="<?php echo $bodyClass; ?>">
    <header class="legacy-brand-bar">
        <div class="legacy-brand-icon" aria-hidden="true"></div>
        <div class="legacy-brand-label"><?php echo htmlspecialchars($brandLabel, ENT_QUOTES, 'UTF-8'); ?></div>
    </header>

    <div class="container-fluid py-3">
        <div class="legacy-toolbar">
            <strong class="legacy-toolbar-title">Ask Wheelder</strong>
            <span class="legacy-created-badge"><?php echo htmlspecialchars($createdLabel, ENT_QUOTES, 'UTF-8'); ?></span>
            <?php echo $toolbarHtml; ?>
        </div>

        <?php if ($abovePanelsHtml): ?>
        <div class="legacy-above-panels">
            <?php echo $abovePanelsHtml; ?>
        </div>
        <?php endif; ?>

        <div class="legacy-body">
            <aside id="legacySidebar" class="legacy-sidebar<?php echo $sidebarHidden ? ' is-hidden' : ''; ?>">
                <?php echo $sidebarHtml; ?>
            </aside>

            <section class="legacy-split-main">
                <div class="legacy-panel legacy-panel-scrollable">
                    <?php echo $leftPaneHtml; ?>
                </div>
                <div class="legacy-panel legacy-panel-scrollable">
                    <?php echo $rightPaneHtml; ?>
                </div>
            </section>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo $jsBase; ?>"></script>
    <?php echo $extraScripts; ?>
</body>
</html>
<?php
    }
}
