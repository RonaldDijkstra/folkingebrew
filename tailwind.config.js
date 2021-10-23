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
      textgray: "#3f3f46",
      gray: colors.gray,
      green: {
        default: "#54b848",
        darker: "#3B9F2F",
        dark: "#326f2b",
      },
      fontSize: {
        sm: ['14px', '20px'],
        base: ['24px', '24px'],
        lg: ['20px', '28px'],
        xl: ['24px', '32px'],
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
