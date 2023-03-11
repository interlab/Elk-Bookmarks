<template>
  <div class="hello">
    <h1>{{ msg }}</h1>
        <div v-show="items" class="result-list">
          <p v-for="it in items" :key="it.url">
            <span v-html="it.text"></span> <a :href="it.localurl" @click.prevent="$emit('loadDescr', $event)">[LOAD]</a> <a :href="it.origurl">[ORIG]</a> 
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
