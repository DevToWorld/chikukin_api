module.exports = {
  publicPath: './',
  devServer: {
    historyApiFallback: {
      rewrites: [
        { from: /.*/, to: '/index.html' }
      ]
    }
  }
}
