const express = require('express');
const { fetchLogs, SYMFONY_API_URL } = require('./api-client');
const { summary, byGenre, top } = require('./stats');

const app = express();
const PORT = process.env.PORT || 3000;

// CORS : autorise le front (servi sur un autre port) à appeler ce service.
app.use((req, res, next) => {
  res.header('Access-Control-Allow-Origin', '*');
  next();
});

app.get('/health', (req, res) => {
  res.json({ status: 'ok', dependsOn: SYMFONY_API_URL });
});

function statsRoute(compute) {
  return async (req, res) => {
    try {
      const logs = await fetchLogs();
      res.json(compute(logs));
    } catch (err) {
      res.status(502).json({ error: "Impossible de joindre l'API des films", detail: err.message });
    }
  };
}

app.get('/stats/summary', statsRoute(summary));
app.get('/stats/by-genre', statsRoute(byGenre));
app.get('/stats/top', statsRoute((logs) => top(logs, 5)));

app.listen(PORT, () => {
  console.log(`Service de stats démarré sur le port ${PORT}`);
  console.log(`Il interroge l'API films sur ${SYMFONY_API_URL}`);
});
