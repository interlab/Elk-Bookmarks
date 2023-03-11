<template>
  <div class="bookmarks">
    <h1>This is an bookmarks TOPICS page</h1>

    <BookmarksComponent :msg="msg"
                        :items="items"
                        :error_msg="error_msg"
                        :error="error"
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
    }
  },
  props: {
    msg: String,
    items: Array[Object],
    error: Boolean,
    error_msg: String
  },
  mounted: function () {
    // this.$router.push('/inbox');
    // console.log(this.$route);
    // this.toggleBox(this.$route.path);
    console.log('run');
    this.$emit('fetchData', this.$elk_scripturl_topics);
  },
  methods: {
  }
}
</script>
