module.exports = {
  devServer: {
    proxy: 'http://api.ycms/'
  },
  css: {
    loaderOptions: {
      sass: {
        prependData: ' @import "@/assets/app.scss"; '
      }
    }
  }
}
