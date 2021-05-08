<template>
  <router-link
    :to="'/categories/' + data.url"
    class="btn btn-sm btn-category mb-1"
  >
    {{ data.name }}
  </router-link>
</template>

<script>
export default {
  props: ['data']
}
</script>
