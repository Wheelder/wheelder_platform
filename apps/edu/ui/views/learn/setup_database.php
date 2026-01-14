<?php
/**
 * Database Setup Script for Learn Module
 * Run this script once to create the required database table
 */

$path = $_SERVER['DOCUMENT_ROOT'];
require_once $path . '/apps/edu/controllers/Controller.php';

class DatabaseSetup extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function createQuestionsTable()
    {
        try {
            $sql = "CREATE TABLE IF NOT EXISTS questions (
                id INT AUTO_INCREMENT PRIMARY KEY,
                question TEXT NOT NULL,
                answer TEXT NOT NULL,
                image VARCHAR(500),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_created_at (created_at),
                INDEX idx_question (question(255))
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
            
            $result = $this->connectDb()->query($sql);
            
            if ($result) {
                echo "✅ Questions table created successfully!\n";
                return true;
            } else {
                echo "❌ Error creating questions table: " . $this->connectDb()->error . "\n";
                return false;
            }
        } catch (Exception $e) {
            echo "❌ Exception occurred: " . $e->getMessage() . "\n";
            return false;
        }
    }

    public function checkTableExists()
    {
        $sql = "SHOW TABLES LIKE 'questions'";
        $result = $this->connectDb()->query($sql);
        
        if ($result && $result->num_rows > 0) {
            echo "✅ Questions table already exists.\n";
            return true;
        } else {
            echo "❌ Questions table does not exist.\n";
            return false;
        }
    }

    public function showTableStructure()
    {
        $sql = "DESCRIBE questions";
        $result = $this->connectDb()->query($sql);
        
        if ($result) {
            echo "\n📋 Table Structure:\n";
            echo str_repeat("-", 50) . "\n";
            echo sprintf("%-15s %-15s %-10s %-8s %-8s %-8s\n", "Field", "Type", "Null", "Key", "Default", "Extra");
            echo str_repeat("-", 50) . "\n";
            
            while ($row = $result->fetch_assoc()) {
                echo sprintf("%-15s %-15s %-10s %-8s %-8s %-8s\n", 
                    $row['Field'], 
                    $row['Type'], 
                    $row['Null'], 
                    $row['Key'], 
                    $row['Default'], 
                    $row['Extra']
                );
            }
        }
    }

    public function insertSampleData()
    {
        $sampleQuestions = [
            [
                'question' => 'What is artificial intelligence?',
                'answer' => 'Artificial Intelligence (AI) is a branch of computer science that aims to create systems capable of performing tasks that typically require human intelligence. These tasks include learning, reasoning, problem-solving, perception, and language understanding.',
                'image' => null
            ],
            [
                'question' => 'How does photosynthesis work?',
                'answer' => 'Photosynthesis is the process by which plants, algae, and some bacteria convert light energy into chemical energy. During this process, carbon dioxide and water are converted into glucose and oxygen using sunlight as the energy source.',
                'image' => null
            ]
        ];

        foreach ($sampleQuestions as $sample) {
            $sql = "INSERT INTO questions (question, answer, image) VALUES (?, ?, ?)";
            $stmt = $this->connectDb()->prepare($sql);
            
            if ($stmt) {
                $stmt->bind_param("sss", $sample['question'], $sample['answer'], $sample['image']);
                $result = $stmt->execute();
                
                if ($result) {
                    echo "✅ Sample question added: " . substr($sample['question'], 0, 50) . "...\n";
                } else {
                    echo "❌ Failed to add sample question: " . $stmt->error . "\n";
                }
                
                $stmt->close();
            }
        }
    }

    public function runSetup()
    {
        echo "🚀 Learn Module Database Setup\n";
        echo str_repeat("=", 40) . "\n\n";
        
        // Check if table exists
        if (!$this->checkTableExists()) {
            // Create table
            if ($this->createQuestionsTable()) {
                echo "\n";
                $this->showTableStructure();
                
                // Ask if user wants sample data
                echo "\n🤔 Would you like to insert sample data? (y/n): ";
                $handle = fopen("php://stdin", "r");
                $line = fgets($handle);
                fclose($handle);
                
                if (trim(strtolower($line)) === 'y' || trim(strtolower($line)) === 'yes') {
                    echo "\n📝 Inserting sample data...\n";
                    $this->insertSampleData();
                }
            }
        } else {
            $this->showTableStructure();
        }
        
        echo "\n✨ Setup complete! You can now use the Learn module.\n";
        echo "\n📚 Next steps:\n";
        echo "1. Set your OpenAI API keys in config.php\n";
        echo "2. Access the learn module through your application\n";
        echo "3. Start asking questions!\n";
    }
}

// Run setup if script is executed directly
if (php_sapi_name() === 'cli' || isset($_GET['run_setup'])) {
    $setup = new DatabaseSetup();
    $setup->runSetup();
} else {
    echo "This script can be run from command line or by adding ?run_setup=1 to the URL.\n";
}
?>
