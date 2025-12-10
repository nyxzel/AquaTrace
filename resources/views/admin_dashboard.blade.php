<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard | AquaTrace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="{{ asset('css/adminDashboard_style.css') }}">
</head>

<body>

    <!-- Sidebar -->
    <aside class="adminbar" id="sidebar">
        <div class="adminbar-title">
            <a href="#" class="navbar-brand">
                <img src="{{ asset('images/AQUATRACELOGO.png') }}" alt="AquaTrace Logo" class="logo-img">
                <span class="logo-text">AQUATRACE</span>
            </a>
        </div>

        <nav>
            <a href="{{ route('admin.dashboard.index') }}" class="adminnav active" data-label="Dashboard">
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

    <!-- Main -->
    <main class="admin-body">

        <!-- Stats -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <a href="{{ url('/admin/vessels') }}" class="text-decoration-none">
                    <div class="card admin-card shadow-sm text-center py-3">
                        <div class="card-body">
                            <i class="fas fa-ship fa-2x mb-2" style="color: #0b3a67;"></i>
                            <div class="text-dark-emphasis small">Vessels</div>
                            <h5>{{ $vesselCount }}</h5>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-6 col-md-3">
                <a href="{{ url('/admin/ports') }}" class="text-decoration-none">
                    <div class="card admin-card shadow-sm text-center py-3">
                        <div class="card-body">
                            <i class="fas fa-map-pin fa-2x mb-2" style="color: #0b3a67;"></i>
                            <div class="text-dark-emphasis small">Ports</div>
                            <h5>{{ $portCount }}</h5>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-6 col-md-3">
                <a href="{{ url('/admin/users') }}" class="text-decoration-none">
                    <div class="card admin-card shadow-sm text-center py-3">
                        <div class="card-body">
                            <i class="fas fa-users fa-2x mb-2" style="color: #0b3a67;"></i>
                            <div class="text-dark-emphasis small">Users</div>
                            <h5>{{ $userCount }}</h5>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-6 col-md-3">
                <a href="{{ url('/admin/reports') }}" class="text-decoration-none">
                    <div class="card admin-card shadow-sm text-center py-3">
                        <div class="card-body bg-white">
                            <i class="fas fa-file-lines fa-2x mb-2" style="color: #0b3a67;"></i>
                            <div class="text-dark-emphasis small">Reports</div>
                            <h5>{{ $reportCount }}</h5>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Map -->
        <div class="card shadow-sm">
            <div class="card-header d-flex text-center text-white bg-primary">
                <h5 class="mb-0 fw-bold">MINDANAO MAP</h5>
            </div>
            <div class="card-body position-relative">
                <div id="map" style="height: 500px;"></div>
            </div>
        </div>
        <br><br>

        <!-- Registered Vessels -->
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center text-white bg-primary">
                <h5 class="mb-0 fw-bold">REGISTERED VESSELS</h5>
            </div>

            <div class="card-body">
                <br>
                <div id="vesselList"></div>
            </div>
        </div>
    </main>

    <!-- Add Vessel Modal -->
    <div class="modal fade" id="addVesselModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-white" style="background-color: #06326a;">
                    <h5 class="modal-title">Approve & Register Vessel</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addVesselForm">
                        @csrf
                        <input type="hidden" id="requestId" name="request_id">
                        <input type="hidden" id="vesselLat" name="latitude">
                        <input type="hidden" id="vesselLng" name="longitude">
                        <input type="hidden" id="vesselOwnerId" name="owner_id">

                        <div class="alert alert-info mb-3">
                            <i class="fas fa-info-circle"></i> <strong>Registration Details:</strong><br>
                            <strong>Owner:</strong> <span id="ownerName">--</span><br>
                            <strong>Email:</strong> <span id="ownerEmail">--</span>
                        </div>

                        <hr>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Vessel Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="vesselName" name="name" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Vessel Type <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="vesselType" name="type" placeholder="e.g., Cargo, Tanker, Passenger" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">IMO Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="vesselIMO" name="imo" placeholder="e.g., IMO1234567" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">MMSI <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="vesselMMSI" name="mmsi" placeholder="e.g., 123456789" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Call Sign <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="vesselCallSign" name="call_sign" placeholder="e.g., ABCD" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Flag State <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="vesselFlag" name="flag" placeholder="e.g., Philippines" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Length Overall (m) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control" id="vesselLoA" name="LoA" placeholder="e.g., 150.50" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Gross Tonnage <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control" id="vesselGrossTonnage" name="gross_tonnage" placeholder="e.g., 5000.00" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Year Built <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="vesselYearBuilt" name="year_built" min="1900" max="2100" placeholder="e.g., 2020" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Speed (knots)</label>
                                <input type="number" step="0.1" class="form-control" id="vesselSpeed" name="speed" value="0" placeholder="e.g., 15.5">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-clipboard"></i> Additional Notes</label>
                            <textarea class="form-control" id="vesselNotes" name="additional_notes" rows="3" placeholder="Enter any additional information about this vessel..."></textarea>
                            <small class="text-muted">Optional: Add any special notes, comments, or additional information about this vessel.</small>
                        </div>

                        <div class="alert alert-warning small mb-3">
                            <i class="fas fa-map-marker-alt"></i> <strong>Click on the map to set vessel position</strong><br>
                            <strong>Latitude:</strong> <span id="displayLat">Not set</span><br>
                            <strong>Longitude:</strong> <span id="displayLng">Not set</span>
                        </div>

                        <div class="text-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times"></i> Cancel
                            </button>
                            <button type="submit" class="btn text-white" style="background-color: #06326a;">
                                <i class="fas fa-check-circle"></i> Approve & Register Vessel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Vessel Modal -->
    <div class="modal fade" id="deleteVesselModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header text-white bg-primary">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle"></i> Confirm Delete</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Are you sure you want to archive this vessel?</p>
                    <div class="alert alert-warning mb-0">
                        <strong>Vessel:</strong> <span id="deleteVesselName"></span><br>
                        <strong>IMO:</strong> <span id="deleteVesselIMO"></span><br>
                        <strong>MMSI:</strong> <span id="deleteVesselMMSI"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmDeleteBtn"><i class="fas fa-archive"></i> Delete Vessel</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Decline Registration Modal -->
    <div class="modal fade" id="declineModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header text-white" style="background-color: #06326a;">
                    <h5 class="modal-title"><i class="fas fa-times-circle"></i> Decline Registration</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Are you sure you want to decline this vessel registration request?</p>
                    <div class="alert alert-warning mb-3">
                        <strong>Vessel:</strong> <span id="declineVesselName"></span><br>
                        <strong>Owner:</strong> <span id="declineOwnerName"></span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Reason for Decline (Optional)</label>
                        <textarea class="form-control" id="declineReason" rows="3" placeholder="Enter reason for declining this registration..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmDeclineBtn">
                        <i class="fas fa-times"></i> Decline Request
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Clear All Notifications Modal -->
    <div class="modal fade" id="clearAllModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header text-white" style="background-color: #06326a;">
                    <h5 class="modal-title"><i class="fas fa-trash-alt"></i> Clear All Notifications</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Are you sure you want to clear all pending registration requests?</p>
                    <div class="alert alert-danger mb-0">
                        <i class="fas fa-exclamation-triangle"></i> <strong>Warning:</strong> This will decline all <span id="pendingCount"></span> pending request(s). This action cannot be undone.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmClearAllBtn">
                        <i class="fas fa-trash-alt"></i> Clear All
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        // CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

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

        // ============ MAP SETUP ============
        const map = L.map('map').setView([7.0, 125.6], 9);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Store markers
        let vesselMarkers = {};
        let portMarkers = {};
        let tempMarker = null;

        // Custom Font Awesome Icons
        const vesselIcon = L.divIcon({
            className: 'custom-marker vessel-marker',
            html: '<div style="background: rgba(255,255,255,0.9); border-radius: 50%; padding: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.3);"><i class="fas fa-ship" style="font-size: 22px; color: #06326a;"></i></div>',
            iconSize: [40, 40],
            iconAnchor: [20, 20],
            popupAnchor: [0, -20]
        });

        const portIcon = L.divIcon({
            className: 'custom-marker port-marker',
            html: '<div style="background: rgba(255,255,255,0.9); border-radius: 50%; padding: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.3);"><i class="fas fa-anchor" style="font-size: 24px; color: #2666b9ff;"></i></div>',
            iconSize: [42, 42],
            iconAnchor: [21, 21],
            popupAnchor: [0, -21]
        });

        const tempVesselIcon = L.divIcon({
            className: 'custom-marker temp-vessel-marker',
            html: '<div style="background: #2833a7ff; border-radius: 50%; padding: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.4); animation: pulse 1.5s infinite;"><i class="fas fa-ship" style="font-size: 22px; color: #fff;"></i></div>',
            iconSize: [40, 40],
            iconAnchor: [20, 20],
            popupAnchor: [0, -20]
        });

        // Add pulse animation CSS
        const style = document.createElement('style');
        style.textContent = `
            @keyframes pulse {
                0%, 100% { transform: scale(1); opacity: 1; }
                50% { transform: scale(1.1); opacity: 0.8; }
            }
            .custom-marker {
                background: transparent !important;
                border: none !important;
            }
        `;
        document.head.appendChild(style);

        // ============ RENDER PORTS ON MAP ============
        const portsData = @json($ports ?? []);
        console.log('Rendering ports:', portsData);

        portsData.forEach(port => {
            if (port.latitude && port.longitude) {
                const marker = L.marker([parseFloat(port.latitude), parseFloat(port.longitude)], {
                    icon: portIcon
                }).addTo(map);

                marker.bindPopup(`
                    <div style="text-align: center; min-width: 150px;">
                        <h6 style="margin-bottom: 8px; color: #2833a7ff;">
                            <i class="fas fa-anchor"></i> ${port.name}
                        </h6>
                        <small style="color: #666;">Port ID: ${port.port_id}</small>
                    </div>
                `);

                portMarkers[port.port_id] = marker;
            }
        });

        // ============ RENDER EXISTING VESSELS ON MAP ============
        const existingVessels = @json($vessels ?? []);
        console.log('Rendering vessels:', existingVessels);

        existingVessels.forEach(vessel => {
            if (vessel.latitude && vessel.longitude) {
                const marker = L.marker([parseFloat(vessel.latitude), parseFloat(vessel.longitude)], {
                    icon: vesselIcon
                }).addTo(map);

                marker.bindPopup(`
                    <div style="text-align: center; min-width: 200px;">
                        <h6 style="margin-bottom: 10px; color: #06326a;">
                            <i class="fas fa-ship"></i> ${vessel.name}
                        </h6>
                        <div style="text-align: left; font-size: 13px;">
                            <p style="margin: 4px 0;"><strong>Type:</strong> ${vessel.type}</p>
                            <p style="margin: 4px 0;"><strong>Flag:</strong> ${vessel.flag}</p>
                            <p style="margin: 4px 0;"><strong>IMO:</strong> ${vessel.imo}</p>
                            <p style="margin: 4px 0;"><strong>MMSI:</strong> ${vessel.mmsi}</p>
                            <p style="margin: 4px 0;"><strong>Speed:</strong> ${vessel.speed || 0} knots</p>
                        </div>
                    </div>
                `);

                vesselMarkers[vessel.vessel_id] = marker;
            }
        });

        // ============ DELETE VESSEL MODAL ============
        let deleteVesselId = null;
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteVesselModal'));

        // Function to open delete modal
        window.openDeleteModal = function(vesselId, vesselName, vesselIMO, vesselMMSI) {
            deleteVesselId = vesselId;
            document.getElementById('deleteVesselName').textContent = vesselName;
            document.getElementById('deleteVesselIMO').textContent = vesselIMO;
            document.getElementById('deleteVesselMMSI').textContent = vesselMMSI;
            deleteModal.show();
        };

        // Confirm delete button click
        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (!deleteVesselId) return;

            const submitBtn = this;
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deleting...';

            fetch("{{ route('admin.deleteVessel') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'Accept': 'application/json'
                    },
                    body: new URLSearchParams({
                        vessel_id: deleteVesselId
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // Remove from UI
                        const vesselCard = document.querySelector(`.vessel-card[data-vessel-id="${deleteVesselId}"]`);
                        if (vesselCard) vesselCard.remove();

                        // Remove marker from map
                        if (vesselMarkers[deleteVesselId]) {
                            map.removeLayer(vesselMarkers[deleteVesselId]);
                            delete vesselMarkers[deleteVesselId];
                        }

                        // Remove from existingVessels array
                        const vesselIndex = existingVessels.findIndex(v => v.vessel_id === deleteVesselId);
                        if (vesselIndex !== -1) {
                            existingVessels.splice(vesselIndex, 1);
                        }

                        deleteModal.hide();
                        showToast('Vessel deleted successfully!', 'success');
                        deleteVesselId = null;

                        // If no vessels left, show empty state
                        if (existingVessels.length === 0) {
                            renderVesselList();
                        }
                    } else {
                        showToast('Error deleting vessel.', 'error');
                    }
                })
                .catch(error => {
                    console.error(error);
                    showToast('Request failed: ' + error.message, 'error');
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                });
        });

        // Reset on modal close
        document.getElementById('deleteVesselModal').addEventListener('hidden.bs.modal', () => {
            deleteVesselId = null;
        });

        // ============ RENDER VESSEL LIST ============
        function renderVesselList() {
            const vesselListContainer = document.getElementById('vesselList');

            if (!vesselListContainer) return;

            if (existingVessels.length === 0) {
                vesselListContainer.innerHTML = `
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-ship fa-3x mb-3"></i>
                        <p>No vessels registered yet. Accept a registration request to add vessels.</p>
                    </div>
                `;
                return;
            }

            vesselListContainer.innerHTML = existingVessels.map(vessel => `
                <div class="card mb-3 vessel-card" data-vessel-id="${vessel.vessel_id}">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h5 class="mb-2">
                                    <i class="fas fa-ship text-primary"></i> ${vessel.name}
                                </h5>
                                <div class="row text-sm">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Type:</strong> ${vessel.type || 'N/A'}</p>
                                        <p class="mb-1"><strong>Flag:</strong> ${vessel.flag || 'N/A'}</p>
                                        <p class="mb-1"><strong>IMO:</strong> ${vessel.imo || 'N/A'}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>MMSI:</strong> ${vessel.mmsi || 'N/A'}</p>
                                        <p class="mb-1"><strong>Call Sign:</strong> ${vessel.call_sign || 'N/A'}</p>
                                        <p class="mb-1"><strong>Speed:</strong> ${vessel.speed || 0} knots</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-end">
                                <button class="btn btn-sm btn-primary me-2" onclick="viewVessel(${vessel.vessel_id})">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <button class="btn btn-sm btn-primary" onclick="openDeleteModal(${vessel.vessel_id}, '${vessel.name.replace(/'/g, "\\'")}', '${vessel.imo}', '${vessel.mmsi}')">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        renderVesselList();

        // View vessel function
        window.viewVessel = function(vessel_id) {
            const vessel = existingVessels.find(v => v.vessel_id === vessel_id);
            if (vessel && vessel.latitude && vessel.longitude) {
                map.setView([parseFloat(vessel.latitude), parseFloat(vessel.longitude)], 12);
                if (vesselMarkers[vessel_id]) {
                    vesselMarkers[vessel_id].openPopup();
                }
            }
        };

        // ============ NOTIFICATIONS ============
        const pendingOwnersData = @json($pendingOwners ?? []);
        let notifications = [];

        const notifBell = document.getElementById('notifBell');
        const notifDropdown = document.getElementById('notifDropdown');
        const notifBadge = document.getElementById('notifBadge');
        const notifList = document.getElementById('notifList');

        let selectedRequest = null;

        function initializeNotifications() {
            pendingOwnersData.forEach(registration => {
                notifications.push({
                    id: registration.id,
                    owner_id: registration.owner_id,
                    user: `${registration.owner?.first_name || 'Unknown'} ${registration.owner?.last_name || 'User'}`,
                    email: registration.user?.email || 'No email',
                    time: 'Pending',
                    vessel_data: {
                        vessel_name: registration.vessel_name,
                        vessel_type: registration.vessel_type,
                        imo_number: registration.imo_number,
                        mmsi_number: registration.mmsi_number,
                        call_sign: registration.call_sign,
                        flag_state: registration.flag_state,
                        length_overall: registration.length_overall,
                        gross_tonnage: registration.gross_tonnage,
                        year_built: registration.year_built,
                        additional_notes: registration.additional_notes,
                        latitude: registration.latitude,
                        longitude: registration.longitude
                    }
                });
            });
            updateNotificationUI();
        }

        function updateNotificationUI() {
            if (notifications.length > 0) {
                notifBadge.textContent = notifications.length;
                notifBadge.classList.remove('d-none');
            } else {
                notifBadge.classList.add('d-none');
            }

            if (notifications.length === 0) {
                notifList.innerHTML = `<div class="empty-state"><i class="fas fa-bell-slash"></i><p>No new notifications</p></div>`;
                return;
            }

            notifList.innerHTML = notifications.map(notif => `
                <div class="notif-item" data-id="${notif.id}">
                    <div class="notif-icon"><i class="fas fa-user-plus"></i></div>
                    <div class="notif-content">
                        <div class="notif-user">${notif.user}</div>
                        <div class="notif-message">Vessel: ${notif.vessel_data.vessel_name}</div>
                        <div class="notif-time">${notif.time}</div>
                    </div>
                    <div class="notif-actions">
                        <button class="action-btn accept-btn" onclick="acceptUser(${notif.id})" title="Click to select position on map">
                            <i class="fas fa-check"></i>
                        </button>
                        <button class="action-btn decline-btn" onclick="declineUser(${notif.id})" title="Reject registration">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            `).join('');
        }

        notifBell.addEventListener('click', e => {
            e.stopPropagation();
            notifDropdown.classList.toggle('show');
        });

        document.addEventListener('click', e => {
            if (!notifDropdown.contains(e.target) && e.target !== notifBell) {
                notifDropdown.classList.remove('show');
            }
        });

        window.acceptUser = function(id) {
            const notif = notifications.find(n => n.id === id);
            if (!notif) return;

            selectedRequest = notif;

            // Close notification dropdown
            notifDropdown.classList.remove('show');

            // Show instruction toast
            showToast(`Click on the map to set position for ${notif.vessel_data.vessel_name}`, 'info');

            // Enable map click mode
            map.getContainer().style.cursor = 'crosshair';

            // Store the notification data for later use
            window.pendingApproval = {
                id: id,
                owner_id: notif.owner_id,
                user: notif.user,
                email: notif.email,
                vessel_data: notif.vessel_data
            };
        };

        function clearPositionFields() {
            document.getElementById('vesselLat').value = '';
            document.getElementById('vesselLng').value = '';
            document.getElementById('displayLat').textContent = 'Not set';
            document.getElementById('displayLng').textContent = 'Not set';
            if (tempMarker) {
                map.removeLayer(tempMarker);
                tempMarker = null;
            }
        }

        // ============ DECLINE MODAL ============
        let declineRequestId = null;
        const declineModal = new bootstrap.Modal(document.getElementById('declineModal'));

        window.declineUser = function(id) {
            const notif = notifications.find(n => n.id === id);
            if (!notif) return;

            declineRequestId = id;
            document.getElementById('declineVesselName').textContent = notif.vessel_data.vessel_name;
            document.getElementById('declineOwnerName').textContent = notif.user;
            document.getElementById('declineReason').value = '';

            declineModal.show();
        };

        // Confirm decline button click
        document.getElementById('confirmDeclineBtn').addEventListener('click', function() {
            if (!declineRequestId) return;

            const submitBtn = this;
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Declining...';

            const reason = document.getElementById('declineReason').value.trim();

            fetch("{{ route('admin.vessel.reject') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        request_id: declineRequestId,
                        reason: reason || null
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        notifications = notifications.filter(n => n.id !== declineRequestId);
                        updateNotificationUI();
                        declineModal.hide();
                        showToast('Registration request declined successfully.', 'info');
                        declineRequestId = null;
                    } else {
                        showToast('Error declining registration: ' + (data.message || 'Unknown error'), 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Error declining registration.', 'error');
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                });
        });

        // Reset on modal close
        document.getElementById('declineModal').addEventListener('hidden.bs.modal', () => {
            declineRequestId = null;
            document.getElementById('declineReason').value = '';
        });

        // ============ CLEAR ALL MODAL ============
        const clearAllModal = new bootstrap.Modal(document.getElementById('clearAllModal'));

        // Clear all notifications button
        document.getElementById('clearAllBtn').addEventListener('click', () => {
            if (notifications.length === 0) {
                showToast('No notifications to clear.', 'info');
                return;
            }

            document.getElementById('pendingCount').textContent = notifications.length;
            clearAllModal.show();
        });

        // Confirm clear all button click
        document.getElementById('confirmClearAllBtn').addEventListener('click', function() {
            const submitBtn = this;
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Clearing...';

            // Decline all pending requests
            const promises = notifications.map(notif =>
                fetch("{{ route('admin.vessel.reject') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        request_id: notif.id,
                        reason: 'Bulk decline - Cleared by admin'
                    })
                })
            );

            Promise.all(promises)
                .then(() => {
                    notifications = [];
                    updateNotificationUI();
                    clearAllModal.hide();
                    notifDropdown.classList.remove('show');
                    showToast('All notifications cleared successfully.', 'success');
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Error clearing notifications.', 'error');
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                });
        });

        // Map click to set vessel position
        map.on('click', function(e) {
            // Check if we're in pending approval mode (user clicked accept in notification)
            if (window.pendingApproval) {
                const lat = e.latlng.lat.toFixed(6);
                const lng = e.latlng.lng.toFixed(6);

                // Set position
                document.getElementById("vesselLat").value = lat;
                document.getElementById("vesselLng").value = lng;
                document.getElementById("displayLat").textContent = lat;
                document.getElementById("displayLng").textContent = lng;

                // Add temp marker
                if (tempMarker) map.removeLayer(tempMarker);
                tempMarker = L.marker([e.latlng.lat, e.latlng.lng], {
                    icon: vesselIcon
                }).addTo(map);

                // Populate form with pending approval data
                const approval = window.pendingApproval;
                document.getElementById('requestId').value = approval.id;
                document.getElementById('vesselOwnerId').value = approval.owner_id;
                document.getElementById('ownerName').textContent = approval.user;
                document.getElementById('ownerEmail').textContent = approval.email;

                if (approval.vessel_data) {
                    const v = approval.vessel_data;
                    document.getElementById('vesselName').value = v.vessel_name || '';
                    document.getElementById('vesselType').value = v.vessel_type || '';
                    document.getElementById('vesselIMO').value = v.imo_number || '';
                    document.getElementById('vesselMMSI').value = v.mmsi_number || '';
                    document.getElementById('vesselCallSign').value = v.call_sign || '';
                    document.getElementById('vesselFlag').value = v.flag_state || '';
                    document.getElementById('vesselLoA').value = v.length_overall || '';
                    document.getElementById('vesselGrossTonnage').value = v.gross_tonnage || '';
                    document.getElementById('vesselYearBuilt').value = v.year_built || '';
                    document.getElementById('vesselNotes').value = v.additional_notes || '';
                }

                // Reset map cursor
                map.getContainer().style.cursor = '';

                // Clear pending approval
                window.pendingApproval = null;

                // Show success toast
                showToast(`Position set: ${lat}, ${lng}. Opening approval form...`, 'success');

                // Open modal
                setTimeout(() => {
                    new bootstrap.Modal(document.getElementById("addVesselModal")).show();
                }, 500);

                return;
            }

            // Only allow position setting when modal is open (for editing position)
            const modal = document.getElementById('addVesselModal');
            const modalInstance = bootstrap.Modal.getInstance(modal);

            if (modalInstance && modal.classList.contains('show')) {
                const lat = e.latlng.lat.toFixed(6);
                const lng = e.latlng.lng.toFixed(6);

                document.getElementById("vesselLat").value = lat;
                document.getElementById("vesselLng").value = lng;
                document.getElementById("displayLat").textContent = lat;
                document.getElementById("displayLng").textContent = lng;

                // Update temp marker
                if (tempMarker) map.removeLayer(tempMarker);
                tempMarker = L.marker([e.latlng.lat, e.latlng.lng], {
                    icon: vesselIcon
                }).addTo(map);

                // Visual feedback
                showToast(`Position updated: ${lat}, ${lng}`, 'success');
            }
        });

        // Add Vessel Form Submission (APPROVE REQUEST)
        document.getElementById("addVesselForm").addEventListener("submit", function(e) {
            e.preventDefault();
            const formData = new FormData(e.target);

            // Enhanced validation
            if (!formData.get('latitude') || !formData.get('longitude')) {
                showToast('Please click on the map to set vessel position!', 'error');
                return;
            }

            if (!formData.get('name') || !formData.get('type') || !formData.get('imo') ||
                !formData.get('mmsi') || !formData.get('call_sign') || !formData.get('flag')) {
                showToast('Please fill in all required fields!', 'error');
                return;
            }

            const loa = parseFloat(formData.get('LoA'));
            const gt = parseFloat(formData.get('gross_tonnage'));
            const year = parseInt(formData.get('year_built'));

            if (loa <= 0 || gt <= 0) {
                showToast('Length and Gross Tonnage must be greater than 0!', 'error');
                return;
            }

            if (year < 1900 || year > 2100) {
                showToast('Invalid year built!', 'error');
                return;
            }

            const submitBtn = e.target.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Approving...';

            fetch("{{ route('admin.vessel.approve') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message || 'Vessel approved and registered successfully!', 'success');

                        // Add vessel to map
                        const lat = parseFloat(formData.get('latitude'));
                        const lng = parseFloat(formData.get('longitude'));
                        const vessel = data.vessel;

                        if (tempMarker) {
                            map.removeLayer(tempMarker);
                            tempMarker = null;
                        }

                        const marker = L.marker([lat, lng], {
                            icon: vesselIcon
                        }).addTo(map);

                        marker.bindPopup(`
                        <div style="text-align: center;">
                            <h6><i class="fas fa-ship"></i> ${vessel.name}</h6>
                            <p style="margin: 5px 0;"><strong>Type:</strong> ${vessel.type}</p>
                            <p style="margin: 5px 0;"><strong>Flag:</strong> ${vessel.flag}</p>
                            <p style="margin: 5px 0;"><strong>IMO:</strong> ${vessel.imo}</p>
                            <p style="margin: 5px 0;"><strong>MMSI:</strong> ${vessel.mmsi}</p>
                            <p style="margin: 5px 0;"><strong>Speed:</strong> ${formData.get('speed') || 0} knots</p>
                        </div>
                    `);

                        vesselMarkers[data.vessel_id] = marker;

                        // Remove from notifications
                        notifications = notifications.filter(n => n.id !== parseInt(formData.get('request_id')));
                        updateNotificationUI();

                        bootstrap.Modal.getInstance(document.getElementById("addVesselModal")).hide();
                        e.target.reset();
                        selectedRequest = null;

                        // Reload page to refresh vessel list
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showToast(data.message || 'Error approving vessel.', 'error');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Error approving vessel.', 'error');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                });
        });

        // Modal cleanup
        document.getElementById('addVesselModal').addEventListener('hidden.bs.modal', function() {
            document.getElementById('addVesselForm').reset();
            clearPositionFields();
            selectedRequest = null;
            window.pendingApproval = null;
            map.getContainer().style.cursor = '';
        });

        // Cancel pending approval when pressing ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && window.pendingApproval) {
                map.getContainer().style.cursor = '';
                window.pendingApproval = null;
                showToast('Position selection cancelled', 'info');
            }
        });

        // Initialize notifications on page load
        initializeNotifications();

        console.log('Dashboard initialized successfully');
        console.log('Vessels:', existingVessels.length);
        console.log('Ports:', portsData.length);
        console.log('Pending requests:', notifications.length);
    </script>

</body>

</html>