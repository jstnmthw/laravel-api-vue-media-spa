<template>
  <div class="content container bg-purple">
    <div class="text-center">
      <h1 class="mt-lg-5">404 Not Found!</h1>
      <p>
        Looks like you stumbled upon a missing page.
        <router-link to="/" style="text-decoration: underline">
          Click here
        </router-link>
        to redirect to the home page.
      </p>
    </div>
  </div>
</template>

<script>
export default {
  name: 'NotFound',
  metaInfo: {
    title: '404 Not Found',
    meta: [{ name: 'robots', content: 'noindex' }]
  }
}
</script>
