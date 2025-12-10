<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AquaTrace | Maritime News</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- AOS CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Icons -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('css/newslanding_style.css') }}">

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
  <!-- NEWS HEADER -->
  <section class="news-header" data-aos="fade-up" data-aos-duration="1000">
    <div class="container">
      <h1>MARITIME NEWS</h1>
      <p>Latest updates from Davao Region ports and vessels</p>
    </div>
  </section>

  <!-- NEWS SECTION -->
  <section class="news-section">
    <div class="container">
      <div class="row">

        @forelse($events as $index => $event)
        <!-- News Article -->
        <div class="col-lg-6" data-aos="fade-up" data-aos-delay="{{ ($index % 6 + 1) * 100 }}">
          <div class="news-card">
            <div class="news-card-body">
              <span class="news-badge">{{ $event->status }}</span>
              <div class="news-date">
                <i class="far fa-calendar"></i>
                {{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}
              </div>
              <h3 class="news-title">{{ $event->event_type }}</h3>
              <p class="news-excerpt">
                {{ $event->description }}
              </p>
            </div>
          </div>
        </div>
        @empty
        <!-- No News Available -->
        <div class="col-12 text-center py-5">
          <i class="fas fa-newspaper" style="font-size: 4rem; color: #ffffffff;"></i>
          <h3 class="mt-3">No news available at the moment</h3>
          <p style="color: #000000ff">Check back later for updates</p>
        </div>
        @endforelse

      </div>
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

  <!-- Bootstrap + AOS JS -->
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