const colors = require('tailwindcss/colors')

module.exports = {
  purge: [
     './source/**/*.html',
     './source/**/*.erb',
     './source/**/*.js',
     './components/**/*.rb'
   ],
  darkMode: false, // or 'media' or 'class'
  theme: {
    colors: {
      transparent: 'transparent',
      white: colors.white,
      black: colors.black,
      textgray: "#3f3f46",
      gray: colors.gray,
      red: colors.red,
      green: {
        default: "#54b848",
        darker: "#3B9F2F",
        dark: "#326f2b",
      },
    },
    extend: {
      fontFamily: {
        'sans': ['Open Sans', 'sans-serif']
      },
      backgroundImage: {
        'beers': "url('beers.jpg')"
      },
    }
  },
  variants: {
    extend: {},
  },
  plugins: [
    require('@tailwindcss/typography'),
  ],
}
