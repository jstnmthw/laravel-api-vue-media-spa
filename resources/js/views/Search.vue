<template>
  <div class="content container bg-purple">
    <div class="row">
      <main-sidebar :categories="categories"></main-sidebar>
      <main class="col-md-10">
        <top-ad-banner></top-ad-banner>

        <page-header
          :title="'Most Viewed Videos'"
          icon="eye"
          v-show="loaded"
        ></page-header>

        <div class="d-flex justify-content-between align-items-center mb-3">
          <h3 class="text-center" v-show="!loaded">Loading API</h3>
          <div v-if="loaded">
            Showing: {{ uf_num(videos.current_page) }} of
            {{ uf_num(videos.last_page) }}
          </div>
          <div>
            <select
              id="sortby"
              v-model="sort"
              name="sortby"
              class="custom-select"
              @change="sortBy()"
            >
              <option selected value="most_views">Most Views</option>
              <option value="top_rated">Top Rated</option>
              <option value="duration">Duration</option>
              <option value="most_recent">Most Recent</option>
            </select>
          </div>
        </div>

        <video-list
          class="mb-5"
          v-if="loaded"
          :videos="videos.data"
          :cards="50"
          :cols="5"
        ></video-list>

        <skeleton-video-card
          class="mb-5"
          v-if="!loaded"
          :cards="50"
          :cols="5"
        ></skeleton-video-card>

        <paginate
          class="mb-5"
          v-show="loaded"
          :pagination="videos"
          :loaded="loaded"
          @paginate="getVideos()"
        ></paginate>
      </main>
    </div>
  </div>
</template>

<script>
export default {
  props: ["categories"],
}
</script>

<style></style>
