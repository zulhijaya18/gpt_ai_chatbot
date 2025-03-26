<template>
    <div class="h-screen flex flex-col p-4 bg-gray-100">
        <div
            class="flex items-center justify-between pb-4 border-b border-gray-200"
        >
            <h2 class="text-2xl font-bold">Profile</h2>
        </div>
        <div
            class="p-4"
        >
            <div v-if="loading" class="text-center">
                <span class="animate-spin"></span> Loading...
            </div>
            <div v-else>
                <p>Name: {{ user.name }}</p>
                <p>Email: {{ user.email }}</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";

const user = ref({});
const loading = ref(false);

const fetchUser = async () => {
    loading.value = true;
    const token = localStorage.getItem('token');
    if (!token) {
        window.location.href = "/login";
        return;
    }
    try {
        const response = await axios.get("/api/user", {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });
        user.value = response.data.user;
    } catch (error) {
        if (error.response?.status === 401) {
            // Handle 401 Unauthorized
            console.error("Unauthorized: Token expired or invalid");
            // Redirect to login
            window.location.href = "/login";
        } else {
            console.error("Error fetching user:", error);
        }
    } finally {
        loading.value = false;
    }
};

fetchUser();
</script>
