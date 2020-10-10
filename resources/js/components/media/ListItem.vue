<template>
  <div class="video">
    <div class="video-data">
      <router-link class="video-poster" :to="'/videos/' + video.id">
        <img :src="video.thumbnail" class="card-img-top" :alt="video.title" />
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
      <div class="video-info px-0">
        <h5 class="video-title mt-2 mb-1">
          <router-link :to="'/videos/' + video.id">{{
            video.title
          }}</router-link>
        </h5>
        <span class="pr-2 position-relative text-sage">
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
  props: ['video'],
  computed: {
    album: function () {
      return this.video.album.split(';')
    },
    views: function () {
      return this.video.views
        .toString()
        .replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
    },
    rating: function () {
      if (this.video.likes >= 1) {
        return Math.trunc(
          (this.video.likes / (this.video.likes + this.video.dislikes)) * 100
        )
      } else {
        return 0
      }
    },
    duration: function () {
      // TODO: This only formats duration under an hour.
      return new Date(this.video.duration * 1000).toISOString().substr(14, 5)
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
          .append($('<img>', { src: '/imgs/loader.svg', class: 'loader-icon' }))
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
        loaded = imgs[i].complete && imgs[i].naturalHeight !== 0;
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
