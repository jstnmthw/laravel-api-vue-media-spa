<template>
  <aside class="col-sm-12 col-md-3 col-lg-2">
    <div class="main-categories">
      <ul class="list-unstyled pl-4">
        <li>
          <a href="/my/subscriptions">
            <ion-icon name="mail-outline"></ion-icon>Subscriptions
            <span class="counter counter-subscriptions"></span>
          </a>
        </li>
        <li>
          <a href="/my/watch-history">
            <ion-icon name="sync-circle-outline"></ion-icon>Watch History
          </a>
        </li>
        <div class="line"></div>
        <li>
          <a href="/best/weekly">
            <ion-icon name="trophy-outline"></ion-icon>Best Videos
          </a>
        </li>
        <li>
          <a href="/most-viewed/weekly">
            <ion-icon name="eye-outline"></ion-icon>Most Viewed
          </a>
        </li>
        <li>
          <a href="/most-commented/weekly">
            <ion-icon name="chatbox-outline"></ion-icon>Most Commented
          </a>
        </li>
        <li>
          <a href="/videos/recommended">
            <ion-icon name="thumbs-up-outline"></ion-icon>Recommended
          </a>
        </li>
      </ul>
    </div>
    <div class="all-categories">
      <div class="sidebar-filter-container">
        <div class="search-wrap">
          <input 
            v-model="search" 
            @keyup="search_category()" 
            class="form-control mb-3" 
            type="text" 
            placeholder="Filter by category…"
          >
          <button type="button" class="btn-clear-search" v-show="search" @click.prevent="clear_search()">
            <ion-icon name="close-circle"></ion-icon>
          </button>
        </div>
      </div>
      <ul id="search-list" class="list-unstyled pl-4">
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
export default {
  data() {
    return {
      search: null
    }
  },
  props: ['categories'],
  methods: {
    search_category() {
      const search = [this.search];
      $('#search-list li').each(function(i) {
        const haystack = search[0].toUpperCase();
        const needle = this.innerText.toUpperCase();
        if(needle.indexOf(haystack) > -1) {
          $(this).show();
        } else {
          $(this).hide();
        }
      }, search);
    },
    clear_search() {
      this.search = null;
      $('#search-list li').show();
    }
  }
}
</script>

<style lang="scss" scoped>
  .main-categories {
    margin-top: .5rem;
    margin-bottom: 2rem;
    li {
      margin-bottom: 5px;
      ion-icon {
        position: relative;
        font-size: 20px;
        top: 5px;
        margin-right: 7px;
      }
    }
  }
</style>