1. 業務フロー

   | 人物     |         業務フロー |
   | :------- | -----------------: |
   | ゲスト   |       ユーザー登録 |
   | ユーザー |         タスク登録 |
   |          |       タイマー登録 |
   |          |   プレイリスト登録 |
   |          | ログイン情報の更新 |

1. 画面遷移図

|                  |     |                    |
| :--------------- | --: | :----------------: |
| ログイン画面     |   → |     ホーム画面     |
| ユーザー登録画面 |   → |     ホーム画面     |
| ホーム画面       |   → |    ログイン画面    |
| タイマー設定     |   → | モーダルウィンドウ |
| ホーム画面       |   → |      統計画面      |
| 統計画面         |   → |     ホーム画面     |

1. ワイヤーフレーム

   https://www.figma.com/design/Ub8EI7K9UrnfFZJhYa2xmr/%E3%83%95%E3%83%AC%E3%83%83%E3%82%AF%E3%82%B9%E3%82%BF%E3%82%A4%E3%83%9E%E3%83%BC?node-id=0-1&t=y1d70mqbPYmNNq7C-1

1. テーブル定義書（もしくは ER 図）

   https://docs.google.com/spreadsheets/d/1Q_93NrIwKYTb-UOqQYJJ1kxwI4epBSz_ly09fgIXWkE/edit?usp=sharing

1. システム構成図

   ブラウザ(HTML/CSS/JavaScript/TypeScript) → Laravel → MySQL
