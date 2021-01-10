module.exports = {
    purge: [
        './resource/**/*.html',
        './resource/**/*.vue'
    ],//[]
    theme: {
        extend: {
            colors: {
                web: {
                    primary: "#e7ab3c"
                }
            },
            spacing: {
                spacing: {
                    "72": "18rem",
                    "84": "21rem",
                    "96": "24rem",
                    "110": "27.5rem",
                    "120": "30rem",
                    "140": "35rem",
                    "240": "60rem",
                    "280": "70rem",
                    "320": "80rem",
                    "360": "90rem",
                    "400": "100rem",
                    "500": "125rem",
                    "640": "150rem",
                }
            }
        },
    },
    variants: {},
    plugins: [
        require('tailwindcss-rtl'),
    ],
}
