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
- Gestion des enseignants
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
    ***git clone https://github.com/Florentak21/EduLinker.git***
    ***cd EduLinker***

### 2. Installer les différentes dépendances et recharger les classes.
    ***composer install***
    ***composer dump-autoload***

### 3. Base de données (mysql)
    - Configurer la base de données
      - Créer la base de données avec le nom **edu_linker**
      - éditer le fichier **config/database.php** en mettant vos identifiants mysql (username et password).
    - Jouer les migrations et les seeders(pour préremplir la base de données)
      - en étant à la racine du projet, exécuter:
        - ***vendor/bin/phinx migrate***
        - ***vendor/bin/seed:run***
  
### 4. Démarrage de l'application
    - executer la commande: ***php -S localhost:8000 -t public***