<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>NovaERA HMS - Modern Hotel Management System</title>

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

            /* Hero Section */
            .hero {
                background-image: linear-gradient(rgba(27, 27, 24, 0.7), rgba(27, 27, 24, 0.7)), url('https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80');
                background-size: cover;
                background-position: center;
                background-attachment: fixed;
                height: 100vh;
                display: flex;
                align-items: center;
                text-align: center;
                color: var(--white);
                position: relative;
            }
            
            .hero-content {
                max-width: 800px;
                margin: 0 auto;
            }
            
            .hero-title {
                font-size: 3.5rem;
                font-weight: 700;
                margin-bottom: 1rem;
                line-height: 1.2;
            }
            
            .hero-title span {
                color: var(--yellow);
            }
            
            .hero-subtitle {
                font-size: 1.2rem;
                margin-bottom: 2rem;
                font-weight: 300;
            }
            
            .hero-btns {
                display: flex;
                gap: 1rem;
                justify-content: center;
                margin-top: 2rem;
            }

            /* Features */
            .features {
                padding: 5rem 0;
                background-color: var(--white);
            }
            
            .section-title {
                text-align: center;
                font-size: 2.5rem;
                margin-bottom: 3rem;
                font-weight: 600;
                position: relative;
            }
            
            .section-title::after {
                content: '';
                position: absolute;
                bottom: -10px;
                left: 50%;
                transform: translateX(-50%);
                width: 80px;
                height: 3px;
                background-color: var(--yellow);
            }

            .features-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 2rem;
            }
            
            .feature-card {
                background-color: var(--white);
                border-radius: 10px;
                padding: 2rem;
                text-align: center;
                transition: all 0.3s ease;
                box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            }
            
            .feature-card:hover {
                transform: translateY(-10px);
                box-shadow: 0 15px 20px rgba(0,0,0,0.1);
            }
            
            .feature-icon {
                font-size: 2.5rem;
                color: var(--yellow);
                margin-bottom: 1rem;
            }
            
            .feature-title {
                font-size: 1.5rem;
                font-weight: 600;
                margin-bottom: 1rem;
            }

            /* Benefits Section */
            .benefits {
                padding: 5rem 0;
                background-color: var(--light-gray);
            }
            
            .benefits-wrapper {
                display: flex;
                align-items: center;
                gap: 3rem;
            }
            
            .benefits-image {
                flex: 1;
                border-radius: 10px;
                overflow: hidden;
            }
            
            .benefits-image img {
                width: 100%;
                height: auto;
                display: block;
            }
            
            .benefits-content {
                flex: 1;
            }
            
            .benefits-title {
                font-size: 2rem;
                margin-bottom: 1.5rem;
                font-weight: 600;
            }
            
            .benefits-list {
                list-style: none;
            }
            
            .benefits-list li {
                margin-bottom: 1rem;
                display: flex;
                align-items: flex-start;
                gap: 0.5rem;
            }
            
            .benefits-list li i {
                color: var(--yellow);
                font-size: 1.2rem;
                margin-top: 5px;
            }

            /* System Showcase with Laptop Frame */
            .system-showcase {
                padding: 5rem 0;
                background-color: var(--white);
            }
            
            .laptop-frame-container {
                max-width: 900px;
                margin: 3rem auto;
                padding: 0 1rem;
            }
            
            .laptop-frame {
                position: relative;
                width: 100%;
                perspective: 2000px;
            }
            
            .laptop-screen {
                position: relative;
                width: 100%;
                height: 0;
                padding-bottom: 62.5%; /* 16:10 aspect ratio */
                background-color: #000;
                border-radius: 15px 15px 0 0;
                border: 20px solid #555;
                border-bottom: none;
                overflow: hidden;
                box-shadow: 0 5px 20px rgba(0,0,0,0.2);
            }
            
            .laptop-keyboard {
                height: 20px;
                background: linear-gradient(to bottom, #888, #666);
                border-radius: 0 0 15px 15px;
                position: relative;
                transform: perspective(1000px) rotateX(6deg);
                transform-origin: top center;
                box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            }
            
            .laptop-keyboard:after {
                content: '';
                position: absolute;
                width: 15%;
                height: 4px;
                background-color: #444;
                bottom: 8px;
                left: 42.5%;
                border-radius: 5px;
            }
            
            .laptop-slider {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
            }
            
            .slide {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                opacity: 0;
                transition: opacity 1s ease-in-out;
            }
            
            .slide.active {
                opacity: 1;
            }
            
            .slide img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
            }

            /* CTA */
            .cta {
                padding: 5rem 0;
                background-color: var(--yellow);
                text-align: center;
                color: var(--soft-black);
            }
            
            .cta-title {
                font-size: 2.5rem;
                margin-bottom: 1.5rem;
                font-weight: 700;
            }
            
            .cta-text {
                margin-bottom: 2rem;
                font-size: 1.1rem;
                max-width: 700px;
                margin-left: auto;
                margin-right: auto;
            }

            .cta-btn {
                background-color: var(--soft-black);
                color: var(--white);
            }
            
            .cta-btn:hover {
                background-color: var(--white);
                color: var(--soft-black);
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

            /* Responsive Styles */
            @media screen and (max-width: 992px) {
                .hero-title {
                    font-size: 2.8rem;
                }
                
                .benefits-wrapper {
                    flex-direction: column;
                }
                
                .section-title, .cta-title {
                    font-size: 2rem;
                }
                
                .laptop-screen {
                    border-width: 15px;
                }
            }
            
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
                
                .hero-title {
                    font-size: 2.2rem;
                }
                
                .hero-subtitle {
                    font-size: 1rem;
                }
                
                .hero-btns {
                    flex-direction: column;
                    align-items: center;
                }
                
                .laptop-screen {
                    border-width: 10px;
                }
            }
            
            @media screen and (max-width: 480px) {
                .laptop-screen {
                    border-width: 8px;
                }
                
                .laptop-keyboard {
                    height: 15px;
                }
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
                        <a href="#features">Features</a>
                        <a href="#benefits">Benefits</a>
                        <a href="#testimonials">Testimonials</a>
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

        <!-- Hero Section -->
        <section class="hero">
            <div class="container">
                <div class="hero-content">
                    <h1 class="hero-title">Revolutionize Your Hotel Management with <span>NovaERA</span></h1>
                    <p class="hero-subtitle">A comprehensive, cloud-based hotel management system designed to streamline operations, enhance guest experiences, and maximize profitability.</p>
                    <div class="hero-btns">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn btn-primary">Go to Dashboard</a>
                        @else
                            <a href="{{ route('register') }}" class="btn btn-primary">Get Started</a>
                        @endauth
                        <a href="#features" class="btn btn-secondary">Explore Features</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="features">
            <div class="container">
                <h2 class="section-title">Key Features</h2>
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <h3 class="feature-title">Reservation Management</h3>
                        <p>Efficiently manage all your bookings with our intuitive reservation system. Track availability, process reservations, and send automated confirmations.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-concierge-bell"></i>
                        </div>
                        <h3 class="feature-title">Front Desk Operations</h3>
                        <p>Streamline check-ins/check-outs, room assignments, and guest services with our user-friendly front desk module designed for operational efficiency.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <h3 class="feature-title">Payment Processing</h3>
                        <p>Accept multiple payment methods, create itemized bills, and process payments securely. Generate invoices and receipts automatically.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-user-friends"></i>
                        </div>
                        <h3 class="feature-title">Guest Relationship Management</h3>
                        <p>Build guest profiles, track preferences, and provide personalized experiences. Improve guest satisfaction and drive repeat business.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3 class="feature-title">Reporting & Analytics</h3>
                        <p>Gain valuable insights with comprehensive reporting tools. Monitor performance metrics and make data-driven decisions for your business.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h3 class="feature-title">Mobile Access</h3>
                        <p>Manage your hotel on the go with our mobile-friendly platform. Access critical information and perform essential tasks from anywhere.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Benefits Section -->
        <section id="benefits" class="benefits">
            <div class="container">
                <h2 class="section-title">Why Choose NovaERA HMS</h2>
                <div class="benefits-wrapper">
                    <div class="benefits-image">
                        <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=1080&q=80" alt="Hotel Management System">
                    </div>
                    <div class="benefits-content">
                        <h3 class="benefits-title">Transform Your Hotel Operations</h3>
                        <ul class="benefits-list">
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <div>
                                    <strong>Increased Efficiency:</strong> Automate routine tasks and reduce administrative burden by up to 70%.
                                </div>
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <div>
                                    <strong>Enhanced Guest Experience:</strong> Deliver personalized service that keeps guests coming back.
                                </div>
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <div>
                                    <strong>Cost Savings:</strong> Optimize resources, reduce overbookings, and minimize operational costs.
                                </div>
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <div>
                                    <strong>Revenue Optimization:</strong> Dynamic pricing strategies and upselling opportunities to maximize profit.
                                </div>
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <div>
                                    <strong>Real-time Data:</strong> Make informed decisions with instant access to critical business metrics.
                                </div>
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <div>
                                    <strong>Cloud-based Solution:</strong> Access your system from anywhere, with automatic updates and backups.
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- System Showcase Section -->
        <section id="testimonials" class="system-showcase">
            <div class="container">
                <h2 class="section-title">NovaERA HMS in Action</h2>
                <div class="laptop-frame-container">
                    <div class="laptop-frame">
                        <div class="laptop-screen">
                            <div class="laptop-slider">
                                <div class="slide active">
                                    <img src="{{ asset('images/roomss.png') }}" alt="Dashboard Screenshot" />
                                </div>
                                <div class="slide">
                                    <img src="{{ asset('images/menu.png') }}" alt="Booking System" />
                                </div>
                                <div class="slide">
                                    <img src="{{ asset('images/cleaning service.png') }}" alt="Room Management" />
                                </div>
                                <div class="slide">
                                    <img src="{{ asset('images/Menu Service.png') }}" alt="Analytics Dashboard" />
                                </div>
                                  <div class="slide">
                                    <img src="{{ asset('images/roomCalendar.png') }}" alt="Analytics Dashboard" />
                                </div>
                                 <div class="slide">
                                    <img src="{{ asset('images/manageRoles.png') }}" alt="Analytics Dashboard" />
                                </div>
                                      <div class="slide">
                                    <img src="{{ asset('images/users.png') }}" alt="Analytics Dashboard" />
                                </div>
                            </div>
                        </div>
                        <div class="laptop-keyboard"></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="cta">
            <div class="container">
                <h2 class="cta-title">Ready to Transform Your Hotel Management?</h2>
                <p class="cta-text">Join thousands of hotels worldwide that trust NovaERA HMS to streamline operations, enhance guest experiences, and boost profitability.</p>
                @auth
                    <a href="{{ route('dashboard') }}" class="btn cta-btn">Go to Dashboard</a>
                @else
                    <a href="{{ route('contact.index') }}" class="btn cta-btn">Request a Demo</a>
                @endauth
            </div>
        </section>

        <!-- Footer -->
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
            
            // Laptop frame image slider
            (function() {
                const slides = document.querySelectorAll('.laptop-slider .slide');
                let currentSlide = 0;
                
                function showSlide(index) {
                    slides.forEach(slide => slide.classList.remove('active'));
                    slides[index].classList.add('active');
                }
                
                function nextSlide() {
                    currentSlide = (currentSlide + 1) % slides.length;
                    showSlide(currentSlide);
                }
                
                // Initialize slider
                if (slides.length > 0) {
                    // Make sure first slide is active
                    showSlide(0);
                    
                    // Auto rotate every 3 seconds
                    setInterval(nextSlide, 3000);
                }
            })();
        </script>
    </body>
</html>
