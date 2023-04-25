<template>
  <nav>
    <router-link :to="{ name: 'home' }">Home</router-link> |
    <router-link :to="{ name: 'about' }">About</router-link> |
    <router-link :to="{ name: 'bookmarks' }">Bookmarks</router-link> |
    <router-link :to="{ name: 'todo' }">Todo</router-link>
  </nav>
  <router-view/>
</template>

<style scoped>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}

#app {
    margin: 0 auto;
    width: 80rem;
}

nav {
  padding: 30px;
}

nav a {
  font-weight: bold;
  color: #2c3e50;
}

nav a.router-link-exact-active {
  color: #42b983;
}
</style>
