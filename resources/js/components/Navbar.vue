<template>
  <nav
    class="navbar navbar-expand-md navbar-light bg-purple border-bottom-1 container"
  >
    <router-link to="/">
      <h1 style="font-size: 20px;">Site</h1>
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
    <div class="collapse navbar-collapse" id="mainNavbar">
      <ul class="navbar-nav mr-auto"></ul>
      <ul class="navbar-nav ml-auto">
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
    </div>
  </nav>
</template>

<script>
export default {
  data() {
    return {
      auth: localStorage.getItem('Authenticated') == 'true'
    }
  },
  mounted() {
    // console.log('Component mounted.');
  },
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
