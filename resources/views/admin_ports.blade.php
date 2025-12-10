<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ports | AquaTrace</title>
    <link href="{{ asset('css/adminPorts_style.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&display=swap" rel="stylesheet">
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
            <a href="{{ route('admin.ports.index') }}" class="adminnav active" data-label="Ports">
                <i class="fas fa-anchor"></i> <span class="sidebar-text">Ports</span>
            </a>
            <a href="{{ route('admin.users.index')}}" class="adminnav" data-label="Users">
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

    <!-- Main Content -->
    <main class="admin-body">
        <div class="row">
            <div class="col-12 mb-4">
                <div class="card-userinfo">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-light fw-bold">PORTS INFORMATION</h5>

                        <button class="btn btn-light btn-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#addPortModal">
                            <i class="fas fa-plus"></i> Add Port
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Port Name</th>
                                    <th>City</th>
                                    <th>Province</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ports as $port)
                                <tr data-port-id="{{ $port->port_id }}">
                                    <td>{{ $port->name }}</td>
                                    <td>{{ $port->address ? $port->address->city : 'No Address' }}</td>
                                    <td>{{ $port->address ? $port->address->state : 'No Address' }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" onclick="deletePort(this)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No ports found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- ===================== ADD PORT MODAL ===================== -->
    <div class="modal fade" id="addPortModal" tabindex="-1" aria-labelledby="addPortModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addPortModalLabel">Add New Port</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addPortForm">
                        <div class="mb-3">
                            <label class="form-label">Port Name</label>
                            <input type="text" class="form-control" id="addPortName" name="port_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">City</label>
                            <input type="text" class="form-control" id="addCity" name="city" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Province</label>
                            <input type="text" class="form-control" id="addProvince" name="province" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Latitude</label>
                                <input type="number" step="0.000001" class="form-control" id="addLatitude" name="latitude" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Longitude</label>
                                <input type="number" step="0.000001" class="form-control" id="addLongitude" name="longitude" required>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveAddPort">Add Port</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ===================== DELETE CONFIRM MODAL ===================== -->
    <div class="modal fade" id="deletePortModal" tabindex="-1" aria-labelledby="deletePortModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="deletePortModalLabel">Delete Port</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this port record?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmDeletePort">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // ============ TOAST FUNCTION ============
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
                    icon = '<i class="fas fa-check-circle"></i> ';
                    break;
                case 'error':
                    icon = '<i class="fas fa-exclamation-circle"></i> ';
                    break;
                case 'warning':
                    icon = '<i class="fas fa-exclamation-triangle"></i> ';
                    break;
                case 'info':
                    icon = '<i class="fas fa-info-circle"></i> ';
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

        // ============ PORT MANAGEMENT ============
        let selectedRow = null;

        // ADD PORT
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("saveAddPort").addEventListener("click", function() {
                const portName = document.getElementById("addPortName").value.trim();
                const city = document.getElementById("addCity").value.trim();
                const province = document.getElementById("addProvince").value.trim();

                if (!portName || !city || !province) {
                    alert("Please fill all fields.");
                    return;
                }

                fetch("{{ route('admin.ports.store') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            name: portName,
                            city: city,
                            state: province,
                            latitude: document.getElementById('addLatitude').value,
                            longitude: document.getElementById('addLongitude').value
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === "success") {
                            window.location.reload();
                        } else {
                            alert("Error: " + data.message);
                        }
                    })
                    .catch(err => console.error(err));
            });
        });


        // DELETE (Archive)
        function deletePort(button) {
            selectedRow = button.closest("tr");
            new bootstrap.Modal(document.getElementById('deletePortModal')).show();
        }

        document.getElementById("confirmDeletePort").addEventListener("click", function() {
            if (selectedRow) {
                const portId = selectedRow.dataset.portId;

                fetch("{{ route('admin.ports.archive') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            port_id: portId
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === "success") {
                            selectedRow.remove();
                            bootstrap.Modal.getInstance(document.getElementById('deletePortModal')).hide();
                        } else {
                            alert("Error: " + data.message);
                        }
                    });
            }
        });
    </script>

</body>

</html>