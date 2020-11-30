<template>
  <div>
    <header-nav @setUserData="setUserData"></header-nav>
    <main class="main">
      <h1>メイン</h1>
      <!-- :TODO ここに画像一覧をもってくる -->
      <router-view :user="user"></router-view>
    </main>
    <footer class="footer">
      <h1>フッター</h1>
    </footer>
  </div>
</template>

<script>
import HeaderNav from './components/HeaderNav.vue'

export default {
  data: function () {
    return {
      user: {
        id: "",
        name: "",
        icon: "",
      },
    }
  },
  
  components: {
    HeaderNav
  },

  methods: {
    setUserData (userdata) {
      // headernavでログインした情報を共有
      this.user = userdata;
    }
  }
}
</script>