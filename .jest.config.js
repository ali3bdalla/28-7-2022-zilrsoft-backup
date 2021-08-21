module.exports = {
    testRegex: '*.test.js$',
    moduleFileExtensions: [
        'js',
        'json',
        'vue'
    ],
    'transform': {
        '^.+\.js$': 'node_modules/babel-jest',
        '.*\.(vue)$': 'node_modules/vue-jest'
    }
}
