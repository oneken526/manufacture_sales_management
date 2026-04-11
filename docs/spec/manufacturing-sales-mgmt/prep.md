# 製造業向け販売管理システム — ユーザー準備タスク

作成日: 2026-03-29

---

## 関連文書

| ファイル | 内容 |
|---|---|
| [requirements.md](requirements.md) | 機能要件・非機能要件（EARS記法） |
| [note.md](note.md) | コンテキストノート（技術スタック・確定事項） |
| [acceptance-criteria.md](acceptance-criteria.md) | 受け入れ基準（Given/When/Then） |

---

## 信頼性レベル凡例

| レベル | 意味 |
|---|---|
| 🔵 | PRD・ヒアリングで確認済みの確実な要件 |
| 🟡 | PRD・設計文書から妥当な推測 |
| 🔴 | 推測による要件（PRD・ヒアリングにない） |

---

## 必須タスク（実装開始前に完了が必要）

### PREP-001: MySQL 8.0+ のローカルインストール

**優先度:** 必須　**信頼性:** 🔵　**出典:** 技術スタック / ヒアリングQ10

Laravel アプリケーションのデータベースとして MySQL 8.0+ が必要です。

#### Laragon を使用する場合（推奨）

Laragon には MySQL が同梱されているため、追加インストールは不要です。
Laragon を起動するだけで MySQL サービスが有効になります。

```
# Laragon の起動後、以下で MySQL の動作確認
# Laragon メニュー → MySQL → HeidiSQL または phpMyAdmin で確認
```

#### Laragon を使用しない場合

1. [MySQL Community Downloads](https://dev.mysql.com/downloads/mysql/) から MySQL 8.0+ をダウンロード
2. インストーラーを実行し、`root` ユーザーのパスワードを設定
3. MySQL サービスが起動していることを確認

```bash
# 接続確認（Windows の場合は mysql.exe のパスを通すこと）
mysql -u root -p
```

---

### PREP-002: .env ファイルの設定

**優先度:** 必須　**信頼性:** 🔵　**出典:** 技術スタック

プロジェクトルートに `.env` ファイルを作成・編集し、以下の設定を行ってください。

#### 手順

1. `.env.example` をコピーして `.env` を作成する（未作成の場合）

```bash
cp .env.example .env
php artisan key:generate
```

2. `.env` の DB 関連設定を以下のように変更する

```dotenv
APP_NAME="製造業販売管理システム"
APP_ENV=local
APP_KEY=  # php artisan key:generate で自動設定
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=manufacture_sales
DB_USERNAME=root
DB_PASSWORD=（MySQLのrootパスワードを設定）
```

> **注意:** `DB_PASSWORD` は空のまま（Laragon のデフォルト設定）でも動作する場合があります。
> 実際の MySQL 設定に合わせて変更してください。

---

### PREP-003: MySQL データベースの作成

**優先度:** 必須　**信頼性:** 🔵　**出典:** 技術スタック

アプリケーション用のデータベースを作成してください。

#### 手順

```sql
-- MySQL に接続後、以下のコマンドを実行
CREATE DATABASE manufacture_sales
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

-- 作成確認
SHOW DATABASES;
```

#### Laragon の場合の接続方法

- Laragon メニュー → 「HeidiSQL」または「phpMyAdmin」を起動
- 接続情報: ホスト `127.0.0.1`、ユーザー `root`、パスワード（Laragon デフォルトは空）

#### コマンドラインの場合

```bash
mysql -u root -p -e "CREATE DATABASE manufacture_sales CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

---

## 推奨タスク（実装中に用意できればOK）

### PREP-004: Composer パッケージのインストール

**優先度:** 推奨　**信頼性:** 🔵　**出典:** 技術スタック

以下のパッケージが必要です。プロジェクトルートで実行してください。

```bash
# Laravel Breeze（認証スキャフォールディング）
composer require laravel/breeze --dev

# Spatie Permission（ロールベースアクセス制御）
composer require spatie/laravel-permission

# barryvdh/laravel-dompdf（PDF出力）
composer require barryvdh/laravel-dompdf
```

#### インストール後の追加手順

```bash
# Breeze のインストール（Blade + Alpine.js を使用）
php artisan breeze:install blade

# Spatie Permission のマイグレーションファイル発行
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"

# dompdf の設定ファイル発行
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```

---

### PREP-005: npm パッケージのインストール

**優先度:** 推奨　**信頼性:** 🔵　**出典:** 技術スタック（Tailwind CSS 4.0 / jQuery 3.x / Vite）

```bash
# 基本パッケージのインストール
npm install

# jQuery のインストール
npm install jquery

# 開発サーバーの起動確認
npm run dev
```

#### 動作確認

```bash
# アセットのビルド（本番用）
npm run build

# 開発用ウォッチモード
npm run dev
```

> **注意:** `vite.config.js` に jQuery のエイリアス設定が必要な場合があります。
> 実装フェーズで適宜設定してください。

---

### PREP-006: メール送信の設定

**優先度:** 推奨　**信頼性:** 🔵　**出典:** ヒアリングQ7（承認通知メール機能）

承認申請・承認完了時のメール通知機能に必要です。

#### .env の設定（SMTP を使用する場合）

```dotenv
MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=your-email@example.com
MAIL_PASSWORD=your-email-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="製造業販売管理システム"
```

#### 開発環境での推奨設定（Mailtrap を使用する場合）

開発中は実際にメールを送信しない Mailtrap が便利です。

```dotenv
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=（Mailtrap のユーザー名）
MAIL_PASSWORD=（Mailtrap のパスワード）
MAIL_ENCRYPTION=tls
```

#### ローカル確認用（log ドライバー）

```dotenv
MAIL_MAILER=log
```

> `log` ドライバーを設定すると、メールは送信されず `storage/logs/laravel.log` に記録されます。
> 開発初期はこの設定で動作確認できます。

---

### PREP-007: 本番 VPS サーバーの MySQL セットアップ

**優先度:** 推奨（本番デプロイ時に必要）　**信頼性:** 🔵　**出典:** 技術スタック確認済み（開発・本番ともに MySQL）

本番環境へのデプロイ時に必要な設定です。実装完了後に対応してください。

#### 本番 VPS での手順（Ubuntu/Debian 系）

```bash
# MySQL 8.0 のインストール
sudo apt update
sudo apt install mysql-server-8.0

# セキュリティ設定
sudo mysql_secure_installation

# 本番データベースの作成
mysql -u root -p
```

```sql
-- 本番DB・ユーザーの作成
CREATE DATABASE manufacture_sales_prod
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

CREATE USER 'manufacture_user'@'localhost'
  IDENTIFIED BY '（強力なパスワードを設定）';

GRANT ALL PRIVILEGES ON manufacture_sales_prod.*
  TO 'manufacture_user'@'localhost';

FLUSH PRIVILEGES;
```

#### 本番 .env の設定例

```dotenv
APP_ENV=production
APP_DEBUG=false
DB_DATABASE=manufacture_sales_prod
DB_USERNAME=manufacture_user
DB_PASSWORD=（上で設定したパスワード）
```

---

## 確認事項

### CHECK-001: 承認者メールアドレスの確認

**優先度:** 必須　**信頼性:** 🔵　**出典:** ヒアリングQ7（承認通知メール）

承認通知メールを受け取る管理者（admin ロール）のメールアドレスを事前に確認してください。

- **確認内容:** admin ロールを付与するユーザーのメールアドレス
- **利用場面:** 見積・受注の承認申請時に、このアドレス宛に通知メールが送信される
- **設定方法:** 実装後、`DatabaseSeeder` または管理画面からユーザー登録時にメールアドレスを設定

```
確認項目:
□ 承認担当者（admin ロール）の氏名
□ 承認担当者のメールアドレス
□ メールを受信できる環境（受信テスト推奨）
```

---

### CHECK-002: PDF フォント設定（日本語フォント）

**優先度:** 必須（PDF出力機能の実装前に確認）　**信頼性:** 🔵　**出典:** ヒアリングQ2（PDF言語: 日本語のみ）

dompdf で日本語 PDF を出力するには、日本語フォントの設定が必要です。

#### 推奨フォント: IPA フォント

```bash
# IPAフォントのダウンロード（例）
# https://moji.or.jp/ipafont/ipaex.html から IPAex明朝・IPAexゴシック を取得

# フォントファイルを以下に配置
storage/fonts/
```

#### dompdf の設定（`config/dompdf.php`）

```php
// PREP-004 の手順で config/dompdf.php を発行後、以下を設定
'font_dir' => storage_path('fonts/'),
'font_cache' => storage_path('fonts/'),
'default_font' => 'ipaexg',  // IPAexゴシックを使用する場合
```

#### 代替手段: Google Noto Sans JP

インターネット接続がある環境では、Google Fonts の Noto Sans JP を Web フォントとして使用することも可能です（dompdf の設定により異なります）。

```
確認項目:
□ 使用する日本語フォントを決定（IPAex推奨）
□ フォントファイルの入手
□ dompdf のフォント設定の動作確認
```

---

### CHECK-003: 得意先の締日パターン確認

**優先度:** 推奨（請求書機能の実装前に確認）　**信頼性:** 🔵　**出典:** ヒアリング確認済み（締日は1〜28または99のみ）

月次請求書の生成ロジックは締日パターンに依存します。実際に使用する得意先の締日パターンを事前に確認してください。

#### 一般的な締日パターン

| パターン | 説明 | 例 |
|---|---|---|
| 月末締め | 毎月末日が締日 | 1月31日、2月28日（うるう年は29日） |
| 15日締め | 毎月15日が締日 | 1月15日、2月15日 |
| 20日締め | 毎月20日が締日 | 1月20日、2月20日 |
| 任意日締め | 任意の日付が締日 | 各得意先ごとに設定 |

#### 確認が必要な項目

```
確認項目:
□ 対象得意先の締日パターン一覧
□ 締日が月末を超える場合の扱い（例: 31日締めで2月の場合）
□ 支払期日（締日から何日後が支払期限か）
□ 請求書の発行タイミング（締日当日・翌月1日など）
```

> **実装上の注意:** 得意先マスタに締日（`closing_day` 等）カラムを設けることで、
> 複数の締日パターンに対応できます。実装フェーズで要確認。

---

## タスク完了チェックリスト

### 実装開始前（必須）

```
□ PREP-001: MySQL 8.0+ がローカルで起動している
□ PREP-002: .env ファイルの DB 設定が完了している
□ PREP-003: manufacture_sales データベースが作成されている
```

### 実装中（推奨）

```
□ PREP-004: Composer パッケージ（Breeze / Spatie / dompdf）がインストールされている
□ PREP-005: npm パッケージ（jQuery 含む）がインストールされている
□ PREP-006: メール送信設定が完了している（開発中は log ドライバーでも可）
□ CHECK-001: 承認者（admin）のメールアドレスが確認済み
□ CHECK-002: 日本語 PDF フォントが準備されている
□ CHECK-003: 得意先の締日パターンが確認済み
```

### 本番デプロイ時

```
□ PREP-007: 本番 VPS の MySQL がセットアップされている
□ 本番 .env の APP_ENV=production / APP_DEBUG=false が設定されている
□ 本番用 Composer パッケージ（--no-dev）がインストールされている
□ php artisan migrate --force が実行されている
□ php artisan config:cache / route:cache / view:cache が実行されている
```
