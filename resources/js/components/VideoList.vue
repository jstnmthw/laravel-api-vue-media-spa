<template>
  <div class="video-listing">
    <!-- TODO: Put skeleton cards in parent -->
    <div class="row skeleton-row no-gutters d-none" :class="'row-cols-' + cols">
      <div
        class="col px-2 mb-md-3 position-relative"
        v-for="card in cards"
        :key="card.index"
      >
        <skeleton-card-video></skeleton-card-video>
      </div>
    </div>
    <div class="row video-list no-gutters" :class="'row-cols-' + cols">
      <div
        class="col px-2 mb-md-3 position-relative"
        v-for="video in videos"
        :key="video.index"
      >
        <video-list-item :data="video"></video-list-item>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {}
  },
  props: ['videos', 'cards', 'cols'],
  mounted() {
    // this.slideShow()
  },
  methods: {
    // Preload images
    preloadImages(srcs) {
      function loadImage(src) {
        return new Promise(function(resolve, reject) {
          let img = new Image()
          img.onload = function() {
            resolve(img)
          }
          img.onerror = img.onabort = function() {
            reject(src)
          }
          img.src = src
        })
      }
      let promises = []
      for (let i = 0; i < srcs.length; i++) {
        promises.push(loadImage(srcs[i]))
      }
      return Promise.all(promises)
    },

    // Check if images are already loaded
    imagesLoaded(imgs) {
      let loaded = false
      for (let i = 0; i < imgs.length; i++) {
        if (imgs[i].complete && imgs[i].naturalHeight !== 0) {
          loaded = true
        } else {
          loaded = false
        }
      }
    },

    startCarousel(element) {},

    // Create slideshow
    // slideShow() {
    //   let carousel = document.getElementsByClassName('carousel')
    //   let interval = 3000
    //   let current = 0
    //   let next = 1
    //   let sliderTimer
    //   // For each carousel attach hover event
    //   for (let i = 0; i < carousel.length; i++) {
    //     // Mouse over event
    //     carousel[i].addEventListener('mouseenter', e => {
    //       startSlider(e)
    //     })
    //     // Mouse leave event
    //     carousel[i].addEventListener('mouseleave', e => {
    //       console.log(sliderTimer)
    //       clearInterval(sliderTimer)
    //       sliderTimer = 0
    //       current = 0
    //       next = 1
    //     })
    //   }
    //   // Next slide
    //   // function nextSlide(element) {
    //   //   let children = element.originalTarget.children
    //   //   children[current].classList.remove('active')
    //   //   children[next].classList.add('active')
    //   //   next = next + 1 < children.length ? next + 1 : 0
    //   //   current = current + 1 < children.length ? current + 1 : 1
    //   // }
    //   // Start the interval
    //   function startSlider(element) {
    //     let imgs = element.originalTarget.children
    //     let srcs = []
    //     // console.log(imgs)
    //     // Set array of img srcs
    //     for (let i = 0; i < imgs.length; i++) {
    //       srcs.push(imgs[i].getAttribute('data-src') + '.jpg')
    //     }
    //     preloadImages(srcs).then(
    //       loadedImgs => {
    //         // Set images after loaded
    //         for (let i = 0; i < imgs.length; i++) {
    //           element.originalTarget.children[i].src = loadedImgs[i].src
    //         }
    //         // Start the slider
    //         sliderTimer = setInterval(function() {
    //           let children = element.originalTarget.children
    //           children[current].classList.remove('active')
    //           children[next].classList.add('active')
    //           next = next + 1 < children.length ? next + 1 : 0
    //           current = current + 1 < children.length ? current + 1 : 1
    //         }, interval)
    //       },
    //       function(errImg) {
    //         alert('Failed')
    //       }
    //     )
    //   }
    //   // Preloading slideshow images
    //   function preloadImages(srcs) {
    //     function loadImage(src) {
    //       return new Promise(function(resolve, reject) {
    //         let img = new Image()
    //         img.onload = function() {
    //           resolve(img)
    //         }
    //         img.onerror = img.onabort = function() {
    //           reject(src)
    //         }
    //         img.src = src
    //       })
    //     }
    //     let promises = []
    //     for (let i = 0; i < srcs.length; i++) {
    //       promises.push(loadImage(srcs[i]))
    //     }
    //     return Promise.all(promises)
    //   }
    // }
  },
}
</script>
