<?php
// WHY: Force initialize the releases table and insert the release document
require_once 'apps/edu/controllers/ReleaseController.php';

echo "Initializing releases system...\n";

try {
    // WHY: Creating ReleaseController will trigger ensureReleasesTable()
    $rc = new ReleaseController();
    echo "✓ ReleaseController initialized\n";
    
    // WHY: Verify table exists
    require_once 'apps/edu/controllers/Controller.php';
    $ctrl = new Controller();
    $result = $ctrl->run_query("SHOW TABLES LIKE 'releases'");
    
    if ($result && $result->num_rows > 0) {
        echo "✓ Releases table exists\n";
    } else {
        echo "✗ Releases table still doesn't exist - attempting manual creation\n";
        
        // WHY: Manually create the table
        $createSQL = "CREATE TABLE IF NOT EXISTS releases (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            content LONGTEXT,
            images JSON,
            videos JSON,
            version VARCHAR(50),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            is_published BOOLEAN DEFAULT 1,
            INDEX idx_created_at (created_at),
            INDEX idx_published (is_published)
        )";
        
        $createResult = $ctrl->run_query($createSQL);
        if ($createResult !== false) {
            echo "✓ Releases table created successfully\n";
        } else {
            echo "✗ Failed to create releases table\n";
            exit(1);
        }
    }
    
    // WHY: Check if release document already exists
    $checkResult = $ctrl->run_query("SELECT COUNT(*) as cnt FROM releases");
    if ($checkResult) {
        $row = $checkResult->fetch_assoc();
        $count = $row['cnt'];
        echo "Current releases: " . $count . "\n";
        
        if ($count === 0) {
            echo "Creating initial release document...\n";
            
            $result = $rc->createRelease(
                'Introducing the Focused Prompt Modal',
                'A revolutionary approach to AI prompting that forces users to focus on crafting better questions for better answers.',
                '<h2>The Problem with Quick Prompting</h2><p>Traditional AI interfaces allow users to type questions directly in a small input box, leading to rushed, poorly-formed prompts. This results in mediocre answers because <strong>the quality of your prompt directly determines the quality of the AI response</strong>.</p><h2>Our Solution: The Focused Prompt Modal</h2><p>We have redesigned the prompting experience to encourage thoughtful, detailed questions through a dedicated modal interface that:</p><ul><li><strong>Separates Prompting from Results:</strong> A full-screen modal forces users to focus exclusively on crafting their prompt, not distracted by previous results</li><li><strong>Provides Ample Writing Space:</strong> A large textarea (220px minimum, 55vh maximum) gives users room to think and write naturally</li><li><strong>Includes Built-in Spell Checking:</strong> Browser-native spell and grammar checking catches errors in real-time</li><li><strong>Shows Character Count:</strong> Real-time feedback on prompt length helps users understand scope and detail level</li><li><strong>Offers Clear Actions:</strong> Three buttons (Ask, Clear, Cancel) make the next step obvious</li></ul><h2>Why Spell Checking Matters</h2><p>Spelling and grammar errors in your prompt can confuse the AI model, leading to misinterpretations. By catching these errors before submission, we ensure clarity, professionalism, and efficiency.</p><h2>The Psychology of Focused Writing</h2><p>Research in cognitive psychology shows that focused environments improve output quality. By removing distractions and providing a dedicated space for prompt composition, we reduce cognitive load, encourage deliberate prompting, and create a ritual that signals importance.</p><h2>How It Works</h2><h3>1. Click the Prompt Trigger</h3><p>The readonly textarea in the main view acts as a trigger. Clicking it opens the modal with a spinning animation (720 degree rotation over 0.8 seconds).</p><h3>2. Write Your Prompt</h3><p>The modal provides a large textarea with real-time character counter, browser spell/grammar checking, keyboard shortcuts (Ctrl+Enter to submit, Escape to cancel), and auto-focus on the textarea when modal opens.</p><h3>3. Submit or Clear</h3><p>Choose your action: Ask (submit and close), Clear (erase and start over), or Cancel (close without submitting).</p><h3>4. View Results</h3><p>The main view displays the AI answer and generated image side-by-side. The modal closes with a reverse spin animation (360 degree counter-rotation).</p><h2>Technical Innovation</h2><p>The modal uses CSS keyframe animations for smooth spin-in (720 degree rotation plus scale from 0 to 1 over 0.8s) and spin-out (-360 degree rotation plus scale to 0 over 0.45s) effects. It seamlessly adapts to dark mode and is fully mobile responsive.</p><h2>Why This Matters</h2><p>Prompt engineering is a critical skill in the age of AI. Users who ask better questions get better answers. Our focused modal is a teaching tool that encourages thoughtful, detailed, well-written prompts.</p><h2>The Result</h2><p>Users report more thoughtful prompts, better quality AI responses, fewer follow-up clarifications, more intentional learning, and increased satisfaction with results.</p><p><strong>Try it now:</strong> Click on the prompt textarea in the main view to experience the focused modal. Notice how the dedicated space encourages you to write more carefully and thoroughly.</p>',
                '1.0.0',
                [],
                []
            );
            
            if ($result['success']) {
                echo "✓ Release document created successfully\n";
            } else {
                echo "✗ Failed to create release: " . $result['message'] . "\n";
                exit(1);
            }
        } else {
            echo "✓ Release document already exists\n";
        }
    } else {
        echo "✗ Failed to query releases table\n";
        exit(1);
    }
    
    echo "\n✓ Releases system initialized successfully!\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
