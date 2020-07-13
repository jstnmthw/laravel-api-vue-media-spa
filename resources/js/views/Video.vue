<template>
  <div class="container bg-purple">
    <div class="d-flex align-items-center mb-1">
      <h3 class="mr-3 mb-1">{{ data.title }}</h3>
      <div v-if="data.views" class="text-muted">
        <ion-icon name="eye" style="position: relative; top: 3px;"></ion-icon>
        {{ views }}
        <ion-icon
          name="thumbs-up"
          style="position: relative; top: 2px;"
          class="ml-2"
        ></ion-icon>
        {{ rating }}%
      </div>
    </div>
    <div class="row">
      <div class="col">
        <div class="label-wrap">
          <ul class="list-inline">
            <li
              class="list-inline-item"
              v-for="label in data.categories"
              :key="label.index"
            >
              <video-labels :label="label"></video-labels>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="row">
      <main class="col-sm-12 col-md-9">
        <div class="video-frame mb-3">
          <iframe
            id="video"
            allowfullscreen="true"
            frameborder="0"
            height="100%"
            width="100%"
            scrolling="no"
          ></iframe>
        </div>
        <div
          class="video-actions d-flex justify-content-between align-items-top mb-5"
        >
          <div class="d-flex align-items-center">
            <button type="button" class="btn btn-primary">
              <ion-icon name="thumbs-up"></ion-icon>
            </button>
            <div class="video-rating ml-3" v-if="data.likes">
              {{ likes }} Likes / {{ dislikes }} Dislikes
              <div class="video-rating-bar mt-1">
                <div
                  class="video-rating-likes"
                  :style="{ width: rating + '%' }"
                ></div>
              </div>
            </div>
            <button type="button" class="btn btn-primary ml-3">
              <ion-icon name="thumbs-down"></ion-icon>
            </button>
            <button class="btn btn-primary ml-2">
              <ion-icon
                name="heart"
                style="position: relative; top: 3px;"
              ></ion-icon>
              Favorite
            </button>
            <button class="btn btn-primary ml-2">
              <ion-icon name="flag"></ion-icon>
            </button>
          </div>
        </div>
      </main>
      <aside class="col-sm-12 col-md-3">
        <div class="sidead-placeholder"></div>
      </aside>
    </div>
    <div class="row">
      <div class="col-12">
        <h4 class="mb-3">Related Videos</h4>
        <video-list
          :videos="related"
          :loaded="related_loaded"
          :cards="12"
          :cols="6"
          class="mb-5"
        ></video-list>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import VideoList from '@/components/VideoList'
import VideoLabels from '@/components/VideoLabels'

// Mixins
import getVideosMixin from '@/mixins/getVideosMixin.js'

export default {
  components: {
    VideoList: VideoList,
    VideoLabels: VideoLabels
  },
  data() {
    return {
      data: [],
      related: [],
      loaded: false,
      related_loaded: false
    }
  },
  mounted() {
    this.getVideo()
  },
  computed: {
    rating: function () {
      if (this.data.likes >= 1) {
        return Math.trunc(
          (this.data.likes / (this.data.likes + this.data.dislikes)) * 100
        )
      } else {
        return 0
      }
    },
    views: function () {
      return this.formatNumber(this.data.views)
    },
    likes: function () {
      return this.formatNumber(this.data.likes)
    },
    dislikes: function () {
      return this.formatNumber(this.data.dislikes)
    }
  },
  methods: {
    formatNumber(num) {
      return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
    },
    async getVideo() {
      this.$Progress.start()
      this.loaded = true

      // Clear iframe src
      $('#video').attr('src', '')

      // Make the call
      await axios
        .get('/api/videos', {
          params: {
            id: this.$route.params.id
          }
        })
        .then((response) => {
          this.$Progress.finish()
          this.data = response.data
          this.loaded = true

          $('#video')[0].contentWindow.location.replace(this.data.embed)

          this.getRelated(12)
        })
        .catch((error) => {
          console.log(error)
          this.loaded = false
          this.$Progress.fail()
        })
    },
    async getRelated(limit) {
      this.related_loaded = false

      await axios
        .get('/api/videos', {
          params: {
            q: this.data.title.replace(/\W/g, '+'),
            limit: limit,
            exclude: this.data.id
          }
        })
        .then((response) => {
          this.related = response.data.data
          this.related_loaded = true
        })
        .catch((error) => {
          this.related_loaded = false
          console.log('There was an error fetching the data.')
        })
    }
  },
  props: ['categories'],
  watch: {
    $route(to, from) {
      this.data = []
      this.related = []
      this.getVideo()
    }
  }
}
</script>
