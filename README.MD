# 🐾 Kennel Hub

A universal kennel management panel and website built with **Laravel**.
Designed for breeders to manage dogs, litters, galleries, pedigrees, and client inquiries.

## ✨ Features
- Photo galleries and pedigrees
- Articles/news module with SEO support
- Dog and litter catalogs with status (available, reserved, sold)
- Inquiry forms and contact management
- Multilingual support
- Modern responsive design

## 🚀 Tech stack
- Laravel 12
- TailwindCSS
- Vue 3 + Vite

## 📦 Installation
```bash
git clone https://github.com/dsamodurov/kennel-hub.git
cd kennel-hub
composer install
cp .env.example .env
php artisan key:generate
php artisan storage:link
php artisan migrate --seed
npm install && npm run build
php artisan serve
```
