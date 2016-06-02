<?php

namespace Sprint;

use Config;

require_once(dirname(__FILE__).'/../Conf.inc');

class API{

    //protected static $hoge;

    public static function GCV($imgname){
        $api_key = Config::get('google_api_key');
        
        // リファラー (許可するリファラーを設定した場合)
        $referer = "" ;

        // 画像へのパス
        //$image_path = "/Users/ohgi/codecheck/MyBot/app/image.jpg";
        $image_path = __DIR__ . "/../public/imgs/". $imgname;

        //Send
        // リクエスト用のJSONを作成
        $json = json_encode( array(
            "requests" => array(
                array(
                    "image" => array(
                        "content" => base64_encode( file_get_contents( $image_path ) ) ,
                    ) ,
                    "features" => array(
                        array(
                            "type" => "LABEL_DETECTION" ,
                            "maxResults" => 3,
                        ) ,
                    ) ,
                ) ,
            ) ,
        ) ) ;

        // リクエストを実行
        $curl = curl_init() ;
        curl_setopt( $curl, CURLOPT_URL, "https://vision.googleapis.com/v1/images:annotate?key=" . $api_key ) ;
        curl_setopt( $curl, CURLOPT_HEADER, true ) ;
        curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, "POST" ) ;
        curl_setopt( $curl, CURLOPT_HTTPHEADER, array( "Content-Type: application/json" ) ) ;
        curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false ) ;
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true ) ;
        if( isset($referer) && !empty($referer) ) curl_setopt( $curl, CURLOPT_REFERER, $referer ) ;
        curl_setopt( $curl, CURLOPT_TIMEOUT, 15 ) ;
        curl_setopt( $curl, CURLOPT_POSTFIELDS, $json ) ;
        $res1 = curl_exec( $curl ) ;
        $res2 = curl_getinfo( $curl ) ;
        curl_close( $curl ) ;

        // 取得したデータ
        $json = substr( $res1, $res2["header_size"] ) ;// 取得したJSON
        $header = substr( $res1, 0, $res2["header_size"] ) ;// レスポンスヘッダー

        $res = json_decode($json, true);
        $labels = $res['responses'][0]['labelAnnotations'];
        
        return $labels;
    }
}
