/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './*.php',
    './parts/**/*.php',
    './templates/**/*.{php,html}',
    './patterns/**/*.php',
    './blocks/**/*.{php,html,js,jsx,ts,tsx}',
    './assets/build/src/**/*.{js,jsx,ts,tsx,html}'
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}



