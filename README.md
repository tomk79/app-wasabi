# WASABI for Pickles 2

Pickles 2 の制作環境に、動的なコミュニケーション機能を追加します。
WASABI は、このためのサーバーの役割を受け持ちます。


## インストール - Installation

### 1. app-wasabi の依存ライブラリをインストールする

```
$ git clone https://github.com/pickles2/app-wasabi.git
$ cd app-wasabi
$ composer install
$ npm install
```

### 2. `.env` ファイルを作成、設定する

サンプルの設定ファイル `.env.example` から `.env` という名前で複製します。

```
$ cp .env.example .env
```

この設定ファイルに、適宜必要な設定を更新してください。

```
$ vi .env
```

#### アプリケーションの基本情報の設定

必要に応じて設定を変更してください。

```
APP_NAME=WASABI
APP_ENV=local
APP_KEY=base64:NOwK3+2AQLj41zWorz0d1JXe7cKSGRTKMtJs9tSm4/g=
APP_DEBUG=true
APP_URL=http://localhost
```

アプリケーションキーは次のコマンドで再生成してください。

```
$ php artisan key:generate
Application key set successfully.
```

#### データベース接続設定

データベースの接続先情報を更新します。
次の例は、SQLite を使用する設定例です。

```
DB_CONNECTION=sqlite
DB_HOST=
DB_PORT=
DB_DATABASE=../database/database.sqlite
DB_USERNAME=
DB_PASSWORD=
```

#### その他

メール送信サーバーなどの設定項目があります。
必要に応じて修正してください。


### 4. データベースを初期化する

```
$ php artisan migrate --seed
```

#### データベースシステム に sqlite を利用する場合の注意点

SQLite を使用する場合は、先に 空白のデータベースファイルを作成しておく必要があります。

また、データベースのパスを相対パスで指定したい場合、 migrate コマンド実行時に注意が必要です。
実際のアプリケーションは相対パスの起点が `public/` で実行されます。 migrate コマンドは、これと同じカレントディレクトリで実行される必要があります。

`DB_DATABASE` の値を `public/` 起点の相対パスに設定して、 publicディレクトリ で migrate を実行します。

```
$ cd public/;
$ touch ../database/database.sqlite;
$ php ../artisan migrate --seed;
$ cd ..;
```

### 5. Passport OAuth Key ファイルを生成する

```
$ php artisan passport:keys
```

### サーバーを起動してみる

以上でセットアップは完了です。
次のコマンドを実行してサーバーを起動し、確認してみることができます。

```
$ php artisan serve
```

正常に起動したら、 `http://127.0.0.1:8000` でアクセスできます。
ブラウザではじめの画面が表示されたら完了です。



## 更新履歴 - Change log

### app-wasabi v0.0.1 (リリース日未定)

- Initial Release.


## ライセンス - License

[MIT license](http://opensource.org/licenses/MIT).


## 開発者向け情報 - for Developer

### データベースへのダミーデータシーディング

```
$ php artisan db:seed --class=DummyDataSeeder
```

## 作者 - Author

- Tomoya Koyanagi <tomk79@gmail.com>
- website: <https://www.pxt.jp/>
- Twitter: @tomk79 <https://twitter.com/tomk79/>
