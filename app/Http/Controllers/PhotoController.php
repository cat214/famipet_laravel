<?php

namespace App\Http\Controllers;

use App\Photo;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    protected function photo(Request $request){
        $user   = new User();
        $user_id = $request->input('user_id');
        $photos = $user->getPhotos($user_id);
        return $photos;
    }

    protected function upload(Request $request){
        // TODO: 画像名のバリデーションいれる？
        // 画像をS3に保存
        $image    = $request->file('photo');
        $filename = $request->input('filename');
        $user_id  = $request->input("userId");
        $disk     = Storage::disk("s3");
        // putFileAs(ディレクトリ名、ファイル、ファイル名、公開範囲)
        $dirname  = $disk->putFileAs('photos', $image, $filename, 'public');
        $url      = $disk->url($dirname);
        // DBに保存
        $photo           = new Photo;
        $photo->filename = $filename;
        $photo->url      = $url;
        $photo->user_id  = $user_id;
        $photo->save();

        return [$filename, $url];
    }
}
