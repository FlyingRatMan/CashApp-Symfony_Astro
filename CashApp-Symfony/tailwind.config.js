/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
    "./src/Components/**/*.php",
  ],
  theme: {
    extend: {
      fontFamily: {
        default: ['Roboto', 'sans-serif'],
      },
      colors: {
        orange: {
          50: '#fcfaf7',
          100: '#fcf6f0',
          200: '#f7e9dc',
          300: '#f2dcc7',
          400: '#e8c29e',
          500: '#DEAA79',
          600: '#c7650a',
          700: '#854f1d',
          800: '#633f1e',
          900: '#422d19',
          950: '#211810',
        },
        green: {
          50: '#f4f7f0',
          100: '#eaf2e1',
          200: '#ccdeb8',
          300: '#aec791',
          400: '#7b9e52',
          500: '#507526',
          600: '#396903',
          700: '#2a4709',
          800: '#21360a',
          900: '#172408',
          950: '#0c1205',
        }
      }
    }
  },
  plugins: [],
}

