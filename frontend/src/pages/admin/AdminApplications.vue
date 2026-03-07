<script setup>
import { ref, onMounted } from "vue"
import api from "@/services/api"

const applications = ref([])
const loading = ref(true)

const getApplications = async () => {
  try {
    const res = await api.get("/admin/applications")
    applications.value = res.data.applications
  } catch (error) {
    console.error(error)
  } finally {
    loading.value = false
  }
}

const updateStatus = async (id, status) => {
  // Opsional: Berikan konfirmasi sebelum mengubah status
  if (!confirm(`Apakah Anda yakin ingin mengubah status menjadi ${status}?`)) return;

  try {
    // Bisa tambahkan state loading per-tombol di sini jika mau lebih advance, 
    // tapi untuk sekarang kita re-fetch data setelah berhasil.
    await api.patch(`/admin/applications/${id}`, {
      apply_status: status
    })
    
    // Refresh data setelah update
    getApplications()
  } catch (error) {
    console.error(error)
    alert("Gagal memperbarui status.")
  }
}

const formatPrice = (price) => {
  return new Intl.NumberFormat("id-ID", {
    maximumFractionDigits: 0
  }).format(price)
}

// Fungsi pembantu untuk warna badge status
const getStatusClass = (status) => {
  const s = status ? status.toLowerCase() : '';
  if (s.includes('accept') || s.includes('diterima')) return 'status-approved';
  if (s.includes('reject') || s.includes('ditolak')) return 'status-rejected';
  return 'status-pending';
}

onMounted(() => {
  getApplications()
})
</script>

<template>
  <div class="admin-wrapper">
    <div class="admin-container">
      
      <div class="page-header">
        <h1 class="title">Kelola Pengajuan</h1>
        <p class="subtitle">Tinjau dan proses aplikasi cicilan masuk dari Society</p>
      </div>

      <div v-if="loading" class="empty-state">
        <p>Memuat data aplikasi...</p>
      </div>

      <div v-else-if="applications.length === 0" class="empty-state card-empty">
        <p>Belum ada aplikasi pengajuan yang masuk.</p>
      </div>

      <div v-else class="applications-grid">
        <div v-for="app in applications" :key="app.id" class="app-card">
          
          <div class="card-header">
            <div>
              <span class="brand">{{ app.instalment_car.brand }}</span>
              <h3 class="car-name">{{ app.instalment_car.car }}</h3>
            </div>
            <div :class="['status-badge', getStatusClass(app.apply_status)]">
              {{ app.apply_status }}
            </div>
          </div>

          <div class="card-body">
            <div class="info-row">
              <span class="label">Tenor Pilihan</span>
              <span class="value">{{ app.months }} Bulan</span>
            </div>
            
            <div class="info-row">
              <span class="label">Cicilan per Bulan</span>
              <span class="value price">Rp {{ formatPrice(app.nominal) }}</span>
            </div>

            <div class="info-row notes-row" v-if="app.notes">
              <span class="label">Catatan Pemohon:</span>
              <span class="value notes">"{{ app.notes }}"</span>
            </div>
            <div class="info-row notes-row" v-else>
              <span class="label">Catatan Pemohon:</span>
              <span class="value notes">- Tidak ada catatan -</span>
            </div>
          </div>

          <div class="card-actions">
            <button 
              @click="updateStatus(app.id, 'accepted')" 
              class="btn-action btn-accept"
            >
              ✓ Accept
            </button>
            
            <button 
              @click="updateStatus(app.id, 'rejected')" 
              class="btn-action btn-reject"
            >
              ✕ Reject
            </button>
          </div>

        </div>
      </div>

    </div>
  </div>
</template>

<style scoped>
/* Wrapper Utama (Background sama dengan halaman sebelumnya) */
.admin-wrapper {
  min-height: calc(100vh - 70px);
  width: 100%;
  background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
  padding: 40px 20px;
  box-sizing: border-box;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  color: white;
}

.admin-container {
  max-width: 900px;
  margin: 0 auto;
}

/* Header */
.page-header {
  margin-bottom: 30px;
  border-bottom: 1px solid rgba(255, 255, 255, 0.2);
  padding-bottom: 20px;
}

.title {
  font-size: 2rem;
  margin: 0 0 5px 0;
  color: white;
}

.subtitle {
  color: rgba(255, 255, 255, 0.8);
  margin: 0;
  font-size: 0.95rem;
}

/* Grid Layout */
.applications-grid {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

/* Card Design Putih */
.app-card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  color: #334155;
  overflow: hidden; /* Agar border-radius rapi sampai ke footer */
}

.app-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
}

/* Card Header */
.card-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  padding: 20px 24px;
  border-bottom: 2px solid #f1f5f9;
}

.brand {
  font-size: 0.8rem;
  font-weight: 700;
  color: #64748b;
  text-transform: uppercase;
  letter-spacing: 1px;
}

.car-name {
  font-size: 1.5rem;
  color: #1e3a8a;
  margin: 5px 0 0 0;
}

/* Status Badges */
.status-badge {
  padding: 6px 14px;
  border-radius: 20px;
  font-size: 0.85rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.status-pending {
  background-color: #fef3c7;
  color: #d97706;
  border: 1px solid #fde68a;
}

.status-approved { /* Dipakai untuk accepted */
  background-color: #dcfce3;
  color: #16a34a;
  border: 1px solid #bbf7d0;
}

.status-rejected {
  background-color: #fee2e2;
  color: #ef4444;
  border: 1px solid #fecaca;
}

/* Card Body */
.card-body {
  padding: 20px 24px;
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.info-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 0.95rem;
}

.label {
  color: #64748b;
  font-weight: 600;
}

.value {
  font-weight: 700;
  color: #1e293b;
}

.price {
  color: #3b82f6;
  font-size: 1.1rem;
}

.notes-row {
  flex-direction: column;
  align-items: flex-start;
  gap: 5px;
  background-color: #f8fafc;
  padding: 12px;
  border-radius: 8px;
  margin-top: 5px;
  border: 1px solid #e2e8f0;
}

.notes {
  font-weight: 400;
  color: #475569;
  font-style: italic;
}

/* Card Actions (Footer) */
.card-actions {
  display: flex;
  gap: 10px;
  padding: 15px 24px;
  background-color: #f8fafc;
  border-top: 1px solid #e2e8f0;
}

.btn-action {
  flex: 1; /* Tombol membagi ruang sama rata */
  padding: 10px 15px;
  font-size: 0.95rem;
  font-weight: 600;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 5px;
}

/* Tombol Accept (Hijau) */
.btn-accept {
  background-color: #10b981;
  color: white;
}

.btn-accept:hover {
  background-color: #059669;
  box-shadow: 0 4px 10px rgba(16, 185, 129, 0.3);
}

/* Tombol Reject (Merah) */
.btn-reject {
  background-color: #ef4444;
  color: white;
}

.btn-reject:hover {
  background-color: #dc2626;
  box-shadow: 0 4px 10px rgba(239, 68, 68, 0.3);
}

.btn-action:active {
  transform: scale(0.98);
}

/* Empty State */
.empty-state {
  text-align: center;
  padding: 60px 20px;
  color: white;
  font-size: 1.1rem;
}

.card-empty {
  background: rgba(255, 255, 255, 0.1);
  border: 2px dashed rgba(255, 255, 255, 0.4);
  border-radius: 16px;
}
</style>