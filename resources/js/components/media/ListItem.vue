<template>
  <div class="media-data">
    <router-link class="media-poster" :to="'/videos/' + url">
      <img v-lazy="media.thumbnail" class="card-img-top" :alt="media.title" />
      <div
        class="carousel"
        @mouseenter="debounce($event)"
        @mouseleave="debounce($event, true)"
      >
        <img
          src=""
          alt=""
          :key="image.index"
          :data-src="image"
          v-for="image in album"
        />
      </div>
      <div class="duration">
        {{ duration }}
      </div>
      <img
        v-show="loading"
        alt="Loading"
        src="/imgs/loader.svg"
        class="loader-icon"
      />
    </router-link>
    <div class="media-info px-0">
      <h3 class="media-title mt-2 mb-2 mb-md-1">
        <router-link :to="'/videos/' + url">{{ media.title }}</router-link>
      </h3>
      <span class="pr-2 position-relative text-sage">
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
      </span>
      <span :class="rating > 40 ? 'liked' : 'disliked'">
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
        {{ rating }}%
      </span>
    </div>
  </div>
</template>

<script>
import { comma_delimiter } from '@/helpers/numbers'

export default {
  props: ['media'],
  data() {
    return {
      next: 1,
      current: 0,
      interval: 1000,
      debounceTimer: 0,
      timer: 0,
      loading: false
    }
  },
  computed: {
    album() {
      return this.media.album.split(';')
    },
    views() {
      return comma_delimiter(this.media.views)
    },
    rating() {
      if (this.media.likes >= 1) {
        return Math.trunc(
          (this.media.likes / (this.media.likes + this.media.dislikes)) * 100
        )
      } else {
        return 0
      }
    },
    duration() {
      // TODO: This only formats duration under an hour.
      return new Date(this.media.duration * 1000).toISOString().substr(14, 5)
    },
    url() {
      return encodeURI(this.media.slug)
    }
  },
  methods: {
    // Debounce hover
    debounce(event, clear = false) {
      if (clear) {
        clearTimeout(this.debounceTimer)
        this.carousel(event, false)
        this.loading = false
      } else {
        this.loading = true
        this.debounceTimer = setTimeout(() => {
          this.carousel(event)
        }, 1000)
      }
    },

    // Image Carousel
    carousel(event, start = true) {
      let images = event.target.children
      let srcs = this.srcToArray(images)
      if (start) {
        this.timer = setInterval(() => {
          this.nextImage(images)
        }, this.interval)
        this.preloadImages(srcs, () => {
          this.loading = false
          for (let i = 0; i < images.length; i++) {
            images[i].setAttribute('src', srcs[i])
          }
        })
      } else {
        clearInterval(this.timer)
      }
    },

    // Next Images
    nextImage(images) {
      for (let i = 0; i < images.length; i++) {
        images[i].classList.remove('active')
      }
      images[this.next].classList.add('active')
      this.next = (this.next + 1) % images.length
    },

    // Preload images
    preloadImages(urls, allImagesLoadedCallback) {
      let loadedCounter = 0
      const toBeLoadedNumber = urls.length
      urls.forEach(function (url) {
        preloadImage(url, function () {
          loadedCounter++
          if (loadedCounter === toBeLoadedNumber) {
            allImagesLoadedCallback()
          }
        })
      })
      function preloadImage(url, anImageLoadedCallback) {
        const img = new Image()
        img.onload = anImageLoadedCallback
        img.src = url
      }
    },

    // Check images are loaded
    imagesLoaded(imgs) {
      let loaded = false
      for (let i = 0; i < imgs.length; i++) {
        loaded = imgs[i].complete && imgs[i].naturalHeight !== 0
      }
      return loaded
    },

    // Image sources to array
    srcToArray(imgs) {
      let srcs = []
      for (let i = 0; i < imgs.length; i++) {
        srcs.push(imgs[i].getAttribute('data-src'))
      }
      return srcs
    }
  }
}
</script>
