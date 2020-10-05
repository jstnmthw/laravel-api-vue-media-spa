import axios from 'axios'

/**
 * Get API data
 */
const state = {
  data: [],
  loading: false,
  current_page: 1
}

const getters = {
  loading() {
    return state.loading
  }
}

const mutations = {
  SET_LOADING(state, payload) {
    state.loading = payload
  },
  SET_DATA(state, payload) {
    state.data = payload.data
    state.currentPage = payload.data.current_page
  },
  SET_ERROR(state, payload) {
    state.error = payload.error
    state.error_msg = payload.error_msg ? payload.error_msg : false
  }
}

const actions = {
  async api({ commit }, payload) {
    commit('SET_LOADING', 1)
    await axios
      .get(payload.url, {
        params: payload.params
      })
      .then((res) => {
        if (res.data.error) {
          commit('SET_ERROR', res.data)
        } else {
          commit('SET_DATA', res)
        }
        commit('SET_LOADING', 0)
      })
      .catch((error) => {
        commit('SET_LOADING', 0)
        commit('SET_ERROR', error)
      })
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
