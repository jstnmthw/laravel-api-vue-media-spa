<template>
  <main class="col-md-10">
    <top-ad-banner></top-ad-banner>
    <page-header :title="this.$route.params.category +' Videos'" icon="" v-show="!loading"></page-header>
    <video-list :videos="videos" :loading="loading" :cards="40"></video-list>
    <paginate :pagination="videos" @paginate="getVideos()" :loading="loading"></paginate>
  </main>
</template>

<script>
export default {
  data() {
    return {
      videos: [],
      pagination: [],
      loading: false,
      title: '',
    }
  },
  computed: {},
  methods: {
    getVideos() {
      // Start loading on frontend
      this.$Progress.start();

      // Set loading
      this.loading = true;

      // Stop unfinished images loading
      $('.video-poster img').attr('src', '');
    
      // Default page number
      var pageNumber = 1;

      // Set if paginated or direct link used
      // if(this.videos.current_page) {
      //   pageNumber = this.videos.current_page;
      // }else if(this.$route.query.page) {
      //   pageNumber = this.$route.query.page;
      // }

      // Make the call
      // axios.get('/api/videos' + '?category=' + this.$route.params.category + "&page=" + pageNumber ).then((response) => {
      axios.get('/api/videos', { params: {...this.$route.params, ...this.$route.query } }).then((response) => {
          // Finish frontend progress bar
          this.$Progress.finish();

          // Set Vue Data
          if(!response['error']) {
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
    }
  },
  mounted() {
    // Get videos on page load
    this.getVideos();
  },
  watch: {
    // When route changes, call API
    $route(to, from) {
      console.log('Route Changed');
      this.videos = [];
      this.getVideos();
    }
  }
}
</script>

<style>

</style>