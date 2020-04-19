<template>
  <div class="container-fluid">
    <div class="video">
      {{ data.embed }}
    </div>
    <h3>{{ data.title }}</h3>
  </div>
</template>

<script>
export default {
  data() {
    return {
      data: []
    }
  },
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
