import { createApp } from 'vue'
import App from './App.vue'
import router from './router'

// Vue.prototype.$elk_scripturl = 'http://localhost/elki1-1/t2/index.php';

var app = createApp(App).use(router);
// app.config.globalProperties.$elk_scripturl = 'http://localhost/elki1-1/t2/index.php';
app.config.globalProperties.$elk_scripturl_topics = 'http://fastnews.pmr/get.php?ajax=rutor';
//app.config.globalProperties.$elk_scripturl_topics = 'http://localhost/elki1-1/t2/index.php?action=bookmarks;type=topics;resp=ajax';
// app.config.globalProperties.$elk_scripturl_posts = 'http://fastnews.pmr/get.php?ajax=3dNews';
app.config.globalProperties.$elk_scripturl_posts = 'http://localhost/elki1-1/t2/index.php?action=bookmarks;type=messages;resp=ajax';
app.config.globalProperties.$elk_scripturl_members = 'http://fastnews.pmr/get.php?ajax=tvzvezda';
//app.config.globalProperties.$elk_scripturl_members = 'http://localhost/elki1-1/t2/index.php?action=bookmarks;type=members;resp=ajax';
app.mount('#app');
