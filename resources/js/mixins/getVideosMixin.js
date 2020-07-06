import axios from 'axios'
export default {
  mounted() {
    // Get videos on page load
    this.getVideos()
    console.log('Mixin called.')
  },
  methods: {
    // Axios Call
    async getVideos() {
      this.$Progress.start()
      this.error = false
      this.loaded = false

      // Stop unfinished images loading
      $('.video-poster img').attr('src', '')

      // Check for sort query string
      let sort = !this.$route.query.sortby ? { sortby: 'most_views' } : ''

      // Make the call
      await axios
        .get('/api/videos', {
          params: {
            ...this.$route.params,
            ...this.$route.query,
            ...sort
          }
        })
        .then((response) => {
          this.$Progress.finish()
          if (response.data.error) {
            this.error = response.data.error
          } else if (response.data.data.length == 0) {
            this.error = "Sorry, looks like there's no results."
          } else {
            this.videos = response.data
          }
          this.loaded = true
        })
        .catch((error) => {
          this.$Progress.fail()
          if (axios.isCancel(error)) {
            console.log('API Request canceled by user.')
          } else {
            console.log('Error calling API.')
          }
        })
    }
  }
}