# Blog

> Blog est un projet de formation de la formation Développeur PHP Symfony d'OpenClassrooms. Il s'agit d'un blog personnel.

Étape du projet : 100%

---

## Prérequis

Avant de commencer, assurez-vous d'avoir installé les prérequis suivants sur votre système. Les instructions sont fournies pour **Windows, Mac et Linux**.

### 1. **Git**

Git est nécessaire pour cloner le projet depuis le dépôt.

#### Installation sur **Windows** :
1. Téléchargez Git depuis le site officiel : [https://git-scm.com/download/win](https://git-scm.com/download/win).
2. Exécutez le programme d'installation et suivez les instructions.
3. Une fois installé, ouvrez un terminal (Git Bash) et vérifiez l'installation avec :\
   ```git --version```

#### Installation sur **Mac** :
1. Ouvrez un terminal.
2. Installez Git via Homebrew (si Homebrew n'est pas installé, suivez les instructions sur [https://brew.sh/]
(https://brew.sh/) :\
   ```brew install git```
3. Vérifiez l'installation avec :\
   ```git --version```

#### Installation sur **Linux** (Ubuntu/Debian) :
1. Ouvrez un terminal.
2. Installez Git avec la commande :\
   ```sudo apt update```\
   ```sudo apt install git```
3. Vérifiez l'installation avec :\
   ```git --version```

---

### 2. **PHP 8.3**

Le projet nécessite PHP 8.3.

#### Installation sur **Windows** :
1. Téléchargez PHP 8.3 depuis le site officiel : [https://windows.php.net/download/](https://windows.php.net/download/).
2. Extrayez l'archive dans un répertoire (par exemple, `C:\php`).
3. Ajoutez le chemin du répertoire `C:\php` à la variable d'environnement `PATH`.
4. Vérifiez l'installation avec :\
   ```php -v```

#### Installation sur **Mac** :
1. Installez PHP 8.3 via Homebrew :\
   ```brew install php@8.3```
2. Ajoutez PHP à votre `PATH` en ajoutant la ligne suivante dans votre fichier `~/.zshrc` ou `~/.bashrc` :\
   ```export PATH="/usr/local/opt/php@8.3/bin:$PATH"```
3. Rechargez votre fichier de configuration :\
   ```source ~/.zshrc```
4. Vérifiez l'installation avec :\
   ```php -v```

#### Installation sur **Linux** (Ubuntu/Debian) :
1. Ajoutez le PPA pour PHP 8.3 :\
   ```sudo apt update```\
   ```sudo apt install software-properties-common```\
   ```sudo add-apt-repository ppa:ondrej/php```\
   ```sudo apt update```
2. Installez PHP 8.3 :\
   ```sudo apt install php8.3```
3. Vérifiez l'installation avec :\
   ```php -v```

---

### 3. **Composer**

Composer est nécessaire pour gérer les dépendances PHP.

#### Installation sur **Windows** :
1. Téléchargez l'installeur Composer depuis le site officiel : [https://getcomposer.org/Composer-Setup.exe](https://getcomposer.org/Composer-Setup.exe).
2. Exécutez l'installeur et suivez les instructions.
3. Vérifiez l'installation avec :\
   ```composer --version```

#### Installation sur **Mac/Linux** :
1. Ouvrez un terminal.
2. Installez Composer globalement avec les commandes suivantes :\
   ```php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"```\
   ```php composer-setup.php --install-dir=/usr/local/bin --filename=composer```\
   ```php -r "unlink('composer-setup.php');"```
3. Vérifiez l'installation avec :\
   ```composer --version```

---

### 4. PostgreSQL
Le projet utilise PostgreSQL comme base de données. Si vous n'utilisez pas Docker, vous devez installer PostgreSQL sur votre machine.

#### Installation sur **Windows** :
1. Téléchargez PostgreSQL depuis le site officiel : [https://www.postgresql.org/download/windows/](https://www.postgresql.org/download/windows/).
2. Exécutez l'installeur et suivez les instructions.
3. Notez le mot de passe administrateur (superuser) et le port (par défaut : 5432).
4. Vérifiez l'installation avec :\
   ```psql --version```

#### Installation sur **Mac** :
1. Installez PostgreSQL via Homebrew :\
   ```brew install postgresql```
2. Démarrez le service PostgreSQL :\
   ```brew services start postgresql```
3. Vérifiez l'installation avec :\
   ```psql --version```

#### Installation sur **Linux** (Ubuntu/Debian) :
1. Installez PostgreSQL avec la commande :\
   ```sudo apt update```\
   ```sudo apt install postgresql```
2. Démarrez le service PostgreSQL :\
   ```sudo systemctl start postgresql```\
   ```sudo systemctl enable postgresql``` (pour démarrer PostgreSQL au démarrage)
3. Vérifiez l'installation avec :\
   ```psql --version```

### 5. **Docker (Optionnel)**

Si vous utilisez Docker pour démarrer le projet, installez Docker, Docker compose et Make.

#### Installation sur **Windows** :
1. Téléchargez Docker Desktop depuis le site officiel : [https://www.docker.com/products/docker-desktop/](https://www.docker.com/products/docker-desktop/).
2. Exécutez l'installeur et suivez les instructions.
3. Vérifiez l'installation avec :\
   ```docker --version```\
   ```docker-compose --version```
4. Installez Make pour Windows depuis le site officiel : [http://gnuwin32.sourceforge.net/packages/make.htm](http://gnuwin32.sourceforge.net/packages/make.htm).
5. Exécutez l'installeur et suivez les instructions.
6. Vérifiez l'installation avec :\
   ```make --version```

#### Installation sur **Mac** :
1. Téléchargez Docker Desktop depuis le site officiel : [https://www.docker.com/products/docker-desktop/](https://www.docker.com/products/docker-desktop/).
2. Exécutez l'installeur et suivez les instructions.
3. Vérifiez l'installation avec :\
   ```docker --version```\
   ```docker-compose --version```
4. Installez Make via Homebrew :\
   ```brew install make```
5. Vérifiez l'installation avec :\
   ```make --version```

#### Installation sur **Linux** (Ubuntu/Debian) :
1. Installez Docker avec les commandes suivantes :\
   ```sudo apt update```\
   ```sudo apt install docker.io```\
   ```sudo systemctl start docker```\
   ```sudo systemctl enable docker```
2. Installez Docker Compose :\
   ```sudo curl -L "https://github.com/docker/compose/releases/download/1.29.2/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose```\
   ```sudo chmod +x /usr/local/bin/docker-compose```
3. Vérifiez l'installation avec :\
   ```docker --version```\
   ```docker-compose --version```
4. Installez Make avec la commande :\
   ```sudo apt install make```
5. Vérifiez l'installation avec :\
   ```make --version```
6. Ajoutez votre utilisateur au groupe Docker :\
   ```sudo usermod -aG docker $USER```
7. Déconnectez-vous et reconnectez-vous pour appliquer les changements.

---

## Installation

### A. **Avec Docker**

1. Clonez le projet :\
   ```git clone <URL_DU_PROJET>```\
   ```cd <NOM_DU_PROJET>```
2. Démarrez le projet avec Docker :\
   ```make start```
3. Ouvrez votre navigateur à l'adresse : [http://localhost](http://localhost).

### B. **Manuellement (sans Docker)**

1. Clonez le projet :\
   ```git clone <URL_DU_PROJET>```\
   ```cd <NOM_DU_PROJET>```
2. Installez les dépendances PHP :\
   ```composer install```
3. Configurez les variables d'environnement dans le fichier `.env` pour la base de données PostgreSQL :\
   ```DATABASE_URL=postgresql://<USER>:<PASSWORD>@<HOST>:<PORT>/<DATABASE>```
4. Créez la base de données et exécutez les migrations :\
   ```php bin/console doctrine:database:create```\
   ```php bin/console doctrine:migrations:migrate --no-interaction```
5. Chargez les fixtures :\
   ```php bin/console doctrine:fixtures:load --append```
6. Démarrez le serveur PHP :\
   ```php -S localhost:8000 -t public```
7. Ouvrez votre navigateur à l'adresse : [http://localhost:8000](http://localhost:8000).

---

## Utilisation

- Une fois sur la page du site, vous pouvez vous inscrire ou vous connecter avec les identifiants suivants :
  - **Utilisateur** : `user@test.com` / `password`
  - **Auteur** : `editor@test.com` / `password`
  - **Administrateur** : `admin@test.com` / `password`
- Le site utilise le service de message gratuit **MAILERSEND** [https://mailersend.com/](https://mailersend.com/). Pour recevoir les e-mails, vous devez vous inscrire sur le site et obtenir une clé API. Ajoutez cette clé dans le fichier `.env` à la variable `MAILERSEND_API_KEY` et `MAILERSEND_MAIL_ADDRESS`. Une clé est déjà fourni dans le projet.

## Utilisation des commandes Doctrine

- Pour créer une nouvelle migration :\
  ```php bin/console make:migration```
- Pour exécuter les migrations :\
  ```php bin/console doctrine:migrations:migrate```
- Pour charger les fixtures :\
  ```php bin/console doctrine:fixtures:load --append```
- Pour supprimer la base de données :\
  ```php bin/console doctrine:database:drop --force```
- Pour mettre à jour la base de données :\
  ```php bin/console doctrine:schema:update --force```

---

## Arrêt

### Avec Docker et Make :
```make down```

### Manuellement avec le serveur PHP :
Arrêtez le serveur PHP en appuyant sur `Ctrl+C` dans le terminal.

---

## Auteur

* **Xavier Lauer** - [Xantoom](https://github.com/Xantoom)
