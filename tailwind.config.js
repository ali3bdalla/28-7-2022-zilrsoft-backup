module.exports = {
  purge: [],//[]
  theme: {
    extend: {
      colors:{
        web:{
          primary:"#e7ab3c"
        }
      }
    },
  },
  variants: {},
  plugins: [
    require('tailwindcss-rtl'),
  ],
}
