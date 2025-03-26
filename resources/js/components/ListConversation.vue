<template>
    <div class="h-screen flex flex-col p-4 bg-gray-100">
        <div
            class="flex items-center justify-between pb-4 border-b border-gray-200"
        >
            <h2 class="text-2xl font-bold">Conversation</h2>
            <a
                href="/create-conversation"
                class="bg-blue-500 text-white px-4 py-2 rounded"
            >
                New Conversation
            </a>
        </div>
        <div class="p-2">
            <div v-if="loading" class="text-center">
                <span class="animate-spin"></span> Loading...
            </div>
            <div v-else-if="!conversations.length" class="text-center">
                No conversations found.
            </div>
            <div v-else>
                <div
                    v-for="conversation in conversations"
                    :key="conversation.id"
                >
                    <div class="flex items-center justify-between border-b border-gray-200 py-4">
                        <div>
                            <p>{{ conversation.title }}</p>
                            <p class="text-gray-500 text-xs">
                                {{
                                    new Date(
                                        conversation.updated_at
                                    ).toLocaleString()
                                }}
                            </p>
                        </div>
                        <a
                            :href="`/chat/${conversation.id}`"
                            class="text-blue-500"
                            >Open</a
                        >
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";

const conversations = ref([]);
const loading = ref(false);

const fetchConversations = async () => {
    loading.value = true;
    const token = localStorage.getItem("token");
    if (!token) {
        window.location.href = "/login";
        return;
    }
    try {
        const response = await axios.get("/api/conversations", {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });
        conversations.value = response.data.conversations;
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

fetchConversations();
</script>
