<script setup>
import { ref, onMounted } from "vue"
import api from "@/services/api"

const validations = ref([])
const loading = ref(true)

const getValidations = async () => {
  try {
    const res = await api.get("/admin/validations")
    validations.value = res.data.validations
  } catch (error) {
    console.error(error)
  } finally {
    loading.value = false
  }
}

const updateStatus = async (id, status) => {
  // Tambahkan konfirmasi agar admin tidak salah klik
  if (!confirm(`Apakah Anda yakin ingin mengubah status validasi menjadi ${status}?`)) return;

  try {
    await api.patch(`/admin/validations/${id}`, {
      status: status
    })
    
    // Opsional: Bisa pakai alert, atau langsung refresh data saja agar lebih mulus
    // alert("Status updated")
    
    getValidations()
  } catch (error) {
    console.error(error)
    alert("Gagal memperbarui status.")
  }
}

// Fungsi format angka untuk Income
const formatPrice = (price) => {
  return new Intl.NumberFormat("id-ID").format(price)
}

// Fungsi warna badge status
const getStatusClass = (status) => {
  const s = status ? status.toLowerCase() : '';
  if (s.includes('accept') || s.includes('diterima')) return 'status-approved';
  if (s.includes('reject') || s.includes('ditolak')) return 'status-rejected';
  return 'status-pending';
}

onMounted(() => {
  getValidations()
})
</script>

<template>
  <div class="admin-wrapper">
    <div class="admin-container">

      <div class="page-header">
        <h1 class="title">Validasi Pemohon</h1>
        <p class="subtitle">Kelola data pekerjaan dan pendapatan dari masyarakat</p>
      </div>

      <div class="table-card">
        
        <div class="card-header">
          <h3>Daftar Validasi Masuk</h3>
        </div>

        <div v-if="loading" class="state-message">
          <p>Memuat data validasi...</p>
        </div>

        <div class="table-responsive" v-else>
          <table class="pro-table">
            <thead>
              <tr>
                <th>Nama Pemohon</th>
                <th>Pekerjaan</th>
                <th>Pendapatan (Bulan)</th>
                <th>Status</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            
            <tbody>
              <tr v-if="validations.length === 0">
                <td colspan="5" class="text-center empty-row">
                  Belum ada data validasi yang masuk.
                </td>
              </tr>

              <tr v-for="v in validations" :key="v.id">
                
                <td class="font-semibold text-dark">
                  {{ v.society?.name || 'Anonim' }}
                </td>
                
                <td class="job-text">
                  {{ v.job }}
                </td>
                
                <td class="income-text">
                  Rp {{ formatPrice(v.income) }}
                </td>
                
                <td>
                  <span :class="['status-badge', getStatusClass(v.status)]">
                    {{ v.status }}
                  </span>
                </td>
                
                <td>
                  <div class="action-buttons">
                    <button
                      @click="updateStatus(v.id, 'accepted')"
                      class="btn-sm btn-accept"
                      title="Terima Validasi"
                    >
                      ✓ Accept
                    </button>

                    <button
                      @click="updateStatus(v.id, 'rejected')"
                      class="btn-sm btn-reject"
                      title="Tolak Validasi"
                    >
                      ✕ Reject
                    </button>
                  </div>
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
  max-width: 1100px;
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

/* --- Kartu Tabel --- */
.table-card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
  overflow: hidden;
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
  padding: 16px 20px;
  text-align: left;
}

.pro-table th {
  background-color: #f8fafc;
  color: #64748b;
  font-size: 0.85rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  font-weight: 700;
  border-bottom: 2px solid #e2e8f0;
}

.pro-table td {
  border-bottom: 1px solid #f1f5f9;
  font-size: 0.95rem;
  vertical-align: middle;
  transition: background-color 0.2s;
}

.pro-table tbody tr:hover td {
  background-color: #f8fafc;
}

/* Elemen Teks Khusus */
.font-semibold {
  font-weight: 600;
}

.text-dark {
  color: #0f172a;
}

.job-text {
  color: #475569;
}

.income-text {
  color: #1e3a8a;
  font-weight: 600;
}

/* Badge Status */
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

/* Tombol Aksi di dalam Tabel */
.action-buttons {
  display: flex;
  gap: 8px;
  justify-content: center;
}

.btn-sm {
  padding: 6px 12px;
  font-size: 0.85rem;
  font-weight: 600;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  transition: all 0.2s ease;
  display: flex;
  align-items: center;
  gap: 4px;
}

.btn-accept {
  background-color: #10b981;
  color: white;
}

.btn-accept:hover {
  background-color: #059669;
  box-shadow: 0 2px 5px rgba(16, 185, 129, 0.3);
}

.btn-reject {
  background-color: #ef4444;
  color: white;
}

.btn-reject:hover {
  background-color: #dc2626;
  box-shadow: 0 2px 5px rgba(239, 68, 68, 0.3);
}

.btn-sm:active {
  transform: scale(0.95);
}

/* Utilities */
.text-center {
  text-align: center !important;
}

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
</style>