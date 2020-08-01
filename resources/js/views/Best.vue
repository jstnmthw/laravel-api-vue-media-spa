<template>
  <div class="content container bg-purple">
    <div class="row">
      <main-sidebar :categories="categories"></main-sidebar>
      <main class="col-md-10">
        Best
      </main>
    </div>
  </div>
</template>

<script>
import MainSidebar from '@/components/MainSidebar'
export default {
  components: {
    MainSidebar: MainSidebar
  },
  props: ['categories']
}
</script>

<style></style>
