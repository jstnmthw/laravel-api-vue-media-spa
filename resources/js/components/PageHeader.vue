<template>
  <div class="row">
    <h4 class="col mb-3 font-weight-bold">
      <ion-icon :name="icon" v-if="icon"></ion-icon>
      {{ deslug }}
    </h4>
  </div>
</template>

<script>
export default {
  props: ['title', 'icon'],
  computed: {
    deslug: function() {
      return this.title.replace('-', ' ');
    }
  }
}
</script>

<style lang="scss" scoped>
  h4 {
    text-transform: capitalize;
  }
  ion-icon {
    top: 3px;
    position: relative;
  }
</style>