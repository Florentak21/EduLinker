# EduLinker

**EduLinker** est une application web de gestion des affectations d'encadreurs aux étudiants dans le cadre de leur mémoire de fin d'études.

---

##  Fonctionnalités principales

###  Étudiants
- Inscription avec génération automatique de matricule
- Connexion
- Soumission de thème avec cahier des charges (CDC)
- Saisie du binôme via son matricule
- Suivi de l’affectation
- Relance en cas de non-affectation

###  Enseignants
- Connexion
- Accès à la liste des étudiants encadrés

###  Administrateur
- Connexion
- Gestion des domaines d'études
- Création des enseignants
- Affectation manuelle des encadreurs
- Statistiques

---

##  Structure du projet

### Le répertoire app contient le code de l'application découpé comme suit:
    - Controllers - ce répertoire contient les controllers.
    - Core - ce répertoire contient le code de base de certain composant de base (le controller ou le model de base par example).
    - Middleware - ce répertoire contient les middlewares.
    - Models - ce répertoire contient les models.
  
### Le répertoire database contient tout ce qui a trait à la base de données.

### Le répertoire public contient les assets et les resources public:
    - css
    - js
    - index.php - Point d'entrée de l'application.

### Le répertoire routes contient les routes de l'application.

### Le répertoire storage contient les fichiers(CDC) uploadés par les students.

### Le répertoire views contient les views de l'application.


##  Mise en route du projet

### 1. Cloner le dépôt
    git clone https://github.com/Florentak21/EduLinker.git
    cd EduLinker

### 2. Installer composer et les différentes dépendances et gérer l'autoloading
    composer install
    composer dump-autoload