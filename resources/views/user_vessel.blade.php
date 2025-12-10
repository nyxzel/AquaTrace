<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AquaTrace | My Vessels</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- AOS CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/vesseluser_style.css') }}">

    <style>
        .pending-alert {
            background: #d4ecffff;
            border-left: 4px solid #4985ffff;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .rejected-alert {
            background: #f8d7da;
            border-left: 4px solid #dc3545;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
    </style>
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
                    <li class="nav-item"><a class="nav-link active" href="{{ route('home') }}">HOME</a></li>
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

    <!-- PAGE HEADER -->
    <div class="page-header" data-aos="fade-up" data-aos-duration="900">
        <div class="container text-center">
            <h1><i class="fas fa-ship"></i> My Vessels</h1>
            <p>Track and manage all your registered vessels in real-time</p>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="container pb-5">

        <!-- Pending Requests Alert -->
        @if($pendingRequests->isNotEmpty())
        <div class="pending-alert" data-aos="fade-up">
            <h5><i class="fas fa-clock"></i> Pending Vessel Registrations ({{ $pendingRequests->count() }})</h5>
            <p class="mb-2">The following vessels are awaiting admin approval:</p>
            <ul class="mb-0">
                @foreach($pendingRequests as $request)
                <li><strong>{{ $request->vessel_name }}</strong> ({{ $request->vessel_type }}) - Submitted: {{ $request->submitted_at->format('M d, Y') }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Rejected Requests Alert -->
        @if($rejectedRequests->isNotEmpty())
        <div class="rejected-alert" data-aos="fade-up">
            <h5><i class="fas fa-times-circle"></i> Recently Rejected Registrations</h5>
            @foreach($rejectedRequests as $request)
            <div class="mb-2">
                <strong>{{ $request->vessel_name }}</strong> - Rejected: {{ $request->rejected_at->format('M d, Y') }}
                <br><small class="text-muted">Reason: {{ $request->rejection_reason }}</small>
            </div>
            @endforeach
        </div>
        @endif

        <!-- Statistics -->
        <div class="stats-container" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
            <div class="stat-card">
                <div class="stat-number" id="totalVessels">{{ $statistics['total'] }}</div>
                <div class="stat-label">Total Vessels</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="activeVessels">{{ $statistics['active'] }}</div>
                <div class="stat-label">Active</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="dockedVessels">{{ $statistics['docked'] }}</div>
                <div class="stat-label">Docked</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="transitVessels">{{ $statistics['transit'] }}</div>
                <div class="stat-label">In Transit</div>
            </div>
        </div>

        <!-- Map Section -->
        <div class="map-container my-4" data-aos="fade-up" data-aos-duration="1000">
            <h2 class="text-center mb-3"><i class="fas fa-map-marker-alt"></i> Live Vessel Tracking</h2>
            <div id="map"></div>
            <div class="map-legend">
                <div class="legend-item">
                    <i class="fas fa-anchor" style="color: #007bff;"></i> Ports
                </div>
                <div class="legend-item">
                    <i class="fas fa-ship" style="color: #89cff3ff;"></i> Active
                </div>
                <div class="legend-item">
                    <i class="fas fa-ship" style="color: #007bff;"></i> Docked
                </div>
                <div class="legend-item">
                    <i class="fas fa-ship" style="color: #001f47ff;"></i> In Transit
                </div>
            </div>
        </div>
        <div id="vesselsContainer" class="mt-4">
            @if($vessels->isEmpty())
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <h4>No approved vessels yet</h4>
                @if($pendingRequests->isNotEmpty())
                <p>You have {{ $pendingRequests->count() }} vessel(s) awaiting approval</p>
                @else
                <p>Register your first vessel to start tracking!</p>
                @endif
            </div>
            @else
            @foreach($vessels as $vessel)
            <div class="vessel-card" data-aos="fade-up">
                <div class="vessel-header">
                    <div class="vessel-icon"><i class="fas fa-ship"></i></div>
                    <div class="vessel-title">
                        <h3>{{ $vessel->name }}</h3>
                        <span class="vessel-type">{{ $vessel->type }}</span>
                    </div>
                    @php
                    $status = 'Docked';
                    if ($vessel->latestPosition && $vessel->latestPosition->speed > 15) {
                    $status = 'Active';
                    } elseif ($vessel->latestPosition && $vessel->latestPosition->speed > 0) {
                    $status = 'In Transit';
                    }
                    @endphp
                    <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $status)) }}">{{ $status }}</span>
                </div>
                <div class="vessel-details">
                    <div class="detail-item">
                        <i class="fas fa-id-card"></i>
                        <div class="detail-content">
                            <div class="detail-label">IMO Number</div>
                            <div class="detail-value">{{ $vessel->imo }}</div>
                        </div>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div class="detail-content">
                            <div class="detail-label">Current Location</div>
                            <div class="detail-value">
                                @if($vessel->latestPosition)
                                {{ number_format($vessel->latestPosition->latitude, 6) }}, {{ number_format($vessel->latestPosition->longitude, 6) }}
                                @else
                                Location not available
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($vessel->activeVoyage)
                    <div class="detail-item">
                        <i class="fas fa-route"></i>
                        <div class="detail-content">
                            <div class="detail-label">Current Voyage</div>
                            <div class="detail-value">
                                {{ $vessel->activeVoyage->departurePort->name ?? 'Unknown' }}
                                <i class="fas fa-arrow-right"></i>
                                {{ $vessel->activeVoyage->arrivalPort->name ?? 'Unknown' }}
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="detail-item">
                        <i class="fas fa-user"></i>
                        <div class="detail-content">
                            <div class="detail-label">Owner</div>
                            <div class="detail-value">
                                @if($vessel->owner)
                                {{ $vessel->owner->first_name }}
                                @if($vessel->owner->middle_name){{ $vessel->owner->middle_name }} @endif
                                {{ $vessel->owner->last_name }}
                                @else
                                N/A
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-anchor"></i>
                        <div class="detail-content">
                            <div class="detail-label">Home Port</div>
                            <div class="detail-value">
                                @if($vessel->owner && $vessel->owner->address)
                                {{ $vessel->owner->address->city ?? 'N/A' }}
                                @else
                                N/A
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-flag"></i>
                        <div class="detail-content">
                            <div class="detail-label">Flag State</div>
                            <div class="detail-value">{{ $vessel->flag }}</div>
                        </div>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-ruler"></i>
                        <div class="detail-content">
                            <div class="detail-label">Length (LOA)</div>
                            <div class="detail-value">{{ $vessel->LoA }} meters</div>
                        </div>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-weight"></i>
                        <div class="detail-content">
                            <div class="detail-label">Gross Tonnage</div>
                            <div class="detail-value">{{ number_format($vessel->gross_tonnage) }} tons</div>
                        </div>
                    </div>
                    @if($vessel->latestPosition)
                    <div class="detail-item">
                        <i class="fas fa-tachometer-alt"></i>
                        <div class="detail-content">
                            <div class="detail-label">Speed</div>
                            <div class="detail-value">{{ $vessel->latestPosition->speed }} knots</div>
                        </div>
                    </div>
                    @endif

                    <!-- Last Updated from Voyage table -->
                    <div class="detail-item">
                        <i class="fas fa-clock"></i>
                        <div class="detail-content">
                            <div class="detail-label">Last Updated</div>
                            <div class="detail-value">
                                @php
                                $lastUpdate = null;
                                if ($vessel->latestVoyage && $vessel->latestVoyage->updated_at) {
                                $lastUpdate = $vessel->latestVoyage->updated_at;
                                } elseif ($vessel->latestPosition && $vessel->latestPosition->recorded_at) {
                                $lastUpdate = $vessel->latestPosition->recorded_at;
                                }
                                @endphp

                                @if($lastUpdate)
                                {{ $lastUpdate->format('M d, Y h:i A') }}
                                @else
                                Not available
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="vessel-actions mt-3">
                    <button class="btn-update" onclick="openUpdateModal({{ $vessel->vessel_id }}, '{{ $vessel->name }}', '{{ $vessel->type }}')">
                        <i class="fas fa-edit"></i> Update Location
                    </button>
                </div>
            </div>
            @endforeach
            @endif
        </div>


        <!-- Update Vessel Modal -->
        <div class="custom-modal-overlay" id="updateModal">
            <div class="custom-modal-content update-modal">
                <div class="modal-header-custom">
                    <h3><i class="fas fa-edit"></i> Update Vessel Location</h3>
                    <button class="close-modal-btn" id="closeUpdateModal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form id="updateVesselForm">
                    @csrf
                    <input type="hidden" id="vesselId" name="vessel_id">
                    <div class="modal-body-custom">
                        <div class="update-vessel-info">
                            <div class="vessel-icon-modal">
                                <i class="fas fa-ship"></i>
                            </div>
                            <div>
                                <h4 id="modalVesselName">Vessel Name</h4>
                                <span id="modalVesselType" class="vessel-type-badge">Type</span>
                            </div>
                        </div>

                        <div class="form-section-modal">
                            <label class="form-label-modal">
                                <i class="fas fa-map-marker-alt"></i> Current Location/Port *
                            </label>
                            <select class="form-control-modal" id="currentLocation" name="current_location" required>
                                <option value="">Select current location</option>
                                @foreach($ports as $port)
                                <option value="{{ $port['latitude'] }},{{ $port['longitude'] }}">
                                    {{ $port['name'] }} ({{ number_format($port['latitude'], 4) }}, {{ number_format($port['longitude'], 4) }})
                                </option>
                                @endforeach
                                <option value="7.05,125.7">En Route - Davao Gulf</option>
                                <option value="7.15,125.8">En Route - Samal Waters</option>
                                <option value="6.95,126.0">En Route - Mati Coast</option>
                            </select>
                            <small class="text-muted">Select where the vessel currently is</small>
                        </div>

                        <div class="form-section-modal">
                            <label class="form-label-modal">
                                <i class="fas fa-flag-checkered"></i> Destination Port
                            </label>
                            <select class="form-control-modal" id="destination" name="destination">
                                <option value="">Select destination (optional)</option>
                                @foreach($ports as $port)
                                <option value="{{ $port['latitude'] }},{{ $port['longitude'] }}">
                                    {{ $port['name'] }} ({{ number_format($port['latitude'], 4) }}, {{ number_format($port['longitude'], 4) }})
                                </option>
                                @endforeach
                                <option value="7.05,125.7">Davao Gulf Area</option>
                                <option value="7.15,125.8">Samal Waters</option>
                                <option value="6.95,126.0">Mati Coast</option>
                            </select>
                            <small class="text-muted">Where is the vessel heading? (required for In Transit status)</small>
                        </div>

                        <div class="form-section-modal">
                            <label class="form-label-modal">
                                <i class="fas fa-clipboard-list"></i> Status *
                            </label>
                            <select class="form-control-modal" id="updateStatus" name="status" required>
                                <option value="">Select status</option>
                                <option value="Active">Active - Moving at high speed</option>
                                <option value="Docked">Docked - Stationary at port</option>
                                <option value="In Transit">In Transit - Moving to destination</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-section-modal">
                                    <label class="form-label-modal">
                                        <i class="fas fa-calendar-times"></i> Departure Date & Time
                                    </label>
                                    <input type="datetime-local" class="form-control-modal" id="updateDeparture" name="departure">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-section-modal">
                                    <label class="form-label-modal">
                                        <i class="fas fa-calendar-check"></i> Expected Arrival
                                    </label>
                                    <input type="datetime-local" class="form-control-modal" id="updateArrival" name="arrival">
                                </div>
                            </div>
                        </div>

                        <div class="form-section-modal">
                            <label class="form-label-modal">
                                <i class="fas fa-sticky-note"></i> Additional Notes
                            </label>
                            <textarea class="form-control-modal" id="updateNotes" name="notes" rows="3"
                                placeholder="Add any relevant information about the vessel's current status..."></textarea>
                        </div>
                    </div>

                    <div class="modal-footer-custom">
                        <button type="button" class="btn-cancel-modal" id="cancelUpdate">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="btn-submit-modal" id="submitUpdateBtn">
                            <i class="fas fa-save"></i> Update Location
                        </button>
                    </div>
                </form>
            </div>
        </div>


        <!-- Success Modal -->
        <div class="custom-modal-overlay" id="successModal">
            <div class="custom-modal-content">
                <div class="custom-modal-body">
                    <div class="success-icon-modal mb-4">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <h3 class="mb-3" id="modalTitle">Update Successful!</h3>
                    <p class="text-muted mb-4" id="modalMessage">Vessel location has been updated successfully.</p>
                    <button type="button" class="btn-modal-close" id="closeSuccessModal">
                        <i class="bi bi-check-lg"></i> Close
                    </button>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="footer" data-aos="fade-up" data-aos-duration="1000">
            <div class="container">
                <div class="footer-top">
                    <nav class="footer-nav" data-aos="fade-up" data-aos-delay="200">
                        <ul>
                            <li><a href="{{ route('user.vessels') }}">VESSELS</a></li>
                            <li><a href="{{ route('user.ports') }}">PORTS</a></li>
                            <li><a href="{{ route('user.reports') }}">ANALYTICS</a></li>
                            <li><a href="{{ route('user.vessels') }}">TRACKING</a></li>
                            <li><a href="{{ route('user.news') }}">NEWS</a></li>
                        </ul>
                    </nav>

                    <div class="social-links" data-aos="fade-up" data-aos-delay="400">
                        <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                    </div>

                    <div class="footer-links" data-aos="fade-up" data-aos-delay="600">
                        <a href="{{ route('user.contact') }}">CONTACT</a>
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
                        REAL-TIME VESSEL TRACKING, CRAFTED WITH ⚓ IN THE MARITIME INDUSTRY<br>
                        ©<span class="footer-brand">AQUATRACE</span> | ALL RIGHTS RESERVED
                    </p>
                </div>
            </div>
        </footer>

        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
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



        <script>
            const vesselsData = @json($vessels);
            const portsData = @json($ports);
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            let map;
            let markers = {};
            let currentEditingVessel = null;

            document.addEventListener("DOMContentLoaded", function() {
                AOS.init({
                    duration: 1000,
                    once: true,
                    offset: 100
                });

                initMap();
                initModalHandlers();
            });

            function initMap() {
                setTimeout(function() {
                    map = L.map('map').setView([7.0, 125.5], 9);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                    }).addTo(map);

                    const portIcon = L.divIcon({
                        className: 'custom-marker port-marker',
                        html: '<div style="background: rgba(255,255,255,0.9); border-radius: 50%; padding: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.3);"><i class="fas fa-anchor" style="font-size: 24px; color: #007bff;"></i></div>',
                        iconSize: [40, 40],
                        iconAnchor: [20, 20]
                    });

                    portsData.forEach(port => {
                        L.marker([port.latitude, port.longitude], {
                                icon: portIcon
                            })
                            .addTo(map)
                            .bindPopup(`<b>${port.name}</b>`);
                    });

                    updateMapMarkers();
                    map.invalidateSize();
                }, 200);
            }

            function updateMapMarkers() {
                Object.values(markers).forEach(marker => map.removeLayer(marker));
                markers = {};

                vesselsData.forEach(vessel => {
                    // Change this line - use camelCase
                    if (!vessel.latest_position && !vessel.latestPosition) {
                        console.log('No position for vessel:', vessel.name);
                        return;
                    }

                    // Try both formats for compatibility
                    const position = vessel.latest_position || vessel.latestPosition;

                    const coords = [position.latitude, position.longitude];
                    const speed = parseFloat(position.speed);
                    let status = 'Docked';
                    if (speed > 15) status = 'Active';
                    else if (speed > 0) status = 'In Transit';

                    const color = getStatusColor(status);

                    const vesselIcon = L.divIcon({
                        className: 'custom-marker vessel-marker',
                        html: `<div style="background: rgba(255,255,255,0.9); border-radius: 50%; padding: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.3);"><i class="fas fa-ship" style="font-size: 20px; color: ${color};"></i></div>`,
                        iconSize: [35, 35],
                        iconAnchor: [17.5, 17.5]
                    });

                    const marker = L.marker(coords, {
                            icon: vesselIcon
                        })
                        .addTo(map)
                        .bindPopup(`
                <div style="text-align: center;">
                    <b>${vessel.name}</b><br> ${status}</div>
            `);

                    markers[vessel.vessel_id] = marker;
                });
            }

            function getStatusColor(status) {
                switch (status) {
                    case 'Active':
                        return '#89cff3ff';
                    case 'Docked':
                        return '#007bff';
                    case 'In Transit':
                        return '#001f47ff';
                    default:
                        return '#6c757d';
                }
            }

            function initModalHandlers() {
                const updateModal = document.getElementById('updateModal');
                const successModal = document.getElementById('successModal');
                const updateForm = document.getElementById('updateVesselForm');

                document.getElementById('closeUpdateModal').onclick = closeUpdateModal;
                document.getElementById('cancelUpdate').onclick = closeUpdateModal;
                document.getElementById('closeSuccessModal').onclick = closeSuccessModal;

                updateModal.onclick = (e) => {
                    if (e.target === updateModal) closeUpdateModal();
                };

                successModal.onclick = (e) => {
                    if (e.target === successModal) closeSuccessModal();
                };

                updateForm.onsubmit = handleUpdateSubmit;
            }

            function openUpdateModal(vesselId, vesselName, vesselType) {
                currentEditingVessel = vesselId;

                document.getElementById('vesselId').value = vesselId;
                document.getElementById('modalVesselName').textContent = vesselName;
                document.getElementById('modalVesselType').textContent = vesselType;

                // Reset form
                document.getElementById('updateVesselForm').reset();
                document.getElementById('vesselId').value = vesselId;

                document.getElementById('updateModal').classList.add('show');
                document.body.style.overflow = 'hidden';
            }

            function closeUpdateModal() {
                document.getElementById('updateModal').classList.remove('show');
                document.body.style.overflow = '';
                currentEditingVessel = null;
                document.getElementById('updateVesselForm').reset();
            }

            function closeSuccessModal() {
                document.getElementById('successModal').classList.remove('show');
                document.body.style.overflow = '';

                // Force complete page reload bypassing ALL caches
                // Clear browser cache first
                if ('caches' in window) {
                    caches.keys().then(names => {
                        names.forEach(name => caches.delete(name));
                    });
                }

                // Reload with cache-busting and forced refresh
                const url = new URL(window.location.href);
                url.searchParams.set('t', Date.now()); // Cache buster
                window.location.replace(url.href); // Replace current page
            }

            function handleUpdateSubmit(e) {
                e.preventDefault();

                const submitBtn = document.getElementById('submitUpdateBtn');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
                submitBtn.disabled = true;

                const formData = new FormData(e.target);
                const vesselId = document.getElementById('vesselId').value;

                // Build JSON payload
                const payload = {
                    current_location: formData.get('current_location'),
                    destination: formData.get('destination'),
                    status: formData.get('status'),
                    arrival: formData.get('arrival'),
                    departure: formData.get('departure'),
                    notes: formData.get('notes')
                };

                // Validation
                if (!payload.current_location) {
                    alert('Please select a current location');
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    return;
                }

                if (!payload.status) {
                    alert('Please select a status');
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    return;
                }

                // If In Transit, destination is required
                if (payload.status === 'In Transit' && !payload.destination) {
                    alert('Destination is required for "In Transit" status');
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    return;
                }

                console.log('Submitting update:', payload);
                console.log('Current Location:', payload.current_location);
                console.log('Destination:', payload.destination);
                console.log('Status:', payload.status);

                fetch(`/user/vessels/${vesselId}/update-location`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    })
                    .then(response => {
                        console.log('Response status:', response.status);
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Response data:', data);

                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;

                        if (data.success) {
                            // Show where the vessel was placed
                            if (data.data && data.data.position) {
                                console.log('Vessel placed at:', data.data.position.latitude, data.data.position.longitude);
                                console.log('Speed:', data.data.position.speed);
                                console.log('Status:', data.data.position.status);
                            }

                            closeUpdateModal();

                            let message = data.message || 'Vessel location has been updated successfully.';
                            if (data.data && data.data.voyage) {
                                message += `\n\nVoyage: ${data.data.voyage.departure_port} → ${data.data.voyage.arrival_port}`;
                                message += `\nStatus: ${data.data.voyage.status}`;
                            }
                            if (data.data && data.data.position) {
                                message += `\n\nNew Position: ${data.data.position.latitude}, ${data.data.position.longitude}`;
                                message += `\nSpeed: ${data.data.position.speed} knots`;
                            }

                            document.getElementById('modalTitle').textContent = 'Location Updated!';
                            document.getElementById('modalMessage').textContent = message;
                            document.getElementById('successModal').classList.add('show');
                        } else {
                            let errorMsg = 'Failed to update vessel location.';
                            if (data.errors) {
                                errorMsg += '\n\nErrors:\n';
                                Object.keys(data.errors).forEach(key => {
                                    errorMsg += `- ${data.errors[key].join(', ')}\n`;
                                });
                            } else if (data.error) {
                                errorMsg += '\n' + data.error;
                            }
                            alert(errorMsg);
                        }
                    })
                    .catch(error => {
                        console.error('Fetch Error:', error);
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                        alert('Network error: Failed to update vessel location. Please check your connection and try again.');
                    });
            }
        </script>
</body>

</html>