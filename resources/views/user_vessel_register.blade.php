<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AquaTrace | Vessel Registration</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    {{-- Fonts & Icons --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    {{-- Custom CSS --}}
    <link rel="stylesheet" href="{{ asset('../css/registerboat_style.css') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Vite for Vue --}}
    @vite(['resources/js/app.js'])
</head>

<body>
    {{-- Sidebar Overlay --}}
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    {{-- Sidebar --}}
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

    {{-- Header --}}
    <div class="page-header">
        <div class="container text-center">
            <h1><i class="fas fa-ship"></i> Registered Vessels</h1>
            <p>Track and manage all registered vessels in the AquaTrace system</p>
        </div>
    </div>

    {{-- Vue App Content --}}
    <div id="app">
        <user-vessel-register></user-vessel-register>
    </div>

    {{-- FOOTER --}}
    <footer class="footer">
        <div class="container">
            <div class="footer-top">
                <nav class="footer-nav">
                    <ul>
                        <li><a href="#">VESSELS</a></li>
                        <li><a href="#">PORTS</a></li>
                        <li><a href="#">ANALYTICS</a></li>
                        <li><a href="#">TRACKING</a></li>
                        <li><a href="#">NEWS</a></li>
                    </ul>
                </nav>

                <div class="social-links">
                    <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                </div>

                <div class="footer-links">
                    <a href="#">CONTACT</a>
                    <a href="#">API DOCS</a>
                    <a href="#">SUPPORT</a>
                    <a href="#">PRIVACY</a>
                    <a href="#">TERMS</a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="container">
                <p>
                    REAL-TIME VESSEL TRACKING ⚓<br>
                    © <span class="footer-brand">AQUATRACE</span> | ALL RIGHTS RESERVED
                </p>
            </div>
        </div>
    </footer>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Sidebar & Navbar Scripts --}}
    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        // Unified sidebar open function
        function openSidebar() {
            sidebar.classList.add('active');
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }

        // Bind both triggers to the same function
        document.getElementById('sidebarToggle').onclick = openSidebar; // Profile icon
        document.getElementById('sidebarClose').onclick = closeSidebar; // Close (X)
        overlay.onclick = closeSidebar;

        window.addEventListener("scroll", function() {
            const navbar = document.getElementById("navbar")
            if (window.scrollY > 50) {
                navbar.classList.add("scrolled")
            } else {
                navbar.classList.remove("scrolled")
            }
        })
    </script>


</body>

</html>