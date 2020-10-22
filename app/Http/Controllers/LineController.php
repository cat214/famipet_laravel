<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use App\Photo;
use App\User;

class LineController extends Controller
{
    protected function login(Request $request){
        // アクセストークンリクエストを作成
        $uri ="https://api.line.me/oauth2/v2.1/token";
        $code           = $request->input("code");
        $grant_type     = "authorization_code";
        $redirect_uri   = "http://localhost:8000";
        $client_id      = env("CLIENT_ID");
        $client_secret  = env("CLIENT_SECRET");

        // アクセストークンリクエストを送信
        $client = new Client();
        $response = $client->request("POST",$uri,[
            "headers" => [
                "Content-Type"   => "application/x-www-form-urlencoded",
            ],
            "form_params" => [
                "code"           => $code,
                "grant_type"     => $grant_type,
                "redirect_uri"   => $redirect_uri,
                "client_id"      => $client_id,
                "client_secret"  => $client_secret,
            ]
        ]);
        $post = $response->getBody();
        $post = json_decode($post,true);      
        
        // アクセストークン使ってUser情報取得
        $access_token = $post["access_token"];
        $client = new Client();
        $uri = "https://api.line.me/v2/profile";
        $response = $client->request("GET",$uri,[
            "headers" => [
                "Authorization" => "Bearer " . $access_token,
            ],
        ]);
        $get = $response->getBody()->getContents();
        $user_info = json_decode($get,true);

        // ユーザーが未登録なら登録する
        $user_id   = $user_info['userId'];
        $user_name = $user_info['displayName'];
        $user_icon = $user_info['pictureUrl'];
        $this->registerUser($user_id,$user_name,$user_icon);

        $user = new User();
        $photos = $user->getPhotos($user_id);

        // アクセストークンはログアウト時にアクセストークンを無効化するために渡す
        return [$user_info, $access_token, $photos];
    }

    /*
    * @param ログインしたいユーザーのアクセストークン
    */
    protected function logout(Request $request){
        $uri ="https://api.line.me/oauth2/v2.1/revoke";
        $access_token = $request->input("at");
        $client_id      = env("CLIENT_ID");
        $client_secret  = env("CLIENT_SECRET");

        // アクセストークンリクエストを送信
        $client = new Client();
        $response = $client->request("POST",$uri,[
            "headers" => [
                "Content-Type"   => "application/x-www-form-urlencoded",
            ],
            "form_params" => [
                "access_token"   => $access_token,
                "client_id"      => $client_id,
                "client_secret"  => $client_secret,
            ]
        ]);
    }

    protected function registerUser($user_id, $user_name, $user_icon){
        $user = new User;
        $isUserRegistered = $user->where('id', $user_id)->first();
        if($isUserRegistered == true){
            return;
        }else{
            $user->id = $user_id;
            $user->name = $user_name;
            $user->icon = $user_icon;
            $user->save();
        }
    }
}
