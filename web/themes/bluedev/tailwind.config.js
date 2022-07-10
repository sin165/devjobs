/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: 'class',
  content: [
    "./templates/**/*.{html,js,php,twig}",
    "../../modules/custom/devjobs/templates/**/*.{html,js,php,twig}"
  ],
  theme: {
    extend: {
      colors: {
        'vio': '#5964E0',
        'lightvio': '#939BF4',
        'darkblue': '#19202D',
        'midnight': '#121721',
        'lightgray': '#F4F6F8',
        'midgray': '#9DAEC2',
        'darkgray': '#6E8098'
      }
    },
  },
  plugins: [],
}
