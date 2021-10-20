const colors = require('tailwindcss/colors')

module.exports = {
  purge: [],
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
        'open-sans': ['"Open Sans"', 'sans-serif']
      }
    }
  },
  variants: {
    extend: {},
  },
  plugins: [],
}
