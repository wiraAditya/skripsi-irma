import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        cors: true,
        host: "0.0.0.0", // Allow external access
        port: 3000,
        hmr: {
            host: "https://e8f6-182-253-178-21.ngrok-free.app", // Your ngrok domain
            protocol: "wss", // Required for secure WebSocket
        },
    },
});
