import axios from 'axios'

/**
 * Get API data
 */
const state = {
  data: [],
  loading: false,
  current_page: 1,
  error: {
    status: false,
    msg: ''
  }
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
    state.error.status = payload.status ?? false
    if (payload.error) {
      if (payload.error.response.status === 500) {
        state.error.msg = 'An error has occurred, please try again later.'
      }
      if (payload.error.response.status === 404) {
        state.error.msg = 'Sorry, no results found.'
      }
    }
  }
}

const actions = {
  async api({ commit }, payload) {
    commit('SET_LOADING', 1)
    await axios
      .get(payload.url, {
        params: payload.query
      })
      .then((res) => {
        if (res.data.error) {
          commit('SET_ERROR', res.data)
        } else {
          commit('SET_DATA', res)
          commit('SET_ERROR', { status: false })
        }
        commit('SET_LOADING', 0)
      })
      .catch((error) => {
        commit('SET_LOADING', 0)
        commit('SET_ERROR', { error: error, status: true })
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
