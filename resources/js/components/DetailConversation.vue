<template>
    <div class="h-screen flex flex-col p-4 bg-gray-100">
        <div
            class="flex items-center justify-between pb-4 border-b border-gray-200"
        >
            <h2 class="text-2xl font-bold">Conversation</h2>
        </div>
        <div v-if="loading" class="text-center">
            <span class="animate-spin"></span> Loading...
        </div>
        <div v-else>
            <p>{{ conversation.title }}</p>
        </div>
    </div>
</template>
    
<script setup>
import { ref } from "vue";
import axios from "axios";

const conversation = ref({});
const loading = ref(false);

const fetchConversation = async () => {
    loading.value = true;
    const token = localStorage.getItem('token');
    if (!token) {
        window.location.href = "/login";
        return;
    }
    try {
        const response = await axios.get("/api/conversations/" + window.location.pathname.split('/').pop(), {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });
        conversation.value = response.data.conversation;
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

fetchConversation();
</script>
