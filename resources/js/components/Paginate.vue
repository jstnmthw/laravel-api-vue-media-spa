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
            <span aria-hidden="true"
              ><svg
                class="icon"
                fill="currentColor"
                viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  fill-rule="evenodd"
                  d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                  clip-rule="evenodd"
                ></path>
              </svg>
            </span>
            Prev
          </a>
        </li>
        <li
          class="page-item d-none d-md-block"
          v-if="pagination.current_page > 3"
        >
          <a
            class="page-link"
            href="javascript:void(0)"
            aria-label="First Page"
            v-on:click.prevent="changePage(1)"
          >
            1
          </a>
        </li>
        <li
          class="page-item d-none d-md-block"
          v-if="pagination.current_page > 3"
        >
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
          class="page-item d-none d-md-block"
          :class="{ active: page === pagination.current_page }"
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
          class="page-item d-none d-md-block"
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
          class="page-item d-none d-md-block"
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
            <span aria-hidden="true"
              ><svg
                class="icon"
                fill="currentColor"
                viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  fill-rule="evenodd"
                  d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                  clip-rule="evenodd"
                ></path>
              </svg>
            </span>
          </a>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
import { comma_delimiter } from '@/helpers/numbers'

export default {
  props: ['loading', 'pagination'],
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
      return pagesArray
    }
  },
  methods: {
    changePage(page) {
      if (!this.loading && this.pagination.current_page !== page) {
        // Push URL
        this.$router.push({
          query: Object.assign({}, this.$route.query, { page: page })
        })
      }
    },
    comma_delimiter
  }
}
</script>
