<?php

namespace App\Controller;

use App\DTO\LowestPriceEnquiry;
use App\Entity\Promotion;
use App\Filter\PromotionsFilterInterface;
use App\Repository\ProductRepository;
use App\Service\Serializer\DTOSerializer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{
    public function __construct(
        private readonly ProductRepository      $repository,
        private readonly EntityManagerInterface $entityManager
    )
    {
    }

    #[Route('/products/{id}/lowest-price', name: 'lowest-price', methods: 'POST')]
    public function lowestPrice(
        Request                   $request,
        int                       $id,
        DTOSerializer             $serializer,
        PromotionsFilterInterface $promotionsFilter
    ): Response
    {
        if ($request->headers->has('force-fail')) {
            return new JsonResponse(
                ['error' => 'Promotions Engine Error'],
                $request->headers->get('force-fail')
            );
        }

        /* @var LowestPriceEnquiry $lowestPriceEquiry */
        $lowestPriceEquiry = $serializer->deserialize(
            $request->getContent(),
            LowestPriceEnquiry::class,
            'json'
        );

        $product = $this->repository->find($id);

        if (!$product) {
            return new JsonResponse(
                ['error' => 'Product doesn`t exist'], 500);
        }

        $lowestPriceEquiry->setProduct($product);

        $promotions = $this->entityManager->getRepository(Promotion::class)
            ->findValidForProduct(
                $product,
                date_create_immutable($lowestPriceEquiry->getRequestDate())
            );

        $modifiedEquiry = $promotionsFilter->apply($lowestPriceEquiry, ...$promotions);

        $responseContent = $serializer->serialize($modifiedEquiry, 'json');

        return new Response($responseContent, 200, ['Content-Type'=>'application/json']);
    }


    #[Route('/products/{id}/promotions', name: 'promotions', methods: 'GET')]
    public function promotions()
    {

    }
}