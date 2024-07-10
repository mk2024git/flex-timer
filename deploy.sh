#!/bin/bash

# 例: サーバー上のディレクトリにコードをコピーし、サービスを再起動
rsync -avz --exclude 'node_modules' ./ user@server:/path/to/deploy
ssh user@server "cd /path/to/deploy && npm install && pm2 restart all"
