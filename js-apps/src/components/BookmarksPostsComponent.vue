<template>
  <div class="hello">
    <h1>{{ msg }}</h1>
        <div class="error" v-if="error">
        Ошибка: {{ error_msg }}
        </div>
        <div v-show="items" class="result-list" v-else>
          <p v-for="it in items" :key="it.url">
          Board: {{ it.board.name }} > 
            <span v-html="it.subject"></span> > {{ it.bookmark.time }}
            <span v-html="it.last_post.link"></span>
          </p>
        </div>
  </div>
</template>

<script>
export default {
  name: 'BookmarksComponent',
  props: {
    msg: String,
    items: Array[Object],
    error: Boolean,
    error_msg: String
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
h3 {
  margin: 40px 0 0;
}
ul {
  list-style-type: none;
  padding: 0;
}
li {
  display: inline-block;
  margin: 0 10px;
}
a {
  color: #42b983;
}
div.result-list {
  text-align: left;
}
</style>
