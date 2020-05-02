<template>
  <div class="container">
    <div class="row bg-purple">
    <main-sidebar :categories="categories"></main-sidebar>
    <main class="col-md-10">
      <top-ad-banner></top-ad-banner>
      <page-header :title="'Most Viewed Videos'" icon="eye" v-show="!loading"></page-header>
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div v-show="!loading">
          Showing: {{ videos.current_page }} of {{ videos.last_page }}
        </div>
        <div v-show="!loading">
          <select v-model="sort" name="sortby" class="custom-select" @change="sortBy()">
            <option value="most_views">Most Views</option>
            <option value="top_rated">Top Rated</option>
            <option value="duration">Duration</option>
            <option value="most_recent">Most Recent</option>
          </select>
        </div>
      </div>
      <video-list :videos="videos.data" :loading="loading" :cards="50" :cols="5" class="mb-5"></video-list>
      <paginate :pagination="videos" @paginate="getVideos()" :loading="loading" class="mb-5"></paginate>
    </main>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      videos: [],
      pagination: [],
      loading: false,
      error: false,
      sort: this.$route.query.sortby ? this.$route.query.sortby : 'most_views'
    }
  },
  mounted() {
    // Get videos on page load
    this.getVideos(); 
  },
  methods: {
    getVideos() {
      // Set error
      this.error = false;

      // Start loading on frontend
      this.$Progress.start();

      // Set loading
      this.loading = true;

      // Stop unfinished images loading
      $('.video-poster img').attr('src', '');

      // Make the call
      axios.get('/api/videos', { params: {...this.$route.params, ...this.$route.query } }).then((response) => {

        // Finish loading on frontend
        this.$Progress.finish();

        // Set video object
        this.videos = response.data;
      
        // Disable loading
        this.loading = false;

      }).catch(error => {
        // Console log API error.
        console.log('Error calling API.');

        // Failed frontend progress bar
        this.$Progress.fail();
      });
    },
    sortBy() {
      this.$router.push({ query: Object.assign({}, this.$route.query, { sortby: this.sort }) });
    }
  },
  props: [
    'categories'
  ],
  watch: {
    // When route changes, call API
    $route(to, from) {

      // Clear data
      this.videos = [];

      // Get new data
      this.getVideos();
    }
  },
}
</script>
