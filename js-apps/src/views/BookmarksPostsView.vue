<template>
  <div class="bookmarks">
    <h1>This is an bookmarks POSTS page</h1>

    <BookmarksComponent :msg="msg"
                        :items="items"
                        @loadDescr="loadDescr"
    />
  </div>
</template>

<script>
// @ is an alias to /src
import BookmarksComponent from '@/components/BookmarksComponent.vue'

export default {
  name: 'BookmarksPostsView',
  components: {
    BookmarksComponent
  },
  data () {
    return {
        // msg: 'This is an bookmarks POSTS page',
    }
  },
  props: {
    msg: String,
    items: Array[Object]
  },
  mounted: function () {
    // this.$router.push('/inbox');
    // console.log(this.$route);
    // this.toggleBox(this.$route.path);
    this.$emit('fetchData', this.$elk_scripturl_posts);
  },
  methods: {
  }
}
</script>
