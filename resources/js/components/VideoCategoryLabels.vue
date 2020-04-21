<template>
  <router-link :to="'/categories/'+slug" class="btn btn-sm btn-category">
    {{ label.name }}
  </router-link>
</template>

<script>
export default {
  props: ['label'],
  computed: {
    slug: function() {
      return this.label.name.replace(' ', '-').toLocaleLowerCase();
    }
  }
}
</script>

<style>

</style>