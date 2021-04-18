<template>
  <div>
    <h4 class="font-weight-bold mb-0">
      <slot>Page header</slot>
    </h4>
  </div>
</template>

<script>
export default {
  props: ['title', 'icon']
}
</script>

<style lang="scss"></style>
