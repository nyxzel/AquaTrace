<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Users | AquaTrace</title>
    <link href="{{ asset('css/adminUsers_style.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <!-- Sidebar -->
    <aside class="adminbar" id="sidebar">
        <div class="adminbar-title">
            <a href="#" class="navbar-brand">
                <img src={{ asset('images/AQUATRACELOGO.png') }} alt="AquaTrace Logo" class="logo-img">
                <span class="logo-text">AQUATRACE</span>
            </a>
        </div>

        <nav>
            <a href="{{ route('admin.dashboard.index') }}" class="adminnav" data-label="Dashboard">
                <i class="fas fa-tachometer-alt"></i> <span class="sidebar-text">Dashboard</span>
            </a>
            <a href="{{ route('admin.ports.index') }}" class="adminnav" data-label="Ports">
                <i class="fas fa-anchor"></i> <span class="sidebar-text">Ports</span>
            </a>
            <a href="{{ route('admin.users.index')}}" class="adminnav active" data-label="Users">
                <i class="fas fa-user"></i> <span class="sidebar-text">Users</span>
            </a>
            <a href="{{ route('admin.vessels.index') }}" class="adminnav" data-label="Vessels">
                <i class="fas fa-ship"></i> <span class="sidebar-text">Vessels</span>
            </a>
            <a href="{{ route('admin.reports.index') }}" class="adminnav" data-label="Reports">
                <i class="fas fa-chart-bar"></i> <span class="sidebar-text">Reports</span>
            </a>
            <a href="{{ route('admin.news.index') }}" class="adminnav" data-label="News">
                <i class="fas fa-newspaper"></i> <span class="sidebar-text">News</span>
            </a>
        </nav>

        <div class="logout-btn">
            <a href="{{ route('login') }}" data-label="Logout">
                <i class="fas fa-sign-out-alt"></i> <span class="sidebar-text">Logout</span>
            </a>
        </div>
    </aside>

    <!-- Topbar -->
    <header class="adminheader">
        <div class="d-flex align-items-center w-100">
            <button class="btn btn-outline-secondary me-2" id="toggle-btn">
                <i class="fas fa-bars"></i>
            </button>
            <h5 class="welcometext mb-0">Welcome, Admin!</h5>

            <!-- Notification Bell -->
            <div class="notif-container">
                <button id="notifBell">
                    <i class="fas fa-bell"></i>
                    <span id="notifBadge" class="d-none">0</span>
                </button>

                <!-- Notification Dropdown -->
                <div id="notifDropdown">
                    <div class="notif-header">
                        <h6>Notifications</h6>
                        <button class="clear-all-btn" id="clearAllBtn">Clear All</button>
                    </div>
                    <div id="notifList">
                        <div class="empty-state">
                            <i class="fas fa-bell-slash"></i>
                            <p>No new notifications</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Toast Container -->
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 11000">
        <div id="liveToast" class="toast align-items-center border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="toastMessage">
                    <!-- Message will be inserted here -->
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <main class="admin-body">
        <div class="row">
            <div class="col-12 mb-4">
                <div class="card-userinfo">
                    <div class="card-header d-flex justify-content-between align-items-center text-white">
                        <h5 class="mb-0 fw-bold">REGISTERED USERS</h5>
                        <button class="btn btn-light btn-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#addUserModal">
                            <i class="fas fa-plus"></i> Add User
                        </button>
                    </div> <br>

                    <!-- Personal Info Table -->
                    <div class="card card-sub table-card">
                        <div class="card-header bg-white py-2 px-3">
                            <strong>Personal Information</strong>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive table-scroll">
                                <table class="table table-hover mb-0">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Username</th>
                                            <th>Password</th>
                                            <th>First Name</th>
                                            <th>Middle Name</th>
                                            <th>Last Name</th>
                                            <th>Date of Birth</th>
                                            <th>Gender</th>
                                            <th>Nationality</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($owners as $row)
                                        <tr
                                            data-owner_id="{{ $row['owner_id'] }}"
                                            data-user_id="{{ $row['user_id'] }}"
                                            data-address_id="{{ $row['address_id'] }}"
                                            data-username="{{ $row['username'] }}"
                                            data-password="{{ $row['password'] }}"
                                            data-first="{{ $row['first_name'] }}"
                                            data-middle="{{ $row['middle_name'] }}"
                                            data-last="{{ $row['last_name'] }}"
                                            data-dob="{{ $row['dob'] }}"
                                            data-gender="{{ $row['gender'] }}"
                                            data-national_id="{{ $row['national_id'] }}"
                                            data-nationality="{{ $row['nationality'] }}"
                                            data-street="{{ $row['street_no'] }}"
                                            data-post="{{ $row['post_code'] }}"
                                            data-city="{{ $row['city'] }}"
                                            data-state="{{ $row['state'] }}"
                                            data-country="{{ $row['country'] }}"
                                            data-email="{{ $row['email'] }}"
                                            data-phone="{{ $row['contact'] }}"
                                            data-company="{{ $row['company'] }}"
                                            data-job="{{ $row['job_title'] }}"
                                            data-industry="{{ $row['industry'] }}">
                                            <td>{{ $row['username'] }}</td>
                                            <td>{{ $row['password'] }}</td>
                                            <td>{{ $row['first_name'] }}</td>
                                            <td>{{ $row['middle_name'] }}</td>
                                            <td>{{ $row['last_name'] }}</td>
                                            <td>{{ $row['dob'] }}</td>
                                            <td>{{ $row['gender'] }}</td>
                                            <td>{{ $row['nationality'] }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary edit-btn"><i class="fas fa-edit"></i></button>
                                                <button class="btn btn-sm btn-outline-danger delete-btn"><i class="fas fa-trash"></i></button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div><br>

                    <!-- Address Information -->
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <div class="card card-sub table-card mb-3">
                                <div class="card-header bg-white py-2 px-3">
                                    <strong>Address Information</strong>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive table-scroll">
                                        <table class="table table-hover mb-0">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>Username</th>
                                                    <th>Street</th>
                                                    <th>Post Code</th>
                                                    <th>City</th>
                                                    <th>State/Province</th>
                                                    <th>Country</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($owners as $row)
                                                <tr>
                                                    <td>{{ $row['username'] }}</td>
                                                    <td>{{ $row['street_no'] }}</td>
                                                    <td>{{ $row['post_code'] }}</td>
                                                    <td>{{ $row['city'] }}</td>
                                                    <td>{{ $row['state'] }}</td>
                                                    <td>{{ $row['country'] }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact & Work -->
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <div class="card card-sub table-card mb-3">
                                <div class="card-header bg-white py-2 px-3">
                                    <strong>Contact</strong>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive table-scroll">
                                        <table class="table table-hover mb-0">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>Username</th>
                                                    <th>National ID</th>
                                                    <th>Email</th>
                                                    <th>Phone</th>
                                                    <th>Company</th>
                                                    <th>Job Title</th>
                                                    <th>Industry</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($owners as $row)
                                                <tr>
                                                    <td>{{ $row['username'] }}</td>
                                                    <td>{{ $row['national_id'] }}</td>
                                                    <td>{{ $row['email'] }}</td>
                                                    <td>{{ $row['contact'] }}</td>
                                                    <td>{{ $row['company'] }}</td>
                                                    <td>{{ $row['job_title'] }}</td>
                                                    <td>{{ $row['industry'] }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </main>

    <!-- ===================== ADD USER MODAL ===================== -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addUserForm" method="POST" action="php/add_user.php">
                        <div class="row g-2">
                            <div class="col-md-12">
                                <label class="form-label fw-bold">Password</label>
                                <input type="text" class="form-control" id="addPassword" name="password" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">First Name</label>
                                <input type="text" class="form-control" id="addFirstName" name="first_name" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Middle Name</label>
                                <input type="text" class="form-control" id="addMiddleName" name="middle_name">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Last Name</label>
                                <input type="text" class="form-control" id="addLastName" name="last_name" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Date of Birth</label>
                                <input type="date" class="form-control" id="addDob" name="dob">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Gender</label>
                                <select class="form-select" id="addGender" name="gender">
                                    <option value="">Select</option>
                                    <option>Female</option>
                                    <option>Male</option>
                                    <option>Other</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Nationality</label>
                                <input type="text" class="form-control" id="addNationality" name="nationality" required>
                            </div>

                            <div class="col-md-12">
                                <hr>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-bold">Street Address</label>
                                <input type="text" class="form-control" id="addStreetAddress" name="street_no" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Post Code</label>
                                <input type="text" class="form-control" id="addPostCode" name="post_code" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">City</label>
                                <input type="text" class="form-control" id="addCity" name="city" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">State/Province</label>
                                <input type="text" class="form-control" id="addState" name="state" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Country</label>
                                <input type="text" class="form-control" id="addCountry" name="country" required>
                            </div>

                            <div class="col-md-12">
                                <hr>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">National ID</label>
                                <input type="text" class="form-control" id="addNationalID" name="national_id" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Email</label>
                                <input type="email" class="form-control" id="addEmail" name="email" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Phone</label>
                                <input type="text" class="form-control" id="addPhone" name="phone" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Company</label>
                                <input type="text" class="form-control" id="addCompany" name="company">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Job Title</label>
                                <input type="text" class="form-control" id="addPosition" name="position">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Industry</label>
                                <input type="text" class="form-control" id="addIndustry" name="industry">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveAddUser">Add User</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ===================== EDIT USER MODAL ===================== -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 rounded-3">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editModalLabel">Edit User</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editUserForm" method="POST" action="php/update_user.php">
                        <input type="hidden" id="editIndex">
                        <input type="hidden" id="editOwnerId" name="owner_id">
                        <input type="hidden" id="editUserId" name="user_id">
                        <input type="hidden" id="editAddressId" name="address_id">

                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Username</label>
                                <input type="text" class="form-control" id="editUsername" name="username" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Password</label>
                                <input type="text" class="form-control" id="editPassword" name="password">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">First Name</label>
                                <input type="text" class="form-control" id="editFirstName" name="first_name" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Middle Name</label>
                                <input type="text" class="form-control" id="editMiddleName" name="middle_name">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Last Name</label>
                                <input type="text" class="form-control" id="editLastName" name="last_name" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Date of Birth</label>
                                <input type="date" class="form-control" id="editDob" name="dob" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Gender</label>
                                <select class="form-select" id="editGender" name="gender" required>
                                    <option value="">Select</option>
                                    <option>Female</option>
                                    <option>Male</option>
                                    <option>Other</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Nationality</label>
                                <input type="text" class="form-control" id="editNationality" name="nationality" required>
                            </div>

                            <div class="col-md-12">
                                <hr>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-bold">Street Address</label>
                                <input type="text" class="form-control" id="editStreetAddress" name="street_no" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Post Code</label>
                                <input type="text" class="form-control" id="editPostCode" name="post_code" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">City</label>
                                <input type="text" class="form-control" id="editCity" name="city" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">State/Province</label>
                                <input type="text" class="form-control" id="editState" name="state" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Country</label>
                                <input type="text" class="form-control" id="editCountry" name="country" required>
                            </div>

                            <div class="col-md-12">
                                <hr>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">National ID</label>
                                <input type="text" class="form-control" id="editNationalID" name="national_id" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Email</label>
                                <input type="email" class="form-control" id="editEmail" name="email" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Phone</label>
                                <input type="text" class="form-control" id="editPhone" name="phone" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Company</label>
                                <input type="text" class="form-control" id="editCompany" name="company">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Position</label>
                                <input type="text" class="form-control" id="editPosition" name="position">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Industry</label>
                                <input type="text" class="form-control" id="editIndustry" name="industry">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveEditUser">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ===================== DELETE USER MODAL ===================== -->
    <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header text-white bg-primary">
                    <h5 class="modal-title">Delete User</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">Are you sure you want to delete this user?</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" id="confirmDeleteUser">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS bundle -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <script>
        // CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Sidebar Toggle
        const toggleBtn = document.getElementById("toggle-btn");
        const sidebar = document.getElementById("sidebar");

        // Toggle sidebar
        toggleBtn.addEventListener("click", (e) => {
            e.stopPropagation(); // prevent triggering document click
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle("show"); // mobile toggle
            } else {
                sidebar.classList.toggle("collapsed"); // desktop toggle
            }
        });

        // Close sidebar if clicked outside (mobile only)
        document.addEventListener("click", (e) => {
            if (window.innerWidth <= 768 && sidebar.classList.contains("show")) {
                if (!sidebar.contains(e.target) && e.target !== toggleBtn) {
                    sidebar.classList.remove("show");
                }
            }
        });

        // Optional: close sidebar when window is resized to desktop
        window.addEventListener("resize", () => {
            if (window.innerWidth > 768) {
                sidebar.classList.remove("show");
            }
        });

        // ============ NOTIFICATION DROPDOWN TOGGLE ============
        const notifBell = document.getElementById('notifBell');
        const notifDropdown = document.getElementById('notifDropdown');

        notifBell.addEventListener('click', (e) => {
            e.stopPropagation();
            notifDropdown.classList.toggle('show');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!notifDropdown.contains(e.target) && e.target !== notifBell) {
                notifDropdown.classList.remove('show');
            }
        });

        // Clear All button (no function, just for display)
        document.getElementById('clearAllBtn').addEventListener('click', () => {
            console.log('Clear All clicked - no function implemented yet');
        });

        // ==== Toast function
        function showToast(message, type = 'info') {
            const toastEl = document.getElementById('liveToast');
            const toastBody = document.getElementById('toastMessage');

            // Remove previous type classes
            toastEl.classList.remove('success', 'error', 'warning', 'info');

            // Add new type class
            toastEl.classList.add(type);

            // Add icon based on type
            let icon = '';
            switch (type) {
                case 'success':
                    icon = '<i class="fas fa-check-circle"></i>';
                    break;
                case 'error':
                    icon = '<i class="fas fa-exclamation-circle"></i>';
                    break;
                case 'warning':
                    icon = '<i class="fas fa-exclamation-triangle"></i>';
                    break;
                case 'info':
                    icon = '<i class="fas fa-info-circle"></i>';
                    break;
            }

            // Set message with icon
            toastBody.innerHTML = icon + message;

            // Show toast
            const toast = new bootstrap.Toast(toastEl, {
                autohide: true,
                delay: 3000
            });
            toast.show();
        }

        // ===================== ADD USER =====================
        document.getElementById('saveAddUser').addEventListener('click', () => {
            const form = document.getElementById('addUserForm'); // Get form first

            // Get first and last names
            const firstName = form.querySelector('input[name="first_name"]').value.trim();
            const lastName = form.querySelector('input[name="last_name"]').value.trim();

            // Generate username
            let usernameInput = form.querySelector('input[name="username"]');
            if (!usernameInput) {
                // If hidden username field doesn't exist, create it
                usernameInput = document.createElement('input');
                usernameInput.type = 'hidden';
                usernameInput.name = 'username';
                form.appendChild(usernameInput);
            }
            usernameInput.value = (firstName + '.' + lastName).toLowerCase().replace(/\s+/g, '');

            // Prepare form data
            const formData = new FormData(form);

            // Send AJAX request
            fetch("{{ route('admin.users.store') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message || 'User added successfully!', 'success');
                        bootstrap.Modal.getInstance(document.getElementById('addUserModal')).hide();
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showToast('Failed to add user: ' + (data.message || 'Unknown error'), 'error');
                    }
                })
                .catch(err => {
                    console.error(err);
                    showToast('Request failed: ' + err.message, 'error');
                });
        });

        // ===================== EDIT USER =====================
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const tr = this.closest('tr');
                if (!tr) return;

                // Fill edit modal fields
                const data = tr.dataset;
                document.getElementById('editOwnerId').value = data.owner_id;
                document.getElementById('editUserId').value = data.user_id;
                document.getElementById('editAddressId').value = data.address_id;
                document.getElementById('editUsername').value = data.username;
                document.getElementById('editPassword').value = ''; // optional: leave blank
                document.getElementById('editFirstName').value = data.first;
                document.getElementById('editMiddleName').value = data.middle;
                document.getElementById('editLastName').value = data.last;
                document.getElementById('editDob').value = data.dob;
                document.getElementById('editGender').value = data.gender;
                document.getElementById('editNationality').value = data.nationality;
                document.getElementById('editNationalID').value = data.national_id;
                document.getElementById('editStreetAddress').value = data.street;
                document.getElementById('editPostCode').value = data.post;
                document.getElementById('editCity').value = data.city;
                document.getElementById('editState').value = data.state;
                document.getElementById('editCountry').value = data.country;
                document.getElementById('editEmail').value = data.email;
                document.getElementById('editPhone').value = data.phone;
                document.getElementById('editCompany').value = data.company;
                document.getElementById('editPosition').value = data.job;
                document.getElementById('editIndustry').value = data.industry;

                // Show modal
                new bootstrap.Modal(document.getElementById('editUserModal')).show();
            });
        });

        // SAVE EDIT
        document.getElementById('saveEditUser').addEventListener('click', () => {
            const form = document.getElementById('editUserForm');
            const formData = new FormData(form);
            formData.append('_method', 'PUT');

            const ownerId = document.getElementById('editOwnerId').value;

            fetch(`/admin/users/${ownerId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message || 'User updated successfully!', 'success');
                        bootstrap.Modal.getInstance(document.getElementById('editUserModal')).hide();
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showToast('Failed to update user: ' + (data.message || 'Unknown error'), 'error');
                    }
                })
                .catch(err => {
                    console.error(err);
                    showToast('Request failed: ' + err.message, 'error');
                });
        });

        // ===================== DELETE USER =====================
        let deleteTargetOwnerId = null;

        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const tr = this.closest('tr');
                if (!tr) return;
                deleteTargetOwnerId = tr.dataset.owner_id;
                new bootstrap.Modal(document.getElementById('deleteUserModal')).show();
            });
        });

        document.getElementById('confirmDeleteUser').addEventListener('click', () => {
            if (!deleteTargetOwnerId) return;

            const formData = new FormData();
            formData.append('_method', 'DELETE');

            fetch(`/admin/users/${deleteTargetOwnerId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message || 'User deleted successfully!', 'success');
                        bootstrap.Modal.getInstance(document.getElementById('deleteUserModal')).hide();
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showToast('Failed to delete user: ' + (data.message || 'Unknown error'), 'error');
                    }
                })
                .catch(err => {
                    console.error(err);
                    showToast('Request failed: ' + err.message, 'error');
                });
        });
    </script>

</body>

</html>