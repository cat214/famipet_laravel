<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LineLoginController extends Controller
{
    protected function login(){
        // ここで各関数を呼び出してユーザー情報を取得してリターン
    }

    protected function hello(){
        var_dump("hello");
    }

    // アクセストークン取得する関数
    protected function SendAccessTokenRequest($code){
        // アクセストークンリクエストを作成
        $uri ="https://api.line.me/oauth2/v2.1/token";
        $code           = $request->input("code");
        $grant_type     = "authorization_code";
        $redirect_uri   = "http://localhost:8000";
        $client_id      = env(CLIENT_ID);
        $client_secret  = env(CLIENT_SECRET);

        // アクセストークンリクエストを送信
        $response = Http::asForm()->post($uri, [
            "code"           => $code,
            "grant_type"     => $grant_type,
            "redirect_uri"   => $redirect_uri,
            "client_id"      => $client_id,
            "client_secret"  => $client_secret,
        ]);
        echo $response;
        return $response;
    }
    // アクセストークンをjsonへ変換する関数
    // アクセストークン使ってsocialAPI叩く関数
}
