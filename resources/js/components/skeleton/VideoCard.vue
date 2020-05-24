<template>
  <!-- TODO: Put skeleton cards in parent -->
  <div class="row skeleton-row no-gutters" :class="'row-cols-' + cols">
    <div
      class="col px-2 mb-md-3 position-relative"
      v-for="card in cards"
      :key="card.index"
    >
      <div class="skeleton-card-video">
        <div class="skeleton-card-image"></div>
        <div class="skeleton-card-line"></div>
        <div class="skeleton-card-line2"></div>
        <div class="skeleton-card-views"></div>
        <div class="skeleton-card-likes"></div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["cards", "cols"],
}
</script>
