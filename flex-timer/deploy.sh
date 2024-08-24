#!/bin/bash

# プロジェクトディレクトリに移動
cd /var/www/flex-timer/flex-timer

# リポジトリから最新のコードを取得
git pull origin main

# Composerで依存関係をインストール
composer install --no-dev --optimize-autoloader

# データベースのマイグレーションを実行
php artisan migrate --force

# npmでフロントエンドの依存関係をインストール
npm install

# フロントエンドをビルド
npm run build

# PHP-FPMとApacheを再起動
sudo systemctl restart php-fpm
sudo systemctl restart httpd
