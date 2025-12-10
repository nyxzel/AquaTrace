<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- BoxIcons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/loginRegister_style.css') }}">

    <title>AquaTrace | Complete Registration</title>

</head>

<body>
    <!-- NAVIGATION BAR -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-transparent fixed-top scrolled" id="navbar">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('web.landing') }}">
                <img src="{{ asset('images/AQUATRACELOGO.png') }}" alt="AquaTrace" height="40" loading="eager">
                <p>AQUATRACE</p>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('web.landing') }}">HOME</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('vessel.landing') }}">VESSELS</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('ports.landing') }}">PORTS</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('news.landing') }}">NEWS</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            ABOUT
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('about.landing') }}">ABOUT US</a></li>
                            <li><a class="dropdown-item" href="{{ route('contact.landing') }}">CONTACT</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('analytics.landing') }}">ANALYTICS</a></li>
                </ul>
                <div class="d-flex">
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">BACK TO LOGIN</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="content-wrapper">
        <div class="form-container" id="formContainer">
            <form id="registrationForm" onsubmit="return completeRegistration(event);">
                <h2 class="text-center mb-4">Complete Your Registration</h2>

                <!-- Step radio controls -->
                <input type="radio" name="step" id="step1" checked>
                <input type="radio" name="step" id="step2">
                <input type="radio" name="step" id="step3">

                <!-- Step indicator -->
                <div class="step-indicator">
                    <div class="step-number">1</div>
                    <div class="step-number">2</div>
                    <div class="step-number">3</div>
                </div>

                <div class="form-content">
                    <!-- Step 1: Personal Details -->
                    <div class="form-step" data-step="1">
                        <div class="section-header">Your Personal Details</div>

                        <div class="mb-3">
                            <label class="form-label">National Identity Number/ID Number *</label>
                            <input type="text" class="form-control" id="idNumber" name="idNumber">
                            <small class="text-muted">This field cannot be left blank</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">First Name *</label>
                                <input type="text" class="form-control" id="firstName" name="firstName" placeholder="First Name" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Last Name *</label>
                                <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Last Name" readonly>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Middle Name (Optional)</label>
                                <input type="text" class="form-control" id="middleName" name="middleName" placeholder="Middle Name">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email Address *</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Email" readonly>
                            </div>
                        </div>

                        <!-- Hidden field -->
                        <input type="hidden" id="password" name="password">
                        <input type="hidden" id="username" name="username">

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Gender *</label>
                                <select class="form-select" id="gender" name="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                    <option value="prefer-not">Prefer not to say</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Date of Birth *</label>
                                <input type="date" class="form-control" id="dateOfBirth" name="dateOfBirth" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Nationality *</label>
                                <input type="text" class="form-control" id="nationality" name="nationality" placeholder="Nationality" required>
                            </div>
                        </div>

                        <div class="section-header">Your Residential Address</div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Street Address *</label>
                                <input type="text" class="form-control" id="streetAddress" name="streetAddress" placeholder="Street Address"
                                    required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">City *</label>
                                <input type="text" class="form-control" id="city" name="city" placeholder="City" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">State/Province *</label>
                                <input type="text" class="form-control" id="state" name="state" placeholder="State/Province" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Postal Code *</label>
                                <input type="text" class="form-control" id="postalCode" name="postalCode" placeholder="Post Code" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Country *</label>
                                <select class="form-select" id="country" name="country" required>
                                    <option value="">Select Country</option>
                                    <option value="Philippines">Philippines</option>
                                    <option value="USA">United States</option>
                                    <option value="Japan">Japan</option>
                                    <option value="Singapore">Singapore</option>
                                    <option value="Malaysia">Malaysia</option>
                                    <option value="Indonesia">Indonesia</option>
                                    <option value="Thailand">Thailand</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>

                        <label for="step2" class="btn btn-primary btn-next">Next</label>
                    </div>

                    <!-- Step 2: Contact & Professional Information -->
                    <div class="form-step" data-step="2">
                        <div class="section-header">Contact Information</div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mobile Number *</label>
                                <input type="tel" class="form-control" id="mobileNumber" name="mobileNumber" placeholder="+63 XXX XXX XXXX"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Alternate Number (Optional)</label>
                                <input type="tel" class="form-control" id="alternateNumber" name="alternateNumber" placeholder="+63 XXX XXX XXXX">
                            </div>
                        </div>

                        <div class="section-header">Professional Information (Optional)</div>

                        <div class="mb-3">
                            <label class="form-label">Company/Organization</label>
                            <input type="text" class="form-control" id="company" name="company" placeholder="Company Name">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Job Title</label>
                                <input type="text" class="form-control" id="jobTitle" name="jobTitle" placeholder="Your Position">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Industry</label>
                                <select class="form-select" id="industry" name="industry">
                                    <option value="">Select Industry</option>
                                    <option value="maritime">Maritime & Shipping</option>
                                    <option value="logistics">Logistics & Supply Chain</option>
                                    <option value="port-management">Port Management</option>
                                    <option value="customs">Customs & Border Control</option>
                                    <option value="freight">Freight Forwarding</option>
                                    <option value="vessel-operations">Vessel Operations</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="section-header">Terms & Conditions</div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="terms-check" required>
                                <label class="form-check-label" for="terms-check">
                                    I agree to the <a href="#" style="color: #667eea;">Terms & Conditions</a> and <a
                                        href="#" style="color: #667eea;">Privacy Policy</a> *
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="newsletter-check">
                                <label class="form-check-label" for="newsletter-check">
                                    Subscribe to newsletter and updates
                                </label>
                            </div>
                        </div>

                        <label for="step1" class="btn btn-secondary btn-back">Back</label>
                        <button type="submit" class="btn btn-success btn-next">Complete Registration</button>
                    </div>

                    <!-- Step 3: Registration Complete -->
                    <div class="form-step" data-step="3">
                        <div class="text-center">
                            <div class="success-icon">
                                <i class="bi bi-check"></i>
                            </div>

                            <h3 class="mb-3">Registration Completed Successfully!</h3>
                            <p class="text-muted fw-bold">Username: <span id="displayUsername"></span></p>
                            <p class="text-muted fw-bold">Password: <span id="displayPassword"></span></p>
                            <p class="text-muted">Your account has been created. You can now proceed to register vessels.
                            </p>

                            <div class="mt-4">
                                <a href="{{ route('user.register.boat') }}" class="btn btn-primary me-2">
                                    <i class="bi bi-ship"></i> Register a Vessel
                                </a>
                                <a href="{{ route('user.home') }}" class="btn btn-outline-primary">
                                    <i class="bi bi-house"></i> Go to Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
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
        // Load initial registration data
        window.addEventListener('DOMContentLoaded', function() {
            // Load initial registration data from sessionStorage
            const registrationData = sessionStorage.getItem('registrationData');

            if (!registrationData) {
                window.location.href = "{{ route('register') }}";
                return;
            }

            const data = JSON.parse(registrationData);
            document.getElementById('firstName').value = data.firstName;
            document.getElementById('lastName').value = data.lastName;
            document.getElementById('email').value = data.email;
            document.getElementById('password').value = data.password;

            // Check URL for step parameter (optional if using redirect method)
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('step') === '3') {
                document.getElementById('step3').checked = true;
                // Show username & password in Step 3
                document.getElementById('displayUsername').innerText = username;
                document.getElementById('displayPassword').innerText = initialData.password;
            }
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Complete registration via AJAX
        function completeRegistration(event) {
            event.preventDefault();

            const form = document.getElementById('registrationForm');

            // Validate required fields
            const requiredFields = [
                'idNumber', 'gender', 'dateOfBirth', 'nationality',
                'streetAddress', 'city', 'state', 'postalCode', 'country',
                'mobileNumber'
            ];

            let allFilled = true;
            requiredFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (!field.value) {
                    allFilled = false;
                    field.style.borderColor = '#dc3545';
                } else {
                    field.style.borderColor = '';
                }
            });

            if (!document.getElementById('terms-check').checked) {
                alert('Please accept the Terms & Conditions to continue.');
                return false;
            }

            if (!allFilled) {
                alert('Please fill in all required fields.');
                return false;
            }

            // Populate hidden password
            const initialData = JSON.parse(sessionStorage.getItem('registrationData'));
            document.getElementById('password').value = initialData.password;

            // Generate clean username
            const cleanFirstName = initialData.firstName.replace(/\s+/g, '');
            const cleanLastName = initialData.lastName.replace(/\s+/g, '');
            const username = (cleanFirstName + '.' + cleanLastName).toLowerCase();

            // Set hidden username field
            document.getElementById('username').value = username;

            // Prepare form data for AJAX
            const formData = new FormData(form);

            // Send registration data to Laravel
            fetch("{{ route('register.process') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(result => {
                    console.log('Server Response:', result);
                    if (result.success) {
                        // Clear only after displaying
                        document.getElementById('step3').checked = true;

                        // Display username & password
                        document.getElementById('displayUsername').innerText = username;
                        document.getElementById('displayPassword').innerText = initialData.password;

                        // Clear storage
                        sessionStorage.removeItem('registrationData');
                    } else {
                        alert('Registration failed: ' + (result.message || 'Please try again.'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Registration failed. Please try again.');
                });

            return true;
        }
    </script>

</body>

</html>