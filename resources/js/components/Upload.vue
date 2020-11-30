<template>
    <div>
        <div v-if=user.name class="imageUpload">
        <h1>画像をアップロード</h1>
        <form>
          <!-- TODO: ファイル名の空欄バリデーションをいれる(コンポーネント化したあと）-->
          <input type="text" v-model="filename">
          <input type ="file" name="photo" @change="onFileChange">
          <input type="submit" value="アップロード" @click.prevent="photoUpload">
          <img :src="imagePreview">
        </form>
        </div>
        <div v-else>
            ログインすると画像をアップロードできます
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import router from '../router.js';
import sha256 from 'js-sha256';
const {randomBytes} = require('crypto')

export default {
    props: ['user'],
    data: function () {
        return {
            filename: "",
            imageFile: "",
            imagePreview: "",
        }
    },
    methods: {
        onFileChange(e) {
            this.imageFile = e.target.files[0] || e.dataTransfer.files;
            // プレビュー機能
            if (this.imageFile.type.startsWith("image/")) {
                this.imagePreview = window.URL.createObjectURL(this.imageFile);
            }
        },
        photoUpload(){
            console.log(this.user.id)
            const formData = new FormData()
            formData.append('userId',this.user.id)
            formData.append('filename',this.filename)
            formData.append('photo',this.imageFile)

            axios.post('/api/photo/upload',formData)
                .then(response => {
                    router.push({ path: '/'})
                // アップロード後写真一覧へアップロードした写真を追加する
                // アップロード後はインデックスへ遷移するのでこれは必要ない？
                // vue.photos.urls.push(response.data[1]);
                // vue.photos.filenames.push(response.data[0]);
            });
        },
    },
    mounted() {
    //  LINEログインフォームからのリダイレクト後を想定
    // this.fetchParams()
    // this.fetchUserInfo()
    // this.fetchPhotos()
    console.log(this.user)
  },
}
</script>