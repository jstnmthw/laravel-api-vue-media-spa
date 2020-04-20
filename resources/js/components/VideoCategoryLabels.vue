<template>
  <div class="label-wrap">
    <ul class="list-inline">
      <li class="list-inline-item" v-for="label in categories" :key="label.index">
        <router-link :to="'/categories/'+label.slug" class="btn btn-sm btn-category">
          {{ label.name }}
        </router-link>
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