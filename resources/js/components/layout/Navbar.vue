<template>
  <nav
    class="navbar navbar-expand-md bg-purple container flex justify-content-between align-items-center pb-4"
  >
    <router-link to="/" class="logo">
        <h1 class="mb-0"><span class="sr-only">🍑</span></h1>
    </router-link>
    <button
      class="navbar-toggler"
      type="button"
      data-toggle="collapse"
      data-target="#mainNavbar"
      aria-controls="mainNavbar"
      aria-expanded="false"
      aria-label="Toggle Nav"
    >
      <span class="navbar-toggler-icon"></span>
    </button>
    <search-input></search-input>
    <ul class="navbar-nav">
      <li v-if="!auth" class="nav-item">
        <button @click="login" class="btn btn-link">
          <ion-icon
            name="person"
            style="position: relative; top: 2px;"
            class="mr-1"
          ></ion-icon
          >Login
        </button>
      </li>
      <li v-if="!auth" class="nav-item">
        <a class="nav-link text-white" href="/register">Register</a>
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
          <button @click="logout" class="btn btn-link text-purple">Logout</button>
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
      return this.$store.state.user.name
    }
  },
  methods: {
    async login() {
      await axios.get('/sanctum/csrf-cookie').then((res) => {
        axios
          .post('/api/login', {
            email: 'web@jstn.ly',
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
      localStorage.removeItem('Authenticated', true)
      this.auth = false
    }
  }
}
</script>
