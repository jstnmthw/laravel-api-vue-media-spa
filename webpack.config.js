const path = require('path')

module.exports = {
  resolve: {
    extensions: ['.js', '.vue', '.json'],
    alias: {
      '@': path.resolve('resources/js')
    }
  },
  devServer: {
    proxy: {
      '*': 'http://localhost'
    },
    clientLogLevel: 'none'
  }
}
