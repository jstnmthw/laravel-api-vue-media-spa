<template>
  <div class="video-listing">
    <div class="row skeleton-row no-gutters" :class="'row-cols-'+cols" v-show="loading">
      <div
        class="col px-2 mb-md-3 position-relative" 
        v-for="card in cards" 
        :key="card.index"
        >
        <!-- TODO: Put skeleton cards in parent -->
        <skeleton-card-video></skeleton-card-video>
      </div>
    </div>
    <div class="row video-list no-gutters" :class="'row-cols-'+cols" v-show="!loading">
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
  props: [
    'videos', 
    'loading',
    'cards',
    'cols'
  ],
  mounted() {
    
    // TODO: Need to only run when images are loaded
    function preloadImages(srcs) {
      function loadImage(src) {
          return new Promise(function(resolve, reject) {
              var img = new Image();
              img.onload = function() {
                  resolve(img);
              };
              img.onerror = img.onabort = function() {
                  reject(src);
              };
              img.src = src;
          });
      }
      var promises = [];
      for (var i = 0; i < srcs.length; i++) {
          promises.push(loadImage(srcs[i]));
      }
      return Promise.all(promises);
    }

    // preloadImages(["src1", "src2", "src3", "src4"]).then(function(imgs) {
    //   // all images are loaded now and in the array imgs
    // }, function(errImg) {
    //   // at least one image failed to load
    // });

    let posters = document.getElementsByClassName('carousel');
    let interval = 1000;

    for (let i = 0; i < posters.length; i++) {
      
      let poster = posters[i];
      let next = 1;		
      let current = 0;
      let slideTimer = '';

      // Next slide
      function nextSlide() {
      
        let slides = poster.children.length;
        let children = poster.children;
        
        for (i = 0; i < slides; i++) {
          children[i].src = children[i].getAttribute('data-src');
        }
        
        children[current].classList.remove('active');
        children[next].classList.add('active');
        
        next = (next + 1) % slides;
        current = (current + 1) % slides; 
      }
      
      // Stop interval
      function stopSlides() {
        clearInterval(slideTimer);
      }

      // Start the interval
      function startSlides() {
        slideTimer = setInterval(function() {
          nextSlide();
        }, interval);
      }

      // Child images of parent
      let slides = posters[i].children;
      let imgs = [];

      console.log(slides.length);

      // Set src to load each img
      for (let j = 0; j < slides.length; j++) {
        // slides.push([slides[j].getAttribute('data-src')]);
      };


      // Mouse over event
      poster.addEventListener('mouseenter', e => {
        startSlides();
      });

      // Mouse leave event
      poster.addEventListener('mouseleave', e => {
        stopSlides();
      });

    }
  }
}
</script>
