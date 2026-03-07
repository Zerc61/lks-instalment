<script setup>
import { onMounted, ref } from 'vue';
import { useRoute } from 'vue-router'
import api from '@/services/api';

const route = useRoute();
const car = ref(null);
const months = ref([]);
const selectedMonths = ref(null);
const notes = ref('');

const getCarDetail = async () => {
    try {
        const carId = route.params.id
        const res = await api.get('/instalment-cars');
        car.value = res.data.cars.find(c => c.id == carId);

        const monthsRes = await api.get(`/instalment-cars/${carId}/months`);
        months.value = monthsRes.data.months
    } catch (error) {
        console.error(error)
    }
}

const formatPrice = (price) => {
    if (!price || isNaN(price)) return "0";
    return new Intl.NumberFormat('id-ID').format(price)
}

const applyInstalment = async () => {
  try {
    const carId = route.params.id

    await api.post('/applications', {
      instalment_car_id: carId,
      months: selectedMonths.value,
      notes: notes.value
    })

    alert('Pengajuan berhasil dikirim!')

  } catch (error) {
    alert(error.response?.data?.message || 'Terjadi kesalahan saat mengirim pengajuan.')
  }
}

onMounted(() => {
    getCarDetail();
})
</script>

<template>
    <div class="detail-wrapper">
        <div class="detail-card" v-if="car">
            
            <router-link to="/dashboard" class="btn-back">
                ← Kembali
            </router-link>

            <div class="header-section">
                <p class="brand">{{ car.brand }}</p>
                <h1 class="car-title">{{ car.car }}</h1>
                <p class="price">Rp {{ formatPrice(car.price) }}</p>
            </div>

            <div class="tenor-section">
                <h3>Pilih Tenor Cicilan</h3>
                <div class="months">
                    <button 
                        v-for="m in months" 
                        :key="m.id"
                        :class="['month-btn', { active: selectedMonths === m.month }]"
                        @click="selectedMonths = m.month"
                    >
                        {{ m.month }} Bulan
                    </button>
                </div>
            </div>

            <div v-if="selectedMonths" class="apply-box">
                <h3>Ringkasan Pengajuan</h3>
                
                <div class="summary-details">
                    <div class="summary-row">
                        <span>Tenor Pilihan:</span>
                        <strong>{{ selectedMonths }} Bulan</strong>
                    </div>
                    <div class="summary-row">
                        <span>Estimasi Cicilan:</span>
                        <strong class="highlight-price">Rp {{ formatPrice(Math.round(car.price/selectedMonths)) }} / bulan</strong>
                    </div>
                </div>

                <div class="form-group">
                    <label for="notes">Catatan Tambahan (Opsional)</label>
                    <textarea
                        id="notes"
                        v-model="notes"
                        placeholder="Masukkan catatan untuk admin..."
                        class="notes-input"
                    ></textarea>
                </div>

                <button class="apply-btn" @click="applyInstalment">
                    Ajukan Cicilan Sekarang
                </button>
            </div>

        </div>
    </div>
</template>

<style scoped>
/* Wrapper Layout */
.detail-wrapper {
    min-height: 100vh;
    width: 100%;
    background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
    display: flex;
    justify-content: center;
    align-items: flex-start;
    padding: 60px 20px;
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Card Utama */
.detail-card {
    background: white;
    width: 100%;
    max-width: 800px;
    border-radius: 16px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    padding: 40px;
}

/* Tombol Kembali */
.btn-back {
    display: inline-block;
    color: #64748b;
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 25px;
    transition: color 0.3s ease;
}

.btn-back:hover {
    color: #1e3a8a;
}

/* Header & Typography */
.header-section {
    border-bottom: 2px solid #f1f5f9;
    padding-bottom: 20px;
    margin-bottom: 25px;
}

.brand {
    color: #64748b;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 1px;
    margin: 0 0 5px 0;
}

.car-title {
    color: #1e3a8a;
    font-size: 2.2rem;
    margin: 0 0 10px 0;
}

.price {
    font-size: 1.8rem;
    font-weight: 700;
    color: #3b82f6;
    margin: 0;
}

/* Bagian Tenor */
.tenor-section h3 {
    color: #1e3a8a;
    font-size: 1.2rem;
    margin-bottom: 15px;
}

.months {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 20px;
}

.month-btn {
    padding: 12px 24px;
    border: 2px solid #e2e8f0;
    background: white;
    color: #64748b;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    border-radius: 8px;
    transition: all 0.2s ease;
}

.month-btn:hover {
    border-color: #3b82f6;
    color: #3b82f6;
    background: #eff6ff;
}

.month-btn.active {
    background: #1e3a8a;
    border-color: #1e3a8a;
    color: white;
    box-shadow: 0 4px 10px rgba(30, 58, 138, 0.2);
}

/* Box Form Pengajuan (Apply Box) */
.apply-box {
    margin-top: 30px;
    background-color: #f8fafc; /* Latar belakang abu-abu sangat muda/kebiruan */
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 25px;
}

.apply-box h3 {
    color: #1e3a8a;
    margin-top: 0;
    margin-bottom: 20px;
    font-size: 1.2rem;
    border-bottom: 1px solid #e2e8f0;
    padding-bottom: 10px;
}

.summary-details {
    margin-bottom: 20px;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
    font-size: 1.05rem;
    color: #475569;
}

.summary-row strong {
    color: #1e3a8a;
}

.highlight-price {
    color: #3b82f6 !important;
    font-size: 1.2rem;
}

/* Form Input (Sama dengan halaman Login) */
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

.notes-input {
    width: 100%;
    height: 100px;
    padding: 0.75rem 1rem;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 1rem;
    font-family: inherit;
    transition: all 0.3s ease;
    box-sizing: border-box;
    resize: vertical; /* User bisa tarik tinggi box, tapi tidak lebarnya */
}

.notes-input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
}

/* Tombol Apply (Sama dengan Tombol Login) */
.apply-btn {
    width: 100%;
    padding: 0.9rem;
    background-color: #1e3a8a;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 1.05rem;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s ease, transform 0.1s;
}

.apply-btn:hover {
    background-color: #2563eb;
}

.apply-btn:active {
    transform: scale(0.98);
}
</style>