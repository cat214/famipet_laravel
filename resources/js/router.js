import Vue from 'vue'
import VueRouter from 'vue-router'

import Index from './components/Index.vue'
import Family from './components/Family.vue'
import Upload from './components/Upload.vue'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    component: Index
  },
  {
    path: '/family',
    component: Family
  },
  {
    path: '/upload',
    component: Upload
  }
]

const router = new VueRouter({
  mode: "history",
  routes
})

export default router