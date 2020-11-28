<template>
  <div class="media">
    <div class="media-data">
      <router-link class="media-poster" :to="'/media/' + url">
        <img :src="media.thumbnail" class="card-img-top" :alt="media.title" />
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
      </router-link>
      <div class="media-info px-0">
        <h5 class="media-title mt-2 mb-1">
          <router-link :to="'/media/' + url">{{ media.title }}</router-link>
        </h5>
        <span class="pr-2 position-relative text-sage">
          <ion-icon name="eye" style="top: 3px"></ion-icon>
          {{ views }}
        </span>
        <span :class="rating > 40 ? 'liked' : 'disliked'">
          <ion-icon name="thumbs-up"></ion-icon>
          <ion-icon name="thumbs-down"></ion-icon>
          {{ rating }}%
        </span>
      </div>
    </div>
  </div>
</template>

<script>
import { slug } from '@/helpers/strings.js'
import { comma_delimiter } from '@/helpers/numbers'

export default {
  props: ['media'],
  data() {
    return {
      next: 1,
      current: 0,
      interval: 1000,
      debounceTimer: 0,
      timer: 0
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
      return slug(this.media.title)
    }
  },
  methods: {
    // Debounce hover
    debounce(event, clear = false) {
      if (clear) {
        clearTimeout(this.debounceTimer)
        this.carousel(event, false)
        $('.loader-icon').remove()
      } else {
        $(event.target)
          .parent()
          .append($('<img alt="" src="/imgs/loader.svg" class="loader-icon">'))
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
          for (let i = 0; i < images.length; i++) {
            images[i].setAttribute('src', srcs[i])
            $('.loader-icon').remove()
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
      $(images).eq(this.next).addClass('active')
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
