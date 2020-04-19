<template>
  <main class="col-sm-12 col-md-10">
    <top-ad-banner></top-ad-banner>
    <div class="video-frame mb-3">
      <iframe id="video" frameborder="0" height="auto" width="100%" scrolling="no"></iframe>
    </div>
    <h3>{{ data.title }}</h3>
  </main>
</template>

<script>
export default {
  data() {
    return {
      data: []
    }
  },
  props: ['categories'],
  mounted() {
    this.getVideo();
  },
  methods: {
    getVideo() {
      // Start loading on frontend
      this.$Progress.start();

      // Make the call
      axios.get('/api/videos/' + this.$route.params.id).then((response) => {

        // Finish loading on frontend
        this.$Progress.finish();

        // Set video object
        this.data = response.data;

        // Set iframe src
        $('#video').attr('src', this.data.embed);

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
