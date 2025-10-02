# CMS テスト手順

## 1. サーバーの起動

### Laravel バックエンドサーバーの起動
```bash
cd laravel-backend
php artisan serve --port=8000
```

### Vue フロントエンドサーバーの起動（別のターミナルで）
```bash
npm run serve
```

## 2. 管理画面へのアクセス

1. ブラウザで `http://localhost:8080/admin/login` にアクセス
2. 以下の資格情報でログイン：
   - メールアドレス: `admin@example.com`
   - パスワード: `password123`

## 3. コンテンツ編集のテスト

### AboutページのCMS編集テスト

1. 管理ダッシュボードで「about」ページの「編集」リンクをクリック
2. コンテンツ（JSON形式）を以下のように編集：
```json
{
  "mission": "テスト：CMSから更新されたミッション内容です",
  "history": "テスト：2024年にCMS機能を実装しました",
  "team": {
    "economists": 30,
    "data_scientists": 25,
    "engineers": 20
  }
}
```
3. 「保存」ボタンをクリック
4. 新しいタブで `http://localhost:8080/about` を開き、変更が反映されているか確認

### ホームページのCMS編集テスト

1. 管理ダッシュボードで「home」ページの「編集」リンクをクリック
2. コンテンツを以下のように編集：
```json
{
  "hero_title": "CMSテスト用タイトル",
  "hero_subtitle": "サブタイトルもCMSから更新",
  "features": [
    {
      "title": "CMS機能",
      "description": "管理画面からコンテンツを簡単に編集"
    }
  ]
}
```
3. 「保存」ボタンをクリック
4. 新しいタブで `http://localhost:8080/` を開き、変更が反映されているか確認

### サービスページのCMS編集テスト

1. 管理ダッシュボードで「services」ページの「編集」リンクをクリック
2. コンテンツを編集して保存
3. `http://localhost:8080/services` で確認

## 4. 新規ページ作成のテスト

1. 管理ダッシュボードで「新規作成」ボタンをクリック
2. 以下の情報を入力：
   - ページキー: `test`
   - タイトル: `テストページ`
   - コンテンツ: 
   ```json
   {
     "test": "これはテストページです"
   }
   ```
3. 「公開する」にチェックを入れて保存
4. APIエンドポイント `http://localhost:8000/api/pages/test` で確認

## 5. 確認事項チェックリスト

- [ ] 管理画面にログインできる
- [ ] ページ一覧が表示される
- [ ] 既存ページを編集できる
- [ ] 編集内容が実際のページに反映される
- [ ] 新規ページを作成できる
- [ ] 公開/非公開の切り替えが機能する
- [ ] JSONバリデーションが機能する（不正なJSONを入力したときにエラーが表示される）

## トラブルシューティング

### データベースエラーが発生する場合
```bash
cd laravel-backend
php artisan migrate:fresh --seed
```

### CORS エラーが発生する場合
Laravel の `config/cors.php` で、`allowed_origins` に `http://localhost:8080` が含まれているか確認

### ページが更新されない場合
1. ブラウザのキャッシュをクリア
2. Vue の開発サーバーを再起動
3. Network タブでAPIレスポンスを確認