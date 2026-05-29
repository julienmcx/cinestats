// Va chercher les films dans l'API Symfony.
// L'URL est configurable via une variable d'environnement :
//  - en local : http://127.0.0.1:8000
//  - dans Docker : http://symfony-api:8000 (nom du service docker-compose)

const SYMFONY_API_URL = process.env.SYMFONY_API_URL || 'http://127.0.0.1:8000';

async function fetchLogs() {
  const res = await fetch(`${SYMFONY_API_URL}/api/logs`);
  if (!res.ok) {
    throw new Error(`L'API films a répondu ${res.status}`);
  }
  return res.json();
}

module.exports = { fetchLogs, SYMFONY_API_URL };
