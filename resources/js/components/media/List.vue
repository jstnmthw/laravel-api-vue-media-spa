<template>
  <div class="media-listing">
    <div class="row video-list no-gutters" :class="'row-cols-' + cols">
      <div
        class="col px-2 mb-md-3 position-relative"
        v-for="(item, index) in media"
      >
        <media-item :media="item"></media-item>
      </div>
    </div>
  </div>
</template>

<script>
import MediaItem from '@/components/media/ListItem'
export default {
  props: ['media', 'cards', 'cols'],
  components: {
    MediaItem
  }
}
// Take out page specific javascript of the API calls.
//   document.querySelectorAll('.video-poster img').forEach((img) => {
//     img.setAttribute('src', '');
//   });
</script>
