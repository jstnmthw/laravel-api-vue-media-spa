import Vue from 'vue'
import Vuex from 'vuex'
import RequestToken from '@/store/modules/request-token'
import axios from 'axios'

Vue.use(Vuex)

export default new Vuex.Store({
  strict: true,
  modules: {
    requestToken: RequestToken
  },
  state: {
    user: [],
    categories: [],
    catsLoading: false
  },
  getters: {
    categories({ state }) {
      return state.categories
    }
  },
  mutations: {
    ADD_USER_INFO(state, payload) {
      state.user = payload
    },
    ADD_CATEGORIES(state, payload) {
      state.categories = payload
    }
  },
  actions: {
    async getCategories({ commit }) {
      await axios.get('/api/categories').then((response) => {
        commit('ADD_CATEGORIES', response.data)
      })
    }
  }
})
