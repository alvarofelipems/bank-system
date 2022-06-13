import { boot } from 'quasar/wrappers'
import VueKeyCloak from '@dsb-norge/vue-keycloak-js'
import axios from 'axios'

export default boot(async ({ app, router, store }) => {
  async function tokenInterceptor () {
    axios.interceptors.request.use(config => {
      config.headers.Authorization = `Bearer ${app.config.globalProperties.$keycloak.token}`
      return config
    }, error => {
      return Promise.reject(error)
    })
  }


  // let initOptions = {
  //   url: 'http://192.168.0.10/auth',
  //   realm: 'bank-system',
  //   clientId: 'application-frontend',
  //   // onLoad: 'login-required',
  //   redirectUri: 'http://localhost:9000/',
  //   onLoad: 'check-sso',
  //   silentCheckSsoRedirectUri: window.location.origin + '/silent-check-sso.html'
  // }

  return new Promise(resolve => {
    // if (window.location.href.search('&state')) {
    //   window.location.href = window.location.href.replace('&state', '?state')
    // }
    app.use(VueKeyCloak, {
      init: {
        onLoad: 'login-required', // 'login-required' or 'check-sso'
        // onLoad: 'check-sso', // 'login-required' or 'check-sso'
        flow: 'standard',
        pkceMethod: 'S256',
        silentCheckSsoRedirectUri: window.location.origin + '/silent-check-sso.html',
        checkLoginIframe: true
      },
      config: {
        url: 'http://localhost/auth',
        realm: 'bank-system',
        clientId: 'application-frontend'
      },
      onReady: (keycloak) => {
        keycloak.loadUserInfo().then(function (data) {
          user = data
        })
        app.config.globalProperties.$api.defaults.headers.common['Authorization'] = 'Bearer ' + keycloak.token;
        window.location.hash = window.location.hash.substr(0, window.location.hash.search('&'))
        tokenInterceptor()
        resolve()
      }
    })
  })
})

let user = null;

export { user }
