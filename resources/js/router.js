import Vue from 'vue'
import VueRouter from 'vue-router'

import PhotoList from './components/PhotoList.vue'
import Login from './components/Login.vue'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    component: PhotoList
  },
  {
    path: '/login',
    component: Login
  }
]

const router = new VueRouter({
  routes
})

export default router