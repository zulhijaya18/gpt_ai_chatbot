import "./bootstrap";
import { createApp } from "vue";
import Chat from "./components/Chat.vue";
import Login from "./components/Login.vue";
import Register from "./components/Register.vue";
import Profile from "./components/Profile.vue";
import ListConversation from "./components/ListConversation.vue";
import CreateConversation from "./components/CreateConversation.vue";
import DetailConversation from "./components/DetailConversation.vue";

// Initialize the appropriate Vue component based on route
const app = createApp({});

const path = window.location.pathname;
console.log("Current path:", path);

// Use startsWith to check for routes with parameters
if (path.startsWith("/chat/")) {
    app.component("chat-app", Chat);
} else if (path === "/login") {
    app.component("login-app", Login);
} else if (path === "/register") {
    app.component("register-app", Register);
} else if (path === "/profile") {
    app.component("profile-app", Profile);
} else if (path === "/list-conversation") {
    app.component("list-conversation-app", ListConversation);
} else if (path === "/create-conversation") {
    app.component("create-conversation-app", CreateConversation);
} else if (path === "/detail-conversation") {
    app.component("detail-conversation-app", DetailConversation);
}

app.mount("#app");
