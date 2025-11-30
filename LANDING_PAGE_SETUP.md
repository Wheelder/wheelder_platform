# Simple Landing Page Setup (No Framework)

## Project Structure
Create this folder structure in your project root:

```
landing/
├── index.html
├── css/
│   └── style.css
├── js/
│   └── script.js
└── images/
    └── (add your images here)
```

## Step 1: Create `landing/index.html`

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wheeleder - Learn Better</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <div class="logo">Wheeleder</div>
            <ul class="nav-links">
                <li><a href="#home">Home</a></li>
                <li><a href="#features">Features</a></li>
                <li><a href="#pricing">Pricing</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="container">
            <div class="hero-content">
                <h1>Learn Better, Grow Faster</h1>
                <p>The all-in-one platform for modern education</p>
                <button class="btn btn-primary">Get Started Free</button>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features">
        <div class="container">
            <h2>Why Choose Wheeleder?</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <h3>AI-Powered Learning</h3>
                    <p>Personalized learning paths powered by artificial intelligence</p>
                </div>
                <div class="feature-card">
                    <h3>Interactive Courses</h3>
                    <p>Engage with interactive lessons and real-world projects</p>
                </div>
                <div class="feature-card">
                    <h3>Expert Instructors</h3>
                    <p>Learn from industry experts and certified professionals</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="pricing">
        <div class="container">
            <h2>Simple Pricing</h2>
            <div class="pricing-grid">
                <div class="pricing-card">
                    <h3>Free</h3>
                    <p class="price">$0/month</p>
                    <ul>
                        <li>✓ 5 courses</li>
                        <li>✓ Basic support</li>
                        <li>✗ No certificates</li>
                    </ul>
                    <button class="btn btn-secondary">Choose Plan</button>
                </div>
                <div class="pricing-card featured">
                    <h3>Pro</h3>
                    <p class="price">$9.99/month</p>
                    <ul>
                        <li>✓ Unlimited courses</li>
                        <li>✓ Priority support</li>
                        <li>✓ Certificates</li>
                    </ul>
                    <button class="btn btn-primary">Choose Plan</button>
                </div>
                <div class="pricing-card">
                    <h3>Enterprise</h3>
                    <p class="price">Custom</p>
                    <ul>
                        <li>✓ Custom solutions</li>
                        <li>✓ Dedicated support</li>
                        <li>✓ Analytics</li>
                    </ul>
                    <button class="btn btn-secondary">Contact Sales</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact">
        <div class="container">
            <h2>Get in Touch</h2>
            <form class="contact-form">
                <input type="text" placeholder="Your Name" required>
                <input type="email" placeholder="Your Email" required>
                <textarea placeholder="Your Message" rows="5" required></textarea>
                <button type="submit" class="btn btn-primary">Send Message</button>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 Wheeleder. All rights reserved.</p>
        </div>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>
```

## Step 2: Create `landing/css/style.css`

```css
/* Reset and Base Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    line-height: 1.6;
    color: #333;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

/* Navigation */
.navbar {
    background: white;
    padding: 1rem 0;
    position: sticky;
    top: 0;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    z-index: 100;
}

.navbar .container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.logo {
    font-size: 1.5rem;
    font-weight: bold;
    color: #667eea;
}

.nav-links {
    display: flex;
    list-style: none;
    gap: 2rem;
}

.nav-links a {
    text-decoration: none;
    color: #333;
    transition: color 0.3s;
}

.nav-links a:hover {
    color: #667eea;
}

/* Hero Section */
.hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 100px 0;
    text-align: center;
}

.hero-content h1 {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.hero-content p {
    font-size: 1.25rem;
    margin-bottom: 2rem;
}

/* Buttons */
.btn {
    padding: 10px 25px;
    border: none;
    border-radius: 5px;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-primary {
    background: #667eea;
    color: white;
}

.btn-primary:hover {
    background: #5568d3;
    transform: translateY(-2px);
}

.btn-secondary {
    background: white;
    color: #667eea;
}

.btn-secondary:hover {
    background: #f0f0f0;
}

/* Features Section */
.features {
    padding: 80px 0;
    background: #f9f9f9;
}

.features h2 {
    text-align: center;
    font-size: 2.5rem;
    margin-bottom: 3rem;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
}

.feature-card {
    background: white;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s, box-shadow 0.3s;
}

.feature-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
}

.feature-card h3 {
    color: #667eea;
    margin-bottom: 1rem;
}

/* Pricing Section */
.pricing {
    padding: 80px 0;
}

.pricing h2 {
    text-align: center;
    font-size: 2.5rem;
    margin-bottom: 3rem;
}

.pricing-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
}

.pricing-card {
    background: white;
    padding: 2rem;
    border-radius: 8px;
    border: 2px solid #eee;
    text-align: center;
    transition: all 0.3s;
}

.pricing-card.featured {
    border-color: #667eea;
    transform: scale(1.05);
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.2);
}

.pricing-card h3 {
    font-size: 1.5rem;
    margin-bottom: 1rem;
}

.pricing-card .price {
    font-size: 2rem;
    color: #667eea;
    margin-bottom: 1.5rem;
    font-weight: bold;
}

.pricing-card ul {
    list-style: none;
    margin-bottom: 2rem;
    text-align: left;
}

.pricing-card li {
    padding: 0.5rem 0;
}

/* Contact Section */
.contact {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 80px 0;
    text-align: center;
}

.contact h2 {
    font-size: 2.5rem;
    margin-bottom: 2rem;
}

.contact-form {
    max-width: 500px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.contact-form input,
.contact-form textarea {
    padding: 12px;
    border: none;
    border-radius: 5px;
    font-family: inherit;
    font-size: 1rem;
}

.contact-form input:focus,
.contact-form textarea:focus {
    outline: none;
    box-shadow: 0 0 10px rgba(255, 255, 255, 0.3);
}

/* Footer */
.footer {
    background: #333;
    color: white;
    text-align: center;
    padding: 2rem 0;
}

/* Responsive Design */
@media (max-width: 768px) {
    .nav-links {
        gap: 1rem;
        font-size: 0.9rem;
    }

    .hero-content h1 {
        font-size: 2rem;
    }

    .hero-content p {
        font-size: 1rem;
    }

    .features h2,
    .pricing h2,
    .contact h2 {
        font-size: 1.8rem;
    }

    .pricing-card.featured {
        transform: scale(1);
    }
}
```

## Step 3: Create `landing/js/script.js`

```javascript
// Smooth scrolling for navigation links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth' });
        }
    });
});

// Handle contact form submission
const contactForm = document.querySelector('.contact-form');
if (contactForm) {
    contactForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = {
            name: this.querySelector('input[type="text"]').value,
            email: this.querySelector('input[type="email"]').value,
            message: this.querySelector('textarea').value
        };
        
        console.log('Form submitted:', data);
        alert('Thank you for your message! We will get back to you soon.');
        this.reset();
    });
}

// Add scroll effect to navbar
window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.navbar');
    if (window.scrollY > 50) {
        navbar.style.boxShadow = '0 5px 15px rgba(0, 0, 0, 0.2)';
    } else {
        navbar.style.boxShadow = '0 2px 5px rgba(0, 0, 0, 0.1)';
    }
});

console.log('Landing page loaded successfully!');
```

## Step 4: Deploy It

### Option A: Direct PHP (Simplest)
1. Place the `landing` folder in your XAMPP `htdocs/wheelder/` directory
2. Access it via: `http://localhost/wheelder/landing/`

### Option B: Serve with Python
```bash
cd landing
python -m http.server 8000
```
Then visit: `http://localhost:8000`

### Option C: Use Python Flask
```bash
cd /path/to/wheelder
python -c "
from flask import Flask, send_from_directory
app = Flask(__name__)

@app.route('/')
def index():
    return send_from_directory('landing', 'index.html')

@app.route('/<path:path>')
def serve_file(path):
    return send_from_directory('landing', path)

app.run(debug=True, port=5000)
"
```

## Key Features

✅ **No framework needed** - Pure HTML, CSS, JavaScript
✅ **Responsive design** - Works on mobile, tablet, desktop
✅ **Smooth scrolling** - Navigation links work smoothly
✅ **Form handling** - Contact form submission
✅ **Hover effects** - Interactive cards and buttons
✅ **Sticky navbar** - Stays at top while scrolling
✅ **Fast loading** - Minimal dependencies

## Customization Tips

- Change colors in CSS variables (look for `#667eea`, `#764ba2`)
- Update content directly in HTML
- Add images to `images/` folder and reference them in HTML
- Modify button text and links
- Adjust padding/margins for spacing

## Next Steps

1. Test the page locally in your browser
2. Customize colors, text, and images
3. Add a proper backend (PHP, Python, Node.js) for contact form
4. Deploy to a hosting service (Netlify, Vercel, AWS, etc.)

