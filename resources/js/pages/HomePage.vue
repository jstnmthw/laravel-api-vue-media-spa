<template>
  <main class="col-md-10">
    {{ this.$route.query }}
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
      categories: [],
      loading: false,
    }
  },
  watch: {
    $route(to, from) {
      this.getVideos();
      console.log('Route changed.')
    }
  },
  mounted() {
    // Get videos on page load
    this.getVideos(); 

    // Get videos and paginate emit
    // this.$on('paginate', function () {
    //   this.getVideos();
    // });
  },
  methods: {
    getVideos() {
      // Start loading on frontend
      this.$Progress.start();

      // Set loading
      this.loading = true;

      // Stop unfinished images loading
      // document.getElementsByClassName('.video-list img').src='';
      $('.video-list img').attr('src', '').attr('alt', '')
    

      // Default page number
      var pageNumber = 1;

      // Set if paginated or direct link used
      if(this.videos.current_page) {
        pageNumber = this.videos.current_page;
      }else if(this.$route.query.page) {
        pageNumber = this.$route.query.page;
      }

      console.log(pageNumber);
      // let pageNumber = this.videos.current_page ? this.videos.current_page : 1;

      // Make the call
      axios.get('/api/videos' + "?page=" + pageNumber).then((response) => {

        // Finish loading on frontend
        this.$Progress.finish();

        // Set video object
        this.videos = response.data;
      
        // Disable loading
        this.loading = false;

      }).catch(error => {
        // Log error
        console.log('Error calling API.');

        // Fail loading on frontend
        this.$Progress.fail();
      });
    }
  }
}
</script>
