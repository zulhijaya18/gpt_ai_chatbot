<template>
    <div class="h-screen flex flex-col p-4 bg-gray-100">
        <div
            class="flex items-center justify-between pb-4 border-b border-gray-200"
        >
            <h2 class="text-2xl font-bold">Chat</h2>
            <a
                href="/profile"
                class="px-4 py-2 bg-white border text-black rounded hover:bg-gray-100"
            >
                Profile
            </a>
        </div>
        <!-- Chat content -->
        <div class="flex-1 overflow-y-auto space-y-4" ref="chatBox">
            <div
                v-for="(msg, index) in messages"
                :key="index"
                class="flex flex-col"
            >
                <div :class="msg.role === 'user' ? 'text-right' : 'text-left'">
                    <div
                        class="inline-block px-4 py-2 rounded"
                        :class="
                            msg.role === 'user'
                                ? 'bg-blue-500 text-white'
                                : 'bg-white text-black border'
                        "
                    >
                        <div v-if="msg.status === 'thinking'">
                            💡 AI is thinking...
                        </div>
                        <div v-else-if="msg.status === 'generating'">
                            <div class="flex space-x-1">
                                <div
                                    class="w-2 h-2 bg-blue-400 rounded-full animate-bounce"
                                ></div>
                                <div
                                    class="w-2 h-2 bg-blue-400 rounded-full animate-bounce delay-150"
                                ></div>
                                <div
                                    class="w-2 h-2 bg-blue-400 rounded-full animate-bounce delay-300"
                                ></div>
                            </div>
                        </div>
                        <div v-html="markedContent(msg.content)"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Input box -->
        <div class="mt-4 flex">
            <textarea
                v-model="input"
                rows="2"
                class="flex-1 border rounded p-2 focus:outline-none"
                placeholder="Type your question, press Enter to send"
                @keydown.enter.exact.prevent="sendMessage"
            />
            <button
                @click="sendMessage"
                class="ml-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
            >
                Send
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, nextTick } from "vue";
import { marked } from "marked";

const messages = ref([]);
const input = ref("");

const sendMessage = async () => {
    if (!input.value.trim()) return;

    // Add user message
    const userMsg = { role: "user", content: input.value, status: "complete" };
    messages.value.push(userMsg);

    // AI placeholder
    const aiMsg = { role: "assistant", content: "", status: "thinking" };
    messages.value.push(aiMsg);

    input.value = "";

    const conversationId = window.location.pathname.split("/").pop();

    try {
        const response = await fetch("/api/chat", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
            body: JSON.stringify({
                message: userMsg.content,
                conversation_id: conversationId,
            }),
        });

        aiMsg.status = "generating";
        const reader = response.body.getReader();
        const decoder = new TextDecoder();

        while (true) {
            const { done, value } = await reader.read();
            if (done) break;
            aiMsg.content += decoder.decode(value);
            await nextTick(() => {
                const el = document.querySelector(".flex-1");
                el.scrollTop = el.scrollHeight;
            });
        }

        aiMsg.status = "complete";
    } catch (e) {
        aiMsg.content = "⚠️ AI service unavailable";
        aiMsg.status = "error";
    }
};

const markedContent = (text) => marked.parse(text, { breaks: true });
</script>
