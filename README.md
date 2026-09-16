# Mersin Modern — Landing Page

Mersin Modern, kurgusal bir çağdaş sanat galerisi için hazırlanan responsive ve glassmorphic bir landing page prototipidir. Frontend Tailwind CSS ile, backend framework kullanmadan OOP/MVC katmanlarıyla PHP 8 üzerinde yazılmıştır. İletişim formu Fetch API ile gönderilir ve MySQL’e PDO prepared statement’larla kaydedilir.

---

## 1. Kurulum

İki yol vardır. **Docker** önerilen yoldur: PHP ve MySQL kurmanız gerekmez, veritabanı ve tablo otomatik oluşur.

### 1.1 Docker ile (önerilen)

Gereksinimler: [Docker Desktop](https://www.docker.com/products/docker-desktop/) ve [Node.js](https://nodejs.org/) 18+.

```bash
# 1) Ortam dosyasını oluştur
copy .env.example .env        # Windows
cp .env.example .env          # macOS / Linux

# 2) CSS'i derle (Tailwind)
npm install
npm run build

# 3) Uygulama + MySQL konteynerlerini başlat
docker compose up --build
```

Tarayıcıda `http://localhost:8080` adresini açın.

İlk açılışta MySQL konteyneri `.env` içindeki `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` değerleriyle veritabanını ve kullanıcıyı oluşturur, `database/migrations/` klasöründeki SQL dosyasını otomatik çalıştırır. Uygulama konteyneri `DB_HOST=mysql` değerini otomatik kullanır; `.env` içindeki `127.0.0.1` değeri yalnızca Docker’sız kurulum içindir.

Durdurmak için `docker compose down`. Veriler `museum_mysql` adlı Docker volume’unda saklanır; konteyner kapansa da silinmez. Veritabanını sıfırlamak isterseniz `docker compose down -v`.

> **Önemli:** `.env` içinde `DB_USERNAME=root` yazmayın. MySQL imajı root kullanıcısını bu değişkenle kabul etmez ve konteyner başlamaz. Uygulama kullanıcısı için `museum` gibi ayrı bir ad kullanın; root şifresi `DB_ROOT_PASSWORD` ile ayrıca verilir.

### 1.2 Docker olmadan (yerel PHP + MySQL)

Gereksinimler: PHP 8.0+ (`pdo_mysql` eklentisi açık), MySQL 8 veya MariaDB 10.6+, Node.js 18+.

Windows’ta en kolay yol [XAMPP](https://www.apachefriends.org/) kurmaktır; PHP, MySQL ve phpMyAdmin birlikte gelir.

```bash
# 1) Ortam dosyası
copy .env.example .env

# 2) CSS derle
npm install
npm run build

# 3) MySQL'de veritabanı, kullanıcı ve tabloyu oluştur (bkz. bölüm 2)

# 4) .env içindeki DB_* değerlerini kendi MySQL kurulumuna göre düzenle

# 5) PHP'nin dahili sunucusunu başlat
php -S 127.0.0.1:8080 -t public public/router.php
```

Tarayıcıda `http://127.0.0.1:8080` adresini açın. `php.ini` içinde `;extension=pdo_mysql` satırının başındaki `;` kaldırılmış olmalıdır.

### 1.3 Canlı önizleme (Vercel, yalnızca frontend)

Arayüzün herkese açık demosu: **https://mersin-modern-landing.vercel.app**

Vercel PHP ve MySQL çalıştırmadığı için bu yayın statik bir dışa aktarımdır: sayfa çalışan uygulamadan render edilip `dist-vercel/` klasörüne alınır, `/api/contact` ise PHP validator’ın kurallarını birebir uygulayan ama hiçbir şey kaydetmeyen küçük bir Node fonksiyonuyla karşılanır. Form gönderiminde toast bunun demo ortamı olduğunu açıkça söyler. Tam sürüm (PHP + MySQL kaydı) Docker kurulumudur.

Yeniden yayınlamak için uygulama Docker’da çalışırken:

```bash
node scripts/export-static.mjs
cd dist-vercel && vercel deploy --prod --yes
```

Kaynak dosyalar `deploy/vercel/` altındadır; `dist-vercel/` Git dışındadır.

---

## 2. MySQL kurulumu ve doğrulama

### 2.1 Docker kullanıyorsanız

Hiçbir şey yapmanız gerekmez; `docker compose up` veritabanını, kullanıcıyı ve tabloyu oluşturur. Kayıtları görmek için:

```bash
docker compose exec mysql mysql -umuseum -p museum -e "SELECT id, full_name, email, phone, created_at FROM contact_messages ORDER BY id DESC;"
```

Şifre sorulduğunda `.env` içindeki `DB_PASSWORD` değerini girin. MySQL host makinede `3307` portundan da erişilebilir; MySQL Workbench, DBeaver veya HeidiSQL ile `127.0.0.1:3307`, kullanıcı `museum` bilgileriyle bağlanabilirsiniz.

### 2.2 Yerel MySQL kullanıyorsanız

**Komut satırıyla:**

```sql
CREATE DATABASE museum CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'museum'@'localhost' IDENTIFIED BY 'guclu-bir-sifre';
GRANT SELECT, INSERT ON museum.* TO 'museum'@'localhost';
FLUSH PRIVILEGES;
```

Ardından tabloyu oluşturun:

```bash
mysql -u museum -p museum < database/migrations/001_create_contact_messages.sql
```

**phpMyAdmin ile (XAMPP):**

1. `http://localhost/phpmyadmin` adresini açın.
2. “Yeni” → veritabanı adı `museum`, karşılaştırma `utf8mb4_unicode_ci` → Oluştur.
3. `museum` veritabanını seçin, “SQL” sekmesine geçin, `database/migrations/001_create_contact_messages.sql` dosyasının içeriğini yapıştırın ve çalıştırın.
4. “Kullanıcı hesapları” → “Kullanıcı hesabı ekle”: ad `museum`, sunucu `localhost`, şifre belirleyin; `museum` veritabanında `SELECT` ve `INSERT` yetkisi verin. XAMPP’te pratiklik için `root` ve boş şifre de kullanılabilir, ancak yalnızca yerel geliştirme için.

Son olarak `.env` dosyasını güncelleyin:

```env
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=museum
DB_USERNAME=museum
DB_PASSWORD=guclu-bir-sifre
```

---

## 3. Teknik mimari

Framework bağımsız, PSR-4 uyumlu bir OOP/MVC yapısı. Tüm istekler tek giriş noktasından geçer; controller yalnızca HTTP koordinasyonu yapar, iş kuralları servis ve validator katmanındadır, veritabanı erişimi arayüz arkasındaki repository’de izole edilmiştir.

```text
HTTP Request
  → public/index.php          (front controller)
  → bootstrap/app.php         (autoload, .env, session, güvenlik başlıkları, bağımlılık kurulumu)
  → App\Core\Router
  → HomeController            GET  /              → resources/views/home.php
  → ContactController         POST /api/contact
        → ContactValidator    (doğrulama + düz metin normalizasyonu)
        → ContactService      (iş akışı)
        → ContactRepositoryInterface → PdoContactRepository → MySQL
```

---

## 4. Veritabanı şeması

Migration: `database/migrations/001_create_contact_messages.sql`

```sql
CREATE TABLE contact_messages (
    id            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    full_name     VARCHAR(120)    NOT NULL,
    email         VARCHAR(254)    NOT NULL,
    phone         VARCHAR(25)     NOT NULL,
    message       TEXT            NOT NULL,
    request_token CHAR(36)        NOT NULL,             -- istemci istek kimliği (UUID)
    created_at    TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_contact_request_token (request_token), -- çift gönderimi engeller
    KEY idx_contact_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

- `request_token`: sayfa yüklenirken sunucunun ürettiği UUID. Aynı form iki kez gönderilirse `UNIQUE` kısıtı ikinci kaydı reddeder; API bu durumu 200 “daha önce alınmış” olarak döndürür.
- `utf8mb4`: Türkçe karakterler ve emoji sorunsuz saklanır.
- Uygulama kullanıcısına yalnızca `SELECT` ve `INSERT` yetkisi yeter.

---

## 5. .env yapılandırması

`.env.example` dosyasını `.env` olarak kopyalayın. Gerçek `.env` Git’e dahil edilmez (`.gitignore`).

| Değişken           | Örnek                        | Açıklama                                                                   |
| ------------------ | ---------------------------- | -------------------------------------------------------------------------- |
| `APP_NAME`         | `Mersin Modern`              | Site adı; başlık ve meta etiketlerinde kullanılır                          |
| `APP_ENV`          | `local`                      | Çalışma ortamı                                                             |
| `APP_DEBUG`        | `false`                      | Üretimde `false` olmalı                                                    |
| `APP_URL`          | `http://localhost:8080`      | Uygulama adresi                                                            |
| `DB_HOST`          | `127.0.0.1`                  | MySQL sunucusu. Docker’da otomatik `mysql` olur                            |
| `DB_PORT`          | `3306`                       | MySQL portu                                                                |
| `DB_DATABASE`      | `museum`                     | Veritabanı adı                                                             |
| `DB_USERNAME`      | `museum`                     | Uygulama kullanıcısı. **`root` olamaz** (Docker imajı reddeder)            |
| `DB_PASSWORD`      | `guclu-bir-sifre`            | Uygulama kullanıcısının şifresi                                            |
| `DB_ROOT_PASSWORD` | `root-sifresi`               | Yalnızca Docker MySQL konteyneri için root şifresi                         |
| `WHATSAPP_URL`     | `https://wa.me/905xxxxxxxxx` | İsteğe bağlı. Doluysa chatbot panelinde WhatsApp bağlantısı görünür        |
| `CAMPAIGN_END_AT`  | `2026-10-31T23:59:59+03:00`  | Kampanya geri sayımının bitiş tarihi (ISO-8601). Geçtiğinde sayaç gizlenir |

Değerler `App\Core\Env` sınıfıyla okunur; `config/app.php` ve `config/database.php` yalnızca bu sınıf üzerinden erişir. Kod içinde hiçbir yerde sabit şifre veya bağlantı bilgisi yoktur.

---

## 6. İletişim formu ve güvenlik

Form alanları: Ad Soyad, E-posta, Telefon, Mesaj. `POST /api/contact` JSON veya klasik form payload’ı kabul eder.

Backend katmanında:

- **SQL Injection:** PDO native prepared statements, `ATTR_EMULATE_PREPARES=false`
- **XSS:** girdide HTML etiketleri ve kontrol karakterleri temizlenir; çıktıda `htmlspecialchars` (PHP) ve `textContent` (JS) kullanılır
- **Doğrulama:** boş alan, uzunluk sınırları, e-posta formatı (`FILTER_VALIDATE_EMAIL`), telefon deseni
- **CSRF:** oturuma bağlı token; `X-CSRF-Token` başlığı veya `_token` alanı
- **Bot koruması:** honeypot alanı, IP bazlı rate limit (10 dakikada 5 deneme)
- **Çift gönderim:** `request_token` üzerinde `UNIQUE` kısıt
- **Bilgi sızıntısı:** kullanıcıya SQL hatası veya stack trace gösterilmez, hata `error_log` ile kaydedilir
- **HTTP başlıkları:** CSP, `X-Frame-Options`, `X-Content-Type-Options`, `Referrer-Policy`, `Permissions-Policy`

Frontend tarafında Fetch API sayfa yenilemeden gönderir; başarıda yeşil, hatada bordo vurgulu glassmorphic toast gösterilir ve alan bazlı hata mesajları ilgili girdinin altına yazılır.

---

## 7. Ekstra modüller ve gerekçeleri

| Modül                         | Neden eklendi                                                                                                                                                                                                                                                                                                                               |
| ----------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **Yönlendirmeli chatbot**     | Saat, bilet, atölye kaydı, ulaşım, fotoğraf ve grup ziyareti gibi en sık sorulan soruları formu doldurmadan yanıtlar; dönüşüm öncesi sürtünmeyi azaltır. Serbest metin ve harici servis yoktur: sorular sunucuda render edilir, yanıtlar `textContent` ile eklenir. Yanıt yetmezse forma, `WHATSAPP_URL` tanımlıysa WhatsApp’a yönlendirir. |
| **Kampanya geri sayımı**      | Dönemsel atölye kayıtlarında zaman bilgisini görünür kılar. Tarih `.env`’den gelir, geçtiğinde sayaç otomatik gizlenir; sahte aciliyet üretmez.                                                                                                                                                                                             |
| **Sosyal kanıt**              | Animasyonlu sayaçlar ve perspektif yörüngeli yorum kartları müzenin ölçeğini hızlı anlatır. İçerik prototip verisidir; yayında gerçek, izinli yorumlarla değiştirilmelidir.                                                                                                                                                                 |
| **Destekçi şeridi**           | Kurumsal güven sinyali. Minimal, arka plansız, sağdan sola kesintisiz akar; `prefers-reduced-motion` tercihinde statik sarılır. Veriler örnektir.                                                                                                                                                                                           |
| **Card-to-post sergi arşivi** | “Önceki Sergiler” kapakları perspektifli bir raf gibi dizilir; seçilen kapak mekânsal sürekliliği koruyan bir geçişle detay görünümüne dönüşür, önceki/sonraki kontrolleriyle arşivde gezilir. Görseller kullanım boyutuna göre küçültülmüş, büyük görsel fareyle üstüne gelindiğinde önden yüklenir.                                       |
| **Atölye odak geçişi**        | Seçilen program FLIP tekniğiyle ilk konuma taşınır ve ayrıntısı açılır; karşılaştırma bağlamı korunur.                                                                                                                                                                                                                                      |
| **Kaydırmaya duyarlı navbar** | Hero üzerinde şeffaf başlar, kaydırmada tam genişlikte cam çerçeveye geçer; içeriğin önüne geçmeden okunabilirliği korur.                                                                                                                                                                                                                   |
| **Özgün logo**                | Çizgisel müze cephesi (alınlık, volütler, sütunlar, kemer) ve Mersin’in Akdeniz kimliğine gönderme yapan bordo dalga. `logo.svg` navbar’da, `favicon.svg` koyu zeminli sürümüyle sekmede kullanılır.                                                                                                                                        |
