<template>
  <div class="bookmarks">
    <h1>This is an bookmarks MEMBERS page</h1>

        <div class="error" v-if="error">
        Ошибка: {{ error_msg }}
        </div>
        <div v-show="items" class="result-list" v-else>

			<form class="generic_list_wrapper" action="?action=bookmarks;sa=delete;type=members" method="post">
				<table class="table_grid">
					<thead>
						<tr class="table_head">
							<th style="width:50px;">Avatar</th>
							<th class="grid20">Username</th>
							<th class="grid8">Status</th>
							<th class="grid17">Position</th>
							<th class="grid20">Date Registered</th>
							<th class="grid8">Posts</th>
							<th class="grid17">Added</th>
							<th class="centertext">
								<input type="checkbox" class="input_check" onclick="" />
							</th>
						</tr>
					</thead>
					<tbody>

                <tr v-for="it in items" :key="it.user.id">
                    <td v-html="it.user.avatar.image"></td>
                    <td v-html="it.user.link"></td>
                    <td v-html="it.online"></td>
                    <td v-text="it.user.group"></td>
                    <td>
                        <span class="smalltext" v-text="it.user.registered"></span>
                    </td>
                    <td>
                        <span class="smalltext" v-text="it.user.posts"></span>
                    </td>
                    <td>
                        <span class="smalltext" v-text="it.time"></span>
                    </td>
                    <td class="centertext">
                        <input type="checkbox" name="remove_bookmarks[]" value="{{it.user.id}}" class="input_check">
                    </td>
                </tr>

					</tbody>
				</table>
				<div class="submitbutton">
					<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
					<input class="button_submit" type="submit" name="send" value="bookmark delete" />
				</div>
			</form>
          
          
        </div>
  </div>
</template>

<script>
// @ is an alias to /src
// import BookmarksComponent from '@/components/BookmarksComponent.vue'

export default {
  name: 'BookmarksPostsView',
  components: {
    //BookmarksComponent
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
  computed: {
    members: function() { return []; },
  },
  mounted: function () {
    // this.$router.push('/inbox');
    // console.log(this.$route);
    // this.toggleBox(this.$route.path);
    this.$emit('fetchData', this.$elk_scripturl_members);
    console.log(this.items);
  },
  methods: {
  }
}
</script>
