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

    <link rel="stylesheet" href="{{ asset('css/contactuser_style.css') }}">

</head>

<body>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h4>Menu</h4>
            <div class="sidebar-close" id="sidebarClose">
                <i class="fas fa-times"></i>
            </div>
        </div>

        <ul class="sidebar-menu">
            <li><a href="{{ route('user.home') }}"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="{{ route('user.vessels') }}"><i class="fas fa-ship"></i> Vessels</a></li>
            <li><a href="{{ route('user.ports') }}"><i class="fas fa-anchor"></i> Ports</a></li>
            <li><a href="{{ route('user.news') }}"><i class="fas fa-newspaper"></i> News</a></li>
            <hr class="sidebar-divider">
            <li><a href="{{ route('user.register.boat') }}"><i class="fas fa-ship"></i> Register Boat</a></li>
            <li><a href="{{ route('user.reports') }}"><i class="fas fa-file-alt"></i> Reports</a></li>
            <hr class="sidebar-divider">
            <li><a href="{{ route('user.about') }}"><i class="fas fa-info-circle"></i> About</a></li>
            <li><a href="{{ route('user.contact') }}"><i class="fas fa-envelope"></i> Contact</a></li>
            <hr class="sidebar-divider">
            <li><a href="{{ route('login') }}" class="logout-item"><i class="fas fa-sign-out-alt"></i> Log Out</a></li>
        </ul>
    </div>

    <!-- Navbar -->
    <nav id="navbar" class="navbar navbar-expand-lg navbar-dark fixed-top" data-aos="fade-down" data-aos-duration="1000">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('user.home') }}">
                <img src="{{ asset('images/AQUATRACELOGO.png') }}" alt="AquaTrace" height="40" loading="eager">
                <p>AQUATRACE</p>
            </a>

            <button class="navbar-toggler" type="button" onclick="openSidebar()">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse d-none d-lg-flex" id="navbarNav">
                <ul class="navbar-nav mx-auto d-none d-lg-flex">
                    <li class="nav-item"><a class="nav-link active" href="{{ route('user.home') }}">HOME</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('user.vessels') }}">VESSELS</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('user.ports') }}">PORTS</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('user.news') }}">NEWS</a></li>
                </ul>

                <!-- Profile Icon -->
                <div class="d-none d-lg-flex">
                    <div class="user-profile-btn" id="sidebarToggle">
                        <i class="fas fa-user"></i>
                    </div>
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
                    <div class="info-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h3>Our Location</h3>
                    <p>
                        Davao del Norte State College<br>
                        Panabo City, Davao del Norte<br>
                        Davao Region, Philippines
                    </p>
                </div>

                <div class="info-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="info-icon">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <h3>Phone Number</h3>
                    <p>
                        <a href="tel:+639123456789">+63 912 345 6789</a><br>
                        <a href="tel:+6382234567">+63 82 234 567</a><br>
                        Mon - Fri, 8:00 AM - 6:00 PM
                    </p>
                </div>

                <div class="info-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="info-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h3>Email Address</h3>
                    <p>
                        <a href="mailto:info@aquatrace.com">info@aquatrace.com</a><br>
                        <a href="mailto:support@aquatrace.com">support@aquatrace.com</a><br>
                        Response within 24 hours
                    </p>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="contact-form-wrapper" data-aos="fade-up" data-aos-delay="400">
                <h2 class="form-title">Send Us a Message</h2>
                <p class="form-subtitle">Fill out the form below and we'll get back to you as soon as possible</p>

                <form>
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Full Name</span></label>
                            <input type="text" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label">Email Address</span></label>
                            <input type="email" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" class="form-control">
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label">Subject</span></label>
                            <select class="form-select" required>
                                <option value="">Select a subject</option>
                                <option value="general">General Inquiry</option>
                                <option value="support">Technical Support</option>
                                <option value="partnership">Partnership Opportunity</option>
                                <option value="feedback">Feedback</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <div class="col-12 mb-4">
                            <label class="form-label">Message</span></label>
                            <textarea class="form-control" placeholder="Tell us how we can help you..." required></textarea>
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
                <nav class="footer-nav" data-aos="fade-up" data-aos-delay="200">
                    <ul>
                        <li><a href="{{ route('user.vessels') }}">VESSELS</a></li>
                        <li><a href="{{ route('user.ports') }}">PORTS</a></li>
                        <li><a href="{{ route('user.home') }}">TRACKING</a></li>
                        <li><a href="{{ route('user.news') }}">NEWS</a></li>
                    </ul>
                </nav>

                <div class="social-links" data-aos="fade-up" data-aos-delay="400">
                    <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                </div>

                <div class="footer-links" data-aos="fade-up" data-aos-delay="600">
                    <a href="{{ route('user.contact') }}">CONTACT</a>
                    <a href="#">API DOCS</a>
                    <a href="#">SUPPORT</a>
                    <a href="#">PRIVACY</a>
                    <a href="#">TERMS</a>
                </div>
            </div>
        </div>

        <div class="footer-bottom" data-aos="fade-up" data-aos-delay="800">
            <div class="container">
                <p>
                    REAL-TIME VESSEL TRACKING, CRAFTED WITH ⚓ IN THE MARITIME INDUSTRY<br>
                    ©<span class="footer-brand">AQUATRACE</span> | ALL RIGHTS RESERVED
                </p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap + AOS JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });

        // Sidebar functionality
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarClose = document.getElementById('sidebarClose');

        function openSidebar() {
            sidebar.classList.add('active');
            sidebarOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }

        sidebarToggle.addEventListener('click', openSidebar);
        sidebarClose.addEventListener('click', closeSidebar);
        sidebarOverlay.addEventListener('click', closeSidebar);

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