<template>
    <div>
      <div>
        <h1>写真一覧</h1>
          <div v-for="(url,index) in photos.urls" :key="index" class="photo">
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
  props: ['user'],
  data: function () {
    return {
      // 写真一覧用データ
      photos: {
        urls: [],
        filenames: [],
      },
      // sessionId: "",
      // state: "",

      // user: {
      //   id: "",
      //   name: "",
      //   icon: "",
      // },
      imageFile: "",
      imagePreview: "",
      // accessToken: "",
    };
  },

  computed: {
    // lineLoginUrl: function () {
    //   return "https://access.line.me/oauth2/v2.1/authorize?response_type=code&client_id=1654463414&redirect_uri=http://localhost:8000&state="+ this.state +"&scope=profile%20openid"
    // },
  },

  methods: {
    show(){
      console.log(this.user)
    },
    // generateRandomString(length){
    //   return randomBytes(length).reduce((p, i) => p + (i % 36).toString(36), '')
    // },

    // setState() {
    //   var random = this.generateRandomString(6);
    //   this.state = random;
    //   console.log("state");
    //   console.log(this.state);
    // },

    // fetchParams() {
    //   if(this.$route.query != null) {
    //     console.log("パラメータゲット")
    //     console.log("パラメータの中身")
    //     console.log(this.$route.query.code)
    //     console.log(this.$route.query.state)
    //     this.code = this.$route.query.code;
    //   }
    // },

    

    // fetchUserInfo(){
    //   // 非同期処理内のthisはvueオブジェクトを参照しない
    //   var vue = this;
    //   var code = vue.code;
    //   axios
    //     .get('api/login?code=' + vue.code)
    //     .then(response => {
    //       vue.$set(vue.user, 'id', response.data[0].userId);
    //       vue.$set(vue.user, 'name', response.data[0].displayName);
    //       vue.$set(vue.user, 'icon', response.data[0].pictureUrl);
    //       vue.accessToken = response.data[1];
    //       // ログイン時にユーザーに対応した画像をとってくる
    //       vue.photos.urls.push(...response.data[2].map(obj => obj.url));
    //       vue.photos.filenames.push(...response.data[2].map(obj => obj.filename));
    //     })
    // },

    // logout() {
    //   var vue = this;
    //   var accessToken = vue.accessToken;
    //   vue.accessToken = "";
    //   vue.user.id = "";
    //   vue.user.name = "";
    //   vue.user.icon = "";
    //   axios
    //   .get('api/logout?at=' + accessToken);
    // },
    // onFileChange(e) {
    //   this.imageFile = e.target.files[0] || e.dataTransfer.files;
    //   // プレビュー機能
    //   if (this.imageFile.type.startsWith("image/")) {
    //     this.imagePreview = window.URL.createObjectURL(this.imageFile);
    //   }
    // },
    // photoUpload(){
    //   var vue = this;
    //   const formData = new FormData()
    //   formData.append('userId',this.user.id)
    //   formData.append('filename',this.filename)
    //   formData.append('photo',this.imageFile)

    //   axios.post('/api/photo/upload',formData)
    //     .then(response => {
    //       // アップロード後写真一覧へアップロードした写真を追加する
    //       vue.photos.urls.push(response.data[1]);
    //       vue.photos.filenames.push(response.data[0]);
    //   });
    // },
  },
  computed: {
    fetchPhotos(){
      var vue = this
      var user_id = this.user.id
      axios.get("api/photo", {
        params: {
          user_id: user_id
        }
      })
      .then(response => {
        // 返ってくるデータをvueの配列に変換する
        vue.photos.urls.push(...response.data.map(obj => obj.url));
        vue.photos.filenames.push(...response.data.map(obj => obj.filename));
      })
    }
  },
  mounted() {
    //  LINEログインフォームからのリダイレクト後を想定
    // this.fetchParams()
    // this.fetchUserInfo()
    // this.fetchPhotos()
  }
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