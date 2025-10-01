# Laravel API デプロイメント手順

## Railway でのデプロイ

### 1. Railwayアカウント準備
1. [Railway.app](https://railway.app) でアカウント作成
2. GitHubアカウントと連携

### 2. プロジェクトのデプロイ
1. Railway ダッシュボードで「New Project」
2. 「Deploy from GitHub repo」を選択
3. `betweentabin/modeitest` リポジトリを選択
4. **root directory を `laravel-backend` に設定**

### 3. 環境変数の設定
Railway のダッシュボードでVariables タブから以下を設定：

```bash
APP_NAME="ちくぎん地域経済研究所API"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://[自動生成されるURL].up.railway.app

# データベース（SQLite使用）
DB_CONNECTION=sqlite
DB_DATABASE=/app/database/database.sqlite

# CORS設定
SANCTUM_STATEFUL_DOMAINS=modeitest.vercel.app,localhost:8080
SESSION_DOMAIN=.railway.app

# セッション設定
SESSION_DRIVER=file
SESSION_LIFETIME=120
CACHE_DRIVER=file
```

### 4. 自動デプロイ設定
- `railway.json` と `Procfile` が既に設定済み
- `start.sh` スクリプトが自動実行される
- マイグレーションとシードが自動実行される

### 5. デプロイ後の確認
以下のエンドポイントで動作確認：

```bash
# ヘルスチェック
curl https://[デプロイURL]/api/seminars

# 管理API（認証が必要）
curl -H "Authorization: Bearer test-token" https://[デプロイURL]/api/admin/seminars
```

## フロントエンド設定の更新

デプロイ完了後、`src/config/api.js` の `production` URLを更新：

```javascript
const API_CONFIG = {
  development: {
    baseURL: 'http://localhost:8001',
  },
  production: {
    baseURL: 'https://[RailwayのURL]', // ← ここを更新
  }
}
```

## トラブルシューティング

### よくある問題
1. **APP_KEYエラー**: 自動生成されるため通常発生しない
2. **データベースエラー**: SQLiteファイルの権限問題
3. **CORS エラー**: `SANCTUM_STATEFUL_DOMAINS` の設定確認

### ログの確認
Railway ダッシュボードの「Deployments」→「View Logs」でログ確認

### 再デプロイ
Railway ダッシュボードの「Deployments」→「Redeploy」
