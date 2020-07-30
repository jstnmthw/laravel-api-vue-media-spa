<template>
  <form action="/search" method="get" class="main-search my-2 mx-auto">
    <div class="input-group input-group-lg">
      <input
        type="text"
        name="q"
        class="form-control"
        aria-label="Search by videos"
        aria-describedby="search-label"
        placeholder="Search videos"
        v-bind:value="search_query"
      />
      <div class="input-group-append">
        <button class="btn" id="search-label">
          <span class="sr-only">Search</span>
          <ion-icon name="search-sharp"></ion-icon>
        </button>
      </div>
    </div>
  </form>
</template>

<script>
export default {
  computed: {
    search_query: {
      get: function () {
        return this.$route.query.q
      },
      set: function (value) {
        this.$router.replace({
          query: { q: value }
        })
      }
    }
  }
}
</script>

<style></style>
