<template>
  <div class="bookmarks">
    <h1>This is an bookmarks TOPICS page</h1>

    <BookmarksTopicsComponent :msg="msg"
                        :items="items"
                        :error_msg="error_msg"
                        :error="error"
    />
  </div>
</template>

<script>
// @ is an alias to /src
import BookmarksTopicsComponent from '@/components/BookmarksTopicsComponent.vue'

export default {
  name: 'BookmarksTopicsView',
  components: {
    BookmarksTopicsComponent
  },
  data () {
    return {
    }
  },
  props: {
    msg: String,
    items: Array[Object],
    error: Boolean,
    error_msg: String
  },
  watch: {
    $route() {
      // react to route changes...
      // fix for items object
      this.$emit('cleanItems');
    }
  },
  // mounted: function () {
  created: function () {
    // this.$router.push('/inbox');
    // console.log(this.$route);
    // this.toggleBox(this.$route.path);
    // this.$emit('cleanItems'); // not work :p
    this.$emit('fetchData', this.$elk_scripturl_topics);
  },
  methods: {
  }
}
</script>
