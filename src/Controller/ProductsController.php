<?php

declare(strict_types=1);

namespace App\Controller;

use App\Factory\ResponseFactory;
use App\Service\ProductServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/api')]
class ProductsController extends AbstractController
{
    #[Route('/get-products')]
    public function index(ProductServiceInterface $productService, ResponseFactory $responseFactory): Response
    {
        $products = $productService->getAllProducts();
        if (\count($products)) {
            return $responseFactory->createSuccessResponse($products);
        } else {
            return $responseFactory->createErrorResponse('No products found');
        }

    }
}
