# Google Sheets Integration Laravel Project

Bu loyiha Laravel framework yordamida Google Sheets bilan integratsiya qilish uchun yaratilgan. Loyiha Google Sheets dan ma'lumotlarni o'qish, ma'lumotlarni boshqarish va ularni ma'lumotlar bazasida saqlash imkoniyatlarini taqdim etadi.

## Loyiha haqida

Bu Laravel 12 asosida qurilgan web-ilova bo'lib, quyidagi asosiy funksiyalarga ega:

- **Google Sheets integratsiyasi**: Google Sheets API orqali ma'lumotlarni o'qish va yozish
- **Ma'lumotlar boshqaruvi**: DataItem modeli orqali ma'lumotlarni CRUD operatsiyalari
- **Status boshqaruvi**: Ma'lumotlarni ALLOWED/PROHIBITED statuslari bilan boshqarish
- **Queue tizimi**: Background job'lar uchun queue worker
- **Schedule tizimi**: Avtomatik vazifalar uchun scheduler
- **Docker containerization**: To'liq Docker muhitida ishlash

## Texnik xususiyatlar

- **Framework**: Laravel 12
- **PHP**: 8.2+
- **Database**: MySQL 8.0
- **Web Server**: Nginx
- **Queue**: Laravel Queue with Supervisor
- **Scheduler**: Laravel Task Scheduling
- **Google API**: Google Sheets API v2

## Docker orqali ishga tushirish

### 1. Loyihani klonlash va o'rnatish

```bash
# Loyihani klonlash
git clone <repository-url>
```
```bash
# Loyiha katologiga o'tish
cd googlesheets
```
```bash
# Docker orqali ishga tushirish
make install
```



### 3. Google Sheets API sozlamalari

1. Google Cloud Console da yangi loyiha yarating
2. Google Sheets API ni faollashtiring
3. Service Account yarating va JSON credentials faylini yuklab oling
4. `storage/app/sheets/credentials.json` papkasiga credentials faylini joylashtiring

### 4. Ma'lumotlar bazasini sozlash

```bash
# Migration'larni ishga tushirish
make artisan-migrate
```
```bash

# Ma'lumotlarni seed qilish (ixtiyoriy)
make artisan-db-seed
```

## Foydalanish

### Asosiy komandalar

Loyihada barcha komandalar Makefile orqali boshqariladi:

```bash
# Yordam ko'rsatish
make help
```
```bash

# Docker container'ga kirish
make bash
```
```bash
# Log'larni ko'rish
make logs
```
```bash

# Queue worker'ni ishga tushirish
make queue-work
```
```bash

# Google Sheets dan ma'lumotlarni o'qish
make artisan-fetch [count]
```

### Web interfeys

Ishga tushgandan so'ng quyidagi URL orqali kirishingiz mumkin:
- **Asosiy sahifa**: http://localhost:8084
- **Data Items**: http://localhost:8084/data-items
- **Google Sheets ma'lumotlarini o'qish**: http://localhost:8084/fetch
- **Google Sheets URL manzilini interfeys orqali kiriting**: http://localhost:8084/data-items eng pastda joylashgan 

### Google Sheets ma'lumotlarini o'qish

```bash
# Barcha ma'lumotlarni o'qish
make artisan-fetch
```
```bash

# Faqat 10 ta qatorni o'qish
make artisan-fetch 10
```

## Loyiha tuzilishi

```
app/
├── Clients/           # Google Sheets API client
├── Console/           # Artisan komandalari
├── Http/              # Controllers va Requests
├── Jobs/              # Queue job'lar
├── Models/            # Eloquent modellar
├── Observers/         # Model observer'lar
├── Services/          # Business logic
└── StatusEnum.php     # Status enum

database/
├── migrations/        # Database migration'lar
├── seeders/          # Database seeder'lar
└── factories/        # Model factory'lar

docker/               # Docker konfiguratsiyalari
├── php/             # PHP Dockerfile va sozlamalar
└── nginx/           # Nginx konfiguratsiyasi
```

## Muhim fayllar

- **GoogleSheetClient.php**: Google Sheets API bilan ishlash
- **DataItem.php**: Asosiy ma'lumotlar modeli
- **FetchGoogleSheetsData.php**: Google Sheets dan ma'lumotlarni o'qish komandasi
- **DataItemController.php**: Web interfeys controller'i
- **docker-compose.yml**: Docker container'lar konfiguratsiyasi

## Xatoliklarni tuzatish

### Umumiy muammolar

1. **Docker container'lar ishlamayapti**:
   ```bash
   make down
   make rebuild
   ```

2. **Queue worker ishlamayapti**:
   ```bash
   make queue-work
   make queue-logs
   ```

3. **Google Sheets API xatosi**:
   - Credentials faylini tekshiring
   - Google Cloud Console da API faolligini tekshiring

### Log'larni ko'rish

```bash
# Barcha log'larni ko'rish
make logs-all
```
```bash

# Laravel log'lari
make logs-laravel
```
```bash
# Supervisor log'lari
make supervisor-logs
```


**Eslatma**: Bu loyiha development muhitida ishlatish uchun mo'ljallangan. Production muhitida qo'llashdan oldin xavfsizlik sozlamalarini tekshiring.
