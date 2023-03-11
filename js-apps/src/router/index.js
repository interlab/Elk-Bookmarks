import { createRouter, createWebHashHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'

const routes = [
  {
    path: '/',
    name: 'home',
    component: HomeView
  },
  {
    path: '/about',
    name: 'about',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => import(/* webpackChunkName: "about" */ '../views/AboutView.vue')
  },
  {
    path: '/bookmarks',
    name: 'bookmarks',
    component: () => import(/* webpackChunkName: "bookmarks" */ '../views/BookmarksView.vue'),
    // redirect: '/bookmarks/topics',
    children: [
        { path: '/bookmarks/topics',
          component: () => import(/* webpackChunkName: "bookmarkstopics" */ '../views/BookmarksTopicsView.vue'),
          name: 'bookmarks-topics'
        },
        { path: '/bookmarks/posts',
          component: () => import(/* webpackChunkName: "bookmarksposts" */ '../views/BookmarksPostsView.vue'),
          name: 'bookmarks-posts'
        },
        { path: '/bookmarks/members',
          component: () => import(/* webpackChunkName: "bookmarksmembers" */ '../views/BookmarksMembersView.vue'),
          name: 'bookmarks-members'
        },
    ]
  }
]

const router = createRouter({
  history: createWebHashHistory(),
  routes
})

export default router
