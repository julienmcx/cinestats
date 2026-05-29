# 🎬 CineStats

Journal de films personnel façon Letterboxd — API REST Symfony + microservice de stats Express, orchestrés avec Docker.

## Architecture
## Prérequis

- Docker 24+ et Docker Compose

## Lancer le projet

```bash
git clone https://github.com/julienmcx/cinestats.git
cd cinestats
docker compose up --build
```

- API Symfony → http://localhost:8000
- Service stats → http://localhost:3000

## Endpoints — API films (Symfony)

| Méthode | Route | Description |
|---------|-------|-------------|
| GET | /api/logs | Liste tous les films |
| GET | /api/logs/{id} | Détail d'un film |
| POST | /api/logs | Ajouter un film |
| PUT | /api/logs/{id} | Modifier un film |
| DELETE | /api/logs/{id} | Supprimer un film |

Exemple :
```bash
curl -X POST http://localhost:8000/api/logs \
  -H "Content-Type: application/json" \
  -d '{"title":"Dune","year":2021,"genre":"Sci-Fi","rating":4.5,"review":"Magnifique"}'
```

## Endpoints — Stats (Express)

| Méthode | Route | Description |
|---------|-------|-------------|
| GET | /stats/summary | Nombre de films, moyenne, top notés |
| GET | /stats/by-genre | Répartition et moyenne par genre |
| GET | /stats/top | Top 5 des mieux notés |

## Tests

```bash
# PHPUnit (Symfony)
cd symfony-api && php bin/phpunit

# Jest (Express)
cd stats-service && npm test
```

## CI/CD

Le pipeline GitHub Actions (`/.github/workflows/ci-cd.yml`) se déclenche à chaque push sur `main` :

1. **test-symfony** — PHPUnit
2. **test-express** — Jest
3. **build-and-push** — Build des images Docker et push sur Docker Hub (si les tests passent)

## Images Docker Hub

- [`julienmcx/cinestats-symfony-api`](https://hub.docker.com/r/julienmcx/cinestats-symfony-api)
- [`julienmcx/cinestats-stats-service`](https://hub.docker.com/r/julienmcx/cinestats-stats-service)

## Déploiement depuis Docker Hub (sans cloner le code)

```bash
curl -o docker-compose.yml https://raw.githubusercontent.com/julienmcx/cinestats/main/docker-compose.yml

# Éditer docker-compose.yml : remplacer "build:" par "image:" pour chaque service
# symfony-api : image: julienmcx/cinestats-symfony-api:latest
# stats-service : image: julienmcx/cinestats-stats-service:latest

docker compose up
```
