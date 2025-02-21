# PROJET DOC 2 WHEELS

# Informations du projet

## Contributeurs
**Val-Developpement/Valentin ANGUILLE**

**Ilian92/Ilian IGOUDJIL**

## Liens du projet
**cahier des charges: https://docs.google.com/document/d/1X1TJpQ_bvve_YD1PdIWw4l2T73_WcemHQXh8iWJKUaw/edit?usp=sharing**

**le figma: https://www.figma.com/design/BkW8NzF2BaSfvqJAE7ACh4/Maquette-PEC?node-id=0-1&p=f&t=e02XCC5rrf5Vqoom-0**

**listing des fonctionnalités et attribution de chacune dans un Kanban: https://github.com/orgs/Doc2Wheels-Groupe3-B3Alt/projects/2**

# INITIALISATION DOC 2 WHEELS

## Etape 1: Création du container front-end et base de donnée

```bash
docker compose up -d --build

docker compose exec web composer install

docker compose exec web composer dump-autoload
```

## Etape 2: Lancement du container bdd dans le terminal

```bash
docker exec -it projetPEC-db bash
```

## Etape 3: Utiliser mysql avec le terminal

### Fichier init.sql: backend-php/mysql/db

**Si le container existe mais n'est pas lancé:**

```bash
docker start projetPEC-db
```

**Ensuite, se connecter à postgres et à la base de donnée db:**

```bash
docker exec -it projetPEC-db psql -U user -d db
```

**Pour quitter postgres:**

```bash
exit
```

## Etape 4: Accéder à la partie front-end

**Si le container n'est pas lancé:**

```bash
docker start projetPEC
```

**URL: http://127.0.0.1:8081/homepage**

