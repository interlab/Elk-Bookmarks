<template>
  <div class="bookmarks">
    <h1>{{ msg }}</h1>
    <div class="infobox" v-if="error">Ошибка: {{ error_msg }}</div>
    <div class="infobox" v-if="!items">LOADING ...</div>
    <div class="result-list" v-else>
    <!--
        <p v-for="it in items" :key="it.url">
        Board: {{ it.board.name }} > 
        <span v-html="it.subject"></span> > {{ it.bookmark.time }}
        <span v-html="it.last_post.link"></span>
        </p>
    -->
    
<form class="generic_list_wrapper" action="$scripturl?action=bookmarks;sa=delete" method="post">
    <table class="table_grid">
        <thead>
            <tr class="table_head">
                <th style="width:50px;"></th>
                <th class="grid33">$txt.subject</th>
                <th class="grid20">$txt.author</th>
                <th class="centertext">$txt.replies</th>
                <th class="centertext">$txt.views</th>
                <th class="grid20">$txt.latest_post</th>
                <th class="grid20">$txt.bmk_added</th>
                <th class="centertext">
                    <input type="checkbox" class="input_check" />
                </th>
            </tr>
        </thead>
        <tbody>

        <tr v-for="msg in items" :key="msg.url">
            <td>
                <p class="topic_icons">
                    <img src="" alt="" />
                </p>
            </td>
            <td>
            <span v-html="msg.post.link"></span>
            <br />
            <span class="smalltext"><i>$txt.in </i><i v-html="msg.board.link"></i></span>
            </td>
            <td>
                <span class="smalltext">{{ msg.post.time }}<br />
                $txt.by </span><span class="smalltext" v-html="msg.post.member.link"></span>
            </td>
            <td class="centertext">{{ msg.replies }}</td>
            <td class="centertext">{{ msg.views }}</td>
            <td>
                <span class="smalltext">{{ msg.last_post.time }}<br />
                $txt_by </span><span class="smalltext" v-html="msg.last_post.member.link"></span>
                <a class="topicicon i-last_post" href="{{ msg.last_post.href }}" title="$txt.last_post"></a>
            </td>
            <td>
                <span class="smalltext">{{ msg.bookmark.time }}</span>
            </td>
            <td class="centertext">
                <input type="checkbox" name="remove_bookmarks[]" value="{{ msg.post.id }}" class="input_check" />
            </td>
        </tr>

                    </tbody>
                </table>
                <div class="submitbutton">
                    <input type="hidden" name="$context.session_var" value="$context.session_id" />
                    <input class="button_submit" type="submit" name="send" value="$txt.bookmark_delete" />
                </div>
            </form>
    
    
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
