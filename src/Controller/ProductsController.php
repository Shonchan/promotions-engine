<?php

namespace App\Controller;

use App\DTO\LowestPriceEnquiry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ProductsController extends AbstractController
{
    #[Route('/products/{id}/lowest-price', name:'lowest-price', methods: 'POST')]
    public function lowestPrice(Request $request, int $id, SerializerInterface $serializer): Response
    {
        if($request->headers->has('force-fail')) {
            return  new JsonResponse(
                ['error' => 'Promotions Engine Error'], $request->headers->get('force-fail'));
        }

        // 1. deserialize json data into EnquiryDTO
        $lowestPriceEquiry = $serializer->deserialize($request->getContent(), LowestPriceEnquiry::class, 'json');
        dd($lowestPriceEquiry);
        // 2. pass enquiry into a promotions filter
        // 3. return a modified enquiry

        return  new JsonResponse([
            "quantity" => 5,
            "request_location" => "UK",
            "voucher_code" => "OU0812",
            "request_date" => date("Y-m-d"),
            "product_id" => $id,
            "price" => 100,
            'discounted_price' => 50,
            'promotion_id' => 3,
            'promotion_name' => 'Black Friday half price sale'
        ], 200);
    }



    #[Route('/products/{id}/promotions', name:'promotions', methods: 'GET')]
    public function promotions()
    {
        
    }
}