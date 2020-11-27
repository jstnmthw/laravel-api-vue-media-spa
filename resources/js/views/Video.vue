<template>
  <div class="container bg-purple">
    <div class="d-flex align-items-center mb-1">
      <h3 class="mr-3 mb-1 font-weight-bold">{{ data.title }}</h3>
      <div v-if="data.views" class="text-muted">
        <ion-icon name="eye" style="position: relative; top: 3px"></ion-icon>
        {{ views }}
        <ion-icon
          name="thumbs-up"
          style="position: relative; top: 2px"
          class="ml-2"
        ></ion-icon>
        {{ rating }}%
      </div>
    </div>
    <div class="row">
      <div class="col">
        <div class="label-wrap">
          <ul class="list-inline">
            <li class="list-inline-item" v-for="category in categories">
              <video-labels :label="category"></video-labels>
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
            allowfullscreen
            height="100%"
            width="100%"
          ></iframe>
        </div>
        <div class="d-flex align-items-center mb-md-3 mb-lg-5">
          <button
            @click="like()"
            type="button"
            class="btn btn-primary"
            :class="{ disabled: voted }"
          >
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
          <button
            @click="dislike()"
            type="button"
            class="btn btn-primary ml-3"
            :class="{ disabled: voted }"
          >
            <ion-icon name="thumbs-down"></ion-icon>
          </button>
          <button class="btn btn-primary ml-2">
            <ion-icon
              name="heart"
              style="position: relative; top: 3px"
            ></ion-icon>
            Favorite
          </button>
          <button class="btn btn-primary ml-auto">
            <ion-icon name="flag"></ion-icon>
          </button>
        </div>
      </main>
      <aside class="col-sm-12 col-md-3">
        <div class="side-ad-placeholder"></div>
      </aside>
    </div>
    <div class="row">
      <div class="col-12">
        <h4 class="font-weight-bold mb-3">Related Videos</h4>
        <video-list
          :media="related"
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
import VideoList from '@/components/media/List'
import VideoLabels from '@/components/media/Labels'
import { comma_delimiter } from '@/helpers/numbers'

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
      related_loaded: false,
      voted: false,
      categories: false
    }
  },
  mounted() {
    this.getMedia()
    this.voteStatus()
  },
  computed: {
    rating() {
      if (this.data.likes >= 1) {
        return Math.trunc(
          (this.data.likes / (this.data.likes + this.data.dislikes)) * 100
        )
      } else {
        return 0
      }
    },
    views() {
      return comma_delimiter(this.data.views)
    },
    likes() {
      return comma_delimiter(this.data.likes)
    },
    dislikes() {
      return comma_delimiter(this.data.dislikes)
    },
    voteClass() {
      return this.voted
    }
  },
  methods: {
    async getMedia() {
      this.$Progress.start()
      this.loaded = true

      // Clear iframe src
      $('#video').attr('src', '')

      // Make the call
      await axios
        .get('/api/media/' + this.$route.params.slug)
        .then((response) => {
          this.$Progress.finish()
          this.data = response.data
          this.categories = this.data.categories.split(';')
          this.loaded = true
          this.watched(this.data.id)
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
        .get('/api/media/related', {
          params: {
            id: this.data.id,
            limit: limit
          }
        })
        .then((response) => {
          this.related = response.data.data
          this.related_loaded = true
        })
        .catch(() => {
          this.related_loaded = false
          console.log('There was an error fetching the data.')
        })
    },
    async like() {
      await axios
        .post('/api/media/' + this.data.id + '/like')
        .then((response) => {
          if (response.data.success) {
            this.likes++
            this.storeVote(String(this.data.id))
          }
        })
    },
    async dislike() {
      await axios
        .post('/api/media/' + this.data.id + '/dislike')
        .then((response) => {
          if (response.data.success) {
            this.dislikes--
            this.storeVote(String(this.data.id))
          }
        })
    },
    storeVote(id) {
      let votes = []
      if (this.getVotes()) {
        votes = this.getVotes()
      }
      votes.push(id)
      localStorage.setItem('votes', JSON.stringify(votes))
      this.voted = true
    },
    getVotes() {
      if (localStorage.getItem('votes') !== undefined) {
        return JSON.parse(localStorage.getItem('votes'))
      }
      return false
    },
    voteStatus() {
      let votes = this.getVotes()
      if (votes && votes.includes(this.$route.params.id)) {
        this.voted = true
      }
    },
    watched(id) {
      let watched = []
      if (localStorage.getItem('watched_ids')) {
        watched = JSON.parse(localStorage.getItem('watched_ids'))
      }
      if (!watched.includes(id)) {
        watched.push(id)
        if (watched.length > 20) {
          watched.shift()
        }
        localStorage.setItem('watched_ids', JSON.stringify(watched))
      }
    }
  },
  watch: {
    $route() {
      this.data = []
      this.related = []
      this.voted = false
      this.getMedia()
    }
  }
}
</script>
