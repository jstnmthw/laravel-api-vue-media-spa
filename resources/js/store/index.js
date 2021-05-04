import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'
import RequestToken from '@/store/modules/request-token'
import api from '@/store/modules/api'

Vue.use(Vuex)

export default new Vuex.Store({
  strict: true,
  modules: {
    requestToken: RequestToken,
    api: api
  },
  state: {
    user: [],
    categories: []
  },
  getters: {
    categories: (state) => {
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
