# Système de Gestion de Colis - Nouvelles Fonctionnalités

## Vue d'ensemble

Ce système complet de gestion de livraison comprend :
- ✅ Suivi de colis pour destinataires et expéditeurs
- ✅ Gestion des colis avec workflow d'affectation
- ✅ Système de dépôts multiples (A, B, C, D...)
- ✅ Photos obligatoires à chaque étape
- ✅ Calcul automatique du poids volumétrique
- ✅ Dashboard super admin complet
- ✅ Historique détaillé des livreurs

## 🚀 Fonctionnalités Principales

### 1. Dashboard Public de Tracking
**Route:** `/tracking`

Les destinataires et expéditeurs peuvent :
- Entrer l'ID du colis (ex: COL-2026-0001)
- Voir toutes les étapes du colis
- Visualiser les photos prises à chaque étape
- Connaître la localisation actuelle (dépôt)
- Identifier le livreur assigné

### 2. Gestion Admin des Colis
**Route:** `/admin/colis`

#### Création de Colis
L'admin peut créer un colis avec :
- **Informations produit:**
  - Nom, description
  - Poids réel (kg)
  - Dimensions: Longueur, Largeur, Hauteur (cm)
  - **Poids volumétrique calculé automatiquement:** `(L × l × h) / 5000`
  
- **Expéditeur:**
  - Nom, email, téléphone
  - Société, matricule fiscale
  
- **Destinataire:**
  - Nom, prénom, téléphone
  - Adresse complète, ville
  - 3 derniers chiffres du CIN

- **Localisation:**
  - Dépôt source
  - Dépôt de destination (optionnel)

#### Workflow du Colis
1. **Création** → Statut: `en_attente` (reste au dépôt)
2. **Réception au dépôt** → Admin prend une photo 📸
3. **Affectation à une flotte** → Admin choisit un camion
4. **Prise en charge** → Livreur prend une photo 📸
5. **Transit** → Vers dépôt intermédiaire ou destinataire final
6. **Réception dépôt intermédiaire** → Admin du dépôt prend une photo 📸
7. **Livraison finale** → Livreur prend une photo 📸

### 3. Système de Dépôts
Les dépôts sont des points de transit identifiés par des codes (A, B, C, D...).

**Dépôts créés par défaut:**
- **A** - Dépôt Central A (Tunis)
- **B** - Dépôt Centre B (Sfax)
- **C** - Dépôt Nord C (Ariana)
- **D** - Dépôt Sud D (Sousse)

**Scénario typique:**
- Colis créé au dépôt A
- Livreur transporte du dépôt A au dépôt B
- Admin du dépôt B réceptionne et photographie
- Autre livreur prend du dépôt B vers le destinataire final

### 4. Interface Livreur
**Route:** `/livreur/livraisons`

Le livreur peut :
- **Démarrer/Terminer une tournée**
- **Voir ses colis assignés**
- **Prendre en charge** avec photo 📸
- **Transférer vers un dépôt** avec photo 📸
- **Marquer comme livré** avec photo 📸
- **Voir son historique** de livraisons

### 5. Super Admin Dashboard
**Route:** `/super-admin/dashboard`

Vue complète avec :
- **Statistiques globales**
  - Total colis, en attente, en transit, livrés
  - Livreurs actifs/inactifs
  - Flottes actives
  - Dépôts actifs

- **Vue détaillée de tous les colis**
  - Code, statut, dépôt actuel
  - Nombre d'étapes
  - Nombre de photos
  - Livreur assigné

- **Gestion des livreurs**
  - Performance de chaque livreur
  - Nombre de colis livrés
  - Camion assigné

- **Relations Flottes-Livreurs**
  - Quel livreur conduit quel camion
  - Statut de chaque flotte
  - Colis actifs par flotte

- **Historique détaillé des tournées**
  - Heure début/fin
  - Durée de chaque tournée
  - Nombre de colis (total, livrés, en cours)
  - Distance parcourue

## 📊 Modèles de Données

### Colis
- Code unique auto-généré (COL-2026-0001)
- Poids réel + poids volumétrique
- Dimensions (L × l × h)
- Infos expéditeur/destinataire
- Dépôts (source, actuel, destination)
- Statut: `en_attente`, `affecte`, `en_transit`, `en_depot`, `livre`
- QR Code généré automatiquement

### Etape
Chaque action sur un colis crée une étape avec :
- Type (création, réception_depot, prise_en_charge, transfert, livraison)
- Photos (array)
- Commentaire
- Date et heure
- Utilisateur qui a effectué l'action
- Dépôt de départ/arrivée
- Géolocalisation (latitude/longitude)

### HistoriqueLivreur
Enregistre chaque tournée :
- Code tournée
- Heure début/fin
- Liste des colis transportés
- Nombre de colis livrés
- Distance parcourue
- Dépôts visités

### Depot
Points de transit :
- Code (A, B, C...)
- Nom, adresse, ville
- Admin responsable
- Téléphone

## 📸 Système de Photos

### Photos Obligatoires
1. **Admin** - Réception au dépôt
2. **Livreur** - Prise en charge
3. **Admin dépôt intermédiaire** - Réception au dépôt
4. **Livreur** - Livraison finale

Les photos sont stockées dans `storage/app/public/etapes/photos/`

### Visualisation
- Dashboard public: visible par tous
- Super admin: accès à toutes les photos
- Livreur: photos de ses livraisons

## 💡 Utilisation

### Pour créer un nouveau colis:
1. Se connecter en tant qu'admin
2. Aller sur `/admin/colis`
3. Cliquer sur "Nouveau Colis"
4. Remplir le formulaire (dimensions pour calcul poids volumétrique)
5. Le colis reste `en_attente` au dépôt

### Pour affecter à une flotte:
1. Dans la liste des colis
2. Sélectionner un camion dans le dropdown
3. Le statut passe à `affecte`
4. Le livreur peut maintenant le prendre en charge

### Pour livrer un colis:
1. Livreur se connecte
2. Démarre une tournée
3. Prend en charge avec photo
4. Transporte (option transfert vers dépôt ou livraison directe)
5. Photographie à chaque étape
6. Marque comme livré avec photo finale

### Pour suivre un colis:
1. Aller sur `/tracking`
2. Entrer le code du colis
3. Voir toutes les étapes avec photos

## 🔄 Workflow Complet

```
CRÉATION (Admin)
    ↓ [Photo réception]
EN_ATTENTE (au dépôt)
    ↓ [Affectation flotte]
AFFECTÉ (à un camion)
    ↓ [Photo prise en charge]
EN_TRANSIT (avec livreur)
    ↓ [Option A: Transfert dépôt] → [Photo réception dépôt] → EN_DEPOT
    ↓ [Option B: Livraison directe]
    ↓ [Photo livraison]
LIVRÉ
```

## 🛠️ Routes Disponibles

### Public
- `GET /tracking` - Suivi de colis

### Admin
- `GET /admin/colis` - Gestion des colis
- `POST /admin/colis/create` - Créer un colis
- `POST /admin/colis/{id}/recevoir` - Recevoir au dépôt (photo)
- `POST /admin/colis/{id}/affecter` - Affecter à une flotte

### Livreur
- `GET /livreur/livraisons` - Mes livraisons
- `POST /livreur/tournee/start` - Démarrer tournée
- `POST /livreur/tournee/end` - Terminer tournée
- `POST /livreur/colis/{id}/prendre-en-charge` - Prendre en charge (photo)
- `POST /livreur/colis/{id}/transferer` - Transférer (photo)
- `POST /livreur/colis/{id}/livrer` - Livrer (photo)

### Super Admin
- `GET /super-admin/dashboard` - Dashboard complet

## 📲 Système SMS

### Notifications Automatiques
Le système envoie des SMS en **arabe tunisien (darija)** via **TextFlow API** :

- **À l'expéditeur** : Confirmation de réception + lien de tracking
- **Au destinataire** : Notification de colis en route + lien de tracking

### Fournisseurs Supportés
| Fournisseur | Statut |
|:---|:---:|
| TextFlow | ✅ Actif |
| Infobip | ⚙️ Configuré |
| Twilio | 📦 Disponible |
| Vonage | 📦 Disponible |
| Orange TN | 📦 Disponible |

### Sélecteur de Code Pays
- Interface Alpine.js avec recherche et défilement
- 70+ pays avec drapeaux emoji
- Présent dans tous les formulaires téléphoniques
- Code pays par défaut : +216 (Tunisie)

### Configuration
```env
SMS_PROVIDER=textflow
TEXTFLOW_API_KEY=votre_cle_api
```

> **Note :** Les SMS sont commentés en développement. Décommentez `$this->sendTrackingSms()` dans `ColisManagement.php` et `CreateProduit.php` pour activer.

---

## 🎯 Prochaines Améliorations Possibles

- ~~Notifications SMS/Email aux destinataires~~ ✅ **FAIT** (SMS en arabe tunisien via TextFlow)
- Signature électronique du destinataire
- Scan de QR codes
- Optimisation d'itinéraires
- Rapports et statistiques avancées
- Export PDF des bordereaux de livraison
- Intégration GPS en temps réel
- API pour suivi externe

## 📝 Notes Importantes

1. **Poids volumétrique:** Utilisé pour la facturation comme FedEx
2. **Photos obligatoires:** Preuve à chaque étape comme les grandes sociétés
3. **Dépôts multiples:** Permet le transit inter-dépôts
4. **Historique complet:** Super admin voit tout
5. **Tournées:** Tracking automatique des performances livreur

---

**Version:** 1.0
**Date:** 24 Janvier 2026
