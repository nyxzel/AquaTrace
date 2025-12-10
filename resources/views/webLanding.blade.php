<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AquaTrace | Vessel Tracking</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- AOS (Animate On Scroll) CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Font Awesome Icons -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{ asset('css/weblanding_style.css') }}">
</head>

<body>
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


  <!-- ==================== HERO SECTION ==================== -->
  <header class="hero">
    <div class="overlay"></div>
    <div class="container position-relative text-center">
      <h4 class="hero-tagline" data-aos="fade-up" data-aos-duration="1200">
        Beyond the horizon, every vessel is within SIGHT.
      </h4>
    </div>
  </header>

  <!-- Decorative Divider Image -->
  <section class="image-explore" data-aos="zoom-in" data-aos-duration="1000">
    <img src="{{ asset('images/divider.png') }}" alt="Divider" class="img-fluid w-100">
  </section>

  <!-- ==================== IMAGE CAROUSEL SECTION ==================== -->
  <section class="py-5" data-aos="fade-up" data-aos-duration="1200">
    <div id="multiCarousel" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">

        <!-- First Slide -->
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

        <!-- Second Slide -->
        <div class="carousel-item">
          <div class="d-flex justify-content-center">
            <div class="carousel-card mx-2">
              <img src="{{ asset('images/ads3.jpg') }}" alt="Photo 4">
            </div>
            <div class="carousel-card mx-2">
              <img src="{{ asset('images/ads9.jpg') }}" alt="Photo 5">
            </div>
            <div class="carousel-card mx-2">
              <img src="{{ asset('images/ads11.jpg') }}" alt="Photo 6">
            </div>
          </div>
        </div>
      </div>

      <!-- Carousel Controls -->
      <button class="carousel-control-prev" type="button" data-bs-target="#multiCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#multiCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
      </button>
    </div>
  </section>

  <!-- ==================== SERVICES SECTION ==================== -->
  <section class="services-section" data-aos="fade-up" data-aos-duration="1000">
    <div class="container-service">

      <!-- Left Text Content -->
      <div class="left-content" data-aos="fade-right" data-aos-duration="1200">
        <h2 class="services-title" data-aos="fade-down" data-aos-duration="800">SERVICES</h2>
        <div class="service-description" data-aos="fade-up" data-aos-delay="200">
          <h3>VESSEL TRACKING IN MARITIME DEPTHS</h3>
          <p>
            AquaTrace offers a smart and efficient way to track vessels in real time.
            With detailed reports and a responsive design, it provides a smooth and
            engaging maritime tracking experience across all devices.
          </p>
        </div>
        <a href="{{ url('login') }}" class="btn btn-primary">VIEW MORE</a>
      </div>

      <!-- Right Image Viewer -->
      <div class="right-visual" data-aos="fade-left" data-aos-duration="1200">
        <div class="navigation nav-left" onclick="previousImage()">‹</div>
        <div class="navigation nav-right" onclick="nextImage()">›</div>

        <!-- Main Image -->
        <div class="main-image-container" data-aos="zoom-in" data-aos-delay="300">
          <img id="mainImage" src="" alt="Vessel Tracking">
        </div>

        <!-- Image Thumbnails -->
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

  <!-- ==================== CALL TO ACTION SECTION ==================== -->
  <section class="calltoaction-section" data-aos="fade-up" data-aos-duration="1000">
    <div class="container">
      <h2 class="calltoaction-title" data-aos="fade-down" data-aos-duration="800">STAY CONNECTED WITH AQUATRACE</h2>
      <p class="calltoaction-subtitle" data-aos="fade-up" data-aos-delay="200">
        Track smarter. Navigate easier. Try it now!
      </p>

      <!-- Call to Action Signup Form -->
      <form class="signup-form" data-aos="fade-up" data-aos-delay="400">
        <input type="email" class="email-input" placeholder="Enter your email address" required>
        <div class="input-box"><a href="{{ url('login') }}" class="btn btn-primary" role="button">TRACK UPDATES</a></div>
      </form>
    </div>
  </section>


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

    const images = [
      "{{ asset('images/ads8.png') }}",
      "{{ asset('images/ads6.jpg') }}",
      "{{ asset('images/ads2.jpg') }}"
    ];

    let currentIndex = 0;

    function changeImage(index) {
      currentIndex = index;
      document.getElementById('mainImage').src = images[index];
    }

    function nextImage() {
      currentIndex = (currentIndex + 1) % images.length;
      changeImage(currentIndex);
    }

    function previousImage() {
      currentIndex = (currentIndex - 1 + images.length) % images.length;
      changeImage(currentIndex);
    }

    document.getElementById('mainImage').src = images[0];

    window.addEventListener('scroll', function() {
      const navbar = document.querySelector('.navbar');
      if (window.scrollY > 50) navbar.classList.add('scrolled');
      else navbar.classList.remove('scrolled');
    });
  </script>

</body>

</html>