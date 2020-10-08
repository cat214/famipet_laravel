<template>
    <div>
        <h1>photo list</h1>
        <h1>ユーザー情報</h1>
        <a v-if="!user.name" :href="lineLoginUrl">ログイン</a>
        <p v-if="user.name">{{ user.name }}</p>
        <img v-if="user.icon" :src="user.icon" alt="">
    </div>
</template>

<script>
import axios from 'axios';
import sha256 from 'js-sha256';
const {randomBytes} = require('crypto')

export default {
  data: function () {
    return {
      photos: [],
      sessionId: "",
      state: "",

      user: {
        id: "",
        name: "",
        icon: "",
      },
      imageUrl: "",
    };
  },

  computed: {
    lineLoginUrl: function () {
      return "https://access.line.me/oauth2/v2.1/authorize?response_type=code&client_id=1654463414&redirect_uri=http://localhost:8000&state="+ this.state +"&scope=profile%20openid"
    },
  },

  methods: {
    generateRandomString(length){
      return randomBytes(length).reduce((p, i) => p + (i % 36).toString(36), '')
    },

    fetchParams() {
      if(this.$route.query != null) {
        console.log("パラメータゲット")
        console.log("パラメータの中身")
        console.log(this.$route.query.code)
        console.log(this.$route.query.state)
        this.code = this.$route.query.code;
      }
    },

    setState() {
      var random = this.generateRandomString(6);
      this.state = random;
      console.log("state");
      console.log(this.state);
    },

    fetchUserInfo(){
      // 非同期処理内のthisはvueオブジェクトを参照しない
      var vue = this;
      var code = vue.code;
      axios
        .get('api/login?code=' + vue.code)
        // .then(response => (console.log(response)))
        .then(response => {
          vue.$set(vue.user, 'id', response.data.userId);
          vue.$set(vue.user, 'name', response.data.displayName);
          vue.$set(vue.user, 'icon', response.data.pictureUrl);
          console.log(vue.user)
        })
    }
  },

  created() {
    this.setState();
  },

  mounted() {
    //  LINEログインフォームからのリダイレクト語を想定
    this.fetchParams()
    this.fetchUserInfo()
  },
}
</script>