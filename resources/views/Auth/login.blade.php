<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- BoxIcons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/logindesign.css') }}">

    <title>AquaTrace | Login & Registration</title>

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

    <div class="wrapper">
        <div class="form-box">

            <!-- Close Button -->
            <button class="close-btn" onclick="goHome()" title="Close">
                <i class="bx bx-x"></i>
            </button>

            <!-- LOGIN FORM -->
            <div class="login-container" id="login">
                <div class="top">
                    <span>Don't have an account? <a onclick="showRegister()">Sign Up</a></span>
                    <header>Login</header>
                </div>
                <form action="{{ route('owner.login') }}" method="POST">
                    @csrf
                    <div class="input-box">
                        <input type="text" class="input-field" name="username" placeholder="Username" value="{{ old('username') }}">
                        <i class="bx bx-user"></i>
                    </div>
                    <div class="input-box">
                        <input type="password" class="input-field" name="password" placeholder="Password" id="loginPassword">
                        <i class="bx bx-hide password-toggle" onclick="togglePassword('loginPassword', this)"></i>
                    </div>
                    @if(request('error'))
                    <small class='text-danger'>{{ request('error') }}</small>
                    @endif
                    <div class="input-box">
                        <button class="btn btn-primary">Log in</button>
                    </div>
                </form>
                <div class="two-col">
                    <div class="one">
                        <input type="checkbox" id="login-check" name="remember">
                        <label for="login-check"> Remember Me</label>
                    </div>
                    <div class="two">
                        <label><a>Forgot password?</a></label>
                    </div>
                </div>

                <!-- Admin Login Icon -->
                <div style="text-align: center; margin-top: 20px;">
                    <div class="admin-icon-btn" onclick="showAdmin()" title="Admin Login">
                        <i class="bx bx-user"></i>
                        <span>Admin Login</span>
                    </div>
                </div>
            </div>

            <!-- REGISTER FORM -->
            <div class="register-container hidden" id="register">
                <div class="top">
                    <span>Have an account? <a onclick="showLogin()">Login</a></span>
                    <header>Sign Up</header>
                </div>
                <form id="initialRegisterForm" onsubmit="proceedToFullRegistration(event)" novalidate>
                    <div class="two-forms">
                        <div class="input-box">
                            <input type="text" class="input-field" placeholder="First Name" id="firstName" name="firstName" required>
                            <i class="bx bx-user"></i>
                        </div>
                        <div class="input-box">
                            <input type="text" class="input-field" placeholder="Last Name" id="lastName" name="lastName" required>
                            <i class="bx bx-user"></i>
                        </div>
                    </div>
                    <div class="input-box">
                        <input type="email" class="input-field" placeholder="Email" id="email" name="email" required>
                        <i class="bx bx-envelope"></i>
                    </div>
                    <div class="input-box">
                        <input type="password" class="input-field" placeholder="Password" id="password" name="password" required minlength="8">
                        <i class="bx bx-hide password-toggle" onclick="togglePassword('password', this)"></i>
                    </div>
                    <div class="input-box">
                        <input type="password" class="input-field" placeholder="Confirm Password" id="confirmPassword" name="confirmPassword" required>
                        <i class="bx bx-hide password-toggle" onclick="togglePassword('confirmPassword', this)"></i>
                    </div>
                    <div class="input-box">
                        <button type="submit" class="btn btn-primary">Sign up</button>
                    </div>
                    <div class="two-col">
                        <div class="one">
                            <input type="checkbox" id="register-check">
                            <label for="register-check"> Remember Me</label>
                        </div>
                        <div class="two">
                            <label><a>Terms & conditions</a></label>
                        </div>
                    </div>
                </form>
            </div>

            <!-- ADMIN LOGIN FORM -->
            <div class="admin-container hidden" id="admin">
                <div class="top">
                    <span>Regular user? <a onclick="showLogin()">User Login</a></span>
                    <header>Admin Login</header>
                </div>
                <form action="{{ route('admin.login') }}" method="POST">
                    @csrf
                    <div class="input-box">
                        <input type="text" class="input-field" name="username" placeholder="Admin Username">
                        <i class="bx bx-shield"></i>
                    </div>
                    <div class="input-box">
                        <input type="password" class="input-field" name="password" placeholder="Admin Password" id="adminPassword">
                        <i class="bx bx-hide password-toggle" onclick="togglePassword('adminPassword', this)"></i>
                    </div>

                    @if(request('error') && request('form') === 'admin')
                    <small class='text-danger'>{{ request('error') }}</small>
                    @endif

                    <div class="input-box">
                        <button type="submit" class="btn btn-primary">Admin Log in</button>
                    </div>
                </form>
                <div class="two-col">
                    <div class="one">
                        <input type="checkbox" id="admin-check" name="remember">
                        <label for="admin-check"> Remember Me</label>
                    </div>
                    <div class="two">
                        <label><a>Contact Support</a></label>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- FOOTER -->
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
                    REAL-TIME VESSEL TRACKING, CRAFTED WITH ⚓ IN THE MARITIME INDUSTRY<br>
                    ©<span class="footer-brand">AQUATRACE</span> | ALL RIGHTS RESERVED
                </p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function togglePassword(inputId, iconElement) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
                iconElement.classList.remove('bx-hide');
                iconElement.classList.add('bx-show');
            } else {
                input.type = 'password';
                iconElement.classList.remove('bx-show');
                iconElement.classList.add('bx-hide');
            }
        }

        function showLogin() {
            document.getElementById('login').classList.remove('hidden');
            document.getElementById('register').classList.add('hidden');
            document.getElementById('admin').classList.add('hidden');
        }

        function showRegister() {
            document.getElementById('login').classList.add('hidden');
            document.getElementById('register').classList.remove('hidden');
            document.getElementById('admin').classList.add('hidden');
        }

        function showAdmin() {
            document.getElementById('login').classList.add('hidden');
            document.getElementById('register').classList.add('hidden');
            document.getElementById('admin').classList.remove('hidden');
        }

        function goHome() {
            window.location.href = "{{ route('home') }}";
        }

        async function proceedToFullRegistration(event) {
            event.preventDefault();
            event.stopPropagation();

            const firstName = document.getElementById('firstName').value.trim();
            const lastName = document.getElementById('lastName').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            if (!firstName || !lastName || !email || !password || !confirmPassword) {
                alert("Please fill in all fields!");
                return;
            }

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                alert("Please enter a valid email!");
                return;
            }

            if (password !== confirmPassword) {
                alert("Passwords do not match!");
                return;
            }

            if (password.length < 8) {
                alert("Password must be at least 8 characters.");
                return;
            }

            // ⛔ CHECK EMAIL FIRST
            const emailCheck = await fetch("{{ route('check.email') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    email
                })
            });

            const emailResult = await emailCheck.json();

            if (emailResult.exists) {
                alert("Email already exists! Please use a different email.");
                return;
            }

            // ✔ Generate UNIQUE username
            const usernameRequest = await fetch("{{ route('generate.username') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    firstName,
                    lastName
                })
            });

            const usernameData = await usernameRequest.json();
            const uniqueUsername = usernameData.username;

            const registrationData = {
                firstName,
                lastName,
                email,
                username: uniqueUsername,
                password
            };

            sessionStorage.setItem('registrationData', JSON.stringify(registrationData));

            fetch('/store-registration-data', {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(registrationData)
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = "{{ route('register.complete') }}";
                    } else {
                        alert("Something went wrong storing session!");
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert("Error processing registration.");
                });
        }
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        window.addEventListener('DOMContentLoaded', function() {
            const form = "{{ $form ?? 'login' }}";

            if (form === 'register') {
                showRegister();
            } else if (form === 'login') {
                showLogin();
            } else if (form === 'admin') {
                showAdmin();
            }
        });
    </script>

</body>

</html>