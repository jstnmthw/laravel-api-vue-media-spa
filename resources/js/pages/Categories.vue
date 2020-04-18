<template>
  <main class="col-md-10">
    <top-ad-banner></top-ad-banner>
    <page-header :title="this.$route.params.cat +' Videos'" icon=""></page-header>
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
    }
  },
  mounted() {
    // Get videos on page load
    this.getVideos(); 

    // Get videos and paginate emit
    this.$on('paginate', function () {
      this.getVideos();
    });
  },
  watch: {
    // When route changes, call API
    $route(to, from) {
      this.getVideos();
    }
  },
  methods: {
    getVideos() {
      // Start loading on frontend
      this.$Progress.start();

      // Set loading
      this.loading = true;

      // Stop unfinished images loading
      document.getElementsByClassName('.video-list img').src='';
    
      // Default page number
      var pageNumber = 1;

      // Set if paginated or direct link used
      if(this.videos.current_page) {
        pageNumber = this.videos.current_page;
      }else if(this.$route.query.page) {
        pageNumber = this.$route.query.page;
      }

      // Make the call
      axios.get('/api/videos' + '?category=' + this.$route.params.cat + "&page=" + pageNumber ).then((response) => {
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
  }
}
</script>

<style>

</style>