<template>
    <div>
      <div class="userInfo">
        <h1>photo list</h1>
        <h1>ユーザー情報</h1>
        <a v-if="!user.name" :href="lineLoginUrl">ログイン</a>
        <button v-else @click="logout">ログアウト</button>
        <p v-if="user.name">{{ user.name }}</p>
        <img v-if="user.icon" :src="user.icon" class="icon">
      </div>
      <div v-if="user.name" class="imageUpload">
        <form>
          <input type="text" v-model="filename">
          <input type="file" name="photo" @change="onFileChange">
          <input type="submit" value="アップロード" @click.prevent="photoUpload">
          <img :src="imagePreview">
        </form>
      </div>
      <div v-else>
        ログインすると画像をアップロードできます
      </div>
      <div>
        <h1>写真一覧</h1>
          <div v-for="url in photos.urls" :key="url" class="photo">
              <img :src="url">
          </div>
      </div>
    </div>
</template>

<script>
import axios from 'axios';
import sha256 from 'js-sha256';
const {randomBytes} = require('crypto')

export default {
  data: function () {
    return {
      photos: {
        urls: [],
        filenames: [],
      },
      sessionId: "",
      state: "",

      user: {
        id: "",
        name: "",
        icon: "",
      },
      imageFile: "",
      imagePreview: "",
      accessToken: "",
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
        .then(response => {
          vue.$set(vue.user, 'id', response.data[0].userId);
          vue.$set(vue.user, 'name', response.data[0].displayName);
          vue.$set(vue.user, 'icon', response.data[0].pictureUrl);
          vue.accessToken = response.data[1];
          vue.photos.urls.push(...response.data[2].map(obj => obj.url));
          vue.photos.filenames.push(...response.data[2].map(obj => obj.filename));
        })
    },

    fetchPhotos(){
      var vue = this;
      
    },

    logout() {
      var vue = this;
      var accessToken = vue.accessToken;
      vue.accessToken = "";
      vue.user.id = "";
      vue.user.name = "";
      vue.user.icon = "";
      axios
      .get('api/logout?at=' + accessToken);
    },
    onFileChange(e) {
      this.imageFile = e.target.files[0] || e.dataTransfer.files;
      // プレビュー機能
      if (this.imageFile.type.startsWith("image/")) {
        this.imagePreview = window.URL.createObjectURL(this.imageFile);
      }
    },
    photoUpload(){
      this.validateForm()
      const formData = new FormData()
      formData.append('userId',this.user.id)
      formData.append('filename',this.filename)
      formData.append('photo',this.imageFile)

      axios.post('/api/photo/upload',formData)
        .then(response => {
          response[0]
          response[1]
      });
    },
  },

  created() {
    this.setState();
  },

  mounted() {
    //  LINEログインフォームからのリダイレクト後を想定
    this.fetchParams()
    this.fetchUserInfo()
    this.fetchPhotos()
  },
}
</script>
<style>
.icon{
  width: 200px;
  height: 200px;
  line-height: 200px;
  border-radius: 50%;
  color: #fff;
  text-align: center;
}
.photo{
  width: 400px;
  height: 400px;
}
</style>