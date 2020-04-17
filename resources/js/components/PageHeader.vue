<template>
  <div class="row">
    <h3 class="col mb-3 font-weight-bold">
      <ion-icon :name="icon"></ion-icon>
      {{ title }}
    </h3>
  </div>
</template>

<script>
export default {
  props: ['title', 'icon'],
}
</script>

<style lang="scss" scoped>
  h3 {
    text-transform: capitalize;
  }
  ion-icon {
    top: 3px;
    position: relative;
  }
</style>