<?php

namespace App\Controller\Balance;

use App\Repository\TransactionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/transactions', name: 'get_transactions', methods: 'GET')]
class GetBalanceAction extends AbstractController
{
    public function __construct(
        private readonly TransactionRepository $transactionRepository,
        private readonly SerializerInterface $serializer
    ) {}

    public function __invoke(): JsonResponse
    {
        $transactions = $this->transactionRepository->findAll();

        if (empty($transactions)) {
            return new JsonResponse(
                ['balance' => 0],
                Response::HTTP_OK,
                ['Content-Type' => 'application/json']
            );
        }

        return new JsonResponse(
            $this->serializer->serialize($transactions, 'json'),
            Response::HTTP_OK,
            ['Content-Type' => 'application/json'],
            true
        );

    }

}