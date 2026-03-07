<script setup>
import { ref } from "vue"
import api from "@/services/api"
// Import useRouter jika kamu ingin mengarahkan pengguna setelah sukses
// import { useRouter } from 'vue-router'

const form = ref({
  job: "",
  job_description: "",
  income: "",
  reason_accepted: ""
})

const isSubmitting = ref(false)

const submitValidation = async () => {
  // Cegah submit berulang jika sedang loading
  if (isSubmitting.value) return;
  
  isSubmitting.value = true;

  try {
    await api.post("/validation", form.value)
    alert("Validasi berhasil dikirim!")
    
    // Kosongkan form setelah sukses
    form.value = {
      job: "",
      job_description: "",
      income: "",
      reason_accepted: ""
    }

    // Opsi: Arahkan kembali ke halaman sebelumnya atau dashboard
    // const router = useRouter()
    // router.push('/admin/dashboard')

  } catch (error) {
    console.error(error)
    alert("Gagal mengirim validasi. Silakan coba lagi.")
  } finally {
    isSubmitting.value = false;
  }
}
</script>

<template>
  <div class="validation-wrapper">
    <div class="validation-card">
      
      <div class="card-header">
        <h2>Submit Validation</h2>
        <p>Lengkapi data pekerjaan dan pendapatan pemohon</p>
      </div>

      <form @submit.prevent="submitValidation" class="form-validation">
        
        <div class="form-group">
          <label for="job">Pekerjaan</label>
          <input
            id="job"
            v-model="form.job"
            type="text"
            placeholder="Contoh: Pegawai Swasta"
            required
            class="input-field"
          />
        </div>

        <div class="form-group">
          <label for="job_description">Deskripsi Pekerjaan</label>
          <input
            id="job_description"
            v-model="form.job_description"
            type="text"
            placeholder="Contoh: Staff IT di PT XYZ"
            required
            class="input-field"
          />
        </div>

        <div class="form-group">
          <label for="income">Pendapatan per Bulan (Rp)</label>
          <div class="input-prefix-wrapper">
            <span class="prefix">Rp</span>
            <input
              id="income"
              v-model="form.income"
              type="number"
              placeholder="Contoh: 8000000"
              required
              class="input-field with-prefix"
            />
          </div>
        </div>

        <div class="form-group">
          <label for="reason_accepted">Alasan Diterima / Catatan</label>
          <textarea
            id="reason_accepted"
            v-model="form.reason_accepted"
            placeholder="Masukkan alasan mengapa pengajuan ini divalidasi..."
            required
            class="input-field textarea-field"
          ></textarea>
        </div>

        <button type="submit" class="btn-submit" :disabled="isSubmitting">
          {{ isSubmitting ? 'Mengirim Data...' : 'Submit Validation' }}
        </button>

      </form>

    </div>
  </div>
</template>

<style scoped>
/* Wrapper Utama (Background Full Screen) */
.validation-wrapper {
  min-height: calc(100vh - 70px);
  width: 100%;
  background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
  display: flex;
  justify-content: center;
  align-items: flex-start;
  padding: 60px 20px;
  box-sizing: border-box;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Card Form Putih */
.validation-card {
  background: white;
  width: 100%;
  max-width: 600px;
  border-radius: 16px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
  padding: 40px;
}

/* Header Form */
.card-header {
  text-align: center;
  margin-bottom: 30px;
  border-bottom: 2px solid #f1f5f9;
  padding-bottom: 20px;
}

.card-header h2 {
  color: #1e3a8a;
  margin: 0 0 8px 0;
  font-size: 1.8rem;
}

.card-header p {
  color: #64748b;
  margin: 0;
  font-size: 0.95rem;
}

/* Grouping Input */
.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  font-size: 0.85rem;
  font-weight: 600;
  color: #1e3a8a;
  margin-bottom: 8px;
}

/* Styling Input & Textarea */
.input-field {
  width: 100%;
  padding: 0.8rem 1rem;
  border: 1px solid #cbd5e1;
  border-radius: 8px;
  font-size: 1rem;
  color: #1e293b;
  font-family: inherit;
  transition: all 0.3s ease;
  box-sizing: border-box;
}

.input-field:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
}

.input-field::placeholder {
  color: #94a3b8;
}

/* Textarea khusus */
.textarea-field {
  height: 120px;
  resize: vertical; /* Pengguna bisa tarik tingginya saja */
}

/* Styling Input Income dengan Prefix "Rp" */
.input-prefix-wrapper {
  position: relative;
  display: flex;
  align-items: center;
}

.prefix {
  position: absolute;
  left: 1rem;
  color: #64748b;
  font-weight: 600;
}

.with-prefix {
  padding-left: 2.8rem; /* Memberi ruang untuk teks "Rp" */
}

/* Tombol Submit */
.btn-submit {
  width: 100%;
  padding: 1rem;
  background-color: #1e3a8a;
  color: white;
  border: none;
  border-radius: 8px;
  font-size: 1.05rem;
  font-weight: 600;
  cursor: pointer;
  margin-top: 10px;
  transition: all 0.3s ease;
}

.btn-submit:hover:not(:disabled) {
  background-color: #2563eb;
  box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
}

.btn-submit:active:not(:disabled) {
  transform: scale(0.98);
}

/* Tombol saat status Loading / Disabled */
.btn-submit:disabled {
  background-color: #94a3b8;
  cursor: not-allowed;
  opacity: 0.8;
}
</style>