<?php

namespace App\Tests\Entity;

use App\Entity\LogEntry;
use PHPUnit\Framework\TestCase;

class LogEntryTest extends TestCase
{
    public function testHighlyRatedWhenRatingIsFourOrMore(): void
    {
        $entry = (new LogEntry())->setTitle('Dune')->setRating(4.5);
        $this->assertTrue($entry->isHighlyRated());
    }

    public function testNotHighlyRatedBelowFour(): void
    {
        $entry = (new LogEntry())->setTitle('Movie 43')->setRating(2.0);
        $this->assertFalse($entry->isHighlyRated());
    }

    public function testJsonSerializeExposesExpectedKeys(): void
    {
        $entry = (new LogEntry())
            ->setTitle('Arrival')
            ->setYear(2016)
            ->setGenre('Sci-Fi')
            ->setRating(5.0);

        $json = $entry->jsonSerialize();
        $this->assertSame('Arrival', $json['title']);
        $this->assertSame(2016, $json['year']);
        $this->assertSame('Sci-Fi', $json['genre']);
        $this->assertArrayHasKey('watchedAt', $json);
    }
}
