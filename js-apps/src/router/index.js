import { createRouter, createWebHashHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'

// const baseUrl = '/elki1-1/t2/apps/bookmarks/dist';
const baseUrl = '';

const routes = [
  {
    path: baseUrl + '/',
    name: 'home',
    component: HomeView
  },
  {
    path: baseUrl + '/about',
    name: 'about',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => import(/* webpackChunkName: "about" */ '../views/AboutView.vue')
  },
  {
    path: baseUrl + '/bookmarks',
    name: 'bookmarks',
    component: () => import(/* webpackChunkName: "bookmarks" */ '../views/BookmarksView.vue'),
    // redirect: '/bookmarks/topics',
    redirect: { name: 'bookmarks-topics' },
    children: [
        { path: baseUrl + '/bookmarks/topics',
          component: () => import(/* webpackChunkName: "bookmarkstopics" */ '../views/BookmarksTopicsView.vue'),
          name: 'bookmarks-topics'
        },
        { path: baseUrl + '/bookmarks/posts',
          component: () => import(/* webpackChunkName: "bookmarksposts" */ '../views/BookmarksPostsView.vue'),
          name: 'bookmarks-posts'
        },
        { path: baseUrl + '/bookmarks/members',
          component: () => import(/* webpackChunkName: "bookmarksmembers" */ '../views/BookmarksMembersView.vue'),
          name: 'bookmarks-members'
        },
    ]
  },
  {
    path: baseUrl + '/todo',
    name: 'todo',
    component: () => import(/* webpackChunkName: "todo" */ '../views/TodoView.vue')
  }
]

const router = createRouter({
  history: createWebHashHistory(),
  routes
})

export default router
