<?php

namespace App\Entity;

use App\Repository\LogEntryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LogEntryRepository::class)]
class LogEntry implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $title = '';

    #[ORM\Column(nullable: true)]
    private ?int $year = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $genre = null;

    #[ORM\Column(type: 'float')]
    private float $rating = 0.0;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $review = null;

    #[ORM\Column(type: 'date_immutable')]
    private \DateTimeImmutable $watchedAt;

    public function __construct()
    {
        $this->watchedAt = new \DateTimeImmutable();
    }

    public function isHighlyRated(): bool
    {
        return $this->rating >= 4.0;
    }

    public function getId(): ?int { return $this->id; }
    public function getTitle(): string { return $this->title; }
    public function setTitle(string $title): self { $this->title = $title; return $this; }
    public function getYear(): ?int { return $this->year; }
    public function setYear(?int $year): self { $this->year = $year; return $this; }
    public function getGenre(): ?string { return $this->genre; }
    public function setGenre(?string $genre): self { $this->genre = $genre; return $this; }
    public function getRating(): float { return $this->rating; }
    public function setRating(float $rating): self { $this->rating = $rating; return $this; }
    public function getReview(): ?string { return $this->review; }
    public function setReview(?string $review): self { $this->review = $review; return $this; }
    public function getWatchedAt(): \DateTimeImmutable { return $this->watchedAt; }
    public function setWatchedAt(\DateTimeImmutable $watchedAt): self { $this->watchedAt = $watchedAt; return $this; }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'year' => $this->year,
            'genre' => $this->genre,
            'rating' => $this->rating,
            'review' => $this->review,
            'watchedAt' => $this->watchedAt->format('Y-m-d'),
        ];
    }
}
