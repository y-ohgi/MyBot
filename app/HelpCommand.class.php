<?php

namespace Sprint;

use \PDO;

// コマンド一覧の表示
class HelpCommand extends Command {
    use Auth;
    
    public function execute($message){
        $bot = new Bot($this->user_id);

        
        if($this->isOwner()){
            $help = <<< TEXT
* bot talk:
　- botが喋る
* bot buront:
　- ブロントさんの名言をランダムで返す
* bot weather:
　- 天気を返す
* bot status:
　- botの情報確認
* bot type list:
　- botの変更可能な属性一覧を取得
* bot type change 属性名:
　- botの属性を変更します
　- listで取得した日本語属性名
TEXT;
                
            $this->addWordInResult($help);
        }else if($this->isAuth()){
            $help = <<< TEXT
1. オーナーにならないと botと喋ることと botに画像を貢ぐことぐらいしかやることがないよ！
2. botはたまに今欲しいものを喋るよ！

* bot talk:
　- botが喋る
* bot status:
　- botの情報確認
TEXT;
            $this->addWordInResult($help);
            
        }else{
            $help = <<< TEXT
1. まずはユーザー登録から！
2. 次はチャットボックス上の「プレゼント」からbotに画像をプレゼントしよう！
　- 画像を送ると好感度が上がる！
　- botが好きな画像だと更に好感度が上昇！
3. 好感度が100%になると bot のオーナーになれるよ！

* bot auth signup "username" "password":
　- ユーザー登録
* bot auth signin "username" "password":
　- ログイン
* bot auth signout
　- ログアウト
TEXT;
                
            $this->addWordInResult($help);
        }
    }


    
}