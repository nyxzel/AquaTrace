<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AquaTrace | Vessel Tracking</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- AOS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/web_style.css') }}">
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
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" data-aos="fade-down" data-aos-duration="1000">
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

    <!-- Hero -->
    <header class="hero">
        <div class="hero-content">
            <div class="welcome-message" data-aos="fade-up" data-aos-duration="1200">
                <span class="welcome-text">Welcome,</span>
                <span class="username-text">
                    {{ isset($display_name) ? e($display_name) : (isset($user->username) ? e($user->username) : 'User') }}
                </span>
                <span class="welcome-text">!</span>
            </div>
            <h4 class="hero-tagline" data-aos="fade-up" data-aos-duration="1200">
                Beyond the horizon, every vessel is within SIGHT.
            </h4>
        </div>
    </header>

    <!-- Divider -->
    <section class="image-explore" data-aos="zoom-in" data-aos-duration="1000">
        <img src="{{ asset('images/divider.png') }}" alt="Divider" class="img-fluid w-100">
    </section>

    <!-- Carousel -->
    <section class="py-5" data-aos="fade-up" data-aos-duration="1200">
        <div id="multiCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="d-flex justify-content-center">
                        <div class="carousel-card mx-2" data-aos="fade-up" data-aos-delay="100">
                            <img src="{{ asset('images/ads4.jpg') }}" alt="Photo 1">
                        </div>
                        <div class="carousel-card mx-2" data-aos="fade-up" data-aos-delay="200">
                            <img src="{{ asset('images/ads5.jpg') }}" alt="Photo 2">
                        </div>
                        <div class="carousel-card mx-2" data-aos="fade-up" data-aos-delay="300">
                            <img src="{{ asset('images/ads10.jpg') }}" alt="Photo 3">
                        </div>
                    </div>
                </div>

                <div class="carousel-item">
                    <div class="d-flex justify-content-center">
                        <div class="carousel-card mx-2"><img src="{{ asset('images/ads3.jpg') }}" alt="Photo 4"></div>
                        <div class="carousel-card mx-2"><img src="{{ asset('images/ads9.jpg') }}" alt="Photo 5"></div>
                        <div class="carousel-card mx-2"><img src="{{ asset('images/ads11.jpg') }}" alt="Photo 6"></div>
                    </div>
                </div>
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#multiCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#multiCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </section>

    <!-- Services -->
    <section class="services-section" data-aos="fade-up" data-aos-duration="1000">
        <div class="container-service">
            <div class="left-content" data-aos="fade-right" data-aos-duration="1200">
                <h2 class="services-title" data-aos="fade-down" data-aos-duration="800">SERVICES</h2>
                <div class="service-description" data-aos="fade-up" data-aos-delay="200">
                    <h3>VESSEL TRACKING IN MARITIME DEPTHS</h3>
                    <p>
                        AquaTrace offers a smart and efficient way to track vessels in real time.
                        With detailed reports and a responsive design, it provides a smooth and engaging
                        maritime tracking experience across all devices.
                    </p>
                </div>
                <a href="{{ route('user.vessels') }}" class="btn btn-primary">VIEW MORE</a>
            </div>

            <div class="right-visual" data-aos="fade-left" data-aos-duration="1200">
                <div class="navigation nav-left" onclick="previousImage()">‹</div>
                <div class="navigation nav-right" onclick="nextImage()">›</div>

                <div class="main-image-container" data-aos="zoom-in" data-aos-delay="300">
                    <img id="mainImage" src="" alt="Vessel Tracking">
                </div>

                <div class="image-thumbnails">
                    <div class="thumbnail" onclick="changeImage(0)" data-aos="fade-up" data-aos-delay="500">
                        <img src="{{ asset('images/ads8.png') }}" alt="Tracking">
                    </div>
                    <div class="thumbnail" onclick="changeImage(1)" data-aos="fade-up" data-aos-delay="600">
                        <img src="{{ asset('images/ads6.jpg') }}" alt="Analytics">
                    </div>
                    <div class="thumbnail" onclick="changeImage(2)" data-aos="fade-up" data-aos-delay="700">
                        <img src="{{ asset('images/ads2.jpg') }}" alt="Monitoring">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- News Header -->
    <section class="news-header" data-aos="fade-up" data-aos-duration="1000">
        <div class="container">
            <h1>MARITIME NEWS</h1>
            <p>Latest updates from regional ports and vessels</p>
        </div>
    </section>


    <!-- News Section -->
    <section class="news-section">
        <div class="container">
            <div class="row">

                @if($news_items->isNotEmpty())
                @foreach($news_items as $index => $news)
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="{{ ($index + 1) * 100 }}">
                    <div class="news-card">

                        <div class="news-card-body">

                            <!-- Badge / Category -->
                            <span class="news-badge">
                                {{ e($news->status) }}
                            </span>

                            <!-- Date -->
                            <div class="news-date">
                                <i class="far fa-calendar"></i>
                                {{ \Carbon\Carbon::parse($news->event_date)->format('F d, Y') }}
                            </div>

                            <!-- Title -->
                            <h3 class="news-title">
                                {{ e($news->event_type) }}
                            </h3>

                            <!-- Description -->
                            <p class="news-excerpt">
                                {{ Str::limit($news->description, 150) }}
                            </p>



                        </div>

                    </div>
                </div>
                @endforeach
                @else

                <div class="col-12">
                    <div class="no-news-message">
                        <i class="fas fa-inbox fa-3x"></i>
                        <h4>No News Available</h4>
                        <p>Check back later for the newest maritime updates.</p>
                    </div>
                </div>

                @endif

            </div>

            <!-- View All News Button -->
            @if($news_items->isNotEmpty())
            <div class="text-center mt-4" data-aos="fade-up">
                <a href="{{ route('user.news') }}" class="btn-view-all-news">
                    <i class="fas fa-newspaper me-2"></i> View All News
                    <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
            @endif

        </div>
    </section>

    <!-- Footer -->
    <footer class="footer" data-aos="fade-up" data-aos-duration="1000">
        <div class="container">
            <div class="footer-top">
                <nav class="footer-nav" data-aos="fade-up" data-aos-delay="200">
                    <ul>
                        <li><a href="{{ route('user.vessels') }}">VESSELS</a></li>
                        <li><a href="{{ route('user.ports') }}">PORTS</a></li>
                        <li><a href="{{ route('user.reports') }}">ANALYTICS</a></li>
                        <li><a href="{{ route('user.vessels') }}">TRACKING</a></li>
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

    <!-- Scripts -->
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

        // Image gallery functionality
        const images = ["{{ asset('images/ads8.png') }}", "{{ asset('images/ads6.jpg') }}", "{{ asset('images/ads2.jpg') }}"];
        let currentIndex = 0;

        function changeImage(index) {
            currentIndex = index;
            document.getElementById('mainImage').src = images[index];

            // Update thumbnail active state
            const thumbnails = document.querySelectorAll('.thumbnail');
            thumbnails.forEach((thumb, i) => {
                thumb.classList.toggle('active', i === index);
            });
        }

        function nextImage() {
            currentIndex = (currentIndex + 1) % images.length;
            changeImage(currentIndex);
        }

        function previousImage() {
            currentIndex = (currentIndex - 1 + images.length) % images.length;
            changeImage(currentIndex);
        }

        // Initialize first image
        document.getElementById('mainImage').src = images[0];
        changeImage(0);

        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const navbar = document.querySelector('.navbar');
            navbar.classList.toggle('scrolled', window.scrollY > 50);
        });
    </script>
</body>

</html>