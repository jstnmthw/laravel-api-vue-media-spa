<template>
  <div class="container">
    <div class="row">
      <ul class="pagination pagination-lg mx-auto">
        <li class="page-item" v-if="pagination.current_page > 1">
          <a
            class="page-link"
            href="javascript:void(0)"
            aria-label="Previous"
            v-on:click.prevent="changePage(pagination.current_page - 1)"
          >
            <span aria-hidden="true">
              <ion-icon style="top: 2;" name="chevron-back-outline"></ion-icon>
            </span>
            Prev
          </a>
        </li>
        <li class="page-item" v-if="pagination.current_page > 3">
          <a
            class="page-link"
            href="javascript:void(0)"
            aria-label="First Page"
            v-on:click.prevent="changePage(1)"
          >
            1
          </a>
        </li>
        <li class="page-item" v-if="pagination.current_page > 3">
          <a
            class="page-link page-hellip"
            href="javascript:void(0)"
            aria-label="..."
            v-on:click.prevent=""
          >
            &hellip;
          </a>
        </li>
        <li
          v-for="page in pagesNumber"
          class="page-item"
          :class="{ active: page == pagination.current_page }"
          :key="page.index"
        >
          <a
            class="page-link"
            href="javascript:void(0)"
            v-on:click.prevent="changePage(page)"
          >
            {{ comma_delimiter(page) }}
          </a>
        </li>
        <li
          class="page-item"
          v-if="
            pagination.current_page < pagination.last_page &&
            pagination.last_page >= 5
          "
        >
          <a
            class="page-link page-hellip"
            href="javascript:void(0)"
            aria-label="Last Page"
            v-on:click.prevent=""
          >
            &hellip;
          </a>
        </li>
        <li
          class="page-item"
          v-if="
            pagination.current_page < pagination.last_page &&
            pagination.last_page >= 5
          "
        >
          <a
            class="page-link"
            :class="{
              active: pagination.current_page <= pagination.last_page
            }"
            href="javascript:void(0)"
            aria-label="Last Page"
            v-on:click.prevent="changePage(pagination.last_page)"
          >
            {{ comma_delimiter(pagination.last_page) }}
          </a>
        </li>
        <li
          class="page-item"
          v-if="pagination.current_page < pagination.last_page"
        >
          <a
            class="page-link"
            href="javascript:void(0)"
            aria-label="Next"
            v-on:click.prevent="changePage(pagination.current_page + 1)"
          >
            Next
            <span aria-hidden="true">
              <ion-icon name="chevron-forward-outline"></ion-icon>
            </span>
          </a>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  props: ['loaded', 'pagination'],
  computed: {
    pagesNumber() {
      let totalPage = Math.ceil(
        this.pagination.total / this.pagination.per_page
      )
      let startPage =
        this.pagination.current_page < 3 ? 1 : this.pagination.current_page - 2
      let endPage = 4 + startPage
      endPage = totalPage < endPage ? totalPage : endPage
      let diff = startPage - endPage + 4
      startPage -= startPage - diff > 0 ? diff : 0

      let pagesArray = []
      for (let i = startPage; i <= endPage; i++) {
        pagesArray.push(i)
      }

      this.currentPage = startPage

      return pagesArray
    }
  },
  methods: {
    changePage(page) {
      if (this.loaded && this.pagination.current_page != page) {
        this.pagination.current_page = page

        // Push URL
        this.$router.push({
          query: Object.assign({}, this.$route.query, { page: page })
        })
      }
    },
    comma_delimiter(int) {
      return int.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
    }
  }
}
</script>
