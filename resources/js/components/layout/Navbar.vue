<template>
  <nav
    class="navbar navbar-dark navbar-expand-lg bg-purple container flex justify-content-between align-items-center pb-0"
  >
    <router-link to="/" class="logo">
      <h1 class="mb-0">🍑</h1>
    </router-link>
    <search-input></search-input>
    <button
      class="navbar-toggler order-2 order-lg-3"
      type="button"
      data-toggle="collapse"
      data-target="#mainSidebar"
      aria-controls="mainSidebar"
      aria-expanded="false"
      aria-label="Toggle Nav"
    >
      <span class="navbar-toggler-icon"></span>
    </button>
    <ul class="navbar-nav">
      <li v-if="!auth" class="nav-item d-none">
        <button @click="login" class="btn btn-link">
          <ion-icon
            name="person"
            style="position: relative; top: 2px"
            class="mr-1"
          ></ion-icon
          >Login
        </button>
      </li>
      <li v-if="!auth" class="nav-item d-none">
        <a class="nav-link text-white" href="javascript: void(0);">Register</a>
      </li>
      <li v-if="auth" class="nav-item dropdown">
        <a
          id="navbarDropdown"
          class="nav-link dropdown-toggle"
          href="#"
          role="button"
          data-toggle="dropdown"
          aria-haspopup="true"
          aria-expanded="false"
        >
          {{ name }} <span class="caret"></span>
        </a>
        <div
          class="dropdown-menu dropdown-menu-right"
          aria-labelledby="navbarDropdown"
        >
          <button @click="logout" class="btn btn-link text-purple">
            Logout
          </button>
        </div>
      </li>
    </ul>
  </nav>
</template>

<script>
import store from '@/store'
import axios from 'axios'
import SearchInput from '@/components/SearchInput'

export default {
  data() {
    return {
      auth: localStorage.getItem('Authenticated') === 'true'
    }
  },
  mounted() {
    if (this.auth) {
      axios.get('/api/user').then((res) => {
        store.commit('ADD_USER_INFO', res.data)
      })
    }
  },
  components: {
    searchInput: SearchInput
  },
  computed: {
    name() {
      return this.$store.state.user.name ? this.$store.state.user.name : 'Guest'
    }
  },
  methods: {
    async login() {
      await axios.get('/sanctum/csrf-cookie').then((res) => {
        axios
          .post('/api/login', {
            email: 'email@example.com',
            password: 'password'
          })
          .then((res) => {
            axios.get('/api/user').then((res) => {
              localStorage.setItem('Authenticated', 'true')
              this.auth = true
              store.commit('ADD_USER_INFO', res.data)
            })
          })
      })
    },
    async logout() {
      await axios.post('/logout')
      localStorage.removeItem('Authenticated')
      this.auth = false
    }
  }
}
</script>
