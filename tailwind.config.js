const colors = require('tailwindcss/colors');

module.exports = {
  content: [
    './source/**/*.html',
    './source/**/*.erb',
    './source/**/*.js',
    './components/**/*.rb'
  ],
  theme: {
    colors: {
      transparent: 'transparent',
      white: colors.white,
      black: colors.black,
      textgray: '#3f3f46',
      gray: colors.gray,
      red: colors.red,
      green: {
        default: '#54b848',
        darker: '#3B9F2F',
        dark: '#326f2b',
      },
      'border-gray': '#71717a',
      neutral: '#171717'
    },
    extend: {
      fontFamily: {
        sans: ['Open Sans', 'sans-serif'],
        black: ['Orbitron', 'sans-serif'],
        bebas: ['Bebas Neue', 'sans-serif'],
      },
    }
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
  ],
};
