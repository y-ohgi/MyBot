
CREATE TABLE users (
	id int(11) not null auto_increment primary key,
	username varchar(255) unique, #index春
	password varchar(255),
	token varchar(255),
	created_at datetime,
	updated_at datetime
);

CREATE TABLE todos (
	`id` int(11) not null auto_increment primary key,
	`title` varchar(255),
	`body` text,
       
	`created_at` datetime
);

# 現在のbotの情報
CREATE TABLE bot_state (
       id int(11) not null auto_increment primary key,
       type_id int(11) not null, # bot_type_master id
       # botの現在のオーナー

       created_at datetime,
       updated_at datetime
);
# ======とりあえずな初期データ========
INSERT INTO bot_state(type_id) values(1);

# ユーザー別 botの好感度
CREATE TABLE bot_favorabilites (
       id int(11) not null auto_increment primary key,
       bot_id int(11) not null, # bot_state id このidより新しいレコードがあった場合はこのレコードは紛失
       user_id int(11) not null, # users id
       percent int(11) not null DEFAULT 0,
       
       created_at datetime,
       updated_at datetime
);

# ボットが受け取ったプレゼントログ
CREATE TABLE bot_present_logs (
       id int(11) not null auto_increment primary key,
       bot_id int(11) not null,
       user_id int(11) not null,
       added_percent int(11) not null DEFAULT 0,
       
       created_at datetime,
       updated_at datetime
);

# botの属性マスタ
CREATE TABLE bot_type_master (
       id int(11) not null auto_increment primary key,
       typename varchar(255) not null,

       created_at datetime,
       updated_at datetime
);
# ======とりあえずな初期データ========
INSERT INTO bot_type_master(typename) VALUES('デフォルト');

-- # botの属性別 セリフマスタ
-- CREATE TABLE bot_word_master (
--        id int(11) not null auto_increment primary key,
--        type_id int(11) not null, # 属性マスタ
--        bot_state_id int(11) not null, # botの状態マスタ
--        body text not null DEFAULT "",

--        created_at datetime,
--        updated_at datetime
-- );

-- # talkの好感度 or コマンド(weather,用とか) を格納する必要あり
-- # ex.
-- #  state = "talk"
-- #  favorabilitiy = 0以上 1以上 51以上 99以上 100以上
-- #    => 呼び出し側 WHERE 降順 && favorability <= "現在の好感度"
-- # SELECT id FROM bot_state_master WHERE state_id = コマンド && favorability <= "現在の好感度" ORDER BY DESC LIMIT 1;
-- # SELECT RAND(body) FROM bot_word_master WHERE type_id = 現在のタイプ && bot_state_id = 上で取得したid
-- CREATE TABLE bot_state_master (
--        id int(11) not null auto_increment primary key,
--        favorability int(11) not null DEFAULT 0,
       
--        created_at datetime,
--        updated_at datetime
-- );
