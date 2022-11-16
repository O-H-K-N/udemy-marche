<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use InterventionImage;

class ImageService
{
  public static function upload($imageFile, $folderName){

    $fileName = uniqid(rand().'_');
    $extension = $imageFile->extension();
    $fileNameToStore = $fileName. '_' . $extension;
    // リサイズ
    $resizedImage = InterventionImage::make($imageFile)->resize(1920, 1080);

    // 画像をフォルダに保存
    Storage::put('public/' . $folderName . '/' . $fileNameToStore, $resizedImage);

    return $fileNameToStore;
  }
}