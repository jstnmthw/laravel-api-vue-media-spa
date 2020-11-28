<template>
  <div class="media-listing row no-gutters row-cols-1 row-cols-md-5">
    <div class="media px-2 mb-4 mb-md-3" v-for="item in media">
      <media-item :media="item"></media-item>
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
</script>
