import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  strict: true,
  state: {
    cancelTokens: [],
    user: [],
    loading: false
  },
  getters: {
    cancelTokens(state) {
      return state.cancelTokens
    },
    isLoading(state) {
      return state.loading
    }
  },
  mutations: {
    ADD_USER_INFO(state, payload) {
      state.user = payload
    },
    ADD_CANCEL_TOKEN(state, token) {
      state.cancelTokens.push(token)
    },
    CLEAR_CANCEL_TOKENS(state) {
      state.cancelTokens = []
    },
    LOADING_STARTED(state) {
      state.loading = true
    },
    LOADING_FINISHED(state) {
      state.loading = false
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
