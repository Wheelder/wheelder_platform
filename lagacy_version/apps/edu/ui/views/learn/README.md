# AI Learning Assistant Module

## Overview
The AI Learning Assistant is an improved, modern learning module that allows users to ask questions and receive intelligent AI-generated responses along with automatically generated images. This module provides an enhanced user experience with better security, performance, and accessibility.

## Features

### 🧠 AI-Powered Learning
- **OpenAI Integration**: Uses GPT-4o for intelligent question answering
- **Image Generation**: Automatically generates relevant images using DALL-E 3
- **Deep Learning**: "Deepen" feature to get more detailed explanations
- **Smart Prompting**: Optimized prompts for better AI responses

### 🎨 Modern User Interface
- **Responsive Design**: Works seamlessly on desktop, tablet, and mobile
- **Bootstrap 5**: Modern, accessible UI components
- **Interactive Elements**: Hover effects, animations, and smooth transitions
- **Dark Mode Support**: Automatic dark mode detection and support

### 🔒 Enhanced Security
- **CSRF Protection**: Built-in CSRF token validation
- **Input Sanitization**: XSS protection and input validation
- **Rate Limiting**: Prevents API abuse (10 requests per minute)
- **Session Management**: Secure authentication handling

### 📱 User Experience
- **Character Counter**: Real-time input length tracking
- **Loading States**: Visual feedback during API calls
- **Copy to Clipboard**: Easy content copying
- **Font Size Controls**: Adjustable text size for readability
- **Image Controls**: Download and regenerate images
- **Example Questions**: Quick-start question templates

## File Structure

```
learn/
├── config.php              # Configuration and constants
├── LearnController.php     # Main controller with business logic
├── app_main.php           # Main application interface
├── process_question.php    # AJAX request handler
├── style.css              # Modern, responsive styles
├── app.js                 # Enhanced JavaScript functionality
└── README.md              # This documentation
```

## Installation & Setup

### 1. Environment Configuration
Create a `.env` file or set environment variables:
```bash
OPENAI_API_KEY=your_openai_api_key_here
OPENAI_IMAGE_API_KEY=your_openai_image_api_key_here
```

### 2. Database Setup
Ensure your database has a `questions` table:
```sql
CREATE TABLE questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question TEXT NOT NULL,
    answer TEXT NOT NULL,
    image VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### 3. Dependencies
- PHP 7.4+
- MySQL/MariaDB
- cURL extension
- OpenSSL extension

## Usage

### Basic Question Asking
1. Navigate to the learn module
2. Type your question in the text area
3. Click "Ask Question" to get an AI response
4. View both the text response and generated image

### Deepening Answers
1. After asking a question, use the "Deepen Previous Answer" button
2. The AI will provide a more detailed explanation based on the previous response

### Quick Actions
- Use example question buttons for common topics
- Copy responses to clipboard
- Download generated images
- Adjust font size for better readability

## API Endpoints

### POST /process_question.php
Processes questions and returns AI responses.

**Parameters:**
- `question` (string): The user's question
- `action` (string): "ask" or "deepen"
- `csrf_token` (string): CSRF protection token

**Response:**
```json
{
    "success": true,
    "question": "What is quantum physics?",
    "formattedResponse": "<div>AI response...</div>",
    "rawResponse": "Raw AI response text",
    "imageUrl": "https://generated-image-url.com/image.png",
    "stored": true
}
```

## Configuration Options

### OpenAI Settings
- `DEFAULT_MODEL`: AI model to use (default: gpt-4o)
- `MAX_TOKENS`: Maximum response length (default: 10000)
- `DEFAULT_TEMPERATURE`: Response creativity (default: 0.7)

### Rate Limiting
- `maxRequestsPerMinute`: API request limit (default: 10)

### UI Settings
- `MAX_QUERY_LENGTH`: Maximum question length (default: 1000)
- `DEFAULT_IMAGE_SIZE`: Generated image size (default: 1024x1024)

## Security Features

### Input Validation
- Length limits and sanitization
- XSS protection
- SQL injection prevention

### Authentication
- Session-based user authentication
- Automatic redirect for unauthorized access

### Rate Limiting
- Per-user request limiting
- IP-based tracking
- Configurable limits

## Performance Optimizations

### Caching
- Session-based rate limiting
- Efficient database queries
- Prepared statements

### API Optimization
- Connection timeouts
- Request timeouts
- Error handling

## Browser Support

- **Modern Browsers**: Chrome 90+, Firefox 88+, Safari 14+, Edge 90+
- **Mobile**: iOS Safari 14+, Chrome Mobile 90+
- **Features**: ES6+, CSS Grid, Flexbox, Fetch API

## Troubleshooting

### Common Issues

1. **API Key Errors**
   - Verify environment variables are set
   - Check API key validity and permissions

2. **Rate Limiting**
   - Wait for rate limit reset (1 minute)
   - Check request frequency

3. **Image Generation Failures**
   - Verify DALL-E API access
   - Check prompt content for policy violations

### Debug Mode
Enable error logging in PHP configuration:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

## Contributing

### Code Style
- Follow PSR-12 coding standards
- Use meaningful variable and function names
- Add comments for complex logic
- Include error handling

### Testing
- Test on multiple devices and browsers
- Verify API integrations
- Check security measures

## License

This module is part of the Wheelder educational platform.

## Support

For issues and questions:
1. Check this documentation
2. Review error logs
3. Verify configuration settings
4. Test with simple questions first

---

**Version**: 2.0.0  
**Last Updated**: December 2024  
**Maintainer**: Wheelder Development Team
