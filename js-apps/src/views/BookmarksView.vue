<template>
  <div class="bookmarks">
    <nav>
    <router-link to="/bookmarks/topics">Topics</router-link> |
    <router-link to="/bookmarks/posts">Posts</router-link> |
    <router-link to="/bookmarks/members">Members</router-link>
    </nav>

    <router-view :msg="msg"
                 :items="items"
                 :error_msg="error_msg"
                 :error="error"
                 @loadDescr="loadDescr"
                 @fetchData="fetchData"
                 @cleanItems="cleanItems"
    />


  </div>
</template>

<script>
// @ is an alias to /src
// import BookmarksComponent from '@/components/BookmarksComponent.vue'
import { HTTP, getToken } from '@/http-common.js'

export default {
  name: 'BookmarksView',
  components: {
    //BookmarksComponent
  },
  data () {
    return {
      msg: "Welcome to Bookmarks Vue.js App!",
      error_msg: "",
      error: false,
      items: null
    }
  },
  watch: {
    $route() {
      // react to route changes...
      // fix for items object
      this.cleanItems();
    }
  },
  mounted: function () {
    // this.$router.push('/inbox');
    // console.log(this.$route);
    // this.toggleBox(this.$route.path);
    // this.fetchData();
  },
  methods: {
      cleanItems() {
        this.items = null;
      },
      toggleAjax() {
        // cancel  previous ajax if exists
        if (this.ajaxRequest) {
          this.ajaxRequest.cancel();
        }
        this.ajaxRequest = getToken();
      },
      fetchData(url) {
        this.loadItems(url);
        this.error = false;
        this.error_msg = '';
      },
      // loadItems(ev, k, skip_pages=false) {
      loadItems(url) {
      
          //this.response = {rows: null, date_added: null};
          this.error_msg = '';
          //if (!skip_pages) {
            //this.pages = null;
          //}
          this.items = null;
          //var et = ev.target;
          
          // const url = this.$elk_scripturl;
          //console.log(ev);
          // setTimeout(function() {
          // }, 3000);

            // if (skip_pages) {
              // console.log(et);
              //var active_els = et.closest("ul").querySelectorAll("a.active-cust-link");
              //active_els.forEach(function(item) {
                //item.classList.remove("active-cust-link");
              //});
              //et.classList.add("active-cust-link");
            // }

          this.toggleAjax();

          HTTP.get(url, { cancelToken: this.ajaxRequest.token }).then(response => {
            // console.log(response.data);
            // console.log(skip_pages);
            //if (!skip_pages) {
              //this.pages = response.data.pages;
              //if (this.pages) {
                // console.log(this.pages);
              //}
            //}
            // console.log(this.items);
            // this.items = response.data.rows;
            this.items = response.data;
            // console.log(this.items);
            //this.response = response.data;

            if (response.data.err)
             this.error_msg = response.data.err;
            if (response.data.problem)
              this.error_msg = response.data.problem;
          }).catch((error) => {
            // handle error
            // console.log('error: ', error.message);
            this.error_msg = error.message;
            //this.error_msg = "lol";
            this.error = true;
          })
          .finally(function () {
            // always executed
          });
      },
      loadDescr(ev) {
        console.log(ev)
        console.log(ev.target.href)
      }
  }
}
</script>
