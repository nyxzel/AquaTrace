<template>
  <div id="admin-dashboard">
    <!-- Sidebar -->
    <aside class="adminbar" :class="{ collapsed: sidebarCollapsed, show: sidebarMobile }">
      <div class="adminbar-title">
        <a href="#" class="navbar-brand">
          <img src="/images/AQUATRACELOGO.png" alt="AquaTrace Logo" class="logo-img">
          <span class="logo-text">AQUATRACE</span>
        </a>
      </div>

      <nav>
        <a href="/admin/dashboard" class="adminnav active">
          <i class="fas fa-tachometer-alt"></i> <span class="sidebar-text">Dashboard</span>
        </a>
        <a href="/admin/ports" class="adminnav">
          <i class="fas fa-anchor"></i> <span class="sidebar-text">Ports</span>
        </a>
        <a href="/admin/users" class="adminnav">
          <i class="fas fa-user"></i> <span class="sidebar-text">Users</span>
        </a>
        <a href="/admin/vessels" class="adminnav">
          <i class="fas fa-ship"></i> <span class="sidebar-text">Vessels</span>
        </a>
        <a href="/admin/reports" class="adminnav">
          <i class="fas fa-chart-bar"></i> <span class="sidebar-text">Reports</span>
        </a>
        <a href="/admin/news" class="adminnav">
          <i class="fas fa-newspaper"></i> <span class="sidebar-text">News</span>
        </a>
      </nav>

      <div class="logout-btn">
        <a href="/login">
          <i class="fas fa-sign-out-alt"></i> <span class="sidebar-text">Logout</span>
        </a>
      </div>
    </aside>

    <!-- Topbar -->
    <header class="adminheader">
      <div class="d-flex align-items-center w-100">
        <button class="btn btn-outline-secondary me-2" @click="toggleSidebar">
          <i class="fas fa-bars"></i>
        </button>
        <h5 class="welcometext mb-0">Welcome, Admin!</h5>

        <!-- Notification Bell -->
        <div class="notif-container">
          <button id="notifBell" @click.stop="toggleNotifications">
            <i class="fas fa-bell"></i>
            <span v-if="notifications.length > 0" id="notifBadge">{{ notifications.length }}</span>
          </button>

          <!-- Notification Dropdown -->
          <div id="notifDropdown" :class="{ show: showNotifications }">
            <div class="notif-header">
              <h6>Notifications</h6>
              <button class="clear-all-btn" @click="openClearAllModal">Clear All</button>
            </div>
            <div id="notifList">
              <div v-if="notifications.length === 0" class="empty-state">
                <i class="fas fa-bell-slash"></i>
                <p>No new notifications</p>
              </div>
              <div v-else>
                <div v-for="notif in notifications" :key="notif.id" class="notif-item">
                  <div class="notif-icon"><i class="fas fa-user-plus"></i></div>
                  <div class="notif-content">
                    <div class="notif-user">{{ notif.user }}</div>
                    <div class="notif-message">Vessel: {{ notif.vessel_data.vessel_name }}</div>
                    <div class="notif-time">{{ notif.time }}</div>
                  </div>
                  <div class="notif-actions">
                    <button class="action-btn accept-btn" @click="acceptUser(notif.id)" title="Click to select position on map">
                      <i class="fas fa-check"></i>
                    </button>
                    <button class="action-btn decline-btn" @click="openDeclineModal(notif)" title="Reject registration">
                      <i class="fas fa-times"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </header>

    <!-- Toast -->
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 11000">
      <div v-if="toast.show" :class="['toast', 'align-items-center', 'border-0', toast.type, 'show']">
        <div class="d-flex">
          <div class="toast-body">
            <i :class="toastIcon"></i> {{ toast.message }}
          </div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" @click="toast.show = false"></button>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <main class="admin-body">
      <!-- Stats -->
      <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
          <a href="/admin/vessels" class="text-decoration-none">
            <div class="card admin-card shadow-sm text-center py-3">
              <div class="card-body">
                <i class="fas fa-ship fa-2x mb-2" style="color: #0b3a67;"></i>
                <div class="text-dark-emphasis small">Vessels</div>
                <h5>{{ vesselCount }}</h5>
              </div>
            </div>
          </a>
        </div>

        <div class="col-6 col-md-3">
          <a href="/admin/ports" class="text-decoration-none">
            <div class="card admin-card shadow-sm text-center py-3">
              <div class="card-body">
                <i class="fas fa-map-pin fa-2x mb-2" style="color: #0b3a67;"></i>
                <div class="text-dark-emphasis small">Ports</div>
                <h5>{{ portCount }}</h5>
              </div>
            </div>
          </a>
        </div>

        <div class="col-6 col-md-3">
          <a href="/admin/users" class="text-decoration-none">
            <div class="card admin-card shadow-sm text-center py-3">
              <div class="card-body">
                <i class="fas fa-users fa-2x mb-2" style="color: #0b3a67;"></i>
                <div class="text-dark-emphasis small">Users</div>
                <h5>{{ userCount }}</h5>
              </div>
            </div>
          </a>
        </div>

        <div class="col-6 col-md-3">
          <a href="/admin/reports" class="text-decoration-none">
            <div class="card admin-card shadow-sm text-center py-3">
              <div class="card-body">
                <i class="fas fa-file-lines fa-2x mb-2" style="color: #0b3a67;"></i>
                <div class="text-dark-emphasis small">Reports</div>
                <h5>{{ reportCount }}</h5>
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
          <div ref="mapContainer" id="map" style="height: 500px;"></div>
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
          <div v-if="vessels.length === 0" class="text-center text-muted py-4">
            <i class="fas fa-ship fa-3x mb-3"></i>
            <p>No vessels registered yet. Accept a registration request to add vessels.</p>
          </div>
          <div v-else>
            <div v-for="vessel in vessels" :key="vessel.vessel_id" class="card mb-3 vessel-card">
              <div class="card-body">
                <div class="row align-items-center">
                  <div class="col-md-8">
                    <h5 class="mb-2">
                      <i class="fas fa-ship text-primary"></i> {{ vessel.name }}
                    </h5>
                    <div class="row text-sm">
                      <div class="col-md-6">
                        <p class="mb-1"><strong>Type:</strong> {{ vessel.type || 'N/A' }}</p>
                        <p class="mb-1"><strong>Flag:</strong> {{ vessel.flag || 'N/A' }}</p>
                        <p class="mb-1"><strong>IMO:</strong> {{ vessel.imo || 'N/A' }}</p>
                      </div>
                      <div class="col-md-6">
                        <p class="mb-1"><strong>MMSI:</strong> {{ vessel.mmsi || 'N/A' }}</p>
                        <p class="mb-1"><strong>Call Sign:</strong> {{ vessel.call_sign || 'N/A' }}</p>
                        <p class="mb-1"><strong>Speed:</strong> {{ vessel.speed || 0 }} knots</p>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 text-end">
                    <button class="btn btn-sm btn-primary me-2" @click="viewVessel(vessel)">
                      <i class="fas fa-eye"></i> View
                    </button>
                    <button class="btn btn-sm btn-primary" @click="openDeleteModal(vessel)">
                      <i class="fas fa-trash"></i> Delete
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Add Vessel Modal -->
    <div class="modal fade" :class="{ show: showAddModal, 'd-block': showAddModal }" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header text-white" style="background-color: #06326a;">
            <h5 class="modal-title">Approve & Register Vessel</h5>
            <button type="button" class="btn-close btn-close-white" @click="closeAddModal"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="submitVessel">
              <div class="alert alert-info mb-3">
                <i class="fas fa-info-circle"></i> <strong>Registration Details:</strong><br>
                <strong>Owner:</strong> {{ formData.ownerName }}<br>
                <strong>Email:</strong> {{ formData.ownerEmail }}
              </div>

              <hr>

              <div class="row mb-3">
                <div class="col-md-6">
                  <label class="form-label">Vessel Name <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" v-model="formData.name" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Vessel Type <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" v-model="formData.type" placeholder="e.g., Cargo, Tanker, Passenger" required>
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-md-6">
                  <label class="form-label">IMO Number <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" v-model="formData.imo" placeholder="e.g., IMO1234567" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">MMSI <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" v-model="formData.mmsi" placeholder="e.g., 123456789" required>
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-md-6">
                  <label class="form-label">Call Sign <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" v-model="formData.call_sign" placeholder="e.g., ABCD" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Flag State <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" v-model="formData.flag" placeholder="e.g., Philippines" required>
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-md-6">
                  <label class="form-label">Length Overall (m) <span class="text-danger">*</span></label>
                  <input type="number" step="0.01" class="form-control" v-model="formData.LoA" placeholder="e.g., 150.50" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Gross Tonnage <span class="text-danger">*</span></label>
                  <input type="number" step="0.01" class="form-control" v-model="formData.gross_tonnage" placeholder="e.g., 5000.00" required>
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-md-6">
                  <label class="form-label">Year Built <span class="text-danger">*</span></label>
                  <input type="number" class="form-control" v-model="formData.year_built" min="1900" max="2100" placeholder="e.g., 2020" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Speed (knots)</label>
                  <input type="number" step="0.1" class="form-control" v-model="formData.speed" placeholder="e.g., 15.5">
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label"><i class="fas fa-clipboard"></i> Additional Notes</label>
                <textarea class="form-control" v-model="formData.additional_notes" rows="3" placeholder="Enter any additional information about this vessel..."></textarea>
                <small class="text-muted">Optional: Add any special notes, comments, or additional information about this vessel.</small>
              </div>

              <div class="alert alert-warning small mb-3">
                <i class="fas fa-map-marker-alt"></i> <strong>Click on the map to set vessel position</strong><br>
                <strong>Latitude:</strong> {{ formData.latitude || 'Not set' }}<br>
                <strong>Longitude:</strong> {{ formData.longitude || 'Not set' }}
              </div>

              <div class="text-end">
                <button type="button" class="btn btn-secondary" @click="closeAddModal">
                  <i class="fas fa-times"></i> Cancel
                </button>
                <button type="submit" class="btn text-white" style="background-color: #06326a;" :disabled="submitting">
                  <i class="fas fa-spinner fa-spin" v-if="submitting"></i>
                  <i class="fas fa-check-circle" v-else></i>
                  {{ submitting ? 'Approving...' : 'Approve & Register Vessel' }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div v-if="showAddModal" class="modal-backdrop fade show"></div>

    <!-- Delete Vessel Modal -->
    <div class="modal fade" :class="{ show: showDeleteModal, 'd-block': showDeleteModal }" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header text-white bg-primary">
            <h5 class="modal-title"><i class="fas fa-exclamation-triangle"></i> Confirm Delete</h5>
            <button type="button" class="btn-close btn-close-white" @click="closeDeleteModal"></button>
          </div>
          <div class="modal-body">
            <p class="mb-3">Are you sure you want to archive this vessel?</p>
            <div class="alert alert-warning mb-0">
              <strong>Vessel:</strong> {{ deleteVessel.name }}<br>
              <strong>IMO:</strong> {{ deleteVessel.imo }}<br>
              <strong>MMSI:</strong> {{ deleteVessel.mmsi }}
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="closeDeleteModal">Cancel</button>
            <button type="button" class="btn btn-primary" @click="confirmDelete" :disabled="deleting">
              <i class="fas fa-spinner fa-spin" v-if="deleting"></i>
              <i class="fas fa-archive" v-else></i>
              {{ deleting ? 'Deleting...' : 'Delete Vessel' }}
            </button>
          </div>
        </div>
      </div>
    </div>
    <div v-if="showDeleteModal" class="modal-backdrop fade show"></div>

    <!-- Decline Modal -->
    <div class="modal fade" :class="{ show: showDeclineModal, 'd-block': showDeclineModal }" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header text-white" style="background-color: #06326a;">
            <h5 class="modal-title"><i class="fas fa-times-circle"></i> Decline Registration</h5>
            <button type="button" class="btn-close btn-close-white" @click="closeDeclineModal"></button>
          </div>
          <div class="modal-body">
            <p class="mb-3">Are you sure you want to decline this vessel registration request?</p>
            <div class="alert alert-warning mb-3">
              <strong>Vessel:</strong> {{ declineData.vessel_name }}<br>
              <strong>Owner:</strong> {{ declineData.owner_name }}
            </div>
            <div class="mb-3">
              <label class="form-label fw-bold">Reason for Decline (Optional)</label>
              <textarea class="form-control" v-model="declineReason" rows="3" placeholder="Enter reason for declining this registration..."></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="closeDeclineModal">Cancel</button>
            <button type="button" class="btn btn-primary" @click="confirmDecline" :disabled="declining">
              <i class="fas fa-spinner fa-spin" v-if="declining"></i>
              <i class="fas fa-times" v-else></i>
              {{ declining ? 'Declining...' : 'Decline Request' }}
            </button>
          </div>
        </div>
      </div>
    </div>
    <div v-if="showDeclineModal" class="modal-backdrop fade show"></div>

    <!-- Clear All Modal -->
    <div class="modal fade" :class="{ show: showClearAllModal, 'd-block': showClearAllModal }" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header text-white" style="background-color: #06326a;">
            <h5 class="modal-title"><i class="fas fa-trash-alt"></i> Clear All Notifications</h5>
            <button type="button" class="btn-close btn-close-white" @click="closeClearAllModal"></button>
          </div>
          <div class="modal-body">
            <p class="mb-3">Are you sure you want to clear all pending registration requests?</p>
            <div class="alert alert-danger mb-0">
              <i class="fas fa-exclamation-triangle"></i> <strong>Warning:</strong> This will decline all {{ notifications.length }} pending request(s). This action cannot be undone.
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="closeClearAllModal">Cancel</button>
            <button type="button" class="btn btn-primary" @click="confirmClearAll" :disabled="clearingAll">
              <i class="fas fa-spinner fa-spin" v-if="clearingAll"></i>
              <i class="fas fa-trash-alt" v-else></i>
              {{ clearingAll ? 'Clearing...' : 'Clear All' }}
            </button>
          </div>
        </div>
      </div>
    </div>
    <div v-if="showClearAllModal" class="modal-backdrop fade show"></div>
  </div>
</template>

<script>
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

export default {
  name: 'AdminDashboard',
  props: {
    initialVesselCount: { type: Number, default: 0 },
    initialPortCount: { type: Number, default: 0 },
    initialUserCount: { type: Number, default: 0 },
    initialReportCount: { type: Number, default: 0 },
    initialVessels: { type: Array, default: () => [] },
    initialPorts: { type: Array, default: () => [] },
    initialPendingOwners: { type: Array, default: () => [] }
  },
  data() {
    return {
      sidebarCollapsed: false,
      sidebarMobile: false,
      showNotifications: false,
      vesselCount: this.initialVesselCount,
      portCount: this.initialPortCount,
      userCount: this.initialUserCount,
      reportCount: this.initialReportCount,
      vessels: [...this.initialVessels],
      ports: [...this.initialPorts],
      notifications: [],
      map: null,
      vesselMarkers: {},
      portMarkers: {},
      tempMarker: null,
      showAddModal: false,
      showDeleteModal: false,
      showDeclineModal: false,
      showClearAllModal: false,
      submitting: false,
      deleting: false,
      declining: false,
      clearingAll: false,
      formData: {
        request_id: null,
        owner_id: null,
        ownerName: '--',
        ownerEmail: '--',
        name: '',
        type: '',
        imo: '',
        mmsi: '',
        call_sign: '',
        flag: '',
        LoA: '',
        gross_tonnage: '',
        year_built: '',
        speed: 0,
        additional_notes: '',
        latitude: null,
        longitude: null
      },
      deleteVessel: {
        vessel_id: null,
        name: '',
        imo: '',
        mmsi: ''
      },
      declineData: {
        id: null,
        vessel_name: '',
        owner_name: ''
      },
      declineReason: '',
      pendingApproval: null,
      toast: {
        show: false,
        message: '',
        type: 'info'
      }
    };
  },
  computed: {
    toastIcon() {
      const icons = {
        success: 'fas fa-check-circle',
        error: 'fas fa-exclamation-circle',
        warning: 'fas fa-exclamation-triangle',
        info: 'fas fa-info-circle'
      };
      return icons[this.toast.type] || icons.info;
    }
  },
  mounted() {
    this.initializeMap();
    this.initializeNotifications();
    this.setupEventListeners();
  },
  methods: {
    toggleSidebar() {
      if (window.innerWidth <= 768) {
        this.sidebarMobile = !this.sidebarMobile;
      } else {
        this.sidebarCollapsed = !this.sidebarCollapsed;
      }
    },
    toggleNotifications(e) {
      e.stopPropagation();
      this.showNotifications = !this.showNotifications;
    },
    setupEventListeners() {
      document.addEventListener('click', (e) => {
        if (window.innerWidth <= 768 && this.sidebarMobile) {
          const sidebar = this.$el.querySelector('.adminbar');
          const toggleBtn = this.$el.querySelector('#toggle-btn');
          if (!sidebar.contains(e.target) && e.target !== toggleBtn) {
            this.sidebarMobile = false;
          }
        }
        
        const notifDropdown = this.$el.querySelector('#notifDropdown');
        const notifBell = this.$el.querySelector('#notifBell');
        if (!notifDropdown?.contains(e.target) && e.target !== notifBell) {
          this.showNotifications = false;
        }
      });

      window.addEventListener('resize', () => {
        if (window.innerWidth > 768) {
          this.sidebarMobile = false;
        }
      });

      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && this.pendingApproval) {
          this.map.getContainer().style.cursor = '';
          this.pendingApproval = null;
          this.showToast('Position selection cancelled', 'info');
        }
      });
    },
    initializeMap() {
      this.$nextTick(() => {
        this.map = L.map(this.$refs.mapContainer).setView([7.0, 125.6], 9);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          attribution: '&copy; OpenStreetMap contributors'
        }).addTo(this.map);

        this.renderPorts();
        this.renderVessels();
        this.setupMapClick();
      });
    },
    setupMapClick() {
      this.map.on('click', (e) => {
        if (this.pendingApproval) {
          const lat = e.latlng.lat.toFixed(6);
          const lng = e.latlng.lng.toFixed(6);

          this.formData.latitude = lat;
          this.formData.longitude = lng;

          if (this.tempMarker) this.map.removeLayer(this.tempMarker);
          this.tempMarker = L.marker([e.latlng.lat, e.latlng.lng], {
            icon: this.createVesselIcon()
          }).addTo(this.map);

          const approval = this.pendingApproval;
          this.formData.request_id = approval.id;
          this.formData.owner_id = approval.owner_id;
          this.formData.ownerName = approval.user;
          this.formData.ownerEmail = approval.email;

          if (approval.vessel_data) {
            const v = approval.vessel_data;
            this.formData.name = v.vessel_name || '';
            this.formData.type = v.vessel_type || '';
            this.formData.imo = v.imo_number || '';
            this.formData.mmsi = v.mmsi_number || '';
            this.formData.call_sign = v.call_sign || '';
            this.formData.flag = v.flag_state || '';
            this.formData.LoA = v.length_overall || '';
            this.formData.gross_tonnage = v.gross_tonnage || '';
            this.formData.year_built = v.year_built || '';
            this.formData.additional_notes = v.additional_notes || '';
          }

          this.map.getContainer().style.cursor = '';
          this.pendingApproval = null;
          
          this.showToast(`Position set: ${lat}, ${lng}. Opening approval form...`, 'success');
          
          setTimeout(() => {
            this.showAddModal = true;
          }, 500);
          
          return;
        }

        if (this.showAddModal) {
          const lat = e.latlng.lat.toFixed(6);
          const lng = e.latlng.lng.toFixed(6);

          this.formData.latitude = lat;
          this.formData.longitude = lng;

          if (this.tempMarker) this.map.removeLayer(this.tempMarker);
          this.tempMarker = L.marker([e.latlng.lat, e.latlng.lng], {
            icon: this.createVesselIcon()
          }).addTo(this.map);
          
          this.showToast(`Position updated: ${lat}, ${lng}`, 'success');
        }
      });
    },
    createVesselIcon() {
      return L.divIcon({
        className: 'custom-marker vessel-marker',
        html: '<div style="background: rgba(255,255,255,0.9); border-radius: 50%; padding: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.3);"><i class="fas fa-ship" style="font-size: 22px; color: #06326a;"></i></div>',
        iconSize: [40, 40],
        iconAnchor: [20, 20],
        popupAnchor: [0, -20]
      });
    },
    createPortIcon() {
      return L.divIcon({
        className: 'custom-marker port-marker',
        html: '<div style="background: rgba(255,255,255,0.9); border-radius: 50%; padding: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.3);"><i class="fas fa-anchor" style="font-size: 24px; color: #2666b9ff;"></i></div>',
        iconSize: [42, 42],
        iconAnchor: [21, 21],
        popupAnchor: [0, -21]
      });
    },
    renderPorts() {
      this.ports.forEach(port => {
        if (port.latitude && port.longitude) {
          const marker = L.marker([parseFloat(port.latitude), parseFloat(port.longitude)], {
            icon: this.createPortIcon()
          }).addTo(this.map);
          
          marker.bindPopup(`
            <div style="text-align: center; min-width: 150px;">
              <h6 style="margin-bottom: 8px; color: #2833a7ff;">
                <i class="fas fa-anchor"></i> ${port.name}
              </h6>
              <small style="color: #666;">Port ID: ${port.port_id}</small>
            </div>
          `);
          
          this.portMarkers[port.port_id] = marker;
        }
      });
    },
    renderVessels() {
      this.vessels.forEach(vessel => {
        if (vessel.latitude && vessel.longitude) {
          const marker = L.marker([parseFloat(vessel.latitude), parseFloat(vessel.longitude)], {
            icon: this.createVesselIcon()
          }).addTo(this.map);
          
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
          
          this.vesselMarkers[vessel.vessel_id] = marker;
        }
      });
    },
    initializeNotifications() {
      this.notifications = this.initialPendingOwners.map(registration => ({
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
      }));
    },
    acceptUser(id) {
      const notif = this.notifications.find(n => n.id === id);
      if (!notif) return;

      this.showNotifications = false;
      this.showToast(`Click on the map to set position for ${notif.vessel_data.vessel_name}`, 'info');
      this.map.getContainer().style.cursor = 'crosshair';
      
      this.pendingApproval = {
        id: id,
        owner_id: notif.owner_id,
        user: notif.user,
        email: notif.email,
        vessel_data: notif.vessel_data
      };
    },
    openDeclineModal(notif) {
      this.declineData = {
        id: notif.id,
        vessel_name: notif.vessel_data.vessel_name,
        owner_name: notif.user
      };
      this.showDeclineModal = true;
    },
    closeDeclineModal() {
      this.showDeclineModal = false;
      this.declineReason = '';
    },
    async confirmDecline() {
      this.declining = true;
      try {
        const response = await fetch('/admin/vessel/reject', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({ 
            request_id: this.declineData.id,
            reason: this.declineReason || null
          })
        });

        const data = await response.json();
        if (data.success) {
          this.notifications = this.notifications.filter(n => n.id !== this.declineData.id);
          this.closeDeclineModal();
          this.showToast('Registration request declined successfully.', 'info');
        } else {
          this.showToast('Error declining registration: ' + (data.message || 'Unknown error'), 'error');
        }
      } catch (error) {
        console.error('Error:', error);
        this.showToast('Error declining registration.', 'error');
      } finally {
        this.declining = false;
      }
    },
    openClearAllModal() {
      if (this.notifications.length === 0) {
        this.showToast('No notifications to clear.', 'info');
        return;
      }
      this.showClearAllModal = true;
    },
    closeClearAllModal() {
      this.showClearAllModal = false;
    },
    async confirmClearAll() {
      this.clearingAll = true;
      try {
        const promises = this.notifications.map(notif => 
          fetch('/admin/vessel/reject', {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({ 
              request_id: notif.id,
              reason: 'Bulk decline - Cleared by admin'
            })
          })
        );
        
        await Promise.all(promises);
        this.notifications = [];
        this.closeClearAllModal();
        this.showNotifications = false;
        this.showToast('All notifications cleared successfully.', 'success');
      } catch (error) {
        console.error('Error:', error);
        this.showToast('Error clearing notifications.', 'error');
      } finally {
        this.clearingAll = false;
      }
    },
    viewVessel(vessel) {
      if (vessel.latitude && vessel.longitude) {
        this.map.setView([parseFloat(vessel.latitude), parseFloat(vessel.longitude)], 12);
        if (this.vesselMarkers[vessel.vessel_id]) {
          this.vesselMarkers[vessel.vessel_id].openPopup();
        }
      }
    },
    openDeleteModal(vessel) {
      this.deleteVessel = {
        vessel_id: vessel.vessel_id,
        name: vessel.name,
        imo: vessel.imo,
        mmsi: vessel.mmsi
      };
      this.showDeleteModal = true;
    },
    closeDeleteModal() {
      this.showDeleteModal = false;
      this.deleteVessel = { vessel_id: null, name: '', imo: '', mmsi: '' };
    },
    async confirmDelete() {
      this.deleting = true;
      try {
        const response = await fetch('/admin/delete-vessel', {
          method: 'POST',
          headers: { 
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/x-www-form-urlencoded',
            'Accept': 'application/json'
          },
          body: new URLSearchParams({ vessel_id: this.deleteVessel.vessel_id })
        });

        const data = await response.json();
        if (data.success) {
          this.vessels = this.vessels.filter(v => v.vessel_id !== this.deleteVessel.vessel_id);
          
          if (this.vesselMarkers[this.deleteVessel.vessel_id]) {
            this.map.removeLayer(this.vesselMarkers[this.deleteVessel.vessel_id]);
            delete this.vesselMarkers[this.deleteVessel.vessel_id];
          }
          
          this.closeDeleteModal();
          this.showToast('Vessel deleted successfully!', 'success');
          this.vesselCount--;
        } else {
          this.showToast('Error deleting vessel.', 'error');
        }
      } catch (error) {
        console.error(error);
        this.showToast('Request failed: ' + error.message, 'error');
      } finally {
        this.deleting = false;
      }
    },
    closeAddModal() {
      this.showAddModal = false;
      this.resetForm();
    },
    resetForm() {
      this.formData = {
        request_id: null,
        owner_id: null,
        ownerName: '--',
        ownerEmail: '--',
        name: '',
        type: '',
        imo: '',
        mmsi: '',
        call_sign: '',
        flag: '',
        LoA: '',
        gross_tonnage: '',
        year_built: '',
        speed: 0,
        additional_notes: '',
        latitude: null,
        longitude: null
      };
      if (this.tempMarker) {
        this.map.removeLayer(this.tempMarker);
        this.tempMarker = null;
      }
      this.pendingApproval = null;
      this.map.getContainer().style.cursor = '';
    },
    async submitVessel() {
      if (!this.formData.latitude || !this.formData.longitude) {
        this.showToast('Please click on the map to set vessel position!', 'error');
        return;
      }

      const loa = parseFloat(this.formData.LoA);
      const gt = parseFloat(this.formData.gross_tonnage);
      const year = parseInt(this.formData.year_built);

      if (loa <= 0 || gt <= 0) {
        this.showToast('Length and Gross Tonnage must be greater than 0!', 'error');
        return;
      }

      if (year < 1900 || year > 2100) {
        this.showToast('Invalid year built!', 'error');
        return;
      }

      this.submitting = true;

      const formData = new FormData();
      Object.keys(this.formData).forEach(key => {
        if (this.formData[key] !== null && key !== 'ownerName' && key !== 'ownerEmail') {
          formData.append(key, this.formData[key]);
        }
      });

      try {
        const response = await fetch('/admin/vessel/approve', {
          method: 'POST',
          headers: { 
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: formData
        });

        const data = await response.json();
        if (data.success) {
          this.showToast(data.message || 'Vessel approved and registered successfully!', 'success');
          
          const lat = parseFloat(this.formData.latitude);
          const lng = parseFloat(this.formData.longitude);
          const vessel = data.vessel;
          
          if (this.tempMarker) {
            this.map.removeLayer(this.tempMarker);
            this.tempMarker = null;
          }
          
          const marker = L.marker([lat, lng], {
            icon: this.createVesselIcon()
          }).addTo(this.map);
          
          marker.bindPopup(`
            <div style="text-align: center;">
              <h6><i class="fas fa-ship"></i> ${vessel.name}</h6>
              <p style="margin: 5px 0;"><strong>Type:</strong> ${vessel.type}</p>
              <p style="margin: 5px 0;"><strong>Flag:</strong> ${vessel.flag}</p>
              <p style="margin: 5px 0;"><strong>IMO:</strong> ${vessel.imo}</p>
              <p style="margin: 5px 0;"><strong>MMSI:</strong> ${vessel.mmsi}</p>
              <p style="margin: 5px 0;"><strong>Speed:</strong> ${this.formData.speed || 0} knots</p>
            </div>
          `);
          
          this.vesselMarkers[data.vessel_id] = marker;
          this.notifications = this.notifications.filter(n => n.id !== this.formData.request_id);
          this.closeAddModal();
          this.vesselCount++;
          
          setTimeout(() => location.reload(), 1500);
        } else {
          this.showToast(data.message || 'Error approving vessel.', 'error');
        }
      } catch (error) {
        console.error('Error:', error);
        this.showToast('Error approving vessel.', 'error');
      } finally {
        this.submitting = false;
      }
    },
    showToast(message, type = 'info') {
      this.toast = { show: true, message, type };
      setTimeout(() => {
        this.toast.show = false;
      }, 3000);
    }
  }
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@600&display=swap');

/* Import your existing CSS here or use @import */
</style>