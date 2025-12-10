<template>
  <div class="analytics-dashboard">
    <!-- Stats Grid -->
    <div class="stats-grid">
      <div class="stat-card" data-aos="fade-up" data-aos-delay="0">
        <div class="stat-card-header">
          <i class="fas fa-ship stat-icon"></i>
          <i class="fas fa-chart-line stat-badge"></i>
        </div>
        <div class="stat-number">{{ stats.totalVessels }}</div>
        <div class="stat-label">Total Vessels Tracked</div>
      </div>

      <div class="stat-card" data-aos="fade-up" data-aos-delay="100">
        <div class="stat-card-header">
          <i class="fas fa-compass stat-icon"></i>
          <div class="pulse"></div>
        </div>
        <div class="stat-number">{{ stats.activeVessels }}</div>
        <div class="stat-label">Active Vessels</div>
      </div>

      <div class="stat-card" data-aos="fade-up" data-aos-delay="200">
        <div class="stat-card-header">
          <i class="fas fa-map-marker-alt stat-icon"></i>
          <i class="fas fa-arrow-trend-up stat-badge"></i>
        </div>
        <div class="stat-number">{{ stats.totalPorts }}</div>
        <div class="stat-label">Total Ports</div>
      </div>

      <div class="stat-card" data-aos="fade-up" data-aos-delay="300">
        <div class="stat-card-header">
          <i class="fas fa-id-card stat-icon"></i>
          <i class="fas fa-user stat-badge"></i>
        </div>
        <div class="stat-number">{{ stats.totalUsers }}</div>
        <div class="stat-label">Total Users</div>
      </div>
    </div>

        <!-- Vessel Categories -->
    <div class="chart-card" data-aos="fade-up" data-aos-delay="150">
      <h3 class="chart-title">
        <i class="fas fa-pie-chart"></i>
        VESSEL CATEGORIES
      </h3>
      <div class="row">
        <div v-for="category in categories" :key="category.type" class="col-md-6">
          <div class="category-item">
            <div class="category-header">
              <span class="category-name">{{ category.type }}</span>
              <span class="category-count">{{ category.count }} vessels</span>
            </div>
            <div class="progress-bar-container">
              <div 
                class="progress-bar-fill" 
                :style="{ 
                  width: category.percentage + '%', 
                  background: getColorForType(category.type) 
                }"
              ></div>
            </div>
            <div 
              class="category-percentage" 
              :style="{ color: getColorForType(category.type) }"
            >
              {{ category.percentage }}%
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Fleet Grid - UPDATED WITH REAL COUNTS -->
    <div class="fleet-grid">
      <div class="fleet-card green" data-aos="zoom-in" data-aos-delay="100">
        <div class="fleet-header">
          <i class="fas fa-compass fleet-icon green"></i>
          <div class="fleet-stats">
            <div class="fleet-number">{{ vesselCounts.active }}</div>
            <div class="fleet-percentage">{{ calculatePercentage(vesselCounts.active) }}%</div>
          </div>
        </div>
        <div class="fleet-title">Vessels Underway</div>
      </div>

      <div class="fleet-card orange" data-aos="zoom-in" data-aos-delay="200">
        <div class="fleet-header">
          <i class="fas fa-route fleet-icon orange"></i>
          <div class="fleet-stats">
            <div class="fleet-number">{{ vesselCounts.inTransit }}</div>
            <div class="fleet-percentage">{{ calculatePercentage(vesselCounts.inTransit) }}%</div>
          </div>
        </div>
        <div class="fleet-title">Vessels In Transit</div>
      </div>

      <div class="fleet-card blue" data-aos="zoom-in" data-aos-delay="300">
        <div class="fleet-header">
          <i class="fas fa-anchor fleet-icon blue"></i>
          <div class="fleet-stats">
            <div class="fleet-number">{{ vesselCounts.docked }}</div>
            <div class="fleet-percentage">{{ calculatePercentage(vesselCounts.docked) }}%</div>
          </div>
        </div>
        <div class="fleet-title">Vessels Docked</div>
      </div>
    </div>

    <!-- Map Section -->
    <div class="map-container my-4" data-aos="fade-up" data-aos-delay="200">
      <h2 class="text-center mb-3">
        <i class="fas fa-map-marker-alt"></i> PORTS OF DAVAO REGION
      </h2>
      <div id="analyticsMap" style="height: 450px; border-radius: 15px;"></div>
    </div>

    <!-- Ports Table -->
    <div class="table-section" data-aos="fade-up" data-aos-delay="250">
      <h3 class="chart-title">
        <i class="fas fa-ship"></i>
        PORTS IN DAVAO REGION
      </h3>
      <table class="data-table">
        <thead>
          <tr>
            <th><i class="fas fa-map-marker-alt"></i> Ports</th>
            <th><i class="fas fa-ship"></i> No. of Vessels</th>
            <th><i class="fas fa-location-dot"></i> Location</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="port in ports" :key="port.port_id">
            <td>{{ port.name }}</td>
            <td><span class="vessel-badge">{{ port.vessel_count }}</span></td>
            <td>{{ port.city }}, {{ port.state }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
export default {
  name: 'AnalyticsDashboard',

  props: {
    initialStats: {
      type: Object,
      default: () => ({
        totalVessels: 0,
        activeVessels: 0,
        totalPorts: 0,
        totalUsers: 0
      })
    },
    initialCategories: {
      type: Array,
      default: () => []
    },
    initialPorts: {
      type: Array,
      default: () => []
    },
    initialVessels: {
      type: Array,
      default: () => []
    },
    // NEW PROPS FOR VESSEL STATUS COUNTS
    activeCount: {
      type: Number,
      default: 0
    },
    inTransitCount: {
      type: Number,
      default: 0
    },
    dockedCount: {
      type: Number,
      default: 0
    }
  },

  data() {
    return {
      stats: this.initialStats,
      categories: this.initialCategories,
      ports: this.initialPorts,
      vessels: this.initialVessels,
      vesselCounts: {
        active: this.activeCount,
        inTransit: this.inTransitCount,
        docked: this.dockedCount
      },
      map: null,
      colorMap: {
        'Cargo': '#4a9eff',
        'Tanker': '#0066cc',
        'Passenger': '#003d7a',
        'Fishing': '#66b3ff'
      }
    };
  },

  mounted() {
    this.$nextTick(() => {
      this.animateProgressBars();
      this.initMap();
      
      // Initialize AOS if available
      if (typeof AOS !== 'undefined') {
        AOS.init({
          duration: 800,
          once: true,
          offset: 100,
          easing: 'ease-in-out'
        });
      }
    });
  },

  methods: {
    // NEW METHOD: Calculate percentage
    calculatePercentage(count) {
      if (this.stats.totalVessels === 0) return 0;
      return Math.round((count / this.stats.totalVessels) * 100);
    },

    getColorForType(type) {
      return this.colorMap[type] || '#4a9eff';
    },

    animateProgressBars() {
      const progressBars = document.querySelectorAll('.progress-bar-fill');
      progressBars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0';
        setTimeout(() => {
          bar.style.width = width;
        }, 300);
      });
    },

    initMap() {
      if (typeof L === 'undefined') {
        console.error('Leaflet not loaded');
        return;
      }

      try {
        this.map = L.map('analyticsMap').setView([7.0, 125.5], 9);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          attribution: '&copy; OpenStreetMap contributors'
        }).addTo(this.map);

        // Vessel Icon
        const vesselIcon = L.divIcon({
          className: 'custom-marker vessel-marker',
          html: '<div style="background: rgba(255,255,255,0.9); border-radius: 50%; padding: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.3);"><i class="fas fa-ship" style="font-size: 22px; color: #06326a;"></i></div>',
          iconSize: [40, 40],
          iconAnchor: [20, 20],
          popupAnchor: [0, -20]
        });

        // Port Icon
        const portIcon = L.divIcon({
          className: 'custom-marker port-marker',
          html: '<div style="background: rgba(255,255,255,0.9); border-radius: 50%; padding: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.3);"><i class="fas fa-anchor" style="font-size: 24px; color: #28a745;"></i></div>',
          iconSize: [42, 42],
          iconAnchor: [21, 21],
          popupAnchor: [0, -21]
        });

        // Add custom styles
        const style = document.createElement('style');
        style.textContent = `
          .custom-marker {
            background: transparent !important;
            border: none !important;
          }
        `;
        document.head.appendChild(style);

        // Add Vessels to Map
        this.vessels.forEach(vessel => {
          if (vessel.latitude && vessel.longitude) {
            L.marker([parseFloat(vessel.latitude), parseFloat(vessel.longitude)], {
              icon: vesselIcon
            })
            .addTo(this.map)
            .bindPopup(`
              <div style="text-align: center; min-width: 200px;">
                <h6 style="margin-bottom: 10px; color: #06326a;">
                  <i class="fas fa-ship"></i> ${vessel.name}
                </h6>
                <div style="text-align: left; font-size: 13px;">
                  <p style="margin: 4px 0;"><strong>Type:</strong> ${vessel.type || 'N/A'}</p>
                  <p style="margin: 4px 0;"><strong>Speed:</strong> ${vessel.speed || 0} knots</p>
                  <p style="margin: 4px 0;"><strong>Position:</strong> ${parseFloat(vessel.latitude).toFixed(4)}, ${parseFloat(vessel.longitude).toFixed(4)}</p>
                </div>
              </div>
            `);
          }
        });

        // Add Ports to Map
        this.ports.forEach(port => {
          if (port.latitude && port.longitude) {
            L.marker([parseFloat(port.latitude), parseFloat(port.longitude)], {
              icon: portIcon
            })
            .addTo(this.map)
            .bindPopup(`
              <div style="text-align: center; min-width: 150px;">
                <h6 style="margin-bottom: 8px; color: #28a745;">
                  <i class="fas fa-anchor"></i> ${port.name}
                </h6>
                <small style="color: #666;">${port.city}, ${port.state}</small><br>
                <small style="color: #666;"><strong>Vessels:</strong> ${port.vessel_count}</small>
              </div>
            `);
          }
        });

        // Fix map rendering
        setTimeout(() => {
          this.map.invalidateSize();
        }, 100);
      } catch (error) {
        console.error('Map initialization error:', error);
      }
    }
  }
};
</script>