<?php
// Configuration file for the Learn module
// Move sensitive data to environment variables or secure config files

// OpenAI Configuration
define('OPENAI_API_ENDPOINT', 'https://api.openai.com/v1/chat/completions');
define('OPENAI_IMAGE_ENDPOINT', 'https://api.openai.com/v1/images/generations');

// API Keys - These should be moved to environment variables
// For development, you can use a .env file or secure configuration
define('OPENAI_API_KEY', getenv('OPENAI_API_KEY') ?: 'your-api-key-here');
define('OPENAI_IMAGE_API_KEY', getenv('OPENAI_IMAGE_API_KEY') ?: 'your-image-api-key-here');

// Application Settings
define('DEFAULT_MODEL', 'gpt-4o');
define('DEFAULT_IMAGE_MODEL', 'dall-e-3');
define('MAX_TOKENS', 10000);
define('DEFAULT_TEMPERATURE', 0.7);

// UI Settings
define('DEFAULT_IMAGE_SIZE', '1024x1024');
define('MAX_QUERY_LENGTH', 1000);

// Security Settings
define('CSRF_TOKEN_NAME', 'learn_csrf_token');
define('SESSION_TIMEOUT', 3600); // 1 hour

// Error Messages
define('ERROR_MESSAGES', [
    'invalid_input' => 'Please provide a valid question.',
    'api_error' => 'Sorry, there was an error processing your request. Please try again.',
    'unauthorized' => 'Please log in to use this feature.',
    'rate_limit' => 'Too many requests. Please wait a moment before trying again.'
]);
?>
