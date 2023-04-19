<template>
  <div class="bookmarks">
    <h1>This is an bookmarks POSTS page</h1>

    <BookmarksPostsComponent :msg="msg"
                             :items="items"
                             :error_msg="error_msg"
                             :error="error"
    />
  </div>
</template>

<script>
// @ is an alias to /src
import BookmarksPostsComponent from '@/components/BookmarksPostsComponent.vue'

export default {
  name: 'BookmarksPostsView',
  components: {
    BookmarksPostsComponent
  },
  data () {
    return {
        // msg: 'This is an bookmarks POSTS page',
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
    this.$emit('fetchData', this.$elk_scripturl_posts);
  },
  methods: {
  }
}
</script>
