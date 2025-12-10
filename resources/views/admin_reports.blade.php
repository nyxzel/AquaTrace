<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reports | AquaTrace</title>
    <link href="{{ asset('css/adminReports_style.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <!-- Sidebar -->
    <aside class="adminbar" id="sidebar">
        <div class="adminbar-title">
            <a href="#" class="navbar-brand">
                <img src="{{ asset('images/AQUATRACELOGO.png') }}" alt="AquaTrace Logo" class="logo-img">
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
            <a href="{{ route('admin.reports.index') }}" class="adminnav active" data-label="Reports">
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

    <!-- MAIN BODY -->
    <div class="admin-body">
        <div class="container-fluid">
            <div class="row g-4 justify-content-center">

                <!-- Bar Chart -->
                <div class="col-lg-6 col-md-10">
                    <div class="card p-4 shadow-sm rounded-4 border-0">
                        <h5 class="fw-bold text-center mb-4"><i class="bi bi-bar-chart-line me-2"></i>MONTHLY INCIDENT REPORTS</h5>

                        <div id="barChart" class="bar-chart"></div>

                        <div class="bar-labels mt-3 text-center">
                            <span>Jan</span><span>Feb</span><span>Mar</span><span>Apr</span>
                            <span>May</span><span>Jun</span><span>Jul</span><span>Aug</span>
                            <span>Sep</span><span>Oct</span><span>Nov</span><span>Dec</span>
                        </div>
                    </div>
                </div>

                <!-- Donut Chart -->
                <div class="col-lg-6 col-md-10">
                    <div class="card p-4 shadow-sm rounded-4 border-0 text-center">
                        <h5 class="fw-bold mb-4"><i class="bi bi-pie-chart me-2"></i>VESSEL TYPES DISTRIBUTION</h5>

                        <div class="donut-chart mx-auto mb-4">
                            <div class="hole"></div>
                        </div>

                        <div class="legend d-flex justify-content-center flex-wrap gap-3">
                            <div><span class="dot blue"></span> Cargo Ships</div>
                            <div><span class="dot darkblue"></span> Tankers</div>
                            <div><span class="dot lightblue"></span> Fishing Vessels</div>
                            <div><span class="dot black"></span> Passenger Ships</div>
                            <div><span class="dot skyblue"></span> Other</div>
                        </div>
                    </div>
                </div>
                <br><br>

                <!-- Reports Table -->
                <div class="col-12">
                    <div class="card shadow-sm p-3">
                        <h5 class="mb-3 text-primary fw-bold text-center">Recent Maritime Incident Reports</h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="bg-dark text-white">
                                    <tr>
                                        <th scope="col">DATE</th>
                                        <th scope="col">VESSEL NAME</th>
                                        <th scope="col">OWNER/USER</th>
                                        <th scope="col">PORT LOCATION</th>
                                        <th scope="col">INCIDENT TYPE</th>
                                        <th scope="col">DESCRIPTION</th>
                                        <th scope="col">SEVERITY</th>
                                        <th scope="col">STATUS</th>
                                        <th scope="col">ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white" id="reportsTableBody">
                                    @forelse ($reports as $report)
                                    <tr data-report-id="{{ $report->report_id }}">
                                        <td>{{ \Carbon\Carbon::parse($report->incident_date)->format('M d, Y') }}</td>
                                        <td>{{ $report->vessel->name ?? '-' }}</td>
                                        <td>{{ $report->vessel->owner->user->username ?? '-' }}</td>
                                        <td>{{ $report->port->name ?? '-' }}</td>
                                        <td>{{ $report->report_type }}</td>
                                        <td>
                                            <span class="description-preview" title="{{ $report->description ?? 'No description' }}">
                                                {{ Str::limit($report->description ?? 'No description', 50) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge 
                                            {{ $report->severity == 'LOW' ? 'bg-secondary' : ($report->severity == 'MEDIUM' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                                {{ $report->severity }}</span>
                                        </td>
                                        <td>
                                            <span class="badge 
                                            {{ $report->status == 'Resolved' ? 'bg-success' : ($report->status == 'Under Investigation' ? 'bg-info text-dark' : 'bg-secondary') }}">
                                                {{ $report->status }}</span>
                                        </td>
                                        <td>
                                            <button class='btn btn-sm btn-outline-primary edit-btn' title='Edit'><i class='fas fa-edit'></i></button>
                                            <button class='btn btn-sm btn-outline-primary delete-btn' title='Delete'><i class='fas fa-trash'></i></button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No reports found</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 rounded-3">
                <div class="modal-header text-white" style="background-color: #06326a;">
                    <h5 class="modal-title" id="editModalLabel">Edit Report</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <input type="hidden" id="editReportId">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Date</label>
                            <input type="text" id="editDate" class="form-control" readonly>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Vessel Name</label>
                                <input type="text" id="editVessel" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Owner/User</label>
                                <input type="text" id="editOwner" class="form-control" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Port Location</label>
                                <input type="text" id="editPort" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Incident Type</label>
                                <input type="text" id="editIncident" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Description</label>
                            <textarea id="editDescription" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Severity</label>
                                <select id="editSeverity" class="form-select" required>
                                    <option value="LOW">LOW</option>
                                    <option value="MEDIUM">MEDIUM</option>
                                    <option value="HIGH">HIGH</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Status</label>
                                <select id="editStatus" class="form-select" required>
                                    <option value="Pending">Pending</option>
                                    <option value="Resolved">Resolved</option>
                                    <option value="Under Investigation">Under Investigation</option>
                                    <option value="Closed">Closed</option>
                                </select>
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
            <div class="modal-content border-0 rounded-3">
                <div class="modal-header text-white" style="background-color: #06326a;">
                    <h5 class="modal-title" id="deleteModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i>Confirm Delete
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Are you sure you want to delete this report?</p>
                    <div class="alert alert-warning mb-0">
                        <strong>Vessel:</strong> <span id="deleteVesselName"></span><br>
                        <strong>Date:</strong> <span id="deleteReportDate"></span><br>
                        <strong>Type:</strong> <span id="deleteIncidentType"></span>
                    </div>
                    <input type="hidden" id="deleteReportId">
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmDeleteBtn">
                        <i class="fas fa-trash me-2"></i>Delete Report
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
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


        const monthlyReports = @json($monthly_data);
        const vesselDistribution = @json(array_values($vessel_types_mapped));
        const colors = ["#3b82f6", "#2563eb", "#60a5fa", "#000000", "#93c5fd"];
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

        // ========== BAR CHART RENDERING ==========
        document.addEventListener("DOMContentLoaded", () => {
            const container = document.getElementById("barChart");
            container.innerHTML = "";

            const maxReports = Math.max(...monthlyReports, 1);

            monthlyReports.forEach((count, index) => {
                const heightPercent = maxReports === 0 ? 0 : (count / maxReports) * 100;

                const bar = document.createElement("div");
                bar.className = "bar";
                bar.style.height = heightPercent + "%";
                bar.setAttribute("data-count", count);
                bar.title = count + " report" + (count !== 1 ? "s" : "") + " in " + getMonthName(index);

                container.appendChild(bar);
            });

            function getMonthName(index) {
                const months = ["January", "February", "March", "April", "May", "June",
                    "July", "August", "September", "October", "November", "December"
                ];
                return months[index];
            }
        });

        // ========== DONUT CHART RENDERING ==========
        document.addEventListener("DOMContentLoaded", () => {
            const donut = document.querySelector(".donut-chart");
            const total = vesselDistribution.reduce((a, b) => a + b, 0);

            if (total === 0) {
                donut.style.background = "#e5e7eb";
                return;
            }

            let start = 0;
            const segments = vesselDistribution.map((value, i) => {
                const percentage = (value / total) * 360;
                const end = start + percentage;
                const segment = `${colors[i]} ${start}deg ${end}deg`;
                start = end;
                return segment;
            });

            donut.style.background = `conic-gradient(${segments.join(", ")})`;
        });

        // ========== EDIT AND DELETE FUNCTIONALITY ==========
        document.addEventListener('DOMContentLoaded', () => {
            let currentRow = null;
            let editModal = null;
            let deleteModal = null;

            const modalElement = document.getElementById('editModal');
            const deleteModalElement = document.getElementById('deleteModal');
            editModal = new bootstrap.Modal(modalElement);
            deleteModal = new bootstrap.Modal(deleteModalElement);

            // DELETE/ARCHIVE FUNCTION - OPEN DELETE MODAL
            document.getElementById('reportsTableBody').addEventListener('click', function(e) {
                const deleteBtn = e.target.closest('.delete-btn');

                if (deleteBtn) {
                    const row = deleteBtn.closest('tr');
                    const vesselName = row.cells[1].textContent;
                    const reportDate = row.cells[0].textContent;
                    const incidentType = row.cells[4].textContent;
                    const reportId = row.dataset.reportId;

                    // Populate delete modal
                    document.getElementById('deleteVesselName').textContent = vesselName;
                    document.getElementById('deleteReportDate').textContent = reportDate;
                    document.getElementById('deleteIncidentType').textContent = incidentType;
                    document.getElementById('deleteReportId').value = reportId;

                    // Store row reference for deletion
                    deleteModal._rowToDelete = row;

                    // Show modal
                    deleteModal.show();
                }
            });

            // CONFIRM DELETE BUTTON
            document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
                const reportId = document.getElementById('deleteReportId').value;
                const row = deleteModal._rowToDelete;

                if (!reportId || !row) return;

                const formData = new FormData();
                formData.append('report_id', reportId);
                formData.append('_token', csrfToken);

                // Disable button during request
                this.disabled = true;
                this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Deleting...';

                fetch('{{ route("admin.reports.archive") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            row.remove();
                            deleteModal.hide();
                            showToast('Report deleted successfully!', 'success');
                        } else {
                            showToast('Error: ' + (data.message || 'Unknown error'), 'error');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        showToast('Error deleting report', 'error');
                    })
                    .finally(() => {
                        // Re-enable button
                        this.disabled = false;
                        this.innerHTML = '<i class="fas fa-trash me-2"></i>Delete Report';
                    });
            });

            // EDIT FUNCTION - OPEN MODAL
            document.getElementById('reportsTableBody').addEventListener('click', function(e) {
                const editBtn = e.target.closest('.edit-btn');

                if (editBtn) {
                    currentRow = editBtn.closest('tr');
                    const cells = currentRow.cells;

                    document.getElementById('editReportId').value = currentRow.dataset.reportId;
                    document.getElementById('editDate').value = cells[0].textContent.trim();
                    document.getElementById('editVessel').value = cells[1].textContent.trim();
                    document.getElementById('editOwner').value = cells[2].textContent.trim();
                    document.getElementById('editPort').value = cells[3].textContent.trim();
                    document.getElementById('editIncident').value = cells[4].textContent.trim();
                    document.getElementById('editDescription').value = cells[5].querySelector('.description-preview').getAttribute('title');
                    document.getElementById('editSeverity').value = cells[6].querySelector('.badge').textContent.trim();
                    document.getElementById('editStatus').value = cells[7].querySelector('.badge').textContent.trim();

                    editModal.show();
                }
            });

            // SAVE CHANGES
            document.getElementById('saveEditBtn').addEventListener('click', function() {
                if (!currentRow) return;

                const vessel = document.getElementById('editVessel').value.trim();
                const owner = document.getElementById('editOwner').value.trim();
                const port = document.getElementById('editPort').value.trim();
                const incident = document.getElementById('editIncident').value.trim();
                const description = document.getElementById('editDescription').value.trim();
                const severity = document.getElementById('editSeverity').value;
                const status = document.getElementById('editStatus').value;
                const reportId = document.getElementById('editReportId').value;

                if (!vessel || !owner || !port || !incident || !description) {
                    showToast('Please fill in all required fields', 'warning');
                    return;
                }

                const formData = new FormData();
                formData.append('report_id', reportId);
                formData.append('vessel_name', vessel);
                formData.append('owner_username', owner);
                formData.append('port_name', port);
                formData.append('incident_type', incident);
                formData.append('description', description);
                formData.append('severity', severity);
                formData.append('status', status);
                formData.append('_token', csrfToken);

                fetch('{{ route("admin.reports.update") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            const cells = currentRow.cells;
                            cells[1].textContent = vessel;
                            cells[2].textContent = owner;
                            cells[3].textContent = port;
                            cells[4].textContent = incident;

                            // Update description with truncation
                            const descPreview = cells[5].querySelector('.description-preview');
                            descPreview.textContent = description.length > 50 ? description.substring(0, 50) + '...' : description;
                            descPreview.setAttribute('title', description);

                            function getSeverityClass(sev) {
                                switch (sev) {
                                    case 'LOW':
                                        return 'bg-secondary';
                                    case 'MEDIUM':
                                        return 'bg-warning text-dark';
                                    case 'HIGH':
                                        return 'bg-danger';
                                    default:
                                        return 'bg-secondary';
                                }
                            }

                            function getStatusClass(stat) {
                                switch (stat) {
                                    case 'Resolved':
                                        return 'bg-success';
                                    case 'Under Investigation':
                                        return 'bg-info text-dark';
                                    case 'Closed':
                                        return 'bg-secondary';
                                    case 'Pending':
                                        return 'bg-warning text-dark';
                                    default:
                                        return 'bg-secondary';
                                }
                            }

                            cells[6].innerHTML = `<span class="badge ${getSeverityClass(severity)}">${severity}</span>`;
                            cells[7].innerHTML = `<span class="badge ${getStatusClass(status)}">${status}</span>`;

                            editModal.hide();
                            showToast('Report updated successfully!', 'success');
                        } else {
                            showToast('Error: ' + (data.message || 'Unknown error'), 'error');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        showToast('Error updating report', 'error');
                    });
            });

            // Reset form when modal is hidden
            modalElement.addEventListener('hidden.bs.modal', function() {
                currentRow = null;
                document.getElementById('editForm').reset();
            });
        });
    </script>
</body>

</html>