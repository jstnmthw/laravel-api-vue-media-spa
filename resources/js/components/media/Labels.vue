<template>
  <router-link
    :to="'/categories/' + category.url"
    class="btn btn-sm btn-category mb-1"
  >
    {{ category.name }}
  </router-link>
</template>

<script>
export default {
  props: ['category']
}
</script>
