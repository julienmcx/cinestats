// Fonctions pures : elles prennent une liste d'entrées et renvoient des stats.
// Aucune dépendance réseau ici -> faciles à tester avec Jest.

function round1(n) {
  return Math.round(n * 10) / 10;
}

function summary(entries) {
  const total = entries.length;
  const sum = entries.reduce((acc, e) => acc + Number(e.rating), 0);
  return {
    totalFilms: total,
    averageRating: total > 0 ? round1(sum / total) : 0,
    highlyRatedCount: entries.filter((e) => Number(e.rating) >= 4).length,
  };
}

function byGenre(entries) {
  const groups = {};
  for (const e of entries) {
    const g = e.genre || 'Inconnu';
    if (!groups[g]) {
      groups[g] = { genre: g, count: 0, sum: 0 };
    }
    groups[g].count += 1;
    groups[g].sum += Number(e.rating);
  }
  return Object.values(groups)
    .map((g) => ({
      genre: g.genre,
      count: g.count,
      averageRating: round1(g.sum / g.count),
    }))
    .sort((a, b) => b.count - a.count);
}

function top(entries, limit = 5) {
  return [...entries]
    .sort((a, b) => Number(b.rating) - Number(a.rating))
    .slice(0, limit)
    .map((e) => ({ title: e.title, rating: e.rating, year: e.year }));
}

module.exports = { summary, byGenre, top };
