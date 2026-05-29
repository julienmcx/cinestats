const { summary, byGenre, top } = require('../src/stats');

const sample = [
  { title: 'Dune', genre: 'Sci-Fi', rating: 4.5, year: 2021 },
  { title: 'Arrival', genre: 'Sci-Fi', rating: 5, year: 2016 },
  { title: 'Cats', genre: 'Comédie', rating: 1.5, year: 2019 },
];

describe('summary', () => {
  test('compte les films et calcule la moyenne', () => {
    const r = summary(sample);
    expect(r.totalFilms).toBe(3);
    expect(r.averageRating).toBe(3.7);
    expect(r.highlyRatedCount).toBe(2);
  });

  test('renvoie des zéros quand il n y a aucun film', () => {
    expect(summary([])).toEqual({ totalFilms: 0, averageRating: 0, highlyRatedCount: 0 });
  });
});

describe('byGenre', () => {
  test('regroupe par genre et trie par nombre décroissant', () => {
    const r = byGenre(sample);
    expect(r[0]).toEqual({ genre: 'Sci-Fi', count: 2, averageRating: 4.8 });
    expect(r[1].genre).toBe('Comédie');
  });

  test('range les films sans genre dans "Inconnu"', () => {
    const r = byGenre([{ title: 'X', rating: 3 }]);
    expect(r[0].genre).toBe('Inconnu');
  });
});

describe('top', () => {
  test('renvoie les mieux notés en premier', () => {
    const r = top(sample, 2);
    expect(r).toHaveLength(2);
    expect(r[0].title).toBe('Arrival');
    expect(r[1].title).toBe('Dune');
  });
});
