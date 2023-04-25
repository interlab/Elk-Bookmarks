<template>
  <div class="bookmarks">
    <h1>{{ msg }}</h1>
    <h3 class="category_header hdicon cat_img_profile">$txt.bookmark_list </h3>

    <div class="infobox" v-if="error">Ошибка: {{ error_msg }}</div>
    <div class="infobox" v-else-if="!items">LOADING ...</div>
    <div class="result-list" v-else>
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
                            <input type="checkbox" class="input_check" @click="invertAll" />
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
                    <input type="checkbox" :value="it.user.id" v-model="checkedMembers" class="input_check">
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
export default {
  name: 'BookmarksMembersComponent',
  props: {
    msg: String,
    items: Array[Object],
    error: Boolean,
    error_msg: String
  },
  data () {
    return {
        checkedMembers: new Set()
    }
  },
  methods: {
    invertAll(event) {
        const isChecked = event.target.checked;
        this.items.forEach((val)=>{
            const value = String(val.user.id);
            if (isChecked) {
                this.checkedMembers.add(value);
            } else {
                this.checkedMembers = new Set();
            }
        });
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.submitbutton {
    text-align: right;
}
/*
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
*/
</style>
