/** @type {import('tailwindcss').Config} */
export default {
  content: ['./resources/views/**/*.php', './public/assets/js/**/*.js'],
  theme: {
    extend: {
      colors: {
        ink: '#171316',
        paper: '#f3f1f1',
        wine: '#7b1937',
        brand: '#c5122f',
        blush: '#f1ccd8',
      },
      fontFamily: {
        sans: ['Inter', 'Arial', 'sans-serif'],
        display: ['Georgia', 'Times New Roman', 'serif'],
      },
      boxShadow: {
        glass: 'inset 0 1px 0 rgba(255,255,255,.42), 0 24px 70px rgba(0,0,0,.24)',
      },
    },
  },
  plugins: [],
};
