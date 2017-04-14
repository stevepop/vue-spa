import Vue from 'vue'

import Vuex from 'vuex'

import auth from '../app/auth/vuex'

Vue.use(Vuex)


// registering beforeEach

export default new Vuex.Store({

  modules: {

    auth: auth

  }

})
