<?php

namespace App\Controller;

use App\DTO\LowestPriceEnquiry;
use App\Service\Serializer\DTOSerializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{
    #[Route('/products/{id}/lowest-price', name:'lowest-price', methods: 'POST')]
    public function lowestPrice(Request $request, int $id, DTOSerializer $serializer): Response
    {
        if($request->headers->has('force-fail')) {
            return  new JsonResponse(
                ['error' => 'Promotions Engine Error'], $request->headers->get('force-fail'));
        }

        // 1. deserialize json data into EnquiryDTO
        /* @var LowestPriceEnquiry $lowestPriceEquiry */
        $lowestPriceEquiry = $serializer->deserialize(
            $request->getContent(), LowestPriceEnquiry::class, 'json'
        );
        // 2. pass enquiry into a promotions filter
        // 3. return a modified enquiry

        $lowestPriceEquiry->setPrice(100);
        $lowestPriceEquiry->setDiscountedPrice(50);
        $lowestPriceEquiry->setPromotionId(3);
        $lowestPriceEquiry->setPromotionName("Black Friday half price sale");

        $responseContent = $serializer->serialize($lowestPriceEquiry, 'json');

        return  new Response($responseContent);
    }



    #[Route('/products/{id}/promotions', name:'promotions', methods: 'GET')]
    public function promotions()
    {
        
    }
}