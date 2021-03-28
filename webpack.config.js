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
      host: '0.0.0.0', // host machine ip
      port: 8080
    }
  }
}
