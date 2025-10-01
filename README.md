<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Email Campaigns (Project-specific)

- Queue: set `QUEUE_CONNECTION=database` and run migrations to create the `jobs` table.
- Worker: run `php artisan queue:work --queue=default` (Railway: add a Worker process).
- Scheduler: run `php artisan schedule:run` every minute to enqueue scheduled campaigns.
- Mail: set SMTP envs (`MAIL_MAILER`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_FROM_ADDRESS`, `MAIL_FROM_NAME`).
- Dry-run: set `MAIL_DRY_RUN=true` to skip real sends while testing.

## Gmail でメール送信する（簡単: SMTP／推奨手順）

Gmailを送信元にする最短手順です。Googleアカウントで2段階認証を有効化し、「アプリ パスワード」を発行してSMTPで送信します。

1) 下準備（Googleアカウント）
- Googleアカウントの「2段階認証」を有効化。
- セキュリティ > アプリ パスワード から「メール」「その他の端末」で16桁のアプリパスワードを生成。

2) 環境変数（Railway の環境変数に設定）

```env
# Gmail SMTP
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_ENCRYPTION=tls
MAIL_USERNAME=your-account@gmail.com
MAIL_PASSWORD=your_16_digit_app_password
MAIL_FROM_ADDRESS=your-account@gmail.com
MAIL_FROM_NAME="ちくぎん地域経済研究所"

# 実送信する場合は false（true だと実送信スキップ）
MAIL_DRY_RUN=false
```

補足:
- From は基本的に Gmail アドレスに合わせてください（`MAIL_FROM_ADDRESS` と `MAIL_USERNAME` を同じにする）。
- Gmail には送信上限があります。大量配信には不向きです。

3) 動作確認（管理画面の即時送信）
- 管理者でログインし、「メール送信（即時）」からテスト送信。
- もしくは `POST /api/admin/emails/send-simple` にて、

```json
{
  "to": "your-destination@example.com",
  "subject": "Gmail SMTP テスト",
  "body_text": "Hello from Gmail SMTP"
}
```

レスポンスの `mailer` が `smtp` で、実際に届けば設定完了です。

## Gmail API（SMTPがブロックされる環境向け）

サーバー環境で外向きSMTPが使えない場合は、Gmail API を利用できます（このリポジトリは `symfony/google-mailer` を同梱済み）。

1) 下準備（Google Cloud）
- プロジェクト作成 → Gmail API を有効化。
- OAuth クライアントID/シークレットを発行し、オフラインアクセスのリフレッシュトークンを取得。

2) 環境変数（どちらかの方式）

方式A: DSN を使う

```env
MAIL_MAILER=gmail
MAIL_URL=gmail+api://USER@gmail.com@default?client_id=XXX&client_secret=YYY&refresh_token=ZZZ
MAIL_FROM_ADDRESS=USER@gmail.com
MAIL_FROM_NAME="ちくぎん地域経済研究所"
```

方式B: 個別キーを使う

```env
MAIL_MAILER=gmail
GOOGLE_GMAIL_USER=USER@gmail.com
GOOGLE_CLIENT_ID=XXX
GOOGLE_CLIENT_SECRET=YYY
GOOGLE_REFRESH_TOKEN=ZZZ
MAIL_FROM_ADDRESS=USER@gmail.com
MAIL_FROM_NAME="ちくぎん地域経済研究所"
```

3) 動作確認
- 管理画面もしくは `POST /api/admin/emails/send-simple` でテスト。
- レスポンスの `mailer` が `gmail` になればOK。

## Xserver（エックスサーバー）でメール送信（SMTP）を使う

Xserverで作成したメールボックス（例: `info@your-domain.jp`）を使ってLaravelから送信する場合の設定例です。Railway等の外部からXserverのSMTPに接続して送信できます（認証必須）。

1) 事前準備（Xserver側）
- メールアカウントを作成（Xserverサーバーパネル）。
- クライアント設定情報（SMTPサーバー名、ポート、暗号化方式、ユーザー名/パスワード）を控える。
  - 一般的には以下のいずれか:
    - 465（SSL）/ `MAIL_ENCRYPTION=ssl`
    - 587（TLS）/ `MAIL_ENCRYPTION=tls`
  - ユーザー名は「メールアドレス（フル）」、パスワードはそのメールボックスのパスワード。
  - ホスト名は Xserver 発行のサーバー名（例: `svXXX.xserver.jp`）または `mail.your-domain.jp` を利用。

2) Laravel 環境変数（Railway の環境変数に設定）

```env
# SMTP（Xserver）例
MAIL_MAILER=smtp
MAIL_HOST=svXXX.xserver.jp          # 例: Xserverのサーバー名 or mail.your-domain.jp

# いずれか片方を使用
# 465 で SSL の場合
MAIL_PORT=465
MAIL_ENCRYPTION=ssl
# 587 で TLS の場合（こちらを使う場合は上の2行をコメントアウト）
# MAIL_PORT=587
# MAIL_ENCRYPTION=tls

MAIL_USERNAME=info@your-domain.jp    # Xserverのメールアカウント（フルのメールアドレス）
MAIL_PASSWORD=your_mailbox_password  # そのメールボックスのパスワード

MAIL_FROM_ADDRESS=info@your-domain.jp
MAIL_FROM_NAME="ちくぎん地域経済研究所"

# HELO/EHLOで名乗るドメイン（任意・配信改善に有効な場合あり）
MAIL_EHLO_DOMAIN=your-domain.jp

# 本番は false（true だと実送信をスキップ）
MAIL_DRY_RUN=false
```

3) 動作確認（管理画面の即時送信）
- 管理者トークンを取得して、`POST /api/admin/emails/send-simple` を実行。
- サンプルボディ:

```json
{
  "to": "your-address@example.com",
  "subject": "Xserver SMTP テスト",
  "body_text": "Hello from Xserver SMTP",
  "from": { "address": "info@your-domain.jp", "name": "ちくぎん地域経済研究所" }
}
```

期待レスポンス: `{"success":true, "mailer":"smtp"}`。届かない場合は下記を参照。

4) 配信品質/エラー対処のヒント
- Fromとユーザー名（送信元メールアドレス）は一致させる。
- ポートと暗号化の組み合わせ（465/ssl または 587/tls）を必ず正しく設定。
- SPF/DKIM/DMARC をドメインDNSに設定して配信到達率を改善（Xserverのガイドに従って有効化）。
- 典型的なエラー:
  - Connection timed out: デプロイ先が外向きSMTPを制限している可能性。APIベース（Resend/SendGrid）も検討。
  - 530/535 認証エラー: ユーザー名がメールアドレスであること、パスワードが正しいことを確認。
  - 553 From拒否: `MAIL_FROM_ADDRESS` が利用可能な送信元であること、認証済みドメインであることを確認。

メモ: 本リポジトリは Resend 経由もサポートしています（`MAIL_MAILER=resend` と `RESEND_API_KEY`）。ホスティングでSMTPが使えない場合はResend利用を推奨します。
