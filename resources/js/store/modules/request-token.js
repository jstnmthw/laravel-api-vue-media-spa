/**
 * Cancel axios request tokens.
 */
  const state = {
      cancelTokens: [],
  };

  const getters = {
      cancelTokens(state) {
          return state.cancelTokens
      }
  };

  const mutations = {
      ADD_CANCEL_TOKEN(state, token) {
          state.cancelTokens.push(token)
      },
      CLEAR_CANCEL_TOKENS(state) {
          state.cancelTokens = []
      }
  };

  const actions = {
      CANCEL_PENDING_REQUESTS(context) {
          context.state.cancelTokens.forEach((request) => {
              if (request.cancel) {
                  request.cancel('Request canceled.')
              }
          })
          context.commit('CLEAR_CANCEL_TOKENS')
      }
  }

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
}
