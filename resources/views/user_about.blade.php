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

    <link rel="stylesheet" href="{{ asset('css/aboutuser_style.css') }}">

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

    <!-- HEADER -->
    <section class="about-header" data-aos="fade-up">
        <h1>ABOUT AQUATRACE</h1>
        <p>Welcome to AquaTrace, a vessel tracking system created by a group of passionate students devoted to learning,
            discovery, and innovation.</p>
    </section>

    <!-- ABOUT SECTION -->
    <section class="about-section">
        <div class="container">
            <div class="about-content justify-content-center" data-aos="fade-up">
                <p>
                    Our mission goes beyond building a functional website — we aim to apply what we’ve learned to
                    real-world challenges, using technology to promote smarter and safer maritime monitoring.
                    AquaTrace represents our dedication to understanding how web systems connect people, data, and purpose. <br>
                    <br>
                    Every feature you see here is a reflection of our effort, teamwork, and curiosity. We believe that
                    learning is a continuous voyage, and this project is one milestone in our pursuit of excellence in the
                    ever-evolving world of web development.
                </p>
            </div>

            <!-- TEAM SECTION -->
            <div class="member-cards">

                <div class="member-card" data-aos="fade-up" data-aos-delay="100">
                    <img src="{{ asset('images/ashlei.jpg') }}" class="member-img" alt="Member 1" />
                    <h3 class="member-name">Ashlei Krystel Panes</h3>
                    <p class="member-role">UI/UX Designer & Front-End Developer</p>
                    <div class="member-social">
                        <a href="https://www.facebook.com/share/1CqmNjqaoM/" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://www.instagram.com/krysteluv?igsh=MWx2b3N0dzhqdXdlcg==" target="_blank"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>

                <div class="member-card" data-aos="fade-up" data-aos-delay="200">
                    <img src="{{ asset('images/ruzzel.jpg') }}" class="member-img" alt="Member 2" />
                    <h3 class="member-name">Ruzzel Mae B. Padrones</h3>
                    <p class="member-role">Front-End & Back-End Developer</p>
                    <div class="member-social">
                        <a href="https://www.facebook.com/share/19frmDPa6v/" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://www.instagram.com/nyx.zzel?igsh=MTVqbTg3M25zaXo1dQ==" target="_blank"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>

                <div class="member-card" data-aos="fade-up" data-aos-delay="300">
                    <img src="{{ asset('images/aira.jpg') }}" class="member-img" alt="Member 3" />
                    <h3 class="member-name">Aira Rocel B. Nazareno</h3>
                    <p class="member-role">Back-End Developer & Database Administrator</p>
                    <div class="member-social">
                        <a href="https://www.facebook.com/share/1BJJkYHCNU/" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://www.instagram.com/aira_rocel?igsh=YjR0dWRrMThwaWI4" target="_blank"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>

                <div class="member-card" data-aos="fade-up" data-aos-delay="400">
                    <img src="{{ asset('images/red Id_ 951491969 (1).jpg') }}" class="member-img" alt="Member 4" />
                    <h3 class="member-name">Elena Mae B. Mendoza</h3>
                    <p class="member-role">Backend Developer</p>
                    <div class="member-social">
                        <a href="https://www.facebook.com/share/1BLJbR6RcK/" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" target="_blank"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>

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