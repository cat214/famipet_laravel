<template>
    <div class="header">
        <ul>
            <li><router-link to="/">HOME</router-link></li>
            <li><router-link to="/family">FAMILY</router-link></li>
            <li><router-link to="/upload">UPLOAD</router-link></li>
            <li>
                <p v-if="!user.name"><a :href="lineLoginUrl">ログイン</a></p>
                <div v-else>
                    <button @click="logout">ログアウト</button>
                    <img v-if="user.icon" :src="user.icon" class="icon">
                </div>
            </li>
        </ul>
    </div>
</template>

<script>
import axios from 'axios';
import sha256 from 'js-sha256';
const {randomBytes} = require('crypto')

// ログイン関係の処理はここに書く
export default {
    data: function () {
        return {
            sessionId: "",
            state: "",

            user: {
                id: "",
                name: "",
                icon: "",
            },
            accessToken: "",
        }
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
        setState() {
            var random = this.generateRandomString(6);
            this.state = random;
            // console.log("state");
            // console.log(this.state);
        },
        fetchParams() {
            if(this.$route.query != null) {
                // console.log("パラメータゲット")
                // console.log("パラメータの中身")
                // console.log(this.$route.query.code)
                // console.log(this.$route.query.state)
                this.code = this.$route.query.code;
            }
        },
        fetchUserInfo(){
            // 非同期処理内のthisはvueオブジェクトを参照しない
            var vue = this;
            var code = vue.code;
            axios
                .get('api/login?code=' + vue.code)
                .then(response => {
                    vue.$set(vue.user, 'id',   response.data[0].userId);
                    vue.$set(vue.user, 'name', response.data[0].displayName);
                    vue.$set(vue.user, 'icon', response.data[0].pictureUrl);
                    vue.accessToken = response.data[1];
                    // ログイン時にユーザーに対応した画像をとってくる
                    // TODO: 画像アップロード後はindexに遷移するためいらないかも
                    // vue.photos.urls.push(...response.data[2].map(obj => obj.url));
                    // vue.photos.filenames.push(...response.data[2].map(obj => obj.filename));
                })
            // App.vueへユーザーデータを渡す
            // this.$emit('setUserData', this.user)
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
    },

    mounted() {
        // LINEログインからのリダイレクト後を想定
        this.fetchParams()
        this.fetchUserInfo()
        // App.vueへユーザーデータを渡す
        this.$emit('setUserData', this.user)
    },

    created() {
        this.setState();
    },
}
</script>