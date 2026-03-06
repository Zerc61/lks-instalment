<script setup>
import { ref } from 'vue'
import api from '@/services/api'
import { useRouter } from 'vue-router'

const router = useRouter()

const form = ref({
  idCard: "",
  password: ""
})

const login = async () => {
  try {
    const res = await api.post('/auth/login', {
      id_card_number: form.value.idCard,
      password: form.value.password
    })

    const token = res.data.token
    localStorage.setItem('token', token)
    router.push('/dashboard')

  } catch (error) {
    alert('Login gagal, periksa kembali ID & Password Anda.')
    console.error(error)
  }
}
</script>

<template>
  <div class="login-page">
    <div class="login-card">
      <div class="login-header">
        <h2>Selamat Datang</h2>
        <p>Silakan masuk ke akun Anda</p>
      </div>

      <form @submit.prevent="login" class="form-login">
        <div class="form-group">
          <label>ID Card Number</label>
          <input 
            v-model="form.idCard" 
            type="text" 
            placeholder="Masukkan ID Card" 
            required
          >
        </div>

        <div class="form-group">
          <label>Password</label>
          <input 
            v-model="form.password" 
            type="password" 
            placeholder="••••••••" 
            required
          >
        </div>

        <button type="submit" class="btn-login">Masuk</button>
      </form>
    </div>
  </div>
</template>

<style scoped>
/* Reset dasar untuk container */
.login-page {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  /* Gradient biru ke biru muda */
  background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.login-card {
  background: white;
  padding: 2.5rem;
  border-radius: 16px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
  width: 100%;
  max-width: 400px;
}

.login-header {
  text-align: center;
  margin-bottom: 2rem;
}

.login-header h2 {
  color: #1e3a8a;
  margin: 0;
  font-size: 1.8rem;
}

.login-header p {
  color: #64748b;
  font-size: 0.9rem;
  margin-top: 0.5rem;
}

.form-group {
  margin-bottom: 1.5rem;
}

.form-group label {
  display: block;
  font-size: 0.85rem;
  font-weight: 600;
  color: #1e3a8a;
  margin-bottom: 0.5rem;
}

.form-group input {
  width: 100%;
  padding: 0.75rem 1rem;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  font-size: 1rem;
  transition: all 0.3s ease;
  box-sizing: border-box; /* Biar padding gak nambah lebar input */
}

.form-group input:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
}

.btn-login {
  width: 100%;
  padding: 0.8rem;
  background-color: #1e3a8a;
  color: white;
  border: none;
  border-radius: 8px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.3s ease;
  margin-top: 1rem;
}

.btn-login:hover {
  background-color: #2563eb;
}

.btn-login:active {
  transform: scale(0.98);
}
</style>
