<template>
  <master-layout>
    <page-header>⚡ Best media</page-header>
  </master-layout>
</template>

<script>
import MasterLayout from '@/components/layout/Master.vue'
import PageHeader from '@/components/PageHeader.vue'
import Master from '@/components/layout/Master.vue'
export default {
  name: 'Recommended',
  components: {
    Master,
    MasterLayout,
    PageHeader
  }
}
</script>

<style scoped></style>
