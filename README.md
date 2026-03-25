# EventPass

Application web de gestion d'événements et de billetterie en ligne. Développée avec Laravel 12 et Filament 3.

---

## Stack technique

- **Backend** : Laravel 12 (PHP 8.2+)
- **Base de données** : MySQL
- **Panel admin** : Filament 3
- **Frontend** : Blade + Tailwind CSS 4

---

## Prérequis

- PHP >= 8.2
- Composer
- Node.js >= 18
- MySQL

---

## Installation

```bash
git clone https://github.com/Darrylwin/EventPass eventpass
cd eventpass

cp .env.example .env
```

Configurez votre base de données dans `.env` :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=eventpass
DB_USERNAME=root
DB_PASSWORD=
```

```bash
composer install

php artisan key:generate

php artisan migrate

php artisan db:seed

npm install && npm run build

php artisan storage:link
```

---

## Lancer le projet en développement

```bash
php artisan serve
npm run dev
```

L'application est accessible sur `http://localhost:8000`.

---

## Comptes de test

| Rôle | Email | Mot de passe |
|---|---|---|
| Administrateur | admin@eventpass.com | password |
| Organisateur | orga@eventpass.com | password |
| Participant | participant@eventpass.com | password |

---

## Accès par rôle

| Rôle | URL |
|---|---|
| Admin | `/admin` (Filament) |
| Organisateur | `/organisateur/dashboard` |
| Participant | `/participant/dashboard` |

La route `/dashboard` redirige automatiquement selon le rôle de l'utilisateur connecté.

---

## Structure du projet

```
app/
├── Filament/
│   ├── Resources/
│   │   ├── EventResource.php       # Gestion des événements (admin)
│   │   └── UserResource.php        # Gestion des utilisateurs (admin)
│   └── Widgets/
│       └── StatsOverview.php       # Statistiques globales
├── Http/
│   ├── Controllers/
│   │   └── Auth/
│   │       ├── LoginController.php
│   │       ├── RegisterController.php
│   │       └── LogoutController.php
│   └── Middleware/
│       └── RoleMiddleware.php      # Protection des routes par rôle
└── Models/
    ├── User.php
    ├── Event.php
    └── Registration.php

database/
├── migrations/
└── seeders/
    ├── UserSeeder.php
    └── EventSeeder.php
```

---

## Modèle de données

### users
| Colonne | Type | Description |
|---|---|---|
| id | bigint | Clé primaire |
| name | string | Nom de l'utilisateur |
| email | string | Email unique |
| password | string | Mot de passe hashé |
| role | enum | `admin`, `organisateur`, `participant` |

### events
| Colonne | Type | Description |
|---|---|---|
| id | bigint | Clé primaire |
| organizer_id | foreignId | Référence vers users |
| title | string | Titre de l'événement |
| description | text | Description |
| starts_at | datetime | Date et heure de début |
| location | string | Lieu |
| capacity | integer | Nombre de places |
| price | decimal | Tarif (0 = gratuit) |
| image_path | string | Chemin de l'image |
| status | enum | `brouillon`, `publié`, `annulé`, `terminé` |

### registrations
| Colonne | Type | Description |
|---|---|---|
| id | bigint | Clé primaire |
| event_id | foreignId | Référence vers events |
| user_id | foreignId | Référence vers users |
| pass_code | string | Code unique du pass (8 caractères) |
| status | enum | `validé`, `annulé` |
| registered_at | timestamp | Date d'inscription |

---

## Rôles et permissions

**Administrateur** - accès via Filament (`/admin`) :
- Modérer les événements (publier, annuler)
- Gérer les utilisateurs
- Consulter les statistiques globales

**Organisateur** - accès via `/organisateur` :
- Créer et gérer ses événements
- Consulter la liste des inscrits
- Invalider ou réactiver un pass

**Participant** - accès via `/participant` :
- Parcourir les événements à venir
- S'inscrire à un événement
- Récupérer et consulter son pass numérique

---

## Équipe

| Membre   | Responsabilité |
|----------|---|
| LOGOSSOU | Auth, administration, panel Filament |
| BOGUE    | Gestion des événements (organisateur) |
| KOKODOKO | Inscriptions et pass (participant) |
| OSSEYI   | Interface publique et UX |
