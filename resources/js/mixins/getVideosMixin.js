import axios from 'axios'
export default {
  mounted() {},
  methods: {
    // Axios Call
    async getVideos() {
      this.$Progress.start()
      this.error = false

      // Stop unfinished images loading
      $('.video-poster img').attr('src', '')

      // Make the call
      await axios
        .get('/api/videos', {
          params: {
            ...this.$route.params,
            ...this.$route.query
          }
        })
        .then((response) => {
          if (response.data.error) {
            this.error = response.data.error
          } else if (response.data.data.length == 0) {
            this.error = "Sorry, looks like there's no results."
          } else {
            this.videos = response.data
          }
          this.$Progress.finish()
          this.loaded = true
        })
        .catch((error) => {
          if (axios.isCancel(error)) {
            console.log('API Request canceled by user.')
          } else {
            console.log('Error calling API.')
            this.error = true
          }
          this.$Progress.fail()
          this.loaded = true
        })
    }
  }
}
