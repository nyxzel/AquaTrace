<template>
  <div class="user-reports-app">
    <!-- Success Modal -->
    <div class="custom-modal-overlay" :class="{ show: modal.show }">
      <div class="custom-modal-content">
        <div class="custom-modal-body">
          <div class="success-icon-modal mb-4">
            <i class="bi bi-check-circle-fill"></i>
          </div>
          <h3 class="mb-3">{{ modal.title }}</h3>
          <p class="text-muted mb-4">{{ modal.message }}</p>
          <button type="button" class="btn-modal-close" @click="closeModal">
            <i class="bi bi-check-lg"></i> Close
          </button>
        </div>
      </div>
    </div>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
      <!-- Report Submission Form -->
      <div class="form-container">
        <form @submit.prevent="submitReport">
          <!-- Report Information Section -->
          <div class="form-section">
            <div class="section-header">
              <div class="section-icon">
                <i class="fas fa-exclamation-circle"></i>
              </div>
              <h3>Incident Details</h3>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">
                  <i class="bi bi-calendar-event"></i> Incident Date
                </label>
                <input
                  type="date"
                  class="form-control"
                  v-model="form.incident_date"
                  :max="today"
                  required
                />
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">
                  <i class="bi bi-clock"></i> Incident Time
                </label>
                <input
                  type="time"
                  class="form-control"
                  v-model="form.incident_time"
                  required
                />
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">
                  <i class="bi bi-ship"></i> Vessel Involved
                </label>
                <select class="form-select" v-model="form.vessel_id" required>
                  <option value="">Select vessel</option>
                  <option
                    v-for="vessel in vessels"
                    :key="vessel.vessel_id"
                    :value="vessel.vessel_id"
                  >
                    {{ vessel.name }}
                  </option>
                </select>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">
                  <i class="bi bi-geo-alt"></i> Port Location
                </label>
                <select class="form-select" v-model="form.port_id" required>
                  <option value="">Select port</option>
                  <option
                    v-for="port in ports"
                    :key="port.port_id"
                    :value="port.port_id"
                  >
                    {{ port.name }}
                  </option>
                </select>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">
                  <i class="bi bi-tag"></i> Incident Type
                </label>
                <select class="form-select" v-model="form.incident_type" required>
                  <option value="">Select incident type</option>
                  <option value="Collision">Collision</option>
                  <option value="Grounding">Grounding</option>
                  <option value="Engine Failure">Engine Failure</option>
                  <option value="Fire">Fire</option>
                  <option value="Oil Spill">Oil Spill</option>
                  <option value="Equipment Malfunction">Equipment Malfunction</option>
                  <option value="Weather Damage">Weather Damage</option>
                  <option value="Personnel Injury">Personnel Injury</option>
                  <option value="Other">Other</option>
                </select>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">
                  <i class="bi bi-exclamation-triangle"></i> Severity Level
                </label>
                <select class="form-select" v-model="form.severity" required>
                  <option value="">Select severity</option>
                  <option value="LOW">Low - Minor incident</option>
                  <option value="MEDIUM">Medium - Moderate incident</option>
                  <option value="HIGH">High - Severe incident</option>
                </select>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">
                <i class="bi bi-card-text"></i> Incident Description
              </label>
              <textarea
                class="form-control"
                v-model="form.description"
                rows="5"
                placeholder="Provide detailed description of the incident..."
                required
              ></textarea>
            </div>

            <div class="mb-3">
              <label class="form-label">
                <i class="bi bi-info-circle"></i> Additional Notes
              </label>
              <textarea
                class="form-control"
                v-model="form.additional_notes"
                rows="3"
                placeholder="Any additional information..."
              ></textarea>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="form-actions">
            <button type="submit" class="btn-submit" :disabled="isSubmitting">
              <i class="bi" :class="isSubmitting ? 'bi-hourglass-split' : 'bi-send'"></i>
              {{ isSubmitting ? 'Submitting...' : 'Submit Report' }}
            </button>
            <button type="reset" class="btn-cancel" @click="resetForm">
              <i class="bi bi-x-circle"></i> Clear Form
            </button>
          </div>
        </form>
      </div>

      <!-- User's Reports List -->
      <div class="reports-list">
        <div class="list-header">
          <h5><i class="bi bi-card-list"></i> My Submitted Reports</h5>
          <div class="report-count">
            {{ reports.length }} Report{{ reports.length !== 1 ? 's' : '' }}
          </div>
        </div>

        <div v-if="reports.length === 0" class="empty-state">
          <i class="bi bi-inbox"></i>
          <p>No reports submitted yet. Submit your first incident report above!</p>
        </div>

        <div v-else>
          <div v-for="report in reports" :key="report.report_id" class="report-card">
            <div class="report-header">
              <div class="report-title">
                <i class="bi bi-file-text"></i>
                <h6>{{ report.report_type }} - {{ report.vessel?.name || 'N/A' }}</h6>
              </div>
              <div class="report-status">
                <span
                  class="status-badge"
                  :class="getStatusClass(report.status)"
                >
                  <i class="bi" :class="getStatusIcon(report.status)"></i>
                  {{ report.status }}
                </span>
                <span
                  class="severity-badge"
                  :class="getSeverityClass(report.severity)"
                >
                  {{ formatSeverity(report.severity) }}
                </span>
              </div>
            </div>

            <div class="report-body">
              <div class="report-info">
                <div class="info-item">
                  <i class="bi bi-calendar"></i>
                  <span>
                    <strong>Date:</strong> {{ formatDate(report.date_created) }}
                  </span>
                </div>
                <div class="info-item">
                  <i class="bi bi-geo-alt"></i>
                  <span>
                    <strong>Location:</strong> {{ report.port?.name || 'N/A' }}
                  </span>
                </div>
                <div class="info-item">
                  <i class="bi bi-tag"></i>
                  <span><strong>Type:</strong> {{ report.report_type }}</span>
                </div>
              </div>
            </div>

            <div class="report-footer">
              <small class="text-muted">
                <i class="bi bi-clock-history"></i> Submitted:
                {{ formatDate(report.date_created) }}
              </small>
              <small class="text-muted">
                <i class="bi bi-arrow-clockwise"></i> Last Updated:
                {{ formatDate(report.updated_on) }}
              </small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'UserReports',

  props: {
    initialReports: {
      type: Array,
      default: () => []
    },
    initialVessels: {
      type: Array,
      default: () => []
    },
    initialPorts: {
      type: Array,
      default: () => []
    }
  },

  data() {
    return {
      form: {
        incident_date: '',
        incident_time: '',
        vessel_id: '',
        port_id: '',
        incident_type: '',
        severity: '',
        description: '',
        additional_notes: ''
      },
      reports: this.initialReports,
      vessels: this.initialVessels,
      ports: this.initialPorts,
      isSubmitting: false,
      modal: {
        show: false,
        title: '',
        message: ''
      }
    };
  },

  computed: {
    today() {
      return new Date().toISOString().split('T')[0];
    }
  },

  methods: {
    async submitReport() {
      this.isSubmitting = true;

      const formData = new FormData();
      Object.keys(this.form).forEach(key => {
        if (this.form[key]) {
          formData.append(key, this.form[key]);
        }
      });

      try {
        const response = await fetch('/user/reports/store', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': this.getCsrfToken(),
            'Accept': 'application/json'
          },
          body: formData
        });

        const data = await response.json();

        if (data.success) {
          this.showModal(
            'Report Submitted Successfully!',
            'Your incident report has been sent to the admin for review.'
          );

          this.resetForm();
          
          // Add new report to list
          if (data.report) {
            this.reports.unshift(data.report);
          }

          // Reload after 2 seconds
          setTimeout(() => {
            window.location.reload();
          }, 2000);
        } else {
          alert(data.message || 'Error submitting report. Please try again.');
        }
      } catch (error) {
        console.error('Error:', error);
        alert('Error submitting report. Please try again.');
      } finally {
        this.isSubmitting = false;
      }
    },

    resetForm() {
      this.form = {
        incident_date: '',
        incident_time: '',
        vessel_id: '',
        port_id: '',
        incident_type: '',
        severity: '',
        description: '',
        additional_notes: ''
      };
    },

    showModal(title, message) {
      this.modal = {
        show: true,
        title,
        message
      };
      document.body.style.overflow = 'hidden';
    },

    closeModal() {
      this.modal.show = false;
      document.body.style.overflow = '';
    },

    getStatusClass(status) {
      const classes = {
        'Pending': 'status-pending',
        'Under Investigation': 'status-investigation',
        'Resolved': 'status-resolved',
        'Closed': 'status-closed'
      };
      return classes[status] || 'status-pending';9
    },

    getStatusIcon(status) {
      const icons = {
        'Pending': 'bi-clock',
        'Under Investigation': 'bi-search',
        'Resolved': 'bi-check-circle',
        'Closed': 'bi-x-circle'
      };
      return icons[status] || 'bi-clock';
    },

    getSeverityClass(severity) {
      const classes = {
        'LOW': 'severity-low',
        'MEDIUM': 'severity-medium',
        'HIGH': 'severity-high'
      };
      return classes[severity] || 'severity-low';
    },

    formatSeverity(severity) {
      if (!severity) return 'N/A';
      return severity.charAt(0) + severity.slice(1).toLowerCase();
    },

    formatDate(dateString) {
      if (!dateString) return 'N/A';
      const date = new Date(dateString);
      return date.toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric'
      });
    },

    getCsrfToken() {
      return document.querySelector('meta[name="csrf-token"]')?.content || '';
    }
  }
};
</script>
