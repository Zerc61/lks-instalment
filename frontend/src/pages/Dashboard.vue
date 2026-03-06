<script setup>
import { onMounted } from 'vue';
import api from '@/services/api';

onMounted(async () => {
    const res = await api.get('/me');

    console.log(res.data)
})
</script>

<template>
    <div class="container">
        <h1>Dashboard</h1>
    </div>
</template>