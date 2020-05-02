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
          <video-list :videos="videos.data" :loading="loading" :cards="50" :cols="5" class="mb-5"></video-list>
          <paginate :pagination="videos" @paginate="getVideos()" :loading="loading" class="mb-5"></paginate>
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
  mounted() {
    // Get videos on page load
    this.getVideos();

    // cookie for cats
    document.cookie = "category="+ this.$route.params.category +"; expires=Sun, 25 April 2020 00:00:00 UTC; path=/"; 
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
      this.error = false;

      // Start loading on frontend
      this.$Progress.start();

      // Set loading
      this.loading = true;

      // Stop unfinished images loading
      $('.video-poster img').attr('src', '');

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

          // console.log(document.cookie);

        }).catch(error => {

          // Console log API error.
          console.log('Error calling API.');
          
          // Failed frontend progress bar
          this.$Progress.fail();

      });
    },
    sortBy() {
      // Push sorting
      this.$router.push({ query: Object.assign({}, this.$route.query, { sortby: this.sort }) });
    }
  },
  props: [
    'categories'
  ],
  watch: {
    $route(to, from) {
      console.log('rotyue ca');
      // Get new videos on route change
      this.videos = [];
      this.getVideos();

      // cookie for cats
      document.cookie = "category="+ this.$route.params.category +"; expires=Sun, 25 April 2020 00:00:00 UTC; path=/"; 
    }
  }
}
</script>
