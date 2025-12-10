<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Vessels | AquaTrace</title>
    <link href="{{ asset('css/adminVessels_style.css') }}" rel="stylesheet">
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
            <a href="{{ route('admin.users.index')}}" class="adminnav" data-label="Users">
                <i class="fas fa-user"></i> <span class="sidebar-text">Users</span>
            </a>
            <a href="{{ route('admin.vessels.index') }}" class="adminnav active" data-label="Vessels">
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

    <!-- Vessels Table -->
    <main class="admin-body">
        <div class="row">
            <div class="col-12 mb-4">
                <div class="card-userinfo">
                    <div class="card-header d-flex justify-content-between align-items-center text-white">
                        <h5 class="mb-0 fw-bold">REGISTERED VESSELS</h5>
                    </div>

                    <div class="p-3">
                        <div class="card card-sub table-card">
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Vessel Name</th>
                                                <th>Owner</th>
                                                <th>IMO Number</th>
                                                <th>MMSI Number</th>
                                                <th>Call Sign</th>
                                                <th>Vessel Type</th>
                                                <th>Flag State</th>
                                                <th>Length Overall (m)</th>
                                                <th>Gross Tonnage</th>
                                                <th>Year Built</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="vesselsTableBody" class="text-center">
                                            @foreach ($vessels as $vessel)
                                            <tr data-id="{{ $vessel->vessel_id }}">
                                                <td>{{ $vessel->name }}</td>
                                                <td>{{ optional(optional($vessel->owner)->user)->username ?? 'N/A' }}</td>
                                                <td>{{ $vessel->imo }}</td>
                                                <td>{{ $vessel->mmsi }}</td>
                                                <td>{{ $vessel->call_sign }}</td>
                                                <td>{{ $vessel->type }}</td>
                                                <td>{{ $vessel->flag }}</td>
                                                <td>{{ $vessel->LoA }}</td>
                                                <td>{{ $vessel->gross_tonnage }}</td>
                                                <td>{{ $vessel->year_built }}</td>

                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary edit-btn">
                                                        <i class="fas fa-edit"></i>
                                                    </button>

                                                    <button class="btn btn-sm btn-outline-primary delete-btn">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
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

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 rounded-3">
                <div class="modal-header text-white" style="background-color: #06326a;">
                    <h5 class="modal-title" id="editModalLabel">Edit Vessel Information</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <div class="row">
                            <input type="hidden" id="editVesselId">

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Vessel Name</label>
                                <input type="text" id="editVesselName" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Owner</label>
                                <input type="text" id="editOwner" class="form-control" disabled>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">IMO Number</label>
                                <input type="text" id="editIMO" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">MMSI Number</label>
                                <input type="text" id="editMMSI" class="form-control" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Call Sign</label>
                                <input type="text" id="editCallSign" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Vessel Type</label>
                                <select id="editVesselType" class="form-select" required>
                                    <option value="Passenger Ship">Passenger Ship</option>
                                    <option value="Cargo Ship">Cargo Ship</option>
                                    <option value="Tanker">Tanker</option>
                                    <option value="Cruise Ship">Cruise Ship</option>
                                    <option value="Fishing Vessel">Fishing Vessel</option>
                                    <option value="Container Ship">Container Ship</option>
                                    <option value="Bulk Carrier">Bulk Carrier</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Flag State</label>
                                <input type="text" id="editFlagState" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Length Overall (m)</label>
                                <input type="number" id="editLength" class="form-control" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Gross Tonnage</label>
                                <input type="text" id="editTonnage" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Year Built</label>
                                <input type="number" id="editYearBuilt" class="form-control" min="1900" max="2025" required>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveEditBtn">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header text-white bg-primary">
                    <h5 class="modal-title" id="deleteModalLabel">
                        Confirm Delete
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Are you sure you want to archive this vessel?</p>
                    <div class="alert alert-warning mb-0">
                        <strong>Vessel:</strong> <span id="deleteVesselName"></span><br>
                        <strong>IMO:</strong> <span id="deleteVesselIMO"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmDeleteBtn">
                        <i class="fas fa-archive"></i> Archive Vessel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
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


            /* -------------------------
                GLOBAL VARIABLES
            ------------------------- */
            let currentRow = null;
            let deleteVesselId = null;
            const modalElement = document.getElementById('editModal');
            const editModal = new bootstrap.Modal(modalElement);
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));


            /* ==========================================================
                TOAST FUNCTION
            ========================================================== */
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


            /* ==========================================================
                OPEN DELETE MODAL
            ========================================================== */
            document.getElementById('vesselsTableBody').addEventListener('click', function(e) {
                const deleteBtn = e.target.closest('.delete-btn');

                if (deleteBtn) {
                    const row = deleteBtn.closest('tr');
                    deleteVesselId = row.dataset.id;

                    const vesselName = row.cells[0].textContent;
                    const imoNumber = row.cells[2].textContent;

                    document.getElementById('deleteVesselName').textContent = vesselName;
                    document.getElementById('deleteVesselIMO').textContent = imoNumber;

                    deleteModal.show();
                }
            });


            /* ==========================================================
                CONFIRM DELETE VESSEL (Soft Delete / Archive)
            ========================================================== */
            document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
                if (!deleteVesselId) return;

                const submitBtn = this;
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Archiving...';

                fetch(`/admin/vessels/${deleteVesselId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            // Remove row from table
                            const row = document.querySelector(`tr[data-id="${deleteVesselId}"]`);
                            if (row) row.remove();

                            deleteModal.hide();
                            showToast('Vessel archived successfully!', 'success');
                            deleteVesselId = null;
                        } else {
                            showToast('Failed to archive vessel.', 'error');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        showToast('Request failed: ' + err.message, 'error');
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    });
            });


            /* ==========================================================
                OPEN EDIT MODAL
            ========================================================== */
            document.getElementById('vesselsTableBody').addEventListener('click', function(e) {
                const editBtn = e.target.closest('.edit-btn');

                if (editBtn) {
                    currentRow = editBtn.closest('tr');
                    const cells = currentRow.cells;

                    document.getElementById('editVesselId').value = currentRow.dataset.id;
                    document.getElementById('editVesselName').value = cells[0].textContent.trim();
                    document.getElementById('editOwner').value = cells[1].textContent.trim();
                    document.getElementById('editIMO').value = cells[2].textContent.trim();
                    document.getElementById('editMMSI').value = cells[3].textContent.trim();
                    document.getElementById('editCallSign').value = cells[4].textContent.trim();
                    document.getElementById('editVesselType').value = cells[5].textContent.trim();
                    document.getElementById('editFlagState').value = cells[6].textContent.trim();
                    document.getElementById('editLength').value = cells[7].textContent.trim();
                    document.getElementById('editTonnage').value = cells[8].textContent.trim();
                    document.getElementById('editYearBuilt').value = cells[9].textContent.trim();

                    editModal.show();
                }
            });


            /* ==========================================================
                SAVE EDIT (PUT REQUEST)
            ========================================================== */
            document.getElementById('saveEditBtn').addEventListener('click', function() {
                if (!currentRow) return;

                const vesselId = document.getElementById('editVesselId').value;

                const payload = {
                    name: document.getElementById('editVesselName').value.trim(),
                    imo: document.getElementById('editIMO').value.trim(),
                    mmsi: document.getElementById('editMMSI').value.trim(),
                    call_sign: document.getElementById('editCallSign').value.trim(),
                    type: document.getElementById('editVesselType').value.trim(),
                    flag: document.getElementById('editFlagState').value.trim(),
                    LoA: document.getElementById('editLength').value.trim(),
                    gross_tonnage: document.getElementById('editTonnage').value.trim(),
                    year_built: document.getElementById('editYearBuilt').value.trim()
                };

                // VALIDATION
                if (Object.values(payload).some(val => val === "")) {
                    showToast('Please fill in all fields.', 'warning');
                    return;
                }

                const submitBtn = this;
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

                fetch(`/admin/vessels/${vesselId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            // Update table visually
                            const cells = currentRow.cells;
                            cells[0].textContent = payload.name;
                            // Owner stays the same
                            cells[2].textContent = payload.imo;
                            cells[3].textContent = payload.mmsi;
                            cells[4].textContent = payload.call_sign;
                            cells[5].textContent = payload.type;
                            cells[6].textContent = payload.flag;
                            cells[7].textContent = payload.LoA;
                            cells[8].textContent = payload.gross_tonnage;
                            cells[9].textContent = payload.year_built;

                            showToast('Vessel information updated successfully!', 'success');
                            editModal.hide();
                        } else {
                            showToast('Failed to update vessel.', 'error');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        showToast('Request failed: ' + err.message, 'error');
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    });
            });


            /* ==========================================================
                RESET FORM WHEN MODAL CLOSED
            ========================================================== */
            modalElement.addEventListener('hidden.bs.modal', () => {
                currentRow = null;
                document.getElementById('editForm').reset();
            });

            document.getElementById('deleteModal').addEventListener('hidden.bs.modal', () => {
                deleteVesselId = null;
            });

        });
    </script>
</body>

</html>