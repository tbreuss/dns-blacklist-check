import Vue from 'vue'
import Router from 'vue-router'
import AboutPage from '@/components/AboutPage'
import StartPage from '@/components/StartPage'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      name: 'StartPage',
      component: StartPage
    },
    {
      path: '/about',
      name: 'AboutPage',
      component: AboutPage
    }
  ]
})
