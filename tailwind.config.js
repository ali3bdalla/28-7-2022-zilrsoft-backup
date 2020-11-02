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
                '72': '18rem',
                '100': '25rem',
                '120': '30rem',
            }
        },
    },
    variants: {},
    plugins: [
        require('tailwindcss-rtl'),
    ],
}
