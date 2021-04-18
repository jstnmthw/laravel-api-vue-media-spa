<template>
  <master-layout>
    <page-header>👀 Most Viewed Media</page-header>
  </master-layout>
</template>

<script>
import MasterLayout from '@/components/layout/Master.vue'
import PageHeader from '@/components/PageHeader.vue'
import Master from '@/components/layout/Master.vue'
export default {
  name: 'Most Viewed',
  components: {
    Master,
    MasterLayout,
    PageHeader
  }
}
</script>

<style scoped></style>
