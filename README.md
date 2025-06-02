# Zoo Arcadia 🐾 - Projet Symfony Fullstack

Bienvenue sur le projet **Zoo Arcadia**, développé dans le cadre du titre professionnel DWWM (Développeur Web et Web Mobile). Ce projet est une application web de gestion de zoo, avec une partie publique (visiteurs) et une partie privée (admin et employés).

---

## 🌐 Fonctionnalités principales

- 🧑‍💼 Connexion sécurisée avec redirection selon le rôle (admin/employé)
- 📋 Tableau de bord pour les employés, personnalisé selon leur poste
- 🐅 Gestion complète des animaux, enclos, visites, employés (EasyAdmin)
- 💬 Avis visiteurs en temps réel via Firebase
- 📞 Formulaire de contact connecté à Firebase
- 🔒 Sécurité front & back avancée
- 📱 Design responsive et optimisé

---

## 🔐 Sécurité & Tests

### Sécurité front et back :

- ✅ **Protection CSRF** : active sur tous les formulaires via `form_start()`
- ✅ **Rate Limiting** (anti-spam) : appliqué dans `ContactController` avec `$limiter->consume()`
- ✅ **Validation & Regex** : champs du formulaire contact sécurisés dans `ContactType.php`
- ✅ **Sanitisation automatique** : gérée par Symfony
- ✅ **Hachage des mots de passe** : via `UserPasswordHasherInterface` dans `EmployeCrudController`
- ✅ **Rôles utilisateurs** : `ROLE_ADMIN` et `ROLE_EMPLOYE`
- ✅ **Redirection post-login** : gérée via `LoginSuccessHandler.php`

---

## 🧠 Architecture et outils

- 🧱 Symfony 6 / PHP 8.3
- 🐘 Base de données PostgreSQL
- 📁 EasyAdmin pour l’administration
- 🔥 Firebase (Realtime Database) pour les avis et messages
- 🐳 Docker (via VS Code) pour le conteneur PostgreSQL
- 🎨 Figma pour les maquettes
- 📋 Trello pour la gestion du projet

---

## ♿️ Accessibilité, SEO & Performance

- ✅ **Images optimisées** et compressées
- ✅ **Balises meta SEO** dans le `<head>`
- ✅ **Structure HTML sémantique** (`<section>`, `<main>`, `<nav>`, etc.)
- ✅ **Responsive** sur mobile, tablette et desktop
- ✅ **Polices lisibles**, contraste renforcé, navigation claire

---

## 🍪 RGPD & Cookies

- ✅ **Bandeau cookie** affiché au premier chargement
- ✅ **Consentement obligatoire** pour utiliser Firebase
- ⚠️ **Pas de reCAPTCHA sur la page contact** (problème d'intégration non résolu)
- ✅ **Token CSRF** actif sur le formulaire de contact

---

## 🛠️ Lancer le projet en local

```bash
git clone https://github.com/TON-UTILISATEUR/zoo-arcadia.git
cd zoo-arcadia
composer install
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
symfony server:start

