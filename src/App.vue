<template>
  <div id="app">
    <div class="cover-container d-flex h-100 p-3 mx-auto flex-column">
      <header class="masthead mb-auto">
        <div class="inner">
          <h3 class="masthead-brand">Cover</h3>
          <nav class="nav nav-masthead justify-content-center">
            <a class="nav-link active" href="#">Home</a>
            <a class="nav-link" href="#">Features</a>
            <a class="nav-link" href="#">Contact</a>
          </nav>
        </div>
      </header>
      <main role="main" class="inner cover">
        <router-view/>
      </main>
      <footer class="mastfoot mt-auto">
        <div class="inner">
          <p>Made by <a href="https://www.tebe.ch">tebe</a>.</p>
        </div>
      </footer>
    </div>
  </div>
</template>

<script>
export default {
  name: 'App'
}
</script>

<style lang="scss">
    /*@import './assets/scss/custom-bootstrap.scss';
    @import '../node_modules/bootstrap/scss/bootstrap.scss';*/
</style>

<style>
  @import '../node_modules/bootstrap/dist/css/bootstrap.min.css';
  @import './assets/css/styles.css'
</style>
