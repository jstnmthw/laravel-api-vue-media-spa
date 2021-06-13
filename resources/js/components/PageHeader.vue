<template>
  <div>
    <h2 class="font-weight-bold mb-0 text-capitalize">
      <slot>Page header</slot>
    </h2>
  </div>
</template>

<script>
export default {
  props: ['title', 'icon']
}
</script>

<style lang="scss" scoped>
h2 {
  font-size: 24px;
}
</style>
