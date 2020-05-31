import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  strict: true,
  state: {
    cancelTokens: []
  },
  getters: {
    cancelTokens(state) {
      return state.cancelTokens
    }
  },
  mutations: {
    ADD_CANCEL_TOKEN(state, token) {
      state.cancelTokens.push(token)
    },
    CLEAR_CANCEL_TOKENS(state) {
      state.cancelTokens = []
    }
  },
  actions: {
    CANCEL_PENDING_REQUESTS(context) {
      // Cancel all request where a token exists
      context.state.cancelTokens.forEach((request) => {
        if (request.cancel) {
          request.cancel('Request canceled.')
        }
      })

      // Reset the cancelTokens store
      context.commit('CLEAR_CANCEL_TOKENS')
    }
  }
})
