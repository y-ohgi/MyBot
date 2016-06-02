<?php

//=== botの属性 id ===
define('TYPE_DEFAULT', 1);


//=== botが喋るセリフid ===
// 万能セリフ
//  ex. 「ご命令を」
//  ex. 「どうかしましたか？」
define('WORD_SOMETHING', 1);

// 好感度 0%
define('WORD_FAVO_ZERO', 10);
// 好感度 1%〜50%
define('WORD_FAVO_LOW', 11);
// 好感度 51%〜75%
define('WORD_FAVO_MIDDLE', 12);
// 好感度 76%〜99%
define('WORD_FAVO_HIGHT', 13);
// 好感度 100%
define('WORD_FAVO_MAX', 14);

// 天気情報
// ex. 「今日の天気は#{$weather}です」
define('WORD_WEATHER', 100);
// ex. 「晴れです、でかけにいきましょう」
define('WORD_WEATHER_SUNNY', 101);
// ex. 「雨です、作業をしましょう」
define('WORD_WEATHER_RAINY', 102);


// リクエストが成功した場合
//  ex. 「リクエストが成功しました」
define('WORD_SUCCESS', 200);
// 作成完了
//  ex. 「#{$created}を作成しました」
define('WORD_SUCCESS_CREATED');

// エラー： ユーザー入力エラー
//  ex. 入力が間違っているようです
define('WORD_ERROR_BAD_REQUEST', 400);
// エラー： 認証が必要
//  ex. 「認証を行って下さい」
define('WORD_ERROR_UNAUTHORIZED', 401);
// エラー： コマンドが見つからない
//  ex. 「コマンドが見つかりません」
define('WORD_ERROR_NOTFOUND', 404);
// エラー： リソースが競合している
//  ex. 「入力された値は既に存在するようです」
define('WORD_ERROR_CONFLICT', 409);

// エラー： サーバーサイドエラー
//  ex. 「サーバーのエラーのようです」
define('WORD_ERROR_SERVER', 500);