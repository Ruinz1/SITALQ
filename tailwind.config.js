/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', 'sans-serif'],
            },
            colors: {
                'primary-blue': '#2192FF',
                'primary-green': '#38E54D',
                'primary-lime': '#9CFF2E',
                'primary-yellow': '#FDFF00',
                'biru-terang': '#2192FF',
                'hijau-sedang': '#38E54D', 
                'hijau-muda': '#9CFF2E',
                'kuning-cerah': '#FDFF00'
            },
            backgroundImage: {
                'gradient-custom': 'linear-gradient(to bottom, #2192FF, #38E54D, #9CFF2E, #FDFF00)',
            }
        },
    },
    plugins: [],
};
