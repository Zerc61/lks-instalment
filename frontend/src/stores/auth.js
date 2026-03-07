import { ref } from "vue";
import api from "@/services/api";

const user = ref(null);

export const useAuth = () => {
    const fetchUser = async () => {
        try {
            const res = await api.get('/me');
            user.value = res.data;
        } catch (error) {
            user.value = null
        }
    }

    const logout = async () => {
        await api.post('/auth/logout');
        localStorage.removeItem('token');
        user.value = null;
    }

    return {
        user,
        fetchUser,
        logout,
    }
}