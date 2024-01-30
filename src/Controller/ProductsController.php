<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController
{
    #[Route('/products/{id}/lowest-price', name:'lowest-price', methods: 'POST')]
    public function lowestPrice(Request $request, int $id): Response
    {
        if($request->headers->has('force-fail')) {
            return  new JsonResponse(
                ['error' => 'Promotions Engine Error'], $request->headers->get('force-fail'));
        }

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