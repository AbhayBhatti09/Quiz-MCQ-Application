import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    // server: {
    //     host: "0.0.0.0", // Important: listen to all devices
    //     port: 5173, // Vite default port
    //     strictPort: true,
    //     hmr: {
    //         host: "192.168.1.42", // Your IP here
    //         protocol: "ws",
    //         port: 5173,
    //     },
    //     cors: true, // <-- solves CORS error
    // },

    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
    ],
});
