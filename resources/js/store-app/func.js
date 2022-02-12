
const currencyFormatter = require('currency-formatter')
const Vue = require('vue')

Vue.prototype.$currency = {
  formatter: currencyFormatter
}
Vue.prototype.$asset = (url) => {
  return '/' + url
}

Vue.prototype.$sound = {
  play: (name) => {
    const audio = new Audio(`./../../../sounds/${name}`)
    audio.play()
  }
}
Vue.prototype.$processedImageUrl = (url, height, width, isPublic = true, isLocale = true) => {
  // return "https://msbrshop.com/storage/" + url;
  const path = isLocale ? 'local:///com.zilrsoft/' : ''
  const finalLink = isPublic && isLocale
    ? `https://images.zilrsoft.com/api/enrypt/fit/${width}/${height}/sm/0/plain/${path}/storage/app/public/${url}`
    : `https://images.zilrsoft.com/api/enrypt/fit/${width}/${height}/sm/0/plain/${path}${url}`
  return finalLink
}
