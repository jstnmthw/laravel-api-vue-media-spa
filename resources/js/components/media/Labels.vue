<template>
  <router-link :to="'/categories/' + slug" class="btn btn-sm btn-category mb-1">
    {{ label }}
  </router-link>
</template>

<script>
export default {
  props: ['label'],
  computed: {
    slug: function () {
      return this.label.replaceAll(' ', '-').toLocaleLowerCase()
    }
  }
}
</script>

<style></style>
