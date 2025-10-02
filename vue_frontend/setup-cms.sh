#!/bin/bash

echo "CMSセットアップを開始します..."

cd laravel-backend

echo "1. データベースマイグレーションを実行..."
php artisan migrate

echo "2. 初期データをシード..."
php artisan db:seed

echo "3. Laravelサーバーを起動..."
php artisan serve --port=8000 &

cd ..

echo "4. フロントエンドサーバーを起動..."
npm run serve

echo "セットアップが完了しました！"
echo ""
echo "管理者ログイン情報:"
echo "URL: http://localhost:8080/admin/login"
echo "メールアドレス: admin@example.com"
echo "パスワード: password123"