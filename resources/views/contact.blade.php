<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Contact Us - NovaERA HMS</title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Font Awesome Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Custom Styles -->
        <style>
            :root {
                --yellow: #F8B803;
                --white: #FFFFFF;
                --soft-black: #1B1B18;
                --gray: #706F6C;
                --light-gray: #F5F5F5;
            }
            
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            
            body {
                font-family: 'Poppins', sans-serif;
                color: var(--soft-black);
                line-height: 1.7;
                overflow-x: hidden;
            }
            
            .container {
                width: 100%;
                max-width: 1200px;
                padding: 0 1rem;
                margin: 0 auto;
            }
            
            a {
                text-decoration: none;
                color: inherit;
            }

            /* Buttons */
            .btn {
                display: inline-block;
                padding: 0.8rem 1.5rem;
                border-radius: 50px;
                font-weight: 500;
                transition: all 0.3s ease;
                cursor: pointer;
                border: none;
            }
            
            .btn-primary {
                background-color: var(--yellow);
                color: var(--soft-black);
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            }
            
            .btn-primary:hover {
                transform: translateY(-3px);
                box-shadow: 0 10px 15px rgba(0,0,0,0.1);
            }
            
            .btn-secondary {
                background-color: var(--white);
                color: var(--soft-black);
                border: 2px solid var(--soft-black);
            }
            
            .btn-secondary:hover {
                background-color: var(--soft-black);
                color: var(--white);
            }
            
            form.inline {
                display: inline-block;
            }
            
            button.btn {
                font-family: 'Poppins', sans-serif;
                font-size: 1rem;
                cursor: pointer;
            }

            /* Header/Navigation */
            .header {
                background-color: var(--white);
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                position: fixed;
                width: 100%;
                top: 0;
                left: 0;
                z-index: 1000;
            }
            
            .nav {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 1.2rem 0;
            }
            
            .logo {
                font-size: 1.8rem;
                font-weight: 700;
                color: var(--soft-black);
            }

            .logo span {
                color: var(--yellow);
            }
            
            .nav-links {
                display: flex;
                align-items: center;
                gap: 2rem;
            }
            
            .nav-links a {
                font-weight: 500;
                position: relative;
            }
            
            .nav-links a::after {
                content: '';
                position: absolute;
                bottom: -5px;
                left: 0;
                width: 0;
                height: 2px;
                background-color: var(--yellow);
                transition: width 0.3s;
            }
            
            .nav-links a:hover::after {
                width: 100%;
            }
            
            .mobile-menu-btn {
                display: none;
                font-size: 1.5rem;
                cursor: pointer;
            }
            
            /* Footer */
            .footer {
                background-color: var(--soft-black);
                color: var(--white);
                padding: 4rem 0 2rem;
            }
            
            .footer-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 2rem;
                margin-bottom: 2rem;
            }
            
            .footer-col h4 {
                color: var(--yellow);
                margin-bottom: 1.5rem;
                font-weight: 600;
                font-size: 1.2rem;
            }
            
            .footer-links {
                list-style: none;
            }
            
            .footer-links li {
                margin-bottom: 0.8rem;
            }
            
            .footer-links a {
                color: var(--white);
                opacity: 0.8;
                transition: all 0.3s;
            }
            
            .footer-links a:hover {
                opacity: 1;
                color: var(--yellow);
            }
            
            .contact-link {
                display: inline-block;
                margin-top: 10px;
                padding: 5px 15px;
                background-color: rgba(255,255,255,0.1);
                border-radius: 20px;
                transition: all 0.3s ease;
            }
            
            .contact-link:hover {
                background-color: var(--yellow);
                color: var(--soft-black);
            }
            
            .footer-social {
                display: flex;
                gap: 1rem;
            }
            
            .social-icon {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                background-color: rgba(255,255,255,0.1);
                display: flex;
                justify-content: center;
                align-items: center;
                transition: all 0.3s;
            }
            
            .social-icon:hover {
                background-color: var(--yellow);
                color: var(--soft-black);
            }
            
            .footer-bottom {
                padding-top: 2rem;
                border-top: 1px solid rgba(255,255,255,0.1);
                text-align: center;
                font-size: 0.9rem;
                opacity: 0.7;
            }
            
            /* Responsive navigation */
            @media screen and (max-width: 768px) {
                .nav-links {
                    display: none;
                    position: absolute;
                    top: 100%;
                    left: 0;
                    width: 100%;
                    background-color: var(--white);
                    padding: 1rem;
                    box-shadow: 0 5px 10px rgba(0,0,0,0.1);
                    flex-direction: column;
                    align-items: flex-start;
                }
                
                .nav-links.show {
                    display: flex;
                }
                
                .mobile-menu-btn {
                    display: block;
                }
            }
            
            /* Contact Page Styles */
            .contact-page {
                padding-top: 80px;
                background-color: #f9f9f9;
            }
            
            /* Hero Section with Angled Design */
            .contact-hero {
                position: relative;
                height: 40vh;
                min-height: 300px;
                background-color: var(--yellow);
                display: flex;
                align-items: center;
                justify-content: center;
                text-align: center;
                color: var(--soft-black);
                overflow: hidden;
                clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%);
            }
            
            .hero-content {
                position: relative;
                z-index: 2;
                padding: 0 20px;
                max-width: 800px;
            }
            
            .contact-hero h1 {
                font-size: 3.5rem;
                margin-bottom: 1rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 1px;
            }
        </style>
    </head>
    <body>
        <!-- Header -->
        <header class="header">
            <div class="container">
                <nav class="nav">
                    <a href="{{ route('home') }}" class="logo">Nova<span>ERA</span> HMS</a>
                    <div class="nav-links">
                        <a href="{{ route('home') }}#features">Features</a>
                        <a href="{{ route('home') }}#benefits">Benefits</a>
                        <a href="{{ route('home') }}#testimonials">Testimonials</a>
                        <a href="{{ route('contact.index') }}">Contact</a>
                        
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn btn-primary">Dashboard</a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="btn btn-secondary">Logout</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                            <a href="{{ route('register') }}" class="btn btn-secondary">Register</a>
                        @endauth
                    </div>
                    <div class="mobile-menu-btn">
                        <i class="fas fa-bars"></i>
                    </div>
                </nav>
            </div>
        </header>
        
        <div class="contact-page">
            <!-- Hero Section with Angled Design -->
            <div class="contact-hero">
                <div class="hero-content">
                    <h1>Contact Us</h1>
                    <p>We're here to help you revolutionize your hotel management experience</p>
                </div>
                <div class="hero-overlay"></div>
            </div>
    
            <!-- Contact Content -->
    <div class="contact-content">
        <div class="container">
            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="alert-box success">
                    <i class="fas fa-check-circle"></i>
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            
            @if ($errors->any())
                <div class="alert-box error">
                    <i class="fas fa-exclamation-circle"></i>
                    <p>Please correct the errors below and try again.</p>
                </div>
            @endif
            
            <div class="contact-panels">
                <!-- Left Panel: Contact Information -->
                <div class="contact-info-panel">
                    <div class="info-header">
                        <h2>Get in Touch</h2>
                        <div class="accent-line"></div>
                    </div>
                    
                    <div class="info-items">
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="info-content">
                                <h3>Location</h3>
                                <p>Kosovo , Prizren</p>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="info-content">
                                <h3>Phone</h3>
                                <p>+383 49821554</p>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="info-content">
                                <h3>Email</h3>
                                <p>info@novaera-hms.com</p>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="info-content">
                                <h3>Business Hours</h3>
                                <p>Monday - Friday: 9:00 AM - 5:00 PM</p>
                                <p>Saturday: 10:00 AM - 2:00 PM</p>
                                <p>Sunday: Closed</p>
                            </div>
                        </div>
                    </div>
                    
                    {{-- <div class="social-connect">
                        <h3>Connect With Us</h3>
                        <div class="social-icons">
                            <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div> --}}
                </div>
                
                <!-- Right Panel: Contact Form -->
                <div class="contact-form-panel">
                    <div class="form-header">
                        <h2>Send Us a Message</h2>
                        <div class="accent-line"></div>
                    </div>
                    
                    <form action="{{ route('contact.send') }}" method="POST" class="modern-form">
                        @csrf
                        <div class="form-group">
                            <input type="text" id="name" name="name" class="modern-input" required>
                            <label for="name" class="floating-label">Your Name</label>
                            @error('name')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <input type="email" id="email" name="email" class="modern-input" required>
                            <label for="email" class="floating-label">Your Email</label>
                            @error('email')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <input type="text" id="subject" name="subject" class="modern-input" required>
                            <label for="subject" class="floating-label">Subject</label>
                            @error('subject')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <textarea id="message" name="message" class="modern-input" rows="5" required></textarea>
                            <label for="message" class="floating-label">Message</label>
                            @error('message')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <button type="submit" class="submit-button">
                            <span>Send Message</span>
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
{{--     
    <!-- Map Section with Modern Design -->
    <div class="map-container">
        <div class="map-overlay">
            <div class="map-card">
                <h3>Visit Our Office</h3>
                <p>Our team is ready to assist you in person at our main office location.</p>
                <a href="https://maps.google.com/?q=123+Hotel+Street,+City,+Country" target="_blank" class="map-button">
                    <span>Get Directions</span>
                    <i class="fas fa-directions"></i>
                </a>
            </div>
        </div>
        <div class="map-section">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d387193.30591910525!2d-74.25986332363774!3d40.697149422113014!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c24fa5d33f083b%3A0xc80b8f06e177fe62!2sNew%20York%2C%20NY%2C%20USA!5e0!3m2!1sen!2sus!4v1687887249493!5m2!1sen!2sus" 
                width="100%" 
                height="500" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>
</div> --}}

        <!-- Footer -->
        <footer class="footer">
            <div class="container">
                <div class="footer-grid">
                    <div class="footer-col">
                        <h4>NovaERA HMS</h4>
                        <p>Modern hotel management system designed to streamline operations and enhance guest experiences.</p>
                        {{-- <div class="footer-social">
                            <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                        </div> --}}
                    </div>
                    <div class="footer-col">
                        <h4>Solutions</h4>
                        <ul class="footer-links">
                            <li>Room Managemen</li>
                            <li>Menu Menagement</li>
                            <li>Cleaning Menagement</li>
                            <li>Service Menagement</li>
                            <li>Realtime Services</li>
                        </ul>
                    </div>
                    {{-- <div class="footer-col">
                        <h4>Company</h4>
                        <ul class="footer-links">
                            <li><a href="#">About Us</a></li>
                            <li><a href="#">Our Team</a></li>
                            <li><a href="#">Careers</a></li>
                            <li><a href="#">Press & Media</a></li>
                            <li><a href="{{ route('contact.index') }}">Contact Us</a></li>
                        </ul>
                    </div> --}}
                    <div class="footer-col">
                        <h4>Contact</h4>
                        <ul class="footer-links">
                            <li><i class="fas fa-map-marker-alt"></i> Kosovo , Prizren</li>
                            <li><i class="fas fa-phone"></i> +383 49821554</li>
                            <li><i class="fas fa-envelope"></i> info@novaera-hms.com</li>
                            <li><a href="{{ route('contact.index') }}" class="contact-link"><i class="fas fa-paper-plane"></i> Contact Us</a></li>
                        </ul>
                    </div>
                </div>
                <div class="footer-bottom">
                    <p>&copy; {{ date('Y') }} NovaERA Hotel Management System. All Rights Reserved.</p>
                </div>
            </div>
        </footer>

        <!-- JavaScript -->
        <script>
            // Mobile menu toggle
            document.querySelector('.mobile-menu-btn').addEventListener('click', function() {
                document.querySelector('.nav-links').classList.toggle('show');
            });
            
            // Smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    if(this.getAttribute('href') !== '#') {
                        e.preventDefault();
                        document.querySelector(this.getAttribute('href')).scrollIntoView({
                            behavior: 'smooth'
                        });
                    }
                });
            });
        </script>
    </body>
</html>

<style>
    /* Modern Contact Page Styles */
    .contact-page {
        padding-top: 0;
        background-color: #f9f9f9;
    }
    
    /* Hero Section with Angled Design */
    .contact-hero {
        position: relative;
        height: 40vh;
        min-height: 300px;
        background-color: var(--yellow);
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: var(--soft-black);
        overflow: hidden;
        clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%);
    }
    
    .hero-content {
        position: relative;
        z-index: 2;
        padding: 0 20px;
        max-width: 800px;
    }
    
    .contact-hero h1 {
        font-size: 3.5rem;
        margin-bottom: 1rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .contact-hero p {
        font-size: 1.2rem;
        opacity: 0.9;
    }
    
    .hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: repeating-linear-gradient(
            45deg,
            rgba(255, 255, 255, 0.1),
            rgba(255, 255, 255, 0.1) 10px,
            rgba(255, 255, 255, 0.15) 10px,
            rgba(255, 255, 255, 0.15) 20px
        );
    }
    
    /* Contact Content */
    .contact-content {
        padding: 5rem 0;
        margin-top: -50px;
    }
    
    .contact-panels {
        display: grid;
        grid-template-columns: 1fr 1.5fr;
        gap: 30px;
        max-width: 1200px;
        margin: 0 auto;
    }
    
    /* Alert Boxes */
    .alert-box {
        display: flex;
        align-items: center;
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 30px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    
    .alert-box i {
        font-size: 24px;
        margin-right: 15px;
    }
    
    .alert-box p {
        margin: 0;
        font-weight: 500;
    }
    
    .alert-box.success {
        background-color: rgba(87, 188, 144, 0.15);
        border-left: 4px solid #57bc90;
        color: #2a805c;
    }
    
    .alert-box.error {
        background-color: rgba(239, 83, 80, 0.15);
        border-left: 4px solid #ef5350;
        color: #b71c1c;
    }
    
    /* Contact Info Panel */
    .contact-info-panel {
        background: white;
        border-radius: 12px;
        padding: 40px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        height: fit-content;
    }
    
    .info-header, .form-header {
        margin-bottom: 30px;
        position: relative;
    }
    
    .info-header h2, .form-header h2 {
        font-size: 1.8rem;
        font-weight: 600;
        margin-bottom: 12px;
    }
    
    .accent-line {
        width: 60px;
        height: 4px;
        background: var(--yellow);
        border-radius: 2px;
    }
    
    .info-items {
        margin-top: 30px;
    }
    
    .info-item {
        display: flex;
        margin-bottom: 25px;
        align-items: flex-start;
    }
    
    .info-icon {
        width: 50px;
        height: 50px;
        background-color: var(--yellow);
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-right: 20px;
        font-size: 1.2rem;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(248, 184, 3, 0.3);
        transition: transform 0.3s ease;
    }
    
    .info-item:hover .info-icon {
        transform: translateY(-3px) scale(1.05);
    }
    
    .info-content h3 {
        font-size: 1.1rem;
        margin-bottom: 5px;
        font-weight: 600;
        color: var(--soft-black);
    }
    
    .info-content p {
        margin-bottom: 3px;
        color: var(--gray);
        line-height: 1.6;
    }
    
    /* Social Connect Section */
    .social-connect {
        margin-top: 40px;
        text-align: center;
        padding-top: 20px;
        border-top: 1px dashed #e0e0e0;
    }
    
    .social-connect h3 {
        font-size: 1.2rem;
        margin-bottom: 15px;
        font-weight: 600;
    }
    
    .social-icons {
        display: flex;
        justify-content: center;
        gap: 15px;
    }
    
    .social-icons .social-icon {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background-color: #f5f5f5;
        display: flex;
        justify-content: center;
        align-items: center;
        color: var(--soft-black);
        font-size: 1.1rem;
        transition: all 0.3s ease;
    }
    
    .social-icons .social-icon:hover {
        background-color: var(--yellow);
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    /* Contact Form Panel */
    .contact-form-panel {
        background: white;
        border-radius: 12px;
        padding: 40px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    }
    
    /* Modern Form Styling */
    .modern-form .form-group {
        position: relative;
        margin-bottom: 25px;
    }
    
    .modern-input {
        width: 100%;
        padding: 15px 20px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        font-family: 'Poppins', sans-serif;
        font-size: 1rem;
        transition: all 0.3s;
        background-color: #f9f9f9;
    }
    
    .modern-input:focus {
        outline: none;
        border-color: var(--yellow);
        box-shadow: 0 0 0 3px rgba(248, 184, 3, 0.15);
        background-color: #fff;
    }
    
    .floating-label {
        position: absolute;
        top: 50%;
        left: 20px;
        transform: translateY(-50%);
        font-size: 1rem;
        color: #999;
        pointer-events: none;
        transition: all 0.3s ease;
    }
    
    textarea ~ .floating-label {
        top: 25px;
        transform: none;
    }
    
    .modern-input:focus ~ .floating-label,
    .modern-input:not(:placeholder-shown) ~ .floating-label {
        top: -12px;
        left: 10px;
        font-size: 0.85rem;
        padding: 0 5px;
        background-color: white;
        color: var(--yellow);
        font-weight: 500;
    }
    
    .error-message {
        color: #ef5350;
        font-size: 0.85rem;
        margin-top: 5px;
        display: block;
    }
    
    /* Submit Button */
    .submit-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 15px 30px;
        background-color: var(--yellow);
        color: var(--soft-black);
        border: none;
        border-radius: 30px;
        font-family: 'Poppins', sans-serif;
        font-weight: 500;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(248, 184, 3, 0.3);
    }
    
    .submit-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(248, 184, 3, 0.4);
    }
    
    .submit-button i {
        transition: transform 0.3s ease;
    }
    
    .submit-button:hover i {
        transform: translateX(5px);
    }
    
    /* Map Section */
    .map-container {
        position: relative;
    }
    
    .map-section {
        height: 500px;
    }
    
    .map-section iframe {
        display: block;
        filter: grayscale(0.6) contrast(1.1);
    }
    
    .map-overlay {
        position: absolute;
        top: -50px;
        left: 0;
        width: 100%;
        display: flex;
        justify-content: center;
        z-index: 100;
    }
    
    .map-card {
        background-color: var(--white);
        padding: 25px 30px;
        border-radius: 12px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        max-width: 400px;
        text-align: center;
    }
    
    .map-card h3 {
        font-size: 1.4rem;
        margin-bottom: 10px;
        font-weight: 600;
        color: var(--soft-black);
    }
    
    .map-card p {
        margin-bottom: 20px;
        color: var(--gray);
    }
    
    .map-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 10px 25px;
        background-color: var(--soft-black);
        color: var(--white);
        border-radius: 25px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .map-button:hover {
        background-color: var(--yellow);
        color: var(--soft-black);
        transform: translateY(-3px);
    }
    
    /* Responsive Styles */
    @media screen and (max-width: 992px) {
        .contact-panels {
            grid-template-columns: 1fr;
            gap: 40px;
        }
        
        .contact-info-panel {
            order: 2;
        }
        
        .contact-form-panel {
            order: 1;
        }
        
        .info-items {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
    }
    
    @media screen and (max-width: 768px) {
        .contact-hero {
            height: 30vh;
        }
        
        .contact-hero h1 {
            font-size: 2.5rem;
        }
        
        .info-items {
            grid-template-columns: 1fr;
        }
        
        .map-overlay {
            position: relative;
            top: 0;
            padding: 20px;
        }
        
        .map-card {
            max-width: 100%;
        }
    }
    
    @media screen and (max-width: 480px) {
        .contact-panels {
            padding: 0 15px;
        }
        
        .contact-info-panel, 
        .contact-form-panel {
            padding: 30px 20px;
        }
        
        .contact-hero h1 {
            font-size: 2rem;
        }
    }
</style>
