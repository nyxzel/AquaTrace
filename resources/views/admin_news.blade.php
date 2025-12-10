<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>News | AquaTrace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/adminNews_style.css') }}">
</head>

<body>

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
            <a href="{{ route('admin.users.index')}}" class="adminnav" data-label="Users">
                <i class="fas fa-user"></i> <span class="sidebar-text">Users</span>
            </a>
            <a href="{{ route('admin.vessels.index') }}" class="adminnav" data-label="Vessels">
                <i class="fas fa-ship"></i> <span class="sidebar-text">Vessels</span>
            </a>
            <a href="{{ route('admin.reports.index') }}" class="adminnav" data-label="Reports">
                <i class="fas fa-chart-bar"></i> <span class="sidebar-text">Reports</span>
            </a>
            <a href="{{ route('admin.news.index') }}" class="adminnav active" data-label="News">
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
        <div class="container-fluid">

            <!-- ADD NEWS CARD -->
            <div class="card shadow-sm p-0 mb-4 border-0" style="border-radius: 12px;">
                <!-- Blue header -->
                <div class="section-header">
                    <h4 class="section-title mb-0">Add News</h4>
                </div>

                <!-- Card Body -->
                <div class="p-4">
                    <form id="addNewsForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Event</label>
                            <input type="text" class="form-control" id="newsTitle" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Date</label>
                            <input type="date" class="form-control" id="newsDate" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" rows="4" id="newsContent" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" id="status" required>
                                <option value="Port Operations">Port Operations</option>
                                <option value="Safety Alert">Safety Alert</option>
                                <option value="Technology">Technology</option>
                                <option value="Incident Report">Incident Report</option>
                                <option value="Development">Development</option>
                                <option value="Environment">Environment</option>
                            </select>
                        </div>

                        <button class="btn btn-primary px-4" type="submit">Publish</button>
                    </form>
                </div>
            </div>

            <!-- RECENT NEWS LIST -->
            <div class="card shadow-sm p-0 mb-4 border-0" style="border-radius: 12px;">
                <!-- Blue header -->
                <div class="section-header">
                    <h4 class="section-title mb-0">Recently Added News</h4>
                </div>

                <!-- Card body -->
                <div class="p-1">
                    <div id="newsList" class="row g-3"></div>
                </div>
            </div>

        </div>
    </main>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-3">
                <div class="modal-header text-white" style="background-color: #06326a;">
                    <h5 class="modal-title" id="deleteModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i>Confirm Delete
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Are you sure you want to delete this news?</p>
                    <div class="alert alert-warning mb-0">
                        <strong>Event:</strong> <span id="deleteEventTitle"></span><br>
                        <strong>Date:</strong> <span id="deleteEventDate"></span><br>
                        <strong>Category:</strong> <span id="deleteEventStatus"></span>
                    </div>
                    <input type="hidden" id="deleteEventId">
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmDeleteBtn">
                        <i class="fas fa-trash me-2"></i>Delete News
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Setup CSRF token for all AJAX requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // ========== TOAST FUNCTION ==========
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
                    icon = '<i class="fas fa-check-circle me-2"></i>';
                    break;
                case 'error':
                    icon = '<i class="fas fa-exclamation-circle me-2"></i>';
                    break;
                case 'warning':
                    icon = '<i class="fas fa-exclamation-triangle me-2"></i>';
                    break;
                case 'info':
                    icon = '<i class="fas fa-info-circle me-2"></i>';
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


        const form = document.getElementById('addNewsForm');
        const newsList = document.getElementById('newsList');

        const statusColors = {
            port: "primary",
            safety: "primary",
            tech: "primary",
            report: "primary",
            dev: "primary",
            environment: "primary"
        };

        function formatDate(value) {
            const date = new Date(value);
            return date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        }

        async function loadNewsFromDatabase() {
            try {
                const response = await fetch("{{ route('admin.news.fetch') }}");
                const json = await response.json();

                newsList.innerHTML = "";
                json.forEach(news => displayNewsCard(news));
            } catch (error) {
                console.error("LOAD ERROR:", error);
                showToast("Failed to load news items", "error");
            }
        }

        function displayNewsCard(news) {
            const card = document.createElement("div");
            card.className = "col-md-4";
            card.id = `news-${news.event_id}`;

            const badgeColor = statusColors[news.status] || "primary";

            card.innerHTML = `
                <div class="event-card shadow-sm p-4 mb-4" 
                    style="border-radius: 18px; background: #ffffff;">

                    <!-- Category Badge -->
                    <span class="badge" 
                        style="background:#06326a; padding:7px 15px; border-radius:20px; font-size:12px;">
                        ${news.status.toUpperCase()}
                    </span>

                    <!-- Date -->
                    <div class="mt-3 d-flex align-items-center text-muted" 
                        style="font-size: 14px;">
                        <i class="far fa-calendar me-2"></i>
                        ${formatDate(news.event_date)}
                    </div>

                    <!-- Title -->
                    <h4 class="fw-bold mt-3" 
                        style="color:#0b3a67; font-family: 'Poppins', sans-serif;">
                        ${news.event_type}
                    </h4>

                    <!-- Description -->
                    <p class="mt-2" style="color:#555; line-height:1.6;">
                        ${news.description}
                    </p>

                    <!-- Delete Button -->
                    <button class="btn btn-sm mt-3 delete-news-btn" 
                        data-id="${news.event_id}"
                        data-title="${news.event_type}"
                        data-date="${formatDate(news.event_date)}"
                        data-status="${news.status}"
                        style="
                            background:#06326a;
                            color:white;
                            border:none;
                            padding:6px 14px;
                            border-radius:6px;
                            transition:.2s;
                        "
                        onmouseover="this.style.background='#07408a'"
                        onmouseout="this.style.background='#06326a'"
                    >
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            `;

            newsList.append(card);
        }

        // Create news
        form.addEventListener("submit", async function(e) {
            e.preventDefault();

            const data = {
                event_type: document.getElementById("newsTitle").value,
                event_date: document.getElementById("newsDate").value,
                description: document.getElementById("newsContent").value,
                status: document.getElementById("status").value
            };

            try {
                const response = await fetch("{{ route('admin.news.store') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken
                    },
                    body: JSON.stringify(data)
                });

                const json = await response.json();

                if (json.error) {
                    alert(json.error);
                    return;
                }

                displayNewsCard(json);
                form.reset();
                showToast("News added successfully!", "success");

            } catch (error) {
                console.error("CREATE ERROR:", error);
                showToast("Failed to create news item", "error");
            }
        });

        // Initialize delete modal
        let deleteModal;
        document.addEventListener("DOMContentLoaded", function() {
            loadNewsFromDatabase();
            deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

            // Handle delete button clicks with event delegation
            document.getElementById('newsList').addEventListener('click', function(e) {
                const deleteBtn = e.target.closest('.delete-news-btn');
                if (deleteBtn) {
                    const id = deleteBtn.getAttribute('data-id');
                    const title = deleteBtn.getAttribute('data-title');
                    const date = deleteBtn.getAttribute('data-date');
                    const status = deleteBtn.getAttribute('data-status');

                    // Populate modal
                    document.getElementById('deleteEventTitle').textContent = title;
                    document.getElementById('deleteEventDate').textContent = date;
                    document.getElementById('deleteEventStatus').textContent = status;
                    document.getElementById('deleteEventId').value = id;

                    // Show modal
                    deleteModal.show();
                }
            });

            // Confirm delete button
            document.getElementById('confirmDeleteBtn').addEventListener('click', async function() {
                const id = document.getElementById('deleteEventId').value;

                // Disable button during request
                this.disabled = true;
                this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Deleting...';

                try {
                    const response = await fetch(`{{ url('/admin/news') }}/${id}`, {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": csrfToken,
                            "Content-Type": "application/json"
                        }
                    });

                    const json = await response.json();

                    if (!json.error) {
                        document.getElementById(`news-${id}`).remove();
                        deleteModal.hide();
                        showToast("News deleted successfully!", "success");
                    } else {
                        showToast(json.error, "error");
                    }
                } catch (error) {
                    console.error("DELETE ERROR:", error);
                    showToast("Failed to delete news item", "error");
                } finally {
                    // Re-enable button
                    this.disabled = false;
                    this.innerHTML = '<i class="fas fa-trash me-2"></i>Delete News';
                }
            });
        });

        document.addEventListener("DOMContentLoaded", loadNewsFromDatabase);
    </script>

</body>

</html>