<?php
// Configuration file for the Learn module
// Move sensitive data to environment variables or secure config files

// Prevent duplicate constant definitions
if (!defined('OPENAI_API_ENDPOINT')) {
    define('OPENAI_API_ENDPOINT', 'https://api.openai.com/v1/chat/completions');
}

if (!defined('OPENAI_IMAGE_ENDPOINT')) {
    define('OPENAI_IMAGE_ENDPOINT', 'https://api.openai.com/v1/images/generations');
}

// API Keys - These should be moved to environment variables
// For development, you can use a .env file or secure configuration
if (!defined('OPENAI_API_KEY')) {
    define('OPENAI_API_KEY', getenv('OPENAI_API_KEY') ?: 'your-api-key-here');
}

if (!defined('OPENAI_IMAGE_API_KEY')) {
    define('OPENAI_IMAGE_API_KEY', getenv('OPENAI_IMAGE_API_KEY') ?: 'your-image-api-key-here');
}

// Application Settings
if (!defined('DEFAULT_MODEL')) {
    define('DEFAULT_MODEL', 'gpt-4o');
}

if (!defined('DEFAULT_IMAGE_MODEL')) {
    define('DEFAULT_IMAGE_MODEL', 'dall-e-3');
}

if (!defined('MAX_TOKENS')) {
    define('MAX_TOKENS', 10000);
}

if (!defined('DEFAULT_TEMPERATURE')) {
    define('DEFAULT_TEMPERATURE', 0.7);
}

// UI Settings
if (!defined('DEFAULT_IMAGE_SIZE')) {
    define('DEFAULT_IMAGE_SIZE', '1024x1024');
}

if (!defined('MAX_QUERY_LENGTH')) {
    define('MAX_QUERY_LENGTH', 1000);
}

// Security Settings
if (!defined('CSRF_TOKEN_NAME')) {
    define('CSRF_TOKEN_NAME', 'learn_csrf_token');
}

if (!defined('SESSION_TIMEOUT')) {
    define('SESSION_TIMEOUT', 3600); // 1 hour
}

// Error Messages
if (!defined('ERROR_MESSAGES')) {
    define('ERROR_MESSAGES', [
        'invalid_input' => 'Please provide a valid question.',
        'api_error' => 'Sorry, there was an error processing your request. Please try again.',
        'unauthorized' => 'Please log in to use this feature.',
        'rate_limit' => 'Too many requests. Please wait a moment before trying again.'
    ]);
}
?>
