import "./bootstrap";
import { createApp } from "vue";
import Chat from "./components/Chat.vue";
import Login from "./components/Login.vue";
import Register from "./components/Register.vue";

// Initialize the appropriate Vue component based on route
const app = createApp({});

switch (window.location.pathname) {
    case "/chat":
        app.component("chat-app", Chat);
        break;
    case "/login":
        app.component("login-app", Login);
        break;
    case "/register":
        app.component("register-app", Register);
        break;
}

app.mount("#app");
