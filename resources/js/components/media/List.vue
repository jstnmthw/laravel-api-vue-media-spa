<template>
  <div class="video-listing">
    <div class="row video-list no-gutters" :class="'row-cols-' + cols">
      <div
        class="col px-2 mb-md-3 position-relative"
        v-for="video in videos"
        :key="video.index"
      >
        <video-list-item :video="video"></video-list-item>
      </div>
    </div>
  </div>
</template>

<script>
import VideoListItem from '@/components/media/ListItem'
export default {
  props: ['videos', 'cards', 'cols'],
  components: {
    VideoListItem: VideoListItem
  }
}
// Take out page specific javascript of the API calls.
//   document.querySelectorAll('.video-poster img').forEach((img) => {
//     img.setAttribute('src', '');
//   });
</script>
