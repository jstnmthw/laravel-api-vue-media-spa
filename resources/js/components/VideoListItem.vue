<template>
  <div class="video">
    <div class="video-data">
      <div class="video-poster">
        <img :src="data.thumbnail" class="card-img-top" :alt="data.title" />
        <div
          class="carousel"
          @mouseenter="debounce($event)"
          @mouseleave="debounce($event, true)"
        >
          <img
            alt=""
            :key="image.index"
            :data-src="image"
            v-for="image in data.album"
          />
        </div>
        <div class="duration">
          {{ duration }}
        </div>
      </div>
      <div class="video-info px-0">
        <h5 class="video-title mt-2 mb-1">
          <router-link :to="'/videos/' + data.id">{{ data.title }}</router-link>
        </h5>
        <span style="opacity: 0.5;" class="pr-2 position-relative">
          <ion-icon name="eye" style="top: 3px;"></ion-icon>
          {{ views }}
        </span>
        <span :class="rating > 50 ? 'liked' : 'disliked'">
          <ion-icon name="thumbs-up"></ion-icon>
          <ion-icon name="thumbs-down"></ion-icon>
          {{ rating }}%
        </span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['data'],
  computed: {
    views: function () {
      return this.data.views
        .toString()
        .replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
    },
    rating: function () {
      if (this.data.likes >= 1) {
        return Math.trunc(
          (this.data.likes / (this.data.likes + this.data.dislikes)) * 100
        )
      } else {
        return 0
      }
    },
    duration: function () {
      // TODO: This only formats duration under an hour.
      return new Date(this.data.duration * 1000).toISOString().substr(14, 5)
    }
  },
  data() {
    return {
      next: 1,
      current: 0,
      interval: 1000,
      debounceTimer: 0,
      timer: 0
    }
  },
  methods: {
    // Debounce hover
    debounce: function (event, clear = false) {
      if (clear) {
        clearTimeout(this.debounceTimer)
        this.carousel(event, false)
        $('.loader-icon').remove()
      } else {
        $(event.target)
          .parent()
          .prepend(
            $('<img>', { src: '/imgs/loader.svg', class: 'loader-icon' })
          )
        this.debounceTimer = setTimeout(() => {
          this.carousel(event)
        }, 1000)
      }
    },

    // Image Carousel
    carousel: function (event, start = true) {
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
      var loadedCounter = 0
      var toBeLoadedNumber = urls.length
      urls.forEach(function (url) {
        preloadImage(url, function () {
          loadedCounter++
          if (loadedCounter == toBeLoadedNumber) {
            allImagesLoadedCallback()
          }
        })
      })
      function preloadImage(url, anImageLoadedCallback) {
        var img = new Image()
        img.onload = anImageLoadedCallback
        img.src = url
      }
    },

    // Check images are loaded
    imagesLoaded(imgs) {
      let loaded = false
      for (let i = 0; i < imgs.length; i++) {
        if (imgs[i].complete && imgs[i].naturalHeight !== 0) {
          loaded = true
        } else {
          loaded = false
        }
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
