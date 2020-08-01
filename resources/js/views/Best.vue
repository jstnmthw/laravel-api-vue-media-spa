<template>
  <div class="content container bg-purple">
    <div class="row">
      <main-sidebar :categories="categories"></main-sidebar>
      <main class="col-md-10">
        <page-header
          :title="'Weekly Best Videos'"
          icon="flame"
          v-show="loaded"
        ></page-header>
      </main>
    </div>
  </div>
</template>

<script>
import PageHeader from '@/components/PageHeader'
import MainSidebar from '@/components/MainSidebar'
export default {
  data() {
    return {
      loaded: true
    }
  },
  components: {
    MainSidebar: MainSidebar,
    PageHeader: PageHeader
  },
  props: ['categories']
}
</script>

<style></style>
