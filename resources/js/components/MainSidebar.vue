<template>
  <div class="col-sm-12 col-md-3 col-lg-2">
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
        <i class="xh-icon search"></i>
        <input class="form-control mb-3" type="text" placeholder="Filter by category…">
        <div class="sidebar-filter__loader dots-loader">
          <div class="dot"></div>
          <div class="dot"></div>
          <div class="dot"></div>
        </div>
      </div>
      <ul class="list-unstyled pl-4">
        <li v-for="category in categories" :key="category.id">
          <router-link :to="'/categories/' + category.slug">
            {{ category.name }}
          </router-link>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  props: ['categories']
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