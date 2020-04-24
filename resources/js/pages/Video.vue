<template>
  <div class="container bg-purple">
    <div class="row">
      <main class="col-sm-12 col-md-9">
        <h3>{{ data.title }}</h3>
        <div class="label-wrap">
          <ul class="list-inline">
            <li class="list-inline-item" v-for="label in data.categories" :key="label.index">
              <video-category-labels :label="label"></video-category-labels>
            </li>
          </ul>
        </div>
        <div class="video-frame mb-3">
          <iframe id="video" allowfullscreen="true" frameborder="0" height="100%" width="100%" scrolling="no"></iframe>
        </div>
        <div class="video-actions d-flex justify-content-between align-items-top mb-5">
          <div class="d-flex justify-content-between align-items-center">
            <button type="button" class="btn btn-primary  mr-3">
              <ion-icon name="thumbs-up"></ion-icon> 
            </button>
            <div class="mr-3 video-rating" v-if="data.likes">
              {{ likes }} Likes / {{ dislikes }} Dislikes
              <div class="video-rating-bar mt-1">
                <div class="video-rating-likes" :style="{ width: rating + '%' }"></div>
              </div>
            </div>
            <button type="button" class="btn btn-primary">
              <ion-icon name="thumbs-down"></ion-icon>
            </button>
            <div class="video-views ml-4" v-if="data.views">
              <ion-icon name="eye"></ion-icon> {{ views }}
            </div>
          </div>
          <button class="btn btn-primary ml-auto">
            <ion-icon name="flag"></ion-icon>
          </button>
        </div>
      </main>
      <aside class="col-sm-12 col-md-3">
        <div class="sidead-placeholder"></div>
      </aside>
    </div>
    <div class="row">
      <div class="col-12">
        <h4 class="mb-3">Related Videos</h4>
        <video-list :videos="related" :loading="related_loading" :cards="12" :cols="6" class="mb-5"></video-list>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      data: [],
      related: [],
      loading: false,
      related_loading: false,
    }
  },
  mounted() {
    this.getVideo();
  },
  computed: {
    rating: function() {
      if(this.data.likes >= 1) {
        return Math.trunc((this.data.likes / (this.data.likes + this.data.dislikes)) * 100);
      }else {
        return 0;
      }
    },
    views: function() {
      return this.formatNumber(this.data.views);
    },
    likes: function() {
      return this.formatNumber(this.data.likes);
    },
    dislikes: function() {
      return this.formatNumber(this.data.dislikes);
    }
  },
  methods: {
    formatNumber(num) {
      return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
    },
    getVideo() {
      // Start loading on frontend
      this.$Progress.start();

      // Loading
      this.loading = true;

      // Stop unfinished images loading
      $('.video-poster img').attr('src', '');

      // Clear iframe src
      $('#video').attr('src', '');

      // Make the call
      axios.get('/api/videos/' + this.$route.params.id).then((response) => {

        // Finish loading on frontend
        this.$Progress.finish();

        // Set video object
        this.data = response.data;

        // Loading
        this.loading = false;

        // Use replace to not effect the browser history
        $('#video')[0].contentWindow.location.replace(this.data.embed);

        this.getRelated();

      }).catch(error => {
        // Console log API error.
        console.log('Error calling API.');

        // Loading
        this.loading = false;

        // Failed frontend progress bar
        this.$Progress.fail();
      });
    },
    getRelated() {

      this.related_loading = true;

      axios.get('/api/videos/' + this.$route.params.id + '?category=' + this.data.categories[0].name.toLowerCase()).then((response) => {
          
          this.related = response.data;
          this.related_loading = false;
          
        }).catch((error) => {
          
          this.related_loading = false;
          console.log('There was an error retrieving data.');

      });

    }
  },
  props: [
    'categories'
  ],
  watch: {
    $route(to, from) {
      // Clear data hen route changes.
      this.data = [];
      this.related = [];
      this.getVideo();
    }
  }
}
</script>
