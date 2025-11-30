// Modern Learning Module JavaScript
class LearnModule {
    constructor() {
        this.currentFontSize = 16;
        this.minFontSize = 12;
        this.maxFontSize = 24;
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.setupCharacterCounter();
        this.setupFormHandling();
        this.setupExampleQuestions();
    }

    setupEventListeners() {
        // Copy to clipboard functionality
        document.addEventListener('click', (e) => {
            if (e.target.matches('[onclick*="copyToClipboard"]')) {
                e.preventDefault();
                const elementId = e.target.getAttribute('onclick').match(/copyToClipboard\('(.+?)'\)/)[1];
                this.copyToClipboard(elementId);
            }
        });

        // Font size controls
        document.addEventListener('click', (e) => {
            if (e.target.matches('[onclick*="increaseFontSize"]')) {
                e.preventDefault();
                this.increaseFontSize();
            }
            if (e.target.matches('[onclick*="decreaseFontSize"]')) {
                e.preventDefault();
                this.decreaseFontSize();
            }
        });

        // Image controls
        document.addEventListener('click', (e) => {
            if (e.target.matches('[onclick*="downloadImage"]')) {
                e.preventDefault();
                this.downloadImage();
            }
            if (e.target.matches('[onclick*="refreshImage"]')) {
                e.preventDefault();
                this.refreshImage();
            }
        });
    }

    setupCharacterCounter() {
        const questionInput = document.getElementById('questionInput');
        const charCount = document.getElementById('charCount');
        
        if (questionInput && charCount) {
            questionInput.addEventListener('input', () => {
                const count = questionInput.value.length;
                charCount.textContent = count;
                
                if (count > 800) {
                    charCount.classList.add('text-danger');
                } else {
                    charCount.classList.remove('text-danger');
                }
            });
        }
    }

    setupFormHandling() {
        const form = document.getElementById('questionForm');
        if (form) {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                const question = document.getElementById('questionInput').value.trim();
                if (!question) {
                    this.showAlert('Please enter a question.', 'warning');
                    return;
                }
                this.submitQuestion(question);
            });
        }
    }

    setupExampleQuestions() {
        // Example questions are handled by inline onclick handlers
        // This method can be extended for more dynamic functionality
    }

    async submitQuestion(question) {
        try {
            this.showLoading(true);
            this.hideResults();
            
            const formData = new FormData();
            formData.append('question', question);
            formData.append('csrf_token', this.getCSRFToken());
            
            const response = await fetch('process_question.php', {
                method: 'POST',
                body: formData
            });
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            
            const data = await response.json();
            
            if (data.success) {
                this.displayResults(data);
                this.showAlert('Question processed successfully!', 'success');
            } else {
                throw new Error(data.error || 'Unknown error occurred');
            }
        } catch (error) {
            console.error('Error submitting question:', error);
            this.showAlert('Error: ' + error.message, 'danger');
        } finally {
            this.showLoading(false);
        }
    }

    displayResults(data) {
        const resultsArea = document.getElementById('resultsArea');
        const aiResponse = document.getElementById('aiResponse');
        const generatedImage = document.getElementById('generatedImage');
        
        if (resultsArea && aiResponse) {
            // Display AI response
            aiResponse.innerHTML = data.formattedResponse;
            
            // Display generated image
            if (data.imageUrl && generatedImage) {
                generatedImage.innerHTML = `
                    <img src="${data.imageUrl}" 
                         class="img-fluid fade-in" 
                         alt="AI Generated Image" 
                         style="max-height: 400px; cursor: pointer;"
                         onclick="this.classList.toggle('img-fluid'); this.style.maxHeight = this.style.maxHeight === 'none' ? '400px' : 'none';">
                    <div class="mt-2">
                        <small class="text-muted">Click image to toggle size</small>
                    </div>
                `;
            }
            
            // Show results with animation
            resultsArea.style.display = 'block';
            resultsArea.classList.add('fade-in');
            
            // Scroll to results
            resultsArea.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    showLoading(show) {
        const loadingIndicator = document.getElementById('loadingIndicator');
        if (loadingIndicator) {
            loadingIndicator.style.display = show ? 'block' : 'none';
        }
    }

    hideResults() {
        const resultsArea = document.getElementById('resultsArea');
        if (resultsArea) {
            resultsArea.style.display = 'none';
            resultsArea.classList.remove('fade-in');
        }
    }

    async copyToClipboard(elementId) {
        try {
            const element = document.getElementById(elementId);
            if (!element) {
                throw new Error('Element not found');
            }
            
            let textToCopy = '';
            
            if (elementId === 'aiResponse') {
                // Get text content without HTML tags
                textToCopy = element.textContent || element.innerText;
            } else {
                textToCopy = element.value || element.textContent || element.innerText;
            }
            
            if (navigator.clipboard && window.isSecureContext) {
                await navigator.clipboard.writeText(textToCopy);
            } else {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = textToCopy;
                textArea.style.position = 'fixed';
                textArea.style.left = '-999999px';
                textArea.style.top = '-999999px';
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                document.execCommand('copy');
                textArea.remove();
            }
            
            this.showAlert('Content copied to clipboard!', 'success');
        } catch (error) {
            console.error('Failed to copy:', error);
            this.showAlert('Failed to copy content', 'danger');
        }
    }

    increaseFontSize() {
        if (this.currentFontSize < this.maxFontSize) {
            this.currentFontSize += 2;
            this.updateFontSize();
        }
    }

    decreaseFontSize() {
        if (this.currentFontSize > this.minFontSize) {
            this.currentFontSize -= 2;
            this.updateFontSize();
        }
    }

    updateFontSize() {
        const aiResponse = document.getElementById('aiResponse');
        if (aiResponse) {
            aiResponse.style.fontSize = `${this.currentFontSize}px`;
        }
        
        // Store preference in localStorage
        localStorage.setItem('learnFontSize', this.currentFontSize);
    }

    async downloadImage() {
        const generatedImage = document.getElementById('generatedImage');
        const img = generatedImage.querySelector('img');
        
        if (!img || !img.src) {
            this.showAlert('No image available to download', 'warning');
            return;
        }
        
        try {
            const response = await fetch(img.src);
            const blob = await response.blob();
            
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `ai-generated-image-${Date.now()}.png`;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
            
            this.showAlert('Image downloaded successfully!', 'success');
        } catch (error) {
            console.error('Error downloading image:', error);
            this.showAlert('Failed to download image', 'danger');
        }
    }

    async refreshImage() {
        const questionInput = document.getElementById('questionInput');
        const question = questionInput.value.trim();
        
        if (!question) {
            this.showAlert('Please enter a question first', 'warning');
            return;
        }
        
        try {
            this.showLoading(true);
            
            const formData = new FormData();
            formData.append('question', question);
            formData.append('action', 'regenerate_image');
            formData.append('csrf_token', this.getCSRFToken());
            
            const response = await fetch('process_question.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success && data.imageUrl) {
                const generatedImage = document.getElementById('generatedImage');
                generatedImage.innerHTML = `
                    <img src="${data.imageUrl}" 
                         class="img-fluid fade-in" 
                         alt="AI Generated Image" 
                         style="max-height: 400px; cursor: pointer;"
                         onclick="this.classList.toggle('img-fluid'); this.style.maxHeight = this.style.maxHeight === 'none' ? '400px' : 'none';">
                    <div class="mt-2">
                        <small class="text-muted">Click image to toggle size</small>
                    </div>
                `;
                this.showAlert('New image generated!', 'success');
            } else {
                throw new Error(data.error || 'Failed to regenerate image');
            }
        } catch (error) {
            console.error('Error regenerating image:', error);
            this.showAlert('Error: ' + error.message, 'danger');
        } finally {
            this.showLoading(false);
        }
    }

    getCSRFToken() {
        const tokenInput = document.querySelector('input[name="csrf_token"]');
        return tokenInput ? tokenInput.value : '';
    }

    showAlert(message, type = 'info') {
        // Create alert element
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        // Add to page
        document.body.appendChild(alertDiv);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }

    loadExampleQuestion(type) {
        const examples = {
            'science': 'How does photosynthesis work and why is it important for life on Earth?',
            'history': 'What were the main causes and consequences of the Industrial Revolution?',
            'technology': 'How do artificial neural networks work and what are their applications?',
            'philosophy': 'What are the main arguments for and against free will?',
            'literature': 'How did Shakespeare influence modern storytelling and language?',
            'mathematics': 'Explain the concept of infinity and its applications in mathematics.'
        };
        
        const questionInput = document.getElementById('questionInput');
        if (questionInput && examples[type]) {
            questionInput.value = examples[type];
            questionInput.focus();
            
            // Trigger character count update
            const event = new Event('input');
            questionInput.dispatchEvent(event);
        }
    }
}

// Initialize the module when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.learnModule = new LearnModule();
    
    // Load saved font size preference
    const savedFontSize = localStorage.getItem('learnFontSize');
    if (savedFontSize) {
        window.learnModule.currentFontSize = parseInt(savedFontSize);
        window.learnModule.updateFontSize();
    }
});

// Global functions for onclick handlers (legacy support)
function copyToClipboard(elementId) {
    if (window.learnModule) {
        window.learnModule.copyToClipboard(elementId);
    }
}

function increaseFontSize() {
    if (window.learnModule) {
        window.learnModule.increaseFontSize();
    }
}

function decreaseFontSize() {
    if (window.learnModule) {
        window.learnModule.decreaseFontSize();
    }
}

function downloadImage() {
    if (window.learnModule) {
        window.learnModule.downloadImage();
    }
}

function refreshImage() {
    if (window.learnModule) {
        window.learnModule.refreshImage();
    }
}

function loadExampleQuestion(type) {
    if (window.learnModule) {
        window.learnModule.loadExampleQuestion(type);
    }
}