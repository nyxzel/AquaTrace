<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AquaTrace | Analytics Dashboard</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <!--AOS-->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/analyticslanding_style.css') }}">

    @vite(['resources/js/app.js'])
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="navbar">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset('images/AQUATRACELOGO.png') }}" alt="AquaTrace" height="40" loading="eager">
                <p>AQUATRACE</p>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">HOME</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('ports.landing') }}">PORTS</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('news.landing') }}">NEWS</a></li>

                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#" data-bs-toggle="dropdown">ABOUT</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('about.landing') }}">ABOUT US</a></li>
                            <li><a class="dropdown-item" href="{{ route('contact.landing') }}">CONTACT</a></li>
                            <li><a class="dropdown-item" href="{{ route('analytics.landing') }}">FLEET INSIGHTS</a></li>
                        </ul>
                    </li>
                </ul>

                <div class="d-flex">
                    <a href="{{ route('register') }}" class="btn btn-outline-light btn-sm me-2">SIGN UP</a>
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">LOG IN</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <div class="page-header">
        <h1>
            <i class="fas fa-chart-line"></i>
            DASHBOARD
        </h1>
        <p>Real-time maritime data and fleet insights</p>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div id="app">
            <analytics-dashboard
                :initial-stats='{
        "totalVessels": {{ $totalVessels }},
        "activeVessels": {{ $activeVessels }},
        "totalPorts": {{ $totalPorts }},
        "totalUsers": {{ $totalUsers }}
    }'
                :initial-categories='@json($categories)'
                :initial-ports='@json($portsWithVesselCount)'
                :initial-vessels='@json($vessels)'
                :active-count="{{ $activeCount }}"
                :in-transit-count="{{ $inTransitCount }}"
                :docked-count="{{ $dockedCount }}"></analytics-dashboard>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-top">
                <nav class="footer-nav">
                    <ul>
                        <li><a href="{{ url('vessels.landing') }}">VESSELS</a></li>
                        <li><a href="{{ url('ports.landing') }}">PORTS</a></li>
                        <li><a href="{{ url('analytics.landing') }}">FLEET INSIGHTS</a></li>
                        <li><a href="{{ url('news.landing') }}">NEWS</a></li>
                    </ul>
                </nav>

                <div class="social-links">
                    <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                </div>

                <div class="footer-links">
                    <a href="{{ url('contact.landing') }}">CONTACT</a>
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
                    REAL-TIME VESSEL TRACKING, CRAFTED WITH ⚓ IN THE MARITIME INDUSTRY<br>
                    ©<span class="footer-brand">AQUATRACE</span> | ALL RIGHTS RESERVED
                </p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    </script>
</body>

</html>