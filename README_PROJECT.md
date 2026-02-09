# Delivery Platform - Laravel 11

Application professionnelle de gestion de livraison.

## Stack Technique
- PHP 8.2+ / Laravel 11
- MySQL 8.0
- Livewire 3 + Alpine.js
- Tailwind CSS 3 + Flowbite

## Installation

1. Installer les dépendances PHP :
```bash
composer install
```

2. Installer les dépendances JS :
```bash
npm install
```

3. Configurer `.env` (base de données : `delivery_platform`).

4. Migrer et Seeder la BDD :
```bash
php artisan migrate:fresh --seed
```

## Lancement

1. Lancer le serveur Vite (pour les styles/JS) :
```bash
npm run dev
```

2. Lancer le serveur Laravel :
```bash
php artisan serve
```

## Comptes de Démonstration

### 1. Super Admin
- **Email**: `superadmin@delivery.com`
- **Password**: `password`
- **Rôle**: Vue globale sur tout le système.

### 2. Admin (Société de Transport)
- **Email**: `admin@delivery.com`
- **Password**: `password`
- **Rôle**: Gestion des colis, création, gestion de flotte.

### 3. Livreur (Mobile First)
- **Email**: `livreur1@delivery.com`
- **Password**: `password`
- **Rôle**: Scan des colis, mise à jour des statuts.
- **Camion Assigné**: Mercedes Actros (Colis assignés automatiquement via le seed).

## Fonctionnalités Clés
- **Scanner QR**: Le liveur peut scanner les colis via sa caméra.
- **Workflow**: Validation de statut (Valide -> Préparé -> En Route -> Livré).
- **Sécurité**: QR Code vérifié pour appartenance au camion.
- **SMS**: Notifications automatiques en arabe tunisien via TextFlow API.
- **Codes Pays**: Sélecteur interactif avec 70+ pays (Alpine.js).

## Configuration SMS

Ajouter dans `.env` :
```env
SMS_PROVIDER=textflow
TEXTFLOW_API_KEY=votre_cle_api
```
