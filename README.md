# flea-market-app(フリマサイト)

## 環境構築

### Docker ビルド

1. git clone https://github.com/812eri/flea-market-app.git
2. DockerDesktop アプリを立ち上げる
3. docker-compose up -d --build

※ MySQL は、OS によって起動しない場合があるのでそれぞれの PC に合わせて docker-compose.yml ファイルを編集してください。

※ Mac の M1・M2 チップ PC の場合、no matching manifest for linux/arm64/v8 in the manifest list entries のメッセージが表示されビルドができないことがあります。
エラーが発生する場合は、docker-compose.yml ファイルの「mysql」内に「platform」の項目を追加で記載してください。

mysql:
platform: linux/x86_64(この文追加)
image: mysql:8.0.26
environment:

### Laravel 環境構築

1. docker-compose exec php bash
2. composer install
3. 「.env.example」ファイルを「.env」ファイルに命名変更、または新しく.env ファイルを作成し環境変数を以下に変更

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass

4. アプリケーションキーの作成
   php artisan key:generate

5. マイグレーションの実行
   php artisan migrate

6. シーディングの実行
   php artisan db:seed

・動作確認用アカウント
メールアドレス：test@example.com
パスワード：password 　※ログイン済みの状態を確認いただけます

## 使用技術

- php 8.1
- Laravel 8.x
- MySQL 8.0

## ER 図

![ER図](flea-market-app.drawio.png)
