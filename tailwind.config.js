const colors = require('tailwindcss/colors')

module.exports = {
  purge: [
     './source/**/*.html',
     './source/**/*.erb',
     './source/**/*.js'
   ],
  darkMode: false, // or 'media' or 'class'
  theme: {
    colors: {
      transparent: 'transparent',
      white: colors.white,
      black: colors.black,
      gray: colors.gray,
      green: {
        default: "#54b848",
        dark: "#326f2b",
      },
    },
    extend: {
      fontFamily: {
        'sans': ['Open Sans', 'sans-serif']
      },
      backgroundImage: {
        'beers': "url('beers.jpg')"
      }
    }
  },
  variants: {
    extend: {},
  },
  plugins: [
    require('@tailwindcss/typography'),
  ],
}
