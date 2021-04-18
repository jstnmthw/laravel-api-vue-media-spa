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
          <span class="sr-only">Search</span
          ><svg
            width="16px"
            height="16px"
            class="icon"
            fill="currentColor"
            viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              fill-rule="evenodd"
              d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
              clip-rule="evenodd"
            ></path>
          </svg>
        </button>
      </div>
    </div>
  </form>
</template>

<script>
export default {
  data() {
    return {
      search_query: this.$route.query.q ? decodeURI(this.$route.query.q) : ''
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
