const path = require('path')

module.exports = {
  resolve: {
    alias: {
      appPath: path.resolve(__dirname, 'resources/js/app'),
      storePath: path.resolve(__dirname, 'resources/js/store-app')
    }
  }
}
