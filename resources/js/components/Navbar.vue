<template>
  <nav
    class="navbar navbar-expand-md bg-purple container flex justify-content-between align-items-center pb-4"
  >
    <router-link to="/" class="logo">
      <h1 class="mb-0">🍑</h1>
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
        <button @click="login" class="btn btn-link">Login</button>
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
          v-pre
        >
          Name <span class="caret"></span>
        </a>
        <div
          class="dropdown-menu dropdown-menu-right"
          aria-labelledby="navbarDropdown"
        >
          <button @click="logout" class="btn btn-link">Logout</button>
        </div>
      </li>
    </ul>
  </nav>
</template>

<script>
import axios from 'axios'
import SearchInput from '@/components/SearchInput'

export default {
  data() {
    return {
      auth: localStorage.getItem('Authenticated') == 'true'
    }
  },
  components: {
    searchInput: SearchInput
  },
  mounted() {},
  methods: {
    async login() {
      await axios.get('/sanctum/csrf-cookie')
      await axios.post('/api/login', {
        email: 'web@jstn.ly',
        password: 'password'
      })

      let userData = await axios.get('/api/user')
      console.log(userData)
      localStorage.setItem('Authenticated', true)
      this.auth = true
    },
    async logout() {
      await axios.post('/logout')
      localStorage.removeItem('Authenticated', true)
      this.auth = false
    }
  }
}
</script>
