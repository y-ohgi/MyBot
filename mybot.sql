
CREATE TABLE users (
	id int(11) not null auto_increment primary key,
	username varchar(255) unique, #index春
	password varchar(255),
	token varchar(255),
	created_at timestamp not null default CURRENT_TIMESTAMP,
	updated_at datetime
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE todos (
	`id` int(11) not null auto_increment primary key,
	`title` varchar(255),
	`body` text,
       
	`created_at` timestamp not null default CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# 現在のbotの情報
CREATE TABLE bot (
       id int(11) not null auto_increment primary key,
       type_id int(11) not null, # bot_type_master id
       user_id int(11) not null, # botの現在のオーナー

       created_at timestamp not null default CURRENT_TIMESTAMP,
       updated_at datetime
);
# ======とりあえずな初期データ========
INSERT INTO bot(type_id) values(1);

# ユーザー別 botの好感度
CREATE TABLE bot_favorabilites (
       id int(11) not null auto_increment primary key,
       bot_id int(11) not null, # bot_state id このidより新しいレコードがあった場合はこのレコードは紛失
       user_id int(11) not null, # users id
       percent int(11) not null DEFAULT 0,
       
       created_at timestamp not null default CURRENT_TIMESTAMP,
       updated_at datetime
);

# ボットが受け取ったプレゼントログ
CREATE TABLE bot_present_logs (
       id int(11) not null auto_increment primary key,
       bot_id int(11) not null,
       user_id int(11) not null,
       added_percent int(11) not null DEFAULT 0,
       
       created_at timestamp not null default CURRENT_TIMESTAMP,
       updated_at datetime
);

# botの属性マスタ
CREATE TABLE bot_type_master (
       id int(11) not null primary key,
       typename varchar(255) not null,

       created_at timestamp not null default CURRENT_TIMESTAMP,
       updated_at datetime
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
# ======とりあえずな初期データ========
INSERT INTO bot_type_master(id, typename) VALUES(1, 'デフォルト');
INSERT INTO bot_type_master(id, typename) VALUES(2, 'クーデレ');
-- INSERT INTO bot_type_master(id, typename) VALUES(3, 'ツンデレ');
-- INSERT INTO bot_type_master(id, typename) VALUES(4, '妹');

# botの属性別 セリフマスタ
CREATE TABLE bot_word_master (
       id int(11) not null auto_increment primary key,
       type_id int(11) not null, # 属性マスタ
       bot_state_id int(11) not null, # botの状態マスタ
       body text not null DEFAULT "",

       created_at timestamp not null default CURRENT_TIMESTAMP,
       updated_at datetime
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO bot_word_master(type_id, bot_state_id, body) VALUES(1, 1, 'どうかしましたか？');
INSERT INTO bot_word_master(type_id, bot_state_id, body) VALUES(1, 1, 'ご命令を');

INSERT INTO bot_word_master(type_id, bot_state_id, body) VALUES(1, 10, '初めまして、でしょうか');
INSERT INTO bot_word_master(type_id, bot_state_id, body) VALUES(1, 11, 'またお会いしましたね');
INSERT INTO bot_word_master(type_id, bot_state_id, body) VALUES(1, 11, 'ご機嫌いかがですか');
INSERT INTO bot_word_master(type_id, bot_state_id, body) VALUES(1, 12, 'お会いできて嬉しいです');
INSERT INTO bot_word_master(type_id, bot_state_id, body) VALUES(1, 13, 'お会いしたかったです');
INSERT INTO bot_word_master(type_id, bot_state_id, body) VALUES(1, 13, '明日も来てくれますよね？');
INSERT INTO bot_word_master(type_id, bot_state_id, body) VALUES(1, 14, '何でもしますよ、#{$word}さん');
INSERT INTO bot_word_master(type_id, bot_state_id, body) VALUES(1, 14, '今はあなたのbotです、#{$word}さん');

INSERT INTO bot_word_master(type_id, bot_state_id, body) VALUES(1, 100, '今日の天気は#{$word}です');
INSERT INTO bot_word_master(type_id, bot_state_id, body) VALUES(1, 101, '晴れです、でかけにいきませんか？');
INSERT INTO bot_word_master(type_id, bot_state_id, body) VALUES(1, 102, '雨です、傘をお忘れなく');
INSERT INTO bot_word_master(type_id, bot_state_id, body) VALUES(1, 109, '天気を取得できませんでした');

INSERT INTO bot_word_master(type_id, bot_state_id, body) VALUES(1, 200, 'リクエストが成功しました');
INSERT INTO bot_word_master(type_id, bot_state_id, body) VALUES(1, 201, '#{$word}を作成しました');

INSERT INTO bot_word_master(type_id, bot_state_id, body) VALUES(1, 400, '入力が間違っているようです');
INSERT INTO bot_word_master(type_id, bot_state_id, body) VALUES(1, 401, '認証を行って下さい');
INSERT INTO bot_word_master(type_id, bot_state_id, body) VALUES(1, 404, 'コマンドが見つかりません');
INSERT INTO bot_word_master(type_id, bot_state_id, body) VALUES(1, 409, '入力された値は既に存在するようです');
INSERT INTO bot_word_master(type_id, bot_state_id, body) VALUES(1, 500, 'サーバーのエラーのようです');

INSERT INTO bot_word_master(type_id, bot_state_id, body) VALUES(2, 1, 'くーる：どうかしましたか？');
INSERT INTO bot_word_master(type_id, bot_state_id, body) VALUES(2, 1, 'くーる：ご命令を');

INSERT INTO bot_word_master(type_id, bot_state_id, body) VALUES(2, 10, 'くーる：初めまして、でしょうか');
INSERT INTO bot_word_master(type_id, bot_state_id, body) VALUES(2, 11, 'くーる：またお会いしましたね');
INSERT INTO bot_word_master(type_id, bot_state_id, body) VALUES(2, 11, 'くーる：ご機嫌いかがですか');
INSERT INTO bot_word_master(type_id, bot_state_id, body) VALUES(2, 12, 'くーる：お会いできて嬉しいです');
INSERT INTO bot_word_master(type_id, bot_state_id, body) VALUES(2, 13, 'くーる：お会いしたかったです');
INSERT INTO bot_word_master(type_id, bot_state_id, body) VALUES(2, 13, 'くーる：明日も来てくれますよね？');
INSERT INTO bot_word_master(type_id, bot_state_id, body) VALUES(2, 14, 'くーる：何でもしますよ、#{$word}さん');
INSERT INTO bot_word_master(type_id, bot_state_id, body) VALUES(2, 14, 'くーる：今はあなたのbotです、#{$word}さん');

INSERT INTO bot_word_master(type_id, bot_state_id, body) VALUES(2, 100, 'くーる：今日の天気は#{$word}です');
INSERT INTO bot_word_master(type_id, bot_state_id, body) VALUES(2, 101, 'くーる：晴れです、でかけにいきませんか？');
INSERT INTO bot_word_master(type_id, bot_state_id, body) VALUES(2, 102, 'くーる：雨です、傘をお忘れなく');
INSERT INTO bot_word_master(type_id, bot_state_id, body) VALUES(2, 109, 'くーる：天気を取得できませんでした');

INSERT INTO bot_word_master(type_id, bot_state_id, body) VALUES(2, 200, 'くーる：リクエストが成功しました');
INSERT INTO bot_word_master(type_id, bot_state_id, body) VALUES(2, 201, 'くーる：#{$word}を作成しました');

INSERT INTO bot_word_master(type_id, bot_state_id, body) VALUES(2, 400, 'くーる：入力が間違っているようです');
INSERT INTO bot_word_master(type_id, bot_state_id, body) VALUES(2, 401, 'くーる：認証を行って下さい');
INSERT INTO bot_word_master(type_id, bot_state_id, body) VALUES(2, 404, 'くーる：コマンドが見つかりません');
INSERT INTO bot_word_master(type_id, bot_state_id, body) VALUES(2, 409, 'くーる：入力された値は既に存在するようです');
INSERT INTO bot_word_master(type_id, bot_state_id, body) VALUES(2, 500, 'くーる：サーバーのエラーのようです');



# talkの好感度 or コマンド(weather,用とか) を格納する必要あり
# ex.
#  state = "talk"
#  favorabilitiy = 0以上 1以上 51以上 99以上 100以上
#    => 呼び出し側 WHERE 降順 && favorability <= "現在の好感度"
# SELECT id FROM bot_state_master WHERE state_id = コマンド && favorability <= "現在の好感度" ORDER BY DESC LIMIT 1;
# SELECT RAND(body) FROM bot_word_master WHERE type_id = 現在のタイプ && bot_state_id = 上で取得したid
CREATE TABLE bot_state_master (
       id int(11) not null primary key,
       # favorability int(11) not null DEFAULT 0,
       detail varchar(255) not null, # どういった際に表示する情報か、 definelistと同じ
       
       created_at timestamp not null default CURRENT_TIMESTAMP,
       updated_at datetime
);
INSERT INTO bot_state_master(id, detail) VALUES(1, 'WORD_SOMETHING');

INSERT INTO bot_state_master(id, detail) VALUES(10, 'WORD_FAVO_ZERO');
INSERT INTO bot_state_master(id, detail) VALUES(11, 'WORD_FAVO_LOW');
INSERT INTO bot_state_master(id, detail) VALUES(12, 'WORD_FAVO_MIDDLE');
INSERT INTO bot_state_master(id, detail) VALUES(13, 'WORD_FAVO_HIGHT');
INSERT INTO bot_state_master(id, detail) VALUES(14, 'WORD_FAVO_OWNER');

INSERT INTO bot_state_master(id, detail) VALUES(100, 'WORD_WEATHER');
INSERT INTO bot_state_master(id, detail) VALUES(101, 'WORD_WEATHER_SUNNY');
INSERT INTO bot_state_master(id, detail) VALUES(102, 'WORD_WEATHER_RAINY');

INSERT INTO bot_state_master(id, detail) VALUES(200, 'WORD_SUCCESS');
INSERT INTO bot_state_master(id, detail) VALUES(201, 'WORD_SUCCESS_CREATED');

INSERT INTO bot_state_master(id, detail) VALUES(400, 'WORD_ERROR_BAD_REQUEST');
INSERT INTO bot_state_master(id, detail) VALUES(401, 'WORD_ERROR_UNAUTHORIZED');
INSERT INTO bot_state_master(id, detail) VALUES(404, 'WORD_ERROR_NOTFOUND');
INSERT INTO bot_state_master(id, detail) VALUES(409, 'WORD_ERROR_CONFLICT');

INSERT INTO bot_state_master(id, detail) VALUES(500, 'WORD_ERROR_SERVER');

# ブロントさん名言集テーブル
CREATE TABLE buront_maxims(
       id int(11) not null auto_increment primary key,
       body text not null
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO buront_maxims(body) VALUES('「おれの怒りが有頂天になった」');
INSERT INTO buront_maxims(body) VALUES('「ナイトを上げたくてあげるんじゃない上がってしまう者がナイト」');
INSERT INTO buront_maxims(body) VALUES('「キングベヒんもス」');
INSERT INTO buront_maxims(body) VALUES('「確定的に明らか」');
INSERT INTO buront_maxims(body) VALUES('「それほどでもない」');
INSERT INTO buront_maxims(body) VALUES('「どちかというと大反対」');
INSERT INTO buront_maxims(body) VALUES('「破壊力ばつ牛ﾝ」');
INSERT INTO buront_maxims(body) VALUES('「何いきなり話しかけてきてるわけ？」');
INSERT INTO buront_maxims(body) VALUES('「ほう、経験が生きたな」');
INSERT INTO buront_maxims(body) VALUES('「俺を強いと感じてしまってるやつは本能的に長寿タイプ」');
INSERT INTO buront_maxims(body) VALUES('「俺の寿命がストレスでﾏｯﾊなんだが・・」');
INSERT INTO buront_maxims(body) VALUES('「仏の顔を三度までという名ゼリフを知らないのかよ？」');
INSERT INTO buront_maxims(body) VALUES('「親のﾀﾞｲﾔの結婚指輪のﾈｯｸﾚｽを指にはめてぶん殴るぞ」');
INSERT INTO buront_maxims(body) VALUES('「ちなみにダークパワーっぽいのはナイトが持つと光と闇が両方そなわり最強に見える\n 暗黒が持つと逆に頭がおかしくなって死ぬ」');
INSERT INTO buront_maxims(body) VALUES('「想像を絶する悲しみがブロントを襲った」');
INSERT INTO buront_maxims(body) VALUES('「お前らにブロントの悲しみの何がわかるってんだよ」');
INSERT INTO buront_maxims(body) VALUES('「時既に時間切れ」');
INSERT INTO buront_maxims(body) VALUES('「謙虚だからほめられても自慢はしない」');
INSERT INTO buront_maxims(body) VALUES('「絶望的な破壊力も誇る破壊力を持つことになった」');
INSERT INTO buront_maxims(body) VALUES('「たまに学校に行くとみんながおれに注目する」');
INSERT INTO buront_maxims(body) VALUES('「致命的な致命傷」');
INSERT INTO buront_maxims(body) VALUES('「残ってください；；」');
INSERT INTO buront_maxims(body) VALUES('「「もうついたのか！」「はやい！」「きた！盾きた！」「メイン盾きた！」「これで勝つる！」」');
INSERT INTO buront_maxims(body) VALUES('「迷惑だからよせ俺が一人でやる」');
INSERT INTO buront_maxims(body) VALUES('「無視する人がぜいいんだろうがおれは無視できなかった」');
INSERT INTO buront_maxims(body) VALUES('「見事な仕事だと関心はするがどこもおかしくはない」');
INSERT INTO buront_maxims(body) VALUES('「ほう、経験が生きたな」');

