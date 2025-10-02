#!/bin/bash

echo "🚀 ちくぎん地域経済研究所プロジェクトを起動中..."

# フロントエンド（Vue.js）を起動
echo "📱 フロントエンドを起動中..."
npm start &
FRONTEND_PID=$!

# 少し待ってからLaravelバックエンドを起動
sleep 3
echo "🔧 Laravelバックエンドを起動中..."
cd laravel-backend
php artisan serve &
BACKEND_PID=$!

# フロントエンドのVite開発サーバーも起動
echo "⚡ Vite開発サーバーを起動中..."
npm run dev &
VITE_PID=$!

echo "✅ プロジェクトが起動しました！"
echo "📱 フロントエンド: http://localhost:8080"
echo "🔧 バックエンド: http://localhost:8000"
echo "⚡ Vite: http://localhost:5173"
echo ""
echo "停止するには Ctrl+C を押してください"

# プロセスを監視
wait
