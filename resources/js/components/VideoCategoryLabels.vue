<template>
  <div class="label-wrap">
    <ul>
      <li v-for="label in categories" :key="label.index">
        {{ label }}
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  props: ['categories']
}
</script>

<style>

</style>