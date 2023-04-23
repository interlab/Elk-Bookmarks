<template>
  <div class="bookmarks">
    <h1>{{ msg }}</h1>

    <div class="infobox" v-if="error">Ошибка: {{ error_msg }}</div>
    <div class="infobox" v-if="!items">LOADING ...</div>
    <div class="result-list" v-else>
        <header class="category_header hdicon cat_img_posts">bookmark list</header>
        <!--
        :key="msg.bookmark.added_time"
        -->
      
        <article class="content forumposts" v-for="msg in items" :key="msg.id">
            <div class="floatright smalltext" style="padding: 6px 0 0 0;">
                txt_bmk_added_2 {{msg.bookmark.short_time}}
            </div>
            <header class="topic_details">
                <h5>
                    <p class="topic_icons{{msg.icon ? ' topicicon i-' + msg.icon : ''}}">
                        <span v-html="msg.post.link"></span> <i>txt_in </i><i v-html="msg.board.link"></i>
                    </p>
                    <br class="clear">
                    <p class="smalltext">
                        txt_by <strong v-html="msg.last_post.member.link"></strong> - {{msg.post.time}}
                    </p>
                </h5>
            </header>
            <section class="inner" v-html="msg.body"></section>
            
			<!--
            if (!empty($msg['buttons']))
				template_quickbutton_strip($msg['buttons'], !empty($msg['tests']) ? $msg['tests'] : []);
            -->
		</article>
      
    </div>
  </div>
</template>

<script>
export default {
  name: 'BookmarksPostComponent',
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
