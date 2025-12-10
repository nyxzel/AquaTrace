<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>AquaTrace | About Us</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

  <!-- AOS CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" />

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
    rel="stylesheet" />

  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />

  <link rel="stylesheet" href="{{ asset('css/aboutlanding_style.css') }}" />
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
              <li><a class="dropdown-item" href="{{ route('analytics.landing') }}">ANALYTICS</a></li>
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

        <!-- Footer Navigation -->
        <nav class="footer-nav" data-aos="fade-up" data-aos-delay="200">
          <ul>
            <li><a href="{{ url('vessels.landing') }}">VESSELS</a></li>
            <li><a href="{{ url('ports.landing') }}">PORTS</a></li>
            <li><a href="{{ url('analytics.landing') }}">ANALYTICS</a></li>
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

  <!-- JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>

  <script>
    AOS.init({
      duration: 1000,
      once: true,
      offset: 100
    });

    window.addEventListener("scroll", () => {
      const navbar = document.querySelector(".navbar");
      navbar.classList.toggle("scrolled", window.scrollY > 50);
    });
  </script>

</body>

</html>