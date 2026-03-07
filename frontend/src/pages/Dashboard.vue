<script setup>
import { onMounted, ref } from 'vue';
import api from '@/services/api';

const cars = ref([]);
const loading = ref(true);
const user = ref(null)

const getCars = async () => {
    try {
        const res = await api.get('/instalment-cars');
        cars.value = res.data.cars;
    } catch (error) {
        console.error("Gagal memuat data mobil:", error);
    } finally {
        loading.value = false;
    }
}

const getUsers = async() => {
    const res = await api.get('/me');
    user.value = res.data[0];
}

const formatPrice = (price) => {
    return new Intl.NumberFormat('id-ID').format(price);
}

onMounted(() => {
    getCars();
    getUsers();
});
</script>

<template>
    <div class="dashboard-wrapper">
        <div class="dashboard-container">
            <header class="dashboard-header">
                <div>
                    <h1 class="title">Koleksi Mobil</h1>
                    <p class="subtitle">Temukan mobil impian dengan cicilan terbaik</p>
                </div>
                <div class="user-badge">
                    {{ user?.name || 'loading...' }}
                </div>
            </header>

            <div v-if="loading" class="cars-grid">
                <div v-for="n in 4" :key="n" class="skeleton-card"></div>
            </div>

            <div v-else class="cars-grid">
                <div v-for="car in cars" :key="car.id" class="cars-card">
                    <div class="card-image-placeholder">
                        <span class="brand-tag">{{ car.brand }}</span>
                    </div>
                    
                    <div class="card-body">
                        <h3 class="car-name">{{ car.car }}</h3>
                        <div class="price-section">
                            <span class="label">Harga</span>
                            <p class="price">Rp {{ formatPrice(car.price) }}</p>
                        </div>

                        <router-link :to="`/cars/${car.id}`" style="text-decoration: none;">
                            <button class="btn-detail">
                                Lihat Detail
                                <span class="icon">→</span>
                            </button>
                        </router-link>
                    </div>
                </div>
            </div>

            <div v-if="!loading && cars.length === 0" class="empty-state">
                Data mobil tidak tersedia.
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Background diletakkan di wrapper agar full width */
.dashboard-wrapper {
    min-height: 100vh;
    width: 100%;
    background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); 
    margin: 0;
    padding: 0;
}

/* Container untuk membatasi lebar konten di tengah */
.dashboard-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 40px 20px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: white; 
}

.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 40px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.2); 
    padding-bottom: 20px;
}

.title {
    font-size: 2rem;
    color: white;
    margin: 0;
}

.subtitle {
    color: rgba(255, 255, 255, 0.8);
    margin-top: 5px;
}

.user-badge {
    background: white;
    color: #1e3a8a;
    padding: 6px 16px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.8rem;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

/* Grid Layout */
.cars-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 25px;
}

/* Card Design */
.cars-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    border: none;
}

.cars-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
}

.card-image-placeholder {
    height: 160px;
    background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}

.brand-tag {
    position: absolute;
    top: 12px;
    left: 12px;
    background: #1e3a8a;
    padding: 4px 12px;
    border-radius: 8px;
    font-size: 0.7rem;
    font-weight: 800;
    color: white;
    text-transform: uppercase;
}

.card-body {
    padding: 20px;
}

.car-name {
    margin: 0 0 10px 0;
    font-size: 1.25rem;
    color: #1e3a8a; 
}

.price-section {
    margin-bottom: 20px;
}

.price-section .label {
    font-size: 0.75rem;
    color: #64748b; 
    display: block;
}

.price {
    font-size: 1.15rem;
    font-weight: 700;
    color: #3b82f6; 
    margin: 0;
}

/* Button Detail */
.btn-detail {
    width: 100%;
    padding: 0.8rem;
    background-color: #1e3a8a;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: background 0.3s ease, transform 0.3s;
}

.btn-detail:hover {
    background-color: #2563eb;
}

.btn-detail:active {
    transform: scale(0.98);
}

/* Loading Skeleton */
.skeleton-card {
    height: 320px;
    background: white;
    border-radius: 16px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
}

.skeleton-card::after {
    content: "";
    position: absolute;
    top: 0; right: 0; bottom: 0; left: 0;
    background: linear-gradient(90deg, transparent, rgba(0,0,0,0.05), transparent);
    animation: wave 1.5s infinite;
}

@keyframes wave {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

.empty-state {
    text-align: center;
    padding: 100px;
    color: rgba(255, 255, 255, 0.9);
}
</style>