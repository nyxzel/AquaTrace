<template>
  <div class="vessel-registration-app">
    <!-- Success Modal -->
    <div class="custom-modal-overlay" :class="{ show: modal.show }">
      <div class="custom-modal-content">
        <div class="custom-modal-body">
          <div class="success-icon-modal mb-4" v-if="modal.type === 'success'">
            <i class="bi bi-check-circle-fill"></i>
          </div>
          <div class="error-icon-modal mb-4" v-else>
            <i class="bi bi-x-circle-fill"></i>
          </div>
          <h3 class="mb-3">{{ modal.title }}</h3>
          <p class="text-muted mb-4">{{ modal.message }}</p>
          <button type="button" class="btn-modal-close" @click="closeModal">
            <i class="bi bi-check-lg"></i> Close
          </button>
        </div>
      </div>
    </div>

    <!-- Registration Form -->
    <div class="content-wrapper">
      <div class="form-container">
        <form @submit.prevent="submitForm">
          <!-- BASIC INFORMATION -->
          <div class="form-section">
            <div class="section-header">
              <div class="section-icon">
                <i class="fas fa-info-circle"></i>
              </div>
              <h3>Basic Information</h3>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">
                  <i class="bi bi-tag"></i> Vessel Name
                </label>
                <input
                  type="text"
                  class="form-control"
                  v-model="form.vessel_name"
                  :class="{ 'is-invalid': errors.vessel_name }"
                  required
                />
                <div class="invalid-feedback" v-if="errors.vessel_name">
                  {{ errors.vessel_name }}
                </div>
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label">
                  <i class="bi bi-hash"></i> IMO Number
                </label>
                <input
                  type="text"
                  class="form-control"
                  v-model="form.imo_number"
                  :class="{ 'is-invalid': errors.imo_number }"
                  required
                />
                <div class="invalid-feedback" v-if="errors.imo_number">
                  {{ errors.imo_number }}
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">
                  <i class="bi bi-broadcast"></i> MMSI Number
                </label>
                <input
                  type="text"
                  class="form-control"
                  v-model="form.mmsi_number"
                  :class="{ 'is-invalid': errors.mmsi_number }"
                  required
                />
                <div class="invalid-feedback" v-if="errors.mmsi_number">
                  {{ errors.mmsi_number }}
                </div>
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label">
                  <i class="bi bi-telephone"></i> Call Sign
                </label>
                <input
                  type="text"
                  class="form-control"
                  v-model="form.call_sign"
                  :class="{ 'is-invalid': errors.call_sign }"
                  required
                />
                <div class="invalid-feedback" v-if="errors.call_sign">
                  {{ errors.call_sign }}
                </div>
              </div>
            </div>
          </div>

          <!-- VESSEL SPECIFICATIONS -->
          <div class="form-section">
            <div class="section-header">
              <div class="section-icon">
                <i class="fas fa-cogs"></i>
              </div>
              <h3>Vessel Specifications</h3>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">
                  <i class="bi bi-layers"></i> Vessel Type
                </label>
                <select
                  class="form-select"
                  v-model="form.vessel_type"
                  :class="{ 'is-invalid': errors.vessel_type }"
                  required
                >
                  <option value="">Select vessel type</option>
                  <option value="Cargo">Cargo</option>
                  <option value="Passenger">Passenger</option>
                  <option value="Tanker">Tanker</option>
                  <option value="Fishing">Fishing</option>
                  <option value="Tug">Tug</option>
                  <option value="Yacht">Yacht</option>
                  <option value="Other">Other</option>
                </select>
                <div class="invalid-feedback" v-if="errors.vessel_type">
                  {{ errors.vessel_type }}
                </div>
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label">
                  <i class="bi bi-flag"></i> Flag State
                </label>
                <select
                  class="form-select"
                  v-model="form.flag_state"
                  :class="{ 'is-invalid': errors.flag_state }"
                  required
                >
                  <option value="">Select flag state</option>
                  <option value="Philippines">Philippines</option>
                  <option value="Panama">Panama</option>
                  <option value="Liberia">Liberia</option>
                  <option value="Marshall Islands">Marshall Islands</option>
                  <option value="Singapore">Singapore</option>
                  <option value="Other">Other</option>
                </select>
                <div class="invalid-feedback" v-if="errors.flag_state">
                  {{ errors.flag_state }}
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4 mb-3">
                <label class="form-label">
                  <i class="bi bi-rulers"></i> Length Overall
                </label>
                <div class="input-group">
                  <input
                    type="number"
                    class="form-control"
                    v-model.number="form.length_overall"
                    :class="{ 'is-invalid': errors.length_overall }"
                    step="0.1"
                    required
                  />
                  <span class="input-group-text">meters</span>
                </div>
                <div class="invalid-feedback" v-if="errors.length_overall">
                  {{ errors.length_overall }}
                </div>
              </div>

              <div class="col-md-4 mb-3">
                <label class="form-label">
                  <i class="bi bi-box"></i> Gross Tonnage
                </label>
                <div class="input-group">
                  <input
                    type="number"
                    class="form-control"
                    v-model.number="form.gross_tonnage"
                    :class="{ 'is-invalid': errors.gross_tonnage }"
                    required
                  />
                  <span class="input-group-text">GT</span>
                </div>
                <div class="invalid-feedback" v-if="errors.gross_tonnage">
                  {{ errors.gross_tonnage }}
                </div>
              </div>

              <div class="col-md-4 mb-3">
                <label class="form-label">
                  <i class="bi bi-calendar-event"></i> Year Built
                </label>
                <input
                  type="number"
                  class="form-control"
                  v-model.number="form.year_built"
                  :class="{ 'is-invalid': errors.year_built }"
                  min="1900"
                  max="2025"
                  required
                />
                <div class="invalid-feedback" v-if="errors.year_built">
                  {{ errors.year_built }}
                </div>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">
                <i class="bi bi-card-text"></i> Additional Notes
              </label>
              <textarea
                class="form-control"
                v-model="form.additional_notes"
                rows="4"
              ></textarea>
            </div>
          </div>

          <!-- Form Actions -->
          <div class="form-actions">
            <button
              type="submit"
              class="btn-submit"
              :disabled="isSubmitting"
            >
              <i class="bi" :class="isSubmitting ? 'bi-hourglass-split' : 'bi-check-circle'"></i>
              {{ isSubmitting ? 'Submitting...' : (editId ? 'Update Vessel' : 'Register Vessel') }}
            </button>

            <button
              type="button"
              class="btn-cancel"
              v-if="editId"
              @click="cancelEdit"
            >
              <i class="bi bi-x-circle"></i> Cancel
            </button>
          </div>
        </form>
      </div>

      <!-- Registered Vessels List -->
      <div class="boats-list">
        <div class="list-header">
          <h5><i class="bi bi-card-list"></i> Registered Vessels</h5>
          <div class="vessel-count">{{ vessels.length }} Vessel{{ vessels.length !== 1 ? 's' : '' }}</div>
        </div>

        <div v-if="vessels.length === 0" class="empty-state">
          <i class="bi bi-inbox"></i>
          <p>No vessels registered yet. Register your first vessel above!</p>
        </div>

        <div v-else>
          <div
            v-for="vessel in vessels"
            :key="vessel.id"
            class="vessel-card"
          >
            <div class="vessel-card-header">
              <div>
                <h5 class="vessel-name">{{ vessel.vessel_name }}</h5>
                <div class="vessel-ids">
                  <span>IMO: {{ vessel.imo_number }}</span>
                  <span class="separator">|</span>
                  <span>MMSI: {{ vessel.mmsi_number }}</span>
                </div>
              </div>
              <div class="vessel-status-badge">
                <i class="bi bi-hourglass-split"></i> Pending
              </div>
            </div>

            <div class="vessel-card-body">
              <div class="vessel-specs-grid">
                <div class="spec-item">
                  <label>Vessel Type</label>
                  <span>{{ vessel.vessel_type }}</span>
                </div>
                <div class="spec-item">
                  <label>Call Sign</label>
                  <span>{{ vessel.call_sign }}</span>
                </div>
                <div class="spec-item">
                  <label>Flag State</label>
                  <span>{{ vessel.flag_state }}</span>
                </div>
                <div class="spec-item">
                  <label>Length</label>
                  <span>{{ vessel.length_overall }} m</span>
                </div>
                <div class="spec-item">
                  <label>Tonnage</label>
                  <span>{{ vessel.gross_tonnage }} GT</span>
                </div>
                <div class="spec-item">
                  <label>Year Built</label>
                  <span>{{ vessel.year_built }}</span>
                </div>
              </div>

              <div v-if="vessel.additional_notes" class="vessel-notes">
                <strong>Notes:</strong>
                <p>{{ vessel.additional_notes }}</p>
              </div>

              <div class="submission-info">
                <i class="bi bi-clock"></i> Submitted: {{ formatDate(vessel.submitted_at) }}
              </div>
            </div>

            <div class="vessel-card-footer">
              <button class="btn-edit" @click="editVessel(vessel)" title="Edit">
                <i class="bi bi-pencil"></i>
              </button>
              <button class="btn-delete" @click="deleteVessel(vessel.id)" title="Delete">
                <i class="bi bi-trash"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'UserVesselRegister',
  
  data() {
    return {
      form: {
        vessel_name: '',
        imo_number: '',
        mmsi_number: '',
        call_sign: '',
        vessel_type: '',
        flag_state: '',
        length_overall: null,
        gross_tonnage: null,
        year_built: null,
        additional_notes: ''
      },
      vessels: [],
      errors: {},
      isSubmitting: false,
      editId: null,
      modal: {
        show: false,
        type: 'success',
        title: '',
        message: ''
      }
    };
  },

  mounted() {
    this.loadVessels();
  },

  methods: {
    async loadVessels() {
      try {
        const response = await fetch('/user/vessels', {
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': this.getCsrfToken()
          }
        });

        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
          console.error('Received HTML instead of JSON');
          return;
        }

        const data = await response.json();
        
        if (data.success) {
          // Use pending registrations array from controller
          this.vessels = data.pending || [];
          console.log('Loaded vessels:', this.vessels);
        }
      } catch (error) {
        console.error('Error loading vessels:', error);
      }
    },

    async submitForm() {
      this.errors = {};
      this.isSubmitting = true;

      try {
        const url = this.editId 
          ? `/user/vessel/registration/${this.editId}`
          : '/user/vessel/register';
        
        const method = this.editId ? 'PUT' : 'POST';

        const response = await fetch(url, {
          method: method,
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': this.getCsrfToken()
          },
          body: JSON.stringify(this.form)
        });

        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
          const text = await response.text();
          if (text.includes('login') || text.includes('Login')) {
            throw new Error('Session expired. Please log in again.');
          }
          throw new Error('Server returned an error. Please try again.');
        }

        const data = await response.json();

        if (data.success) {
          const message = this.editId 
            ? 'Vessel registration updated successfully!'
            : 'Vessel registration submitted successfully! Awaiting admin approval.';
          
          this.showModal('success', this.editId ? 'Updated!' : 'Registration Submitted!', data.message || message);
          this.resetForm();
          await this.loadVessels();
        } else {
          if (data.errors) {
            this.errors = data.errors;
          }
          this.showModal('error', 'Submission Failed', data.message || 'Please check the form for errors.');
        }
      } catch (error) {
        console.error('Error:', error);
        
        let errorMsg = 'Error submitting registration. Please try again.';
        if (error.message.includes('Session expired')) {
          errorMsg = 'Your session has expired. Redirecting to login...';
          setTimeout(() => {
            window.location.href = '/login';
          }, 2000);
        } else if (error.message) {
          errorMsg = error.message;
        }

        this.showModal('error', 'Submission Failed', errorMsg);
      } finally {
        this.isSubmitting = false;
      }
    },

    editVessel(vessel) {
      this.form = {
        vessel_name: vessel.vessel_name,
        imo_number: vessel.imo_number,
        mmsi_number: vessel.mmsi_number,
        call_sign: vessel.call_sign,
        vessel_type: vessel.vessel_type,
        flag_state: vessel.flag_state,
        length_overall: vessel.length_overall,
        gross_tonnage: vessel.gross_tonnage,
        year_built: vessel.year_built,
        additional_notes: vessel.additional_notes || ''
      };
      this.editId = vessel.id;

      this.$nextTick(() => {
        document.querySelector('.form-container').scrollIntoView({
          behavior: 'smooth'
        });
      });
    },

    async deleteVessel(id) {
      if (!confirm('Are you sure you want to delete this vessel registration?')) {
        return;
      }

      try {
        const response = await fetch(`/user/vessel/registration/${id}`, {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': this.getCsrfToken(),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          }
        });

        const data = await response.json();

        if (data.success) {
          this.vessels = this.vessels.filter(v => v.id !== id);
          this.showModal('success', 'Deleted Successfully!', 'Vessel registration has been deleted.');
        } else {
          alert(data.message || 'Error deleting vessel');
        }
      } catch (error) {
        console.error('Error:', error);
        alert('Error deleting vessel. Please try again.');
      }
    },

    cancelEdit() {
      this.resetForm();
      this.editId = null;
    },

    resetForm() {
      this.form = {
        vessel_name: '',
        imo_number: '',
        mmsi_number: '',
        call_sign: '',
        vessel_type: '',
        flag_state: '',
        length_overall: null,
        gross_tonnage: null,
        year_built: null,
        additional_notes: ''
      };
      this.errors = {};
      this.editId = null;
    },

    showModal(type, title, message) {
      this.modal = {
        show: true,
        type,
        title,
        message
      };
      document.body.style.overflow = 'hidden';
    },

    closeModal() {
      this.modal.show = false;
      document.body.style.overflow = '';
    },

    formatDate(dateString) {
      if (!dateString) return 'N/A';
      const date = new Date(dateString);
      return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      });
    },

    getCsrfToken() {
      return document.querySelector('meta[name="csrf-token"]')?.content || '';
    }
  }
};
</script>

<style scoped>
.error-icon-modal {
  font-size: 4rem;
  color: #dc3545;
}

.is-invalid {
  border-color: #dc3545 !important;
}

.invalid-feedback {
  display: block;
  color: #dc3545;
  font-size: 0.875rem;
  margin-top: 0.25rem;
}

/* ==================== VESSEL CARD STYLING ==================== */
.vessel-card {
  background: white;
  border-radius: 18px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
  margin-bottom: 25px;
  overflow: hidden;
  border: 2px solid transparent;
  transition: all 0.3s ease;
}

.vessel-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 15px 40px rgba(102, 126, 234, 0.15);
  border-color: #667eea;
}

.vessel-card-header {
  background: linear-gradient(135deg, #667eea 0%, #4a9eff 100%);
  padding: 25px 30px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 20px;
}

.vessel-name {
  color: white;
  font-size: 1.4rem;
  font-weight: 600;
  margin: 0 0 10px 0;
}

.vessel-ids {
  display: flex;
  gap: 10px;
  align-items: center;
  color: rgba(255, 255, 255, 0.9);
  font-size: 0.9rem;
  font-weight: 500;
}

.vessel-ids .separator {
  color: rgba(255, 255, 255, 0.5);
}

.vessel-status-badge {
  background: white;
  color: #f39c12;
  padding: 8px 16px;
  border-radius: 20px;
  font-weight: 600;
  font-size: 0.85rem;
  white-space: nowrap;
  display: flex;
  align-items: center;
  gap: 6px;
}

.vessel-card-body {
  padding: 25px 30px;
}

.vessel-specs-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
  gap: 15px;
  margin-bottom: 20px;
}

.spec-item {
  display: flex;
  flex-direction: column;
  gap: 5px;
  padding: 12px;
  background: #f8f9fa;
  border-radius: 10px;
}

.spec-item label {
  font-size: 0.75rem;
  color: #6c757d;
  font-weight: 600;
  text-transform: uppercase;
  margin: 0;
}

.spec-item span {
  font-size: 1rem;
  color: #2c3e50;
  font-weight: 600;
}

.vessel-notes {
  background: #f8f9fa;
  padding: 15px;
  border-radius: 10px;
  margin-bottom: 15px;
  border-left: 3px solid #b9daffff;
}

.vessel-notes strong {
  color: #2c3e50;
  display: block;
  margin-bottom: 8px;
  font-size: 0.9rem;
}

.vessel-notes p {
  margin: 0;
  color: #495057;
  line-height: 1.6;
  font-size: 0.9rem;
}

.submission-info {
  display: flex;
  align-items: center;
  gap: 6px;
  padding: 10px;
  background: #d4e9fdff;
  border-radius: 8px;
  color: #05182fff;
  font-size: 0.85rem;
  font-weight: 500;
}

.vessel-card-footer {
  padding: 15px 30px;
  background: #f8f9fa;
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  border-top: 1px solid #e9ecef;
}

@media (max-width: 768px) {
  .vessel-card-header {
    flex-direction: column;
    align-items: flex-start;
    padding: 20px;
  }

  .vessel-name {
    font-size: 1.2rem;
  }

  .vessel-specs-grid {
    grid-template-columns: 1fr;
  }

  .vessel-card-body {
    padding: 20px;
  }

  .vessel-card-footer {
    padding: 15px 20px;
  }
}
</style>