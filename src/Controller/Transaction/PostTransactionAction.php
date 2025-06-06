<?php

namespace App\Controller\Transaction;

use App\Entity\Transaction;
use App\Enum\Transaction\TransactionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/transactions', name: 'post_transactions', methods: 'post')]
class PostTransactionAction extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly SerializerInterface $serializer,
    )
    {}

    public function __invoke(Request $request): JsonResponse
    {
        $body = json_decode($request->getContent(), true);

        if(!isset($body['amount']) || !isset($body['type'])) {
            return $this->json(['error' => 'Invalid transaction data'], 400);
        }

        $type =  match($body['type']) {
            'expense' => TransactionType::EXPENSE,
            'income' => TransactionType::INCOME,
        };

        $transaction = new Transaction();
        $transaction->setAmount($body['amount']);
        $transaction->setType($type);
        $transaction->setCreatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($transaction);
        $this->entityManager->flush();

        return new JsonResponse(
            $this->serializer->serialize($transaction, 'json'),
            Response::HTTP_CREATED,
            ['Content-Type' => 'application/json'],
            true
        );
    }
}