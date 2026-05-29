<?php
namespace App\Controller;
use App\Entity\LogEntry;
use App\Repository\LogEntryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/logs')]
class LogController extends AbstractController
{
    #[Route('', name: 'log_list', methods: ['GET'])]
    public function list(LogEntryRepository $repo): JsonResponse
    {
        return $this->json($repo->findBy([], ['watchedAt' => 'DESC']));
    }

    #[Route('/{id}', name: 'log_show', methods: ['GET'])]
    public function show(LogEntry $entry): JsonResponse
    {
        return $this->json($entry);
    }

    #[Route('', name: 'log_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em, ValidatorInterface $validator): JsonResponse
    {
        $entry = new LogEntry();
        $this->hydrate($entry, $this->payload($request));

        $errors = $validator->validate($entry);
        if (count($errors) > 0) {
            return $this->json(['errors' => array_map(fn($e) => $e->getMessage(), iterator_to_array($errors))], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $em->persist($entry);
        $em->flush();
        return $this->json($entry, Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'log_update', methods: ['PUT'])]
    public function update(LogEntry $entry, Request $request, EntityManagerInterface $em, ValidatorInterface $validator): JsonResponse
    {
        $this->hydrate($entry, $this->payload($request));

        $errors = $validator->validate($entry);
        if (count($errors) > 0) {
            return $this->json(['errors' => array_map(fn($e) => $e->getMessage(), iterator_to_array($errors))], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $em->flush();
        return $this->json($entry);
    }

    #[Route('/{id}', name: 'log_delete', methods: ['DELETE'])]
    public function delete(LogEntry $entry, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($entry);
        $em->flush();
        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    private function payload(Request $request): array
    {
        return json_decode($request->getContent(), true) ?? [];
    }

    private function hydrate(LogEntry $entry, array $data): void
    {
        if (isset($data['title'])) $entry->setTitle((string) $data['title']);
        if (array_key_exists('year', $data)) $entry->setYear($data['year'] !== null ? (int) $data['year'] : null);
        if (array_key_exists('genre', $data)) $entry->setGenre($data['genre']);
        if (isset($data['rating'])) $entry->setRating((float) $data['rating']);
        if (array_key_exists('review', $data)) $entry->setReview($data['review']);
        if (!empty($data['watchedAt'])) $entry->setWatchedAt(new \DateTimeImmutable($data['watchedAt']));
    }
}
