<script setup>
import { ref, onMounted } from 'vue'
import api from '@/services/api'

const applications = ref([])
const loading = ref(true)

const getApplications = async () => {
  try {
    const res = await api.get('/admin/applications')
    applications.value = res.data.applications
  } catch (error) {
    console.error(error)
  } finally {
    loading.value = false
  }
}

// Fungsi pembantu untuk warna badge status di tabel
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
        <h1 class="title">Admin Dashboard</h1>
        <p class="subtitle">Ikhtisar seluruh data pengajuan cicilan mobil masyarakat</p>
      </div>

      <div class="table-card">
        
        <div class="card-header">
          <h3>Daftar Pengajuan Terbaru</h3>
          </div>

        <div v-if="loading" class="state-message">
          <p>Memuat data tabel...</p>
        </div>

        <div class="table-responsive" v-else>
          <table class="pro-table">
            <thead>
              <tr>
                <th>Nama Pemohon</th>
                <th>Mobil Pilihan</th>
                <th>Tenor</th>
                <th>Status</th>
              </tr>
            </thead>
            
            <tbody>
              <tr v-if="applications.length === 0">
                <td colspan="4" class="text-center empty-row">
                  Belum ada data pengajuan.
                </td>
              </tr>

              <tr v-for="app in applications" :key="app.id">
                <td class="font-semibold text-dark">
                  {{ app.society?.name || 'Anonim' }}
                </td>
                
                <td>
                  <span class="brand-text">{{ app.instalment_car?.brand }}</span>
                  <br>
                  <span class="car-text">{{ app.instalment_car?.car }}</span>
                </td>
                
                <td>{{ app.months }} Bulan</td>
                
                <td>
                  <span :class="['status-badge', getStatusClass(app.apply_status)]">
                    {{ app.apply_status }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

      </div>

    </div>
  </div>
</template>

<style scoped>
/* Wrapper Utama dengan Background Biru */
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
  max-width: 1000px;
  margin: 0 auto;
}

/* Header Halaman */
.page-header {
  margin-bottom: 30px;
}

.title {
  font-size: 2.2rem;
  margin: 0 0 5px 0;
  color: white;
}

.subtitle {
  color: rgba(255, 255, 255, 0.8);
  margin: 0;
  font-size: 1rem;
}

/* --- Kartu Tabel (Table Card) --- */
.table-card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
  overflow: hidden; /* Mencegah elemen keluar dari lengkungan radius */
  color: #334155;
}

.card-header {
  padding: 20px 25px;
  background-color: white;
  border-bottom: 1px solid #e2e8f0;
}

.card-header h3 {
  margin: 0;
  font-size: 1.2rem;
  color: #1e3a8a;
}

/* Supaya tabel tidak rusak di layar kecil (HP) */
.table-responsive {
  width: 100%;
  overflow-x: auto;
}

/* Desain Tabel Pro */
.pro-table {
  width: 100%;
  border-collapse: collapse;
  background-color: white;
}

.pro-table th, .pro-table td {
  padding: 16px 25px;
  text-align: left;
}

/* Header Tabel */
.pro-table th {
  background-color: #f8fafc;
  color: #64748b;
  font-size: 0.85rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  font-weight: 700;
  border-bottom: 2px solid #e2e8f0;
}

/* Isi Tabel (Rows) */
.pro-table td {
  border-bottom: 1px solid #f1f5f9;
  font-size: 0.95rem;
  vertical-align: middle;
  transition: background-color 0.2s;
}

.pro-table tbody tr:hover td {
  background-color: #f8fafc; /* Efek highlight abu muda saat mouse di atas baris */
}

/* Elemen Teks Khusus di Tabel */
.font-semibold {
  font-weight: 600;
}

.text-dark {
  color: #0f172a;
}

.brand-text {
  font-size: 0.75rem;
  color: #64748b;
  text-transform: uppercase;
  font-weight: 700;
}

.car-text {
  font-weight: 600;
  color: #1e3a8a;
  font-size: 1rem;
}

/* Badge Status (Sama seperti halaman sebelumnya) */
.status-badge {
  display: inline-block;
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 700;
  text-transform: capitalize;
}

.status-pending {
  background-color: #fef3c7;
  color: #d97706;
}

.status-approved { 
  background-color: #dcfce3;
  color: #16a34a;
}

.status-rejected {
  background-color: #fee2e2;
  color: #ef4444;
}

/* Pesan Loading & Kosong */
.state-message {
  padding: 50px;
  text-align: center;
  color: #64748b;
  font-weight: 600;
}

.empty-row {
  color: #94a3b8;
  padding: 40px !important;
}

.text-center {
  text-align: center !important;
}
</style>