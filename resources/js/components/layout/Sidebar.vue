<template>
  <aside
    id="mainSidebar"
    class="main-sidebar collapse col-sm-12 col-lg-3 col-xl-2"
    :class="classes"
  >
    <div class="main-categories">
      <ul class="list-unstyled pl-4">
        <li>
          <router-link :to="'/my/subscriptions'"
            ><svg
              class="text-lilac icon mr-2"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
              ></path>
            </svg>
            Subscriptions
            <span class="counter counter-subscriptions"></span>
          </router-link>
        </li>
        <li>
          <router-link :to="'/my/watch-history'">
            <svg
              class="text-lilac icon mr-2"
              fill="currentColor"
              viewBox="0 0 20 20"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                fill-rule="evenodd"
                d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                clip-rule="evenodd"
              ></path>
            </svg>
            Watch History
          </router-link>
        </li>
        <li class="line"><hr style="background: #fff; opacity: 0.1" /></li>
        <li>
          <router-link :to="'/best'"
            ><svg
              class="text-lilac icon mr-2"
              fill="currentColor"
              viewBox="0 0 20 20"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                fill-rule="evenodd"
                d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z"
                clip-rule="evenodd"
              ></path>
            </svg>
            Best Videos
          </router-link>
        </li>
        <li>
          <router-link :to="'/most-viewed'"
            ><svg
              class="text-lilac icon mr-2"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
              ></path>
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
              ></path>
            </svg>
            Most Viewed
          </router-link>
        </li>
        <li>
          <router-link :to="'/recommended'">
            <svg
              class="text-lilac icon mr-2"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"
              ></path>
            </svg>
            Recommended
          </router-link>
        </li>
      </ul>
    </div>
    <div class="all-categories">
      <div class="sidebar-filter-container">
        <div class="search-wrap position-relative">
          <label>
            <span class="sr-only">Search Categories</span>
            <input
              v-model="search"
              @keyup="search_category()"
              class="form-control mb-3"
              type="text"
              placeholder="Filter by category…"
            />
          </label>
          <button
            type="button"
            class="btn-clear-search"
            v-show="search"
            @click.prevent="clear_search()"
          >
            <svg
              class="icon"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              ></path>
            </svg>
          </button>
        </div>
      </div>
      <ul id="search-list" class="list-unstyled pl-4" v-if="categories">
        <li v-for="category in categories" :key="category.id">
          <router-link :to="'/categories/' + category.url">
            {{ category.name }}
          </router-link>
        </li>
      </ul>
    </div>
  </aside>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  data() {
    return {
      search: null
    }
  },
  props: ['classes'],
  computed: {
    ...mapGetters(['categories'])
  },
  methods: {
    search_category() {
      const search = [this.search]
      $('#search-list li').each(function (i) {
        const haystack = search[0].toUpperCase()
        const needle = this.innerText.toUpperCase()
        if (needle.indexOf(haystack) > -1) {
          $(this).show()
        } else {
          $(this).hide()
        }
      }, search)
    },
    clear_search() {
      this.search = null
      $('#search-list li').show()
    }
  }
}
</script>
