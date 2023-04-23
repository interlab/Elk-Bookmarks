<template>
  <div class="bookmarks">
    <h1>This is an bookmarks MEMBERS VIEW page</h1>

    <BookmarksMembersComponent :msg="msg"
                             :items="items"
                             :error_msg="error_msg"
                             :error="error"
    />
  </div>
</template>

<script>
// @ is an alias to /src
import BookmarksMembersComponent from '@/components/BookmarksMembersComponent.vue'

export default {
  name: 'BookmarksMembersView',
  components: {
    BookmarksMembersComponent
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
  /*
  computed: {
    members: function() { return []; },
  },
  */
  // mounted: function () {
  created: function () {
    // this.$emit('cleanItems'); // not work :p
    this.$emit('fetchData', this.$elk_scripturl_members);
    //console.log(this.items);
  },
  methods: {
  }
}
</script>
