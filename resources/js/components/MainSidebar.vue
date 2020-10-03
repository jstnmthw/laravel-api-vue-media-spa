<template>
  <aside class="main-sidebar col-sm-12 col-md-3 col-lg-2">
    <div class="main-categories">
      <ul class="list-unstyled pl-4">
        <li>
          <router-link :to="'/my/subscriptions'">
            <ion-icon class="text-lilac" name="mail-outline"></ion-icon
            >Subscriptions
            <span class="counter counter-subscriptions"></span>
          </router-link>
        </li>
        <li>
          <router-link :to="'/my/watch-history'">
            <ion-icon class="text-lilac" name="sync-circle-outline"></ion-icon
            >Watch History
          </router-link>
        </li>
        <div class="line"></div>
        <li>
          <router-link :to="'/best/weekly'">
            <ion-icon class="text-lilac" name="trophy-outline"></ion-icon>Best
            Videos
          </router-link>
        </li>
        <li>
          <router-link :to="'/most-viewed/weekly'">
            <ion-icon class="text-lilac" name="eye-outline"></ion-icon>Most
            Viewed
          </router-link>
        </li>
        <li>
          <router-link :to="'/most-commented/weekly'">
            <ion-icon class="text-lilac" name="chatbox-outline"></ion-icon>Most
            Commented
          </router-link>
        </li>
        <li>
          <router-link :to="'/videos/recommended'">
            <ion-icon class="text-lilac" name="thumbs-up-outline"></ion-icon
            >Recommended
          </router-link>
        </li>
      </ul>
    </div>
    <div class="all-categories">
      <div class="sidebar-filter-container">
        <div class="search-wrap position-relative">
          <input
            v-model="search"
            @keyup="search_category()"
            class="form-control mb-3"
            type="text"
            placeholder="Filter by category…"
          />
          <button
            type="button"
            class="btn-clear-search"
            v-show="search"
            @click.prevent="clear_search()"
          >
            <ion-icon name="close-circle"></ion-icon>
          </button>
        </div>
      </div>
      <ul id="search-list" class="list-unstyled pl-4" v-if="categories">
        <li v-for="category in categories" :key="category.id">
          <router-link :to="'/categories/' + category.slug">
            {{ category.name }}
          </router-link>
        </li>
      </ul>
    </div>
  </aside>
</template>

<script>
import { mapState, mapGetters } from 'vuex'

export default {
  data() {
    return {
      search: null
    }
  },
  computed: mapState({
    categories: (state) => state.categories
  }),
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
