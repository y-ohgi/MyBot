MyBot
---

## STEP1. デプロイ情報
以下の情報を入力してください。
- チャットボットをデプロイしたサーバーのURL
 - http://sprint.y-ohgi.net/
- デプロイに使ったサービス: AWS / DigitalOcean / Sakura / Heroku / Cloudn / etc.
 - AWS

## STEP2. 必須機能の実装
必須機能を実装する上で、創意工夫した点があれば記述してください。
* コマンドを実装/複製しやすくすることを意識しました

## STEP3. 独自コマンドの実装

### 一般コマンド
#### bot help
* ヘルプです
* 現在使用可能なコマンド一覧を表示します
* 引数はありません

#### bot auth
* トークンを用いたをユーザー認証機能です
* SSLは導入していません！
* **bot auth signin**
 - サインインを行います
* **bot auth signup**
 - サインアップ行います

### 認証が必要なコマンド
#### bot talk
* ボットが喋ります
* 喋るたびに好感度が若干上がります
* ボットの **好感度** / **属性** に応じて喋る内容が変わります
* 引数はありません

#### bot status
* ボットの現在の状態を取得できます
* 引数はありません

### ボットのオーナーになる必要があるコマンド
#### bot type
* ボットの **属性** を扱います
* **bot type list**
 - 変更可能なbotの属性を返します
* **bot type change 属性名**
 - ボットの属性を変更します
 - 属性名はlistで取得した日本語属性名

#### bot buront
* ブロントさんの名言を返します
* 引数はありません


## 今回の開発に使用した技術
### 言語、ライブラリ、フレームワーク
* PHP
* Ratchet
### API
* GoogleCloudVisionAPI

## その他独自実装した内容の説明
* チャット
* プレゼント
 - 画像をプレゼントし、好感度を上げます
 - GoogleCloudVisionAPIを用いて なんの画像か(label)を取得し、好みの物なら大幅に上昇したりします

## その他創意工夫点、アピールポイントなど
タイトルの通り、"MyBot"ということで一人しかBotを使えないBotを作ってみました  
Botを使うためにはBotの好感度を100%まで上げる必要があります  
[Gatebox](http://gatebox.ai/)にインスパイアされました
