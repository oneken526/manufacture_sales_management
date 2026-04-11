# プロジェクト技術スタック定義

## 🔧 生成情報
- **生成日**: 2026-03-29
- **生成ツール**: init-tech-stack
- **プロジェクトタイプ**: Webアプリケーション
- **チーム規模**: 個人開発
- **開発期間**: プロトタイプ/MVP

## 🎯 プロジェクト要件サマリー
- **パフォーマンス**: 軽負荷（同時利用者10人以下、レスポンス3秒以内）
- **セキュリティ**: 高度（個人情報取り扱い）
- **技術スキル**: 技術スキル限定的（バランス重視で習得）
- **学習コスト許容度**: バランス重視（新技術と安定技術のバランス）
- **デプロイ先**: VPS/専用サーバー（ローカル素驱き → VPS 直接デプロイ）
- **予算**: コスト最小化（無料・低コストツール優先）

## 🚀 フロントエンド
- **テンプレートエンジン**: Blade（Laravel 組み込み）
- **CSSフレームワーク**: Tailwind CSS 4.0
- **JavaScriptライブラリ**: jQuery 3.x
- **バンドラー**: Vite 6.x

### 選択理由
- Laravel の標準 Blade は学習コストが低く、サーバーサイドレンダリングでセキュリティに強い
- Tailwind CSS は既にプロジェクトに組み込み済みで、カスタマイズ性が高い
- jQuery は動的な明細入力・フォーム操作に広く普及しており、ドキュメントが豊富
- SPA フレームワーク（React/Vue）を使わないことで、複雑さを排除しメンテナンスを容易にする

## ⚙️ バックエンド
- **フレームワーク**: Laravel 13.0
- **言語**: PHP 8.3+
- **ORM**: Eloquent（Laravel 組み込み）
- **認証**: Laravel Breeze（Blade スタック）
- **権限管理**: Spatie Laravel Permission 6.x
- **PDF出力**: barryvdh/laravel-dompdf
- **キュー**: database ドライバ（開発） → 必要に応じて Redis へ移行

### 選択理由
- Laravel 13 は既にセットアップ済みで、製造業販売管理のような CRUD 中心システムに最適
- Eloquent ORM によりデータベース操作が直感的に記述できる
- Spatie Permission は Laravel エコシステムで最も普及したロール管理ライブラリ
- dompdf は無料かつ Laravel との統合が容易で、PDF 出力コストゼロ

## 💾 データベース設計
- **メインDB**: MySQL 8.0+（開発・本番ともに）
- **キャッシュ**: database ドライバ（初期） → Redis 7.x（スケール時）
- **ファイルストレージ**: ローカルディスク（storage/app/public）

### 設計方針
- 全業務テーブルにソフトデリート（`deleted_at`）を適用し、データ復元を可能にする
- 外部キー制約を適切に設定し、参照整合性を確保する
- マイグレーションファイルで全スキーマを管理し、バージョン管理可能にする

## 🛠️ 開発環境
- **環境構成**: ローカル素驱き（Laragon / XAMPP 推奨）
- **コンテナ**: 不使用（VPS への直接デプロイ）
- **PHPバージョン**: 8.3+
- **データベース**: MySQL 8.0+（ローカルインストール）

### 開発ツール
- **テストフレームワーク**: PHPUnit 12.5（Laravel 組み込み）
- **コードフォーマット**: Laravel Pint（PSR-12 準拠）
- **コード品質**: PHP_CodeSniffer（必要に応じて）

### CI/CD
- **推奨**: GitHub Actions（VPS への SSH デプロイ）
- **テスト**: Unit, Feature テスト（PHPUnit）
- **デプロイ**: `git pull` + `composer install` + マイグレーション実行

## ☁️ インフラ・デプロイ
- **アプリケーション**: VPS（Nginx + PHP-FPM）
- **データベース**: VPS 内 MySQL 8.0+
- **Webサーバー**: Nginx（推奨） または Apache
- **SSL**: Let's Encrypt（無料・自動更新）
- **ファイルストレージ**: VPS ローカルディスク

## 🔒 セキュリティ
- **HTTPS**: 必須（Let's Encrypt による証明書自動更新）
- **認証**: Laravel Breeze（セッション認証）
- **認可**: Spatie Permission によるロール・パーミッション管理
- **CSRF**: Laravel 組み込み CSRF トークン
- **XSS対策**: Blade の自動エスケープ（`{{ }}`）
- **SQLインジェクション対策**: Eloquent ORM のパラメータバインディング
- **バリデーション**: FormRequest によるサーバーサイドバリデーション
- **環境変数**: `.env` ファイルで機密情報を管理（`.gitignore` に追加済み）
- **ソフトデリート**: 全マスタ・業務テーブルに `deleted_at` を適用

## 📊 品質基準
- **テストカバレッジ**: 主要ビジネスロジックに対して Feature テストを作成
- **コードスタイル**: Laravel Pint（PSR-12）
- **型安全性**: PHP 8.3 の型宣言を積極的に活用
- **パフォーマンス**: ページ読み込み 3 秒以内（軽負荷環境）

## 📁 推奨ディレクトリ構造

```
./（プロジェクトルート: manufacture_sales_management/）
├── app/
│   ├── Http/
│   │   ├── Controllers/     # 業務コントローラー
│   │   └── Requests/        # FormRequest バリデーション
│   ├── Models/              # Eloquent モデル
│   └── Services/            # ビジネスロジック（StockService 等）
├── database/
│   ├── migrations/          # DBマイグレーション
│   ├── seeders/             # 初期データ投入
│   └── factories/           # テスト用ファクトリ
├── resources/
│   ├── views/               # Blade テンプレート
│   │   ├── layouts/         # 共通レイアウト
│   │   ├── components/      # 再利用コンポーネント
│   │   ├── customers/       # 得意先管理
│   │   ├── products/        # 商品管理
│   │   ├── quotations/      # 見積管理
│   │   ├── orders/          # 受注管理
│   │   ├── manufacture_orders/ # 製造指示管理
│   │   ├── shipments/       # 出荷管理
│   │   ├── stocks/          # 在庫管理
│   │   ├── invoices/        # 請求管理
│   │   └── payments/        # 入金管理
│   ├── css/
│   │   └── app.css          # Tailwind CSS エントリポイント
│   └── js/
│       └── app.js           # jQuery + カスタム JS エントリポイント
├── routes/
│   └── web.php              # 全ルーティング定義
├── storage/
│   └── app/public/          # PDF・アップロードファイル
├── tests/
│   ├── Unit/                # ユニットテスト
│   └── Feature/             # フィーチャーテスト
├── docs/
│   ├── tech-stack.md        # このファイル
│   └── ...                  # 要件定義書・設計書等
├── .env                     # 環境変数（Git 管理外）
├── .env.example             # 環境変数テンプレート
└── vite.config.js           # Vite 設定
```

## 🚀 セットアップ手順

### 1. 開発環境準備（Laragon 推奨）
```bash
# Composer 依存パッケージのインストール
composer install

# Spatie Permission, Breeze, dompdf のインストール
composer require laravel/breeze spatie/laravel-permission barryvdh/laravel-dompdf

# Breeze のインストール（Blade スタック）
php artisan breeze:install blade

# npm 依存パッケージのインストール（jQuery を含む）
npm install
npm install jquery

# .env 設定（DB_CONNECTION=mysql に変更）
cp .env.example .env
php artisan key:generate

# データベースのマイグレーション
php artisan migrate

# 開発サーバー起動
php artisan serve
npm run dev
```

### 2. 主要コマンド
```bash
# 開発サーバー
php artisan serve

# フロントエンドビルド（開発）
npm run dev

# フロントエンドビルド（本番）
npm run build

# マイグレーション実行
php artisan migrate

# テスト実行
php artisan test

# コードフォーマット
./vendor/bin/pint
```

### 3. VPS デプロイ
```bash
# VPS サーバー上での操作
git pull origin main
composer install --no-dev --optimize-autoloader
npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 📝 カスタマイズ方法

このファイルはプロジェクトの進行に応じて更新してください：

1. **技術の追加**: 新しいライブラリ・ツールを追加
2. **要件の変更**: パフォーマンス・セキュリティ要件の更新
3. **インフラの変更**: デプロイ先・スケール要件の変更

## 🔄 更新履歴
- 2026-03-29: 初回生成（init-tech-stack により自動生成）
