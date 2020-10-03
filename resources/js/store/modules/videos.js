import axios from "axios";

export default {
    state: {
        videos: undefined,
    },
    getters: {
        // read only
    },
    mutations: {
        // transform store
    },
    actions: {
        async callAPI(state, payload) {
            await axios.get('api/videos').then((res) => {
                return res;
            })
        }
    }
}
