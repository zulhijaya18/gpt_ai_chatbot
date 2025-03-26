<template>
    <div class="h-screen flex flex-col p-4 bg-gray-100">
        <div
            class="flex items-center justify-between pb-4 border-b border-gray-200"
        >
            <h2 class="text-2xl font-bold">Create Conversation</h2>
        </div>
        <form @submit.prevent="createConversation">
            <div class="mb-4">
                <label
                    for="title"
                    class="block text-sm font-medium text-gray-700"
                    >Title</label
                >
                <input
                    type="text"
                    id="title"
                    name="title"
                    class="mt-1 p-2 border rounded w-full"
                />
            </div>
            <button
                type="submit"
                class="bg-blue-500 text-white px-4 py-2 rounded"
            >
                Create
            </button>
        </form>
    </div>
</template>

<script setup>
import axios from "axios";

const createConversation = async () => {
    const title = document.getElementById("title").value;
    try {
        const response = await axios.post(
            "/api/conversations",
            {
                title: title,
            },
            {
                headers: {
                    Authorization: `Bearer ${localStorage.getItem("token")}`,
                },
            }
        );
        window.location.href = `/list-conversation`;
    } catch (error) {
        if (error.response && error.response.status === 401) {
            window.location.href = "/login";
        }
        console.error("Error creating conversation:", error);
    }
};
</script>
