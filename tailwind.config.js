module.exports = {
    purge: [],//[]
    theme: {
        extend: {
            colors: {
                web: {
                    primary: "#e7ab3c"
                }
            },
            spacing: {
                '72': '18rem'
            }
        },
    },
    variants: {},
    plugins: [
        require('tailwindcss-rtl'),
    ],
}
