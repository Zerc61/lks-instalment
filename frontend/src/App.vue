<script setup>
import { useRoute, useRouter } from "vue-router"
import { ref, watchEffect } from "vue"

const route = useRoute()
const router = useRouter()

const isLoggedIn = ref(!!localStorage.getItem("token"))
// Tambahkan ref untuk menangkap role
const userRole = ref(localStorage.getItem("role"))

// Pantau perubahan token dan role
watchEffect(() => {
  isLoggedIn.value = !!localStorage.getItem("token")
  userRole.value = localStorage.getItem("role")
})

// Fungsi Logout
const handleLogout = () => {
  // Hapus token dan role dari penyimpanan browser
  localStorage.removeItem("token");
  localStorage.removeItem("role");
  
  // Update state
  isLoggedIn.value = false;
  userRole.value = null;
  
  // Arahkan kembali ke login
  router.push("/");
}
</script>

<template>
  <div v-if="isLoggedIn" class="navbar-container">
    <nav class="navbar">
      <div class="nav-links">
        
        <template v-if="userRole === 'admin'">
          <router-link to="/admin/dashboard">Dashboard</router-link>
          <router-link to="/admin/applications">Applications</router-link>
          <router-link to="/admin/validations">Validations</router-link>
        </template>

        <template v-else-if="userRole === 'society'">
          <router-link to="/dashboard">Dashboard</router-link>
          <router-link to="/applications">Applications</router-link>
          <router-link to="/validations"> Validation </router-link>
        </template>

      </div>
      
      <button @click="handleLogout" class="btn-logout">Logout</button>
    </nav>
  </div>

  <main class="main-content">
    <router-view></router-view>
  </main>
</template>

<style scoped>
/* Wadah luar navbar */
.navbar-container {
  background-color: rgb(24, 73, 179);
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
  position: sticky; 
  top: 0;
  z-index: 100;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Bagian dalam navbar agar rapi di tengah */
.navbar {
  max-width: 1200px;
  margin: 0 auto;
  padding: 15px 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

/* Wadah untuk link navigasi */
.nav-links {
  display: flex;
  gap: 15px;
}

.nav-links a {
  text-decoration: none;
  color: #ffffff; 
  font-weight: 600;
  font-size: 0.95rem;
  padding: 10px 20px;
  border-radius: 8px;
  transition: all 0.3s ease;
}

.nav-links a:hover {
  background-color: #eff6ff; 
  color: #3b82f6; 
}

.nav-links a.router-link-active {
  background-color: #1e3a8a; 
  color: white;
  box-shadow: 0 4px 10px rgba(30, 58, 138, 0.2);
}

/* --- CSS Baru untuk Tombol Logout --- */
.btn-logout {
  padding: 8px 20px;
  background-color: rgb(255, 0, 0);
  color: #ffffff; 
  border: 2px solid #fee2e2;
  border-radius: 8px;
  font-size: 0.95rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-logout:hover {
  background-color: #9c0505ef;
  border-color: #ffffff;
  box-shadow: 0 4px 10px rgba(239, 68, 68, 0.15);
}

.btn-logout:active {
  transform: scale(0.95);
}
/* ---------------------------------- */

/* Mengatur main content agar rapi */
.main-content {
  min-height: calc(100vh - 70px); 
}
</style>