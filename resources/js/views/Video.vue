<template>
  <div class="container media-page bg-purple pt-sm-2 pt-md-4">
    <sidebar :classes="'collapse'"></sidebar>
    <div
      class="d-flex flex-column flex-md-row align-items-start align-items-md-center mb-sm-3 mb-md-1"
    >
      <h3 class="mr-3 mb-1 font-weight-bold">{{ data.title }}</h3>
      <div v-if="data.views" class="text-muted mb-2 mb-md-0">
        <svg
          class="icon"
          fill="currentColor"
          viewBox="0 0 20 20"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
          <path
            fill-rule="evenodd"
            d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
            clip-rule="evenodd"
          ></path>
        </svg>
        {{ views }}
        <svg
          class="ml-2 icon"
          fill="currentColor"
          viewBox="0 0 20 20"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"
          ></path>
        </svg>
        {{ rating }}%
      </div>
    </div>
    <div class="row">
      <div class="col">
        <div class="label-wrap">
          <ul class="list-inline">
            <li class="list-inline-item" v-for="category in categories">
              <category-label :data="category"></category-label>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="row">
      <main class="col-sm-12 col-md-9 px-0 px-md-3 mb-3 mb-md-0">
        <div class="media-frame">
          <iframe
            id="video"
            allowfullscreen
            height="100%"
            width="100%"
          ></iframe>
        </div>
        <div class="media-actions d-flex mb-md-2 mb-lg-5 mt-md-3">
          <button
            @click="like()"
            type="button"
            class="btn btn-primary flex-fill flex-md-grow-0"
            :class="{ disabled: voted }"
          >
            <svg
              class="icon"
              name="thumbs-up"
              fill="currentColor"
              viewBox="0 0 20 20"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"
              ></path>
            </svg>
          </button>
          <div
            class="media-rating mx-3 d-none d-md-block flex-grow-1 flex-md-grow-0"
            v-if="data.likes"
          >
            {{ likes }} Likes / {{ dislikes }} Dislikes
            <div class="media-rating-bar mt-1">
              <div
                class="video-rating-likes"
                :style="{ width: rating + '%' }"
              ></div>
            </div>
          </div>
          <button
            @click="dislike()"
            type="button"
            class="btn btn-primary flex-fill flex-md-grow-0"
            :class="{ disabled: voted }"
          >
            <svg
              class="icon"
              name="thumbs-down"
              fill="currentColor"
              viewBox="0 0 20 20"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                d="M18 9.5a1.5 1.5 0 11-3 0v-6a1.5 1.5 0 013 0v6zM14 9.667v-5.43a2 2 0 00-1.105-1.79l-.05-.025A4 4 0 0011.055 2H5.64a2 2 0 00-1.962 1.608l-1.2 6A2 2 0 004.44 12H8v4a2 2 0 002 2 1 1 0 001-1v-.667a4 4 0 01.8-2.4l1.4-1.866a4 4 0 00.8-2.4z"
              ></path>
            </svg>
          </button>
          <button
            class="btn btn-primary ml-md-2 mr-md-2 flex-fill flex-md-grow-0"
          >
            <svg
              class="icon"
              fill="currentColor"
              viewBox="0 0 20 20"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                fill-rule="evenodd"
                d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                clip-rule="evenodd"
              ></path>
            </svg>
            Favorite
          </button>
          <button class="btn btn-primary flex-fill flex-md-grow-0">
            <svg
              class="icon"
              fill="currentColor"
              viewBox="0 0 20 20"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                fill-rule="evenodd"
                d="M3 6a3 3 0 013-3h10a1 1 0 01.8 1.6L14.25 8l2.55 3.4A1 1 0 0116 13H6a1 1 0 00-1 1v3a1 1 0 11-2 0V6z"
                clip-rule="evenodd"
              ></path>
            </svg>
            Flag
          </button>
        </div>
      </main>
      <aside class="col-sm-12 col-md-3 mb-3 mb-md-0">
        <div class="side-ad-placeholder"></div>
      </aside>
    </div>
    <div class="row related-content">
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
import CategoryLabel from '@/components/media/Label'
import Sidebar from '@/components/layout/Sidebar'
import { comma_delimiter } from '@/helpers/numbers'

export default {
  metaInfo() {
    return {
      title: this.data.title,
      meta: [{ name: 'description', content: this.data.title }],
      link: [
        {
          rel: 'canonical',
          href: window.location.origin + this.$route.fullPath
        }
      ]
    }
  },
  components: {
    VideoList,
    CategoryLabel,
    Sidebar
  },
  data() {
    return {
      data: {
        likes: 0,
        dislikes: 0
      },
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
      document.getElementById('video').setAttribute('src', '')

      let key = this.$route.params.slug.split('-').pop()

      // Make the call
      await axios
        .get('/api/media/' + key)
        .then((response) => {
          this.$Progress.finish()
          this.data = response.data
          this.categories = this.data.categories
          this.loaded = true
          this.watched(this.data.id)
          this.getRelated(12)
          document
            .querySelector('#video')
            .contentWindow.location.replace(
              window.embedUrl + this.data.unique_key
            )
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
            this.data.likes++
            this.storeVote(String(this.data.id))
          }
        })
    },
    async dislike() {
      await axios
        .post('/api/media/' + this.data.id + '/dislike')
        .then((response) => {
          if (response.data.success) {
            this.data.dislikes--
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
