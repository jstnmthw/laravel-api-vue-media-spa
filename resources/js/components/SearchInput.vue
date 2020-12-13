<template>
  <form
    v-on:submit.prevent="onSubmit()"
    class="main-search order-3 order-md-2 my-2 mx-auto"
  >
    <div class="input-group input-group-lg">
      <input
        type="text"
        name="q"
        class="form-control"
        aria-label="Search by videos"
        aria-describedby="search-label"
        placeholder="Search videos"
        v-model="search_query"
      />
      <div class="input-group-append">
        <button type="submit" class="btn" id="search-label">
          <span class="sr-only">Search</span>
          <ion-icon name="search-sharp"></ion-icon>
        </button>
      </div>
    </div>
  </form>
</template>

<script>
export default {
  data() {
    return {
      search_query: this.$route.query.q ? this.$route.query.q : ''
    }
  },
  methods: {
    onSubmit() {
      const current_query = this.$route.query.q
      if (current_query !== this.search_query)
        this.$router.push({
          path: '/search',
          query: { q: encodeURI(this.search_query) }
        })
    }
  }
}
</script>
