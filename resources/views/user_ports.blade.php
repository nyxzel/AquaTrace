<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>AquaTrace | Davao Region Ports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet" />
    <!-- AOS CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" />
    <link rel="stylesheet" href="{{ asset('css/portuser_style.css') }}" />
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

    <div class="content-wrapper">
        <!-- PAGE HEADER -->
        <div class="page-header" data-aos="fade-down" data-aos-duration="1000">
            <div class="container">
                <h1><i class="fas fa-anchor"></i> DAVAO REGION PORTS</h1>
                <p>
                    Comprehensive guide to Port Management Office of Davao (PMO DVO)
                    ports, terminals, and maritime infrastructure
                </p>
            </div>
        </div>

        <!-- MAIN CONTENT -->
        <div class="container pb-5">
            <!-- BASEPORT Section -->
            <div class="port-category">
                <div class="category-header" data-aos="fade-right" data-aos-duration="1000">
                    <i class="fas fa-star"></i>
                    <h2>BASEPORT</h2>
                </div>

                <!-- Port 1: SASA WHARF -->
                <div class="port-card" data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
                    <div class="port-image">
                        <img src="{{ asset('images/sasawharf.jpg') }}" alt="Sasa Wharf" />
                    </div>
                    <div class="port-content">
                        <div class="port-number">Port #1</div>
                        <h3 class="port-name">SASA WHARF</h3>
                        <span class="port-classification">Baseport - Davao City</span>
                        <div class="port-details">
                            <div class="detail-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Davao City</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-ship"></i>
                                <span>Non-RORO Terminal</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-anchor"></i>
                                <span>Commercial Port</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-water"></i>
                                <span>Deep Water Access</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Port 2: MACO -->
                <div class="port-card" data-aos="fade-up" data-aos-duration="800" data-aos-delay="250">
                    <div class="port-image">
                        <img src="{{ asset('images/maco.jpg') }}" alt="Maco Anchorage" />
                    </div>
                    <div class="port-content">
                        <div class="port-number">Port #2</div>
                        <h3 class="port-name">MACO (ANCHORAGE)</h3>
                        <span class="port-classification">Baseport - Davao de Oro</span>
                        <div class="port-details">
                            <div class="detail-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Davao de Oro</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-ship"></i>
                                <span>Anchorage Point</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-anchor"></i>
                                <span>Mining Exports</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-water"></i>
                                <span>Cargo Loading</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Port 3: PANABO -->
                <div class="port-card" data-aos="fade-up" data-aos-duration="800" data-aos-delay="300">
                    <div class="port-image">
                        <img src="{{ asset('images/panabo.jpg') }}" alt="Panabo Anchorage" />
                    </div>
                    <div class="port-content">
                        <div class="port-number">Port #3</div>
                        <h3 class="port-name">PANABO (ANCHORAGE)</h3>
                        <span class="port-classification">Baseport - Davao del Norte</span>
                        <div class="port-details">
                            <div class="detail-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Davao del Norte</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-ship"></i>
                                <span>Anchorage Point</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-anchor"></i>
                                <span>Agricultural Exports</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-water"></i>
                                <span>Banana Loading</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Port 4: STA. ANA -->
                <div class="port-card" data-aos="fade-up" data-aos-duration="800" data-aos-delay="350">
                    <div class="port-image">
                        <img src="{{ asset('images/staana.jpg') }}" alt="Sta. Ana Anchorage" />
                    </div>
                    <div class="port-content">
                        <div class="port-number">Port #4</div>
                        <h3 class="port-name">STA. ANA (ANCHORAGE)</h3>
                        <span class="port-classification">Baseport - Davao City</span>
                        <div class="port-details">
                            <div class="detail-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Davao City</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-ship"></i>
                                <span>Anchorage Point</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-anchor"></i>
                                <span>Commercial Vessels</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-water"></i>
                                <span>Safe Harbor</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Port 5: TIBUNGCO -->
                <div class="port-card" data-aos="fade-up" data-aos-duration="800" data-aos-delay="400">
                    <div class="port-image">
                        <img src="{{ asset('images/tibungco.jpg') }}" alt="Tibungco Anchorage" />
                    </div>
                    <div class="port-content">
                        <div class="port-number">Port #5</div>
                        <h3 class="port-name">TIBUNGCO (ANCHORAGE)</h3>
                        <span class="port-classification">Baseport - Davao City</span>
                        <div class="port-details">
                            <div class="detail-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Davao City</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-ship"></i>
                                <span>Anchorage Point</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-anchor"></i>
                                <span>Fishing Vessels</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-water"></i>
                                <span>Protected Area</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TMO - MATI Section -->
            <div class="port-category">
                <div class="category-header" data-aos="fade-right" data-aos-duration="1000">
                    <i class="fas fa-building"></i>
                    <h2>TMO - MATI</h2>
                </div>

                <!-- Port 6: OTP MATI WHARF -->
                <div class="port-card" data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
                    <div class="port-image">
                        <img src="{{ asset('images/mati.jpg') }}" alt="OTP Mati Wharf" />
                    </div>
                    <div class="port-content">
                        <div class="port-number">Port #6</div>
                        <h3 class="port-name">OTP MATI WHARF</h3>
                        <span class="port-classification">TMO - Mati City</span>
                        <div class="port-details">
                            <div class="detail-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Mati, Davao Oriental</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-ship"></i>
                                <span>Terminal Operations</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-anchor"></i>
                                <span>Passenger & Cargo</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-water"></i>
                                <span>Provincial Port</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TMO - BABAK/SAMAL Section -->
            <div class="port-category">
                <div class="category-header" data-aos="fade-right" data-aos-duration="1000">
                    <i class="fas fa-building"></i>
                    <h2>TMO - BABAK/SAMAL</h2>
                </div>

                <!-- Port 7: OTP BABAK -->
                <div class="port-card" data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
                    <div class="port-image">
                        <img src="{{ asset('images/babak.jpg') }}" alt="OTP Babak" />
                    </div>
                    <div class="port-content">
                        <div class="port-number">Port #7</div>
                        <h3 class="port-name">OTP BABAK</h3>
                        <span class="port-classification">TMO - Island Garden City of Samal</span>
                        <div class="port-details">
                            <div class="detail-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Babak, Samal Island</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-ship"></i>
                                <span>Inter-Island Ferry</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-anchor"></i>
                                <span>Tourism Gateway</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-water"></i>
                                <span>Island Port</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Port 8: OTP MAE WESS -->
                <div class="port-card" data-aos="fade-up" data-aos-duration="800" data-aos-delay="150">
                    <div class="port-image">
                        <img src="{{ asset('images/maewess.jpg') }}" alt="OTP Mae Wess" />
                    </div>
                    <div class="port-content">
                        <div class="port-number">Port #8</div>
                        <h3 class="port-name">OTP MAE WESS</h3>
                        <span class="port-classification">TMO - Island Garden City of Samal</span>
                        <div class="port-details">
                            <div class="detail-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Samal Island</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-ship"></i>
                                <span>Passenger Terminal</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-anchor"></i>
                                <span>Tourist Routes</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-water"></i>
                                <span>Ferry Services</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- OTHER GOVERNMENT PORTS Section -->
            <div class="port-category">
                <div class="category-header" data-aos="fade-right" data-aos-duration="1000">
                    <i class="fas fa-landmark"></i>
                    <h2>OTHER GOVERNMENT PORTS</h2>
                </div>

                <!-- Port 9: BANAY-BANAY -->
                <div class="port-card" data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
                    <div class="port-image">
                        <img src="{{ asset('images/banaybanay.jpg') }}" alt="Banay-Banay Port" />
                    </div>
                    <div class="port-content">
                        <div class="port-number">Port #9</div>
                        <h3 class="port-name">BANAY-BANAY</h3>
                        <span class="port-classification">Government Port - Davao Oriental</span>
                        <div class="port-details">
                            <div class="detail-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Banay-Banay, Davao Oriental</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-ship"></i>
                                <span>Municipal Port</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-anchor"></i>
                                <span>Local Transport</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-water"></i>
                                <span>Community Access</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Port 10: DAVAO FISHPORT -->
                <div class="port-card" data-aos="fade-up" data-aos-duration="800" data-aos-delay="150">
                    <div class="port-image">
                        <img src="{{ asset('images/davaofishport.jpeg') }}" alt="Davao Fishport" />
                    </div>
                    <div class="port-content">
                        <div class="port-number">Port #10</div>
                        <h3 class="port-name">DAVAO FISHPORT</h3>
                        <span class="port-classification">Government Port - Davao City</span>
                        <div class="port-details">
                            <div class="detail-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Davao City</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-ship"></i>
                                <span>Fishing Port</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-anchor"></i>
                                <span>Fishing Fleet Base</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-water"></i>
                                <span>Seafood Hub</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="footer" data-aos="fade-up" data-aos-duration="1000">
        <div class="container">
            <div class="footer-top">
                <nav class="footer-nav" data-aos="fade-up" data-aos-delay="200">
                    <ul>
                        <li><a href="#">VESSELS</a></li>
                        <li><a href="#">PORTS</a></li>
                        <li><a href="#">ANALYTICS</a></li>
                        <li><a href="#">TRACKING</a></li>
                        <li><a href="#">NEWS</a></li>
                    </ul>
                </nav>

                <div class="social-links" data-aos="fade-up" data-aos-delay="400">
                    <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                </div>

                <div class="footer-links" data-aos="fade-up" data-aos-delay="600">
                    <a href="#">CONTACT</a>
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
                    REAL-TIME VESSEL TRACKING, CRAFTED WITH ⚓ IN THE MARITIME
                    INDUSTRY<br />
                    ©<span class="footer-brand">AQUATRACE</span> | ALL RIGHTS RESERVED
                </p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AOS JS -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100,
        })

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

        // Navbar scroll effect
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