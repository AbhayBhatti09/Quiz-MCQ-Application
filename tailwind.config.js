import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.js", // ← include JS also
    ],

    safelist: [
        // Status box colors
        "bg-red-100",
        "bg-green-200",

        // Grid layout for question numbers
        "grid",
        "grid-cols-5",
        "gap-2",
        "p-2",

        // If you use more:
        // 'bg-yellow-200',
        // 'bg-blue-200',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
