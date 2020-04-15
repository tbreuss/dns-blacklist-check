<template>
  <div id="app">
    <div class="cover-container d-flex h-100 p-3 mx-auto flex-column">
      <header class="masthead mb-auto">
        <div class="inner">
          <h3 class="masthead-brand">Blacklist Check</h3>
          <!-- nav class="nav nav-masthead justify-content-center">
            <router-link class="nav-link" to="/">Home</router-link>
            <a class="nav-link" href="#">Features</a>
            <a class="nav-link" href="#">Contact</a>
            <router-link class="nav-link" to="/about">About</router-link>
          </nav -->
        </div>
      </header>
      <main role="main" class="inner cover">
        <router-view/>
      </main>
      <footer class="mastfoot mt-auto">
        <div class="inner">
          <p>A tiny <a href="https://www.tebe.ch">tebe.ch</a> project</p>
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

<style>
  @import '../node_modules/bootstrap/dist/css/bootstrap.min.css';
  @import './assets/css/styles.css';
</style>
