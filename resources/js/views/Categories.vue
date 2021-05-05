<template>
  <master-layout>
    <page-header>{{ category }} media</page-header>
  </master-layout>
</template>

<script>
import MasterLayout from '@/components/layout/Master.vue'
import PageHeader from '@/components/PageHeader.vue'
import Master from '@/components/layout/Master.vue'
import { mapGetters } from 'vuex'
export default {
  name: 'Category',
  metaInfo() {
    return {
      title: this.category
    }
  },
  components: {
    Master,
    MasterLayout,
    PageHeader
  },
  computed: {
    ...mapGetters(['categories']),
    category() {
      let cat = this.categories.find(
        (x) => x.url === this.$route.params.category
      )

      return cat ? cat.name : ''
    }
  }
}
</script>

<style scoped></style>
