<template>
  <div class="content container bg-purple">
    <div class="row">
      <main-sidebar :categories="categories"></main-sidebar>
      <main class="col-md-10">
        <top-ad-banner></top-ad-banner>
        <page-header
          :title="'Weekly Best Videos'"
          icon="flash"
          v-show="loaded"
        ></page-header>
      </main>
    </div>
  </div>
</template>

<script>
import TopAdBanner from '@/components/TopAdBanner'
import PageHeader from '@/components/PageHeader'
import MainSidebar from '@/components/MainSidebar'
export default {
  data() {
    return {
      loaded: true
    }
  },
  components: {
    TopAdBanner: TopAdBanner,
    MainSidebar: MainSidebar,
    PageHeader: PageHeader
  },
  props: ['categories']
}
</script>

<style></style>
