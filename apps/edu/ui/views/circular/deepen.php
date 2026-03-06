<?php
// WHY: Deepen workflow view - opens in new tab showing workflow steps + tool recommendations
// Uses Bootstrap nav-tabs for clean tabbed interface

session_start();

// WHY: validate session and get workflow data from POST or session
$workflowData = $_SESSION['deepen_workflow'] ?? null;

// WHY: if no workflow data in session, show error
if (!$workflowData) {
    http_response_code(400);
    die('No workflow data available. Please go back and click Deepen again.');
}

// WHY: extract workflow components
$workflowSteps = $workflowData['answer'] ?? '';
$recommendedTools = $workflowData['recommended_tools'] ?? [];
$queryType = $workflowData['query_type'] ?? 'general';
$originalQuestion = $workflowData['original_question'] ?? '';
$depthLevel = $workflowData['depth_level'] ?? 0;

// WHY: clear session data after retrieving (one-time use)
unset($_SESSION['deepen_workflow']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deepen Workflow - Wheelder Circular</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Verdana, sans-serif;
        }
        
        .deepen-header {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
        }
        
        .deepen-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }
        
        .deepen-header p {
            margin: 8px 0 0 0;
            font-size: 14px;
            opacity: 0.9;
        }
        
        .nav-tabs {
            border-bottom: 2px solid #dee2e6;
            margin-bottom: 20px;
        }
        
        .nav-tabs .nav-link {
            color: #495057;
            border: none;
            border-bottom: 3px solid transparent;
            font-weight: 500;
            padding: 12px 16px;
            transition: all 0.3s ease;
        }
        
        .nav-tabs .nav-link:hover {
            border-bottom-color: #007bff;
            color: #007bff;
        }
        
        .nav-tabs .nav-link.active {
            color: #007bff;
            background-color: transparent;
            border-bottom-color: #007bff;
        }
        
        .tab-content {
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .tab-pane {
            display: none;
        }
        
        .tab-pane.active {
            display: block;
        }
        
        .workflow-steps {
            line-height: 1.8;
            color: #333;
        }
        
        .workflow-steps h2,
        .workflow-steps h3 {
            color: #007bff;
            margin-top: 20px;
            margin-bottom: 12px;
        }
        
        .workflow-steps ol,
        .workflow-steps ul {
            margin-bottom: 16px;
        }
        
        .workflow-steps li {
            margin-bottom: 8px;
        }
        
        .tool-card {
            padding: 16px;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            margin-bottom: 12px;
            background: #f8f9fa;
            transition: all 0.3s ease;
        }
        
        .tool-card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border-color: #007bff;
        }
        
        .tool-name {
            font-weight: bold;
            color: #007bff;
            font-size: 16px;
            margin-bottom: 6px;
        }
        
        .tool-description {
            font-size: 13px;
            color: #666;
            margin-bottom: 10px;
        }
        
        .tool-best-for {
            font-size: 12px;
            color: #999;
            margin-bottom: 10px;
            font-style: italic;
        }
        
        .tool-link {
            display: inline-block;
            padding: 8px 16px;
            background: #007bff;
            color: white;
            border-radius: 4px;
            text-decoration: none;
            font-size: 12px;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        
        .tool-link:hover {
            background: #0056b3;
            color: white;
            text-decoration: none;
        }
        
        .back-button {
            margin-bottom: 20px;
        }
        
        .back-button a {
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
        }
        
        .back-button a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <!-- Back button -->
        <div class="back-button">
            <a href="javascript:window.close();" title="Close this tab">
                <i class="fas fa-arrow-left"></i> Close Tab
            </a>
        </div>
        
        <!-- Header -->
        <div class="deepen-header">
            <h1>🔍 Deepen Workflow</h1>
            <p>Question: <strong><?php echo htmlspecialchars($originalQuestion); ?></strong></p>
            <p>Type: <strong><?php echo htmlspecialchars($queryType); ?></strong> | Depth Level: <strong><?php echo (int)$depthLevel; ?>/7</strong></p>
        </div>
        
        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="workflow-tab" data-bs-toggle="tab" data-bs-target="#workflow" type="button" role="tab" aria-controls="workflow" aria-selected="true">
                    📋 Workflow Steps
                </button>
            </li>
            
            <?php foreach ($recommendedTools as $tool): ?>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tool-<?php echo htmlspecialchars(str_replace(' ', '-', strtolower($tool['name'] ?? 'tool'))); ?>-tab" data-bs-toggle="tab" data-bs-target="#tool-<?php echo htmlspecialchars(str_replace(' ', '-', strtolower($tool['name'] ?? 'tool'))); ?>" type="button" role="tab" aria-controls="tool-<?php echo htmlspecialchars(str_replace(' ', '-', strtolower($tool['name'] ?? 'tool'))); ?>" aria-selected="false">
                    🔧 <?php echo htmlspecialchars($tool['name'] ?? 'Tool'); ?>
                </button>
            </li>
            <?php endforeach; ?>
        </ul>
        
        <!-- Tabs Content -->
        <div class="tab-content">
            <!-- Workflow Tab -->
            <div class="tab-pane fade show active" id="workflow" role="tabpanel" aria-labelledby="workflow-tab">
                <div class="workflow-steps">
                    <?php echo $workflowSteps; ?>
                </div>
            </div>
            
            <!-- Tool Tabs -->
            <?php foreach ($recommendedTools as $tool): ?>
            <div class="tab-pane fade" id="tool-<?php echo htmlspecialchars(str_replace(' ', '-', strtolower($tool['name'] ?? 'tool'))); ?>" role="tabpanel" aria-labelledby="tool-<?php echo htmlspecialchars(str_replace(' ', '-', strtolower($tool['name'] ?? 'tool'))); ?>-tab">
                <div class="tool-card">
                    <div class="tool-name"><?php echo htmlspecialchars($tool['name'] ?? 'Tool'); ?></div>
                    <div class="tool-description">
                        <?php echo htmlspecialchars($tool['description'] ?? 'AI tool for ' . ($tool['category'] ?? 'general') . ' tasks'); ?>
                    </div>
                    
                    <?php if (!empty($tool['best_for'])): ?>
                    <div class="tool-best-for">
                        <strong>Best for:</strong> <?php echo htmlspecialchars($tool['best_for']); ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($tool['url'])): ?>
                    <a href="<?php echo htmlspecialchars($tool['url']); ?>" target="_blank" class="tool-link">
                        Open in New Tab <i class="fas fa-external-link-alt"></i>
                    </a>
                    <?php endif; ?>
                </div>
                
                <!-- Additional info -->
                <div style="margin-top: 20px; padding: 16px; background: #e7f3ff; border-radius: 6px; border-left: 4px solid #007bff;">
                    <strong>💡 How to use this tool:</strong>
                    <p style="margin-top: 8px; margin-bottom: 0; font-size: 13px;">
                        This tool is recommended for your query. Click the button above to open it in a new tab, then use it to work on your task. You can switch between tabs to reference the workflow steps while using the tool.
                    </p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Footer -->
        <div style="margin-top: 40px; padding: 20px; background: #f8f9fa; border-radius: 8px; text-align: center; color: #666; font-size: 13px;">
            <p>💡 <strong>Tip:</strong> Keep this tab open while using the recommended tools. You can switch between tabs to reference the workflow steps.</p>
            <p style="margin-bottom: 0;">Return to Wheelder Circular to continue your research or deepen further.</p>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
