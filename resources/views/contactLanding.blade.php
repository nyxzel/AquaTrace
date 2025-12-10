<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AquaTrace | Contact Us</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- AOS CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/contactlanding_style.css') }}">

</head>

<body>

    <!-- Success Message -->
    @if(session('success'))
    <div class="alert alert-success text-center m-0 p-2">
        {{ session('success') }}
    </div>
    @endif

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="navbar">
        <div class="container">

            <!-- Logo and Brand Name -->
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset('images/AQUATRACELOGO.png') }}" alt="AquaTrace" height="40" loading="eager">
                <p>AQUATRACE</p>
            </a>

            <!-- Mobile Menu Toggler -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar Links -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">HOME</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('ports.landing') }}">PORTS</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('news.landing') }}">NEWS</a></li>

                    <!-- Dropdown Menu -->
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#" data-bs-toggle="dropdown">ABOUT</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('about.landing') }}">ABOUT US</a></li>
                            <li><a class="dropdown-item" href="{{ route('contact.landing') }}">CONTACT</a></li>
                            <li><a class="dropdown-item" href="{{ route('analytics.landing') }}">FLEET INSIGHTS</a></li>
                        </ul>
                    </li>
                </ul>

                <!-- Sign Up / Log In Buttons -->
                <div class="d-flex">
                    <a href="{{ route('register') }}" class="btn btn-outline-light btn-sm me-2">SIGN UP</a>
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">LOG IN</a>
                </div>
            </div>
        </div>
    </nav>
    <!-- CONTACT HEADER -->
    <section class="contact-header" data-aos="fade-up" data-aos-duration="1000">
        <div class="container">
            <h1>CONTACT US</h1>
            <p>We're here to help with your maritime tracking needs</p>
        </div>
    </section>

    <!-- CONTACT SECTION -->
    <section class="contact-section">
        <div class="container contact-container">

            <!-- Contact Info Cards -->
            <div class="contact-info-cards">
                <div class="info-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <h3>Our Location</h3>
                    <p>Davao del Norte State College<br>Panabo City, Davao del Norte<br>Davao Region, Philippines</p>
                </div>

                <div class="info-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="info-icon"><i class="fas fa-phone-alt"></i></div>
                    <h3>Phone Number</h3>
                    <p>
                        <a href="tel:+639539502958">+63 953 950 2958</a><br>
                        <a href="tel:+639153881171">+63 915 388 1171</a><br>
                        Mon - Fri, 8:00 AM - 6:00 PM
                    </p>
                </div>

                <div class="info-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="info-icon"><i class="fas fa-envelope"></i></div>
                    <h3>Email Address</h3>
                    <p>
                        <a href="mailto:info@aquatrace.com">aquatrace.ph@gmail.com</a><br>
                        <a href="mailto:support@aquatrace.com">aquatrace.support@gmail.com</a><br>
                        Response within 24 hours
                    </p>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="contact-form-wrapper" data-aos="fade-up" data-aos-delay="400">
                <h2 class="form-title">Send Us a Message</h2>
                <p class="form-subtitle">Fill out the form below and we'll get back to you as soon as possible</p>

                <form action="{{ route('contact.send') }}" method="POST">
                    @csrf

                    <div class="row">

                        <div class="col-md-6 mb-4">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label">Email Address</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" name="phone">
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label">Subject</label>
                            <select class="form-select" name="subject" required>
                                <option value="">Select a subject</option>
                                <option value="General Inquiry">General Inquiry</option>
                                <option value="Technical Support">Technical Support</option>
                                <option value="Partnership Opportunity">Partnership Opportunity</option>
                                <option value="Feedback">Feedback</option>
                            </select>
                        </div>

                        <div class="col-12 mb-4">
                            <label class="form-label">Message</label>
                            <textarea class="form-control" name="message" required></textarea>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="submit-btn">
                                <i class="fas fa-paper-plane me-2"></i>Send Message
                            </button>
                        </div>

                    </div>
                </form>
            </div>

        </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer" data-aos="fade-up" data-aos-duration="1000">
        <div class="container">
            <div class="footer-top">

                <!-- Footer Navigation -->
                <nav class="footer-nav" data-aos="fade-up" data-aos-delay="200">
                    <ul>
                        <li><a href="{{ url('vessels.landing') }}">VESSELS</a></li>
                        <li><a href="{{ url('ports.landing') }}">PORTS</a></li>
                        <li><a href="{{ url('analytics.landing') }}">FLEET INSIGHTS</a></li>
                        <li><a href="{{ url('news.landing') }}">NEWS</a></li>
                    </ul>
                </nav>

                <!-- Social Media Icons -->
                <div class="social-links" data-aos="fade-up" data-aos-delay="400">
                    <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                </div>

                <!-- Footer Links -->
                <div class="footer-links" data-aos="fade-up" data-aos-delay="600">
                    <a href="{{ url('contact.landing') }}">CONTACT</a>
                    <a href="#">API DOCS</a>
                    <a href="#">SUPPORT</a>
                    <a href="#">PRIVACY</a>
                    <a href="#">TERMS</a>
                </div>
            </div>
        </div>

        <!-- Footer Bottom Text -->
        <div class="footer-bottom" data-aos="fade-up" data-aos-delay="800">
            <div class="container">
                <p>
                    REAL-TIME VESSEL TRACKING, CRAFTED WITH ⚓ IN THE MARITIME INDUSTRY<br>
                    ©<span class="footer-brand">AQUATRACE</span> | ALL RIGHTS RESERVED
                </p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>

    <script>
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });

        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    </script>
</body>

</html>