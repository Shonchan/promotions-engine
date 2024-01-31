<?php

namespace App\Controller;

use App\DTO\LowestPriceEnquiry;
use App\Filter\PromotionsFilterInterface;
use App\Service\Serializer\DTOSerializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{
    #[Route('/products/{id}/lowest-price', name:'lowest-price', methods: 'POST')]
    public function lowestPrice(
        Request $request,
        int $id,
        DTOSerializer $serializer,
        PromotionsFilterInterface $promotionsFilter
    ): Response
    {
        if($request->headers->has('force-fail')) {
            return  new JsonResponse(
                ['error' => 'Promotions Engine Error'], $request->headers->get('force-fail'));
        }

        /* @var LowestPriceEnquiry $lowestPriceEquiry */
        $lowestPriceEquiry = $serializer->deserialize(
            $request->getContent(), LowestPriceEnquiry::class, 'json'
        );

        $modifiedEquiry = $promotionsFilter->apply($lowestPriceEquiry);

        $responseContent = $serializer->serialize($modifiedEquiry, 'json');

        return  new Response($responseContent);
    }



    #[Route('/products/{id}/promotions', name:'promotions', methods: 'GET')]
    public function promotions()
    {
        
    }
}