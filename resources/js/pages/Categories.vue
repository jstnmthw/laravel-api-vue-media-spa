<template>
  <div class="container">
    <div class="row bg-purple">
      <main-sidebar :categories="categories"></main-sidebar>
      <main class="col-md-10">
        <top-ad-banner></top-ad-banner>
        <div v-if="!error">
          <page-header :title="category_title" icon="" v-show="!loading"></page-header>
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
          <video-list :videos="videos" :loading="loading" :cards="40"></video-list>
          <paginate :pagination="videos" @paginate="getVideos()" :loading="loading"></paginate>
        </div>
        <div v-else class="text-center m-4">
          {{ error }}
        </div>
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
  computed: {
    category_title: function() {
      const categories = this.categories.map(el => el.slug);
      const key = categories.indexOf(this.$route.params.category)
      if(key >= 0) {
        return this.categories[key]['name'] + ' Video Category';
      }
    }
  },
  methods: {
    getVideos() {
      // Clear data
      this.videos = [];
      this.error = false;

      // Start loading on frontend
      this.$Progress.start();

      // Set loading
      this.loading = true;

      // Stop unfinished images loading
      // $('.video-poster img').attr('src', '');

      // Push default sort
      this.$router.push({ query: Object.assign({}, this.$route.query, { sortby: this.sort }) });

      // Make the call
      axios.get('/api/videos', { params: {...this.$route.params, ...this.$route.query } }).then((response) => {

          // Finish frontend progress bar
          this.$Progress.finish();

          // Set Vue data if present
          if(response.data.error) {
            this.error = response.data.error;
          }else {
            this.videos = response.data;
          }

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
      // this.$router.push({ query: { sortby: this.sort } });
    }
  },
  mounted() {
    // Get videos on page load
    this.getVideos();
  },
  props: ['categories'],
  watch: {
    // When route changes, call API
    $route(to, from) {

      // Get new videos
      this.getVideos();
    }
  }
}
</script>
