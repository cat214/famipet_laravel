<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class LineController extends Controller
{
    protected function hello(){
        var_dump("hello");
    }

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
        $get = $response->getBody();
        $get = json_decode($get,true);
        return $get;
    }
}
