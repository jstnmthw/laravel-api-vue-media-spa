<template>
  <div class="content container bg-purple">
    <div class="row">
      <main-sidebar :categories="categories"></main-sidebar>
      <main class="col-md-10">
        <page-header :title="''" icon="" v-show="loaded"></page-header>
      </main>
    </div>
  </div>
</template>

<script>
import PageHeader from '@/components/PageHeader'
import Sidebar from '@/components/layout/Sidebar'
export default {
  data() {
    return {
      loaded: true
    }
  },
  components: {
    Sidebar: Sidebar,
    PageHeader: PageHeader
  },
  props: ['categories']
}
</script>

<style></style>
