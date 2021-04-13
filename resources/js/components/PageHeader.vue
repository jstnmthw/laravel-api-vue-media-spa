<template>
  <div>
    <h4 class="font-weight-bold mb-0">
      {{ title }}
    </h4>
  </div>
</template>

<script>
export default {
  props: ['title', 'icon']
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
