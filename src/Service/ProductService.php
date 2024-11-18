<?php

namespace App\Service;

use App\DTO\ProductDTO;
use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\ProductServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

class ProductService implements ProductServiceInterface
{

    public function __construct(
        private readonly EntityManagerInterface $em
    ) {
    }
    public function getAllProducts(): array
    {

        $products = $this->em->getRepository(Product::class)->findAll();

        return array_map(
            fn($product) => ProductDTO::fromEntity($product),
            $products
        );
    }
    public function createProducts(array $dtos): array
    {
        $products = [];
        foreach ($dtos as $productDto) {
            $product = ProductDTO::toEntity($productDto);
            $this->em->persist($product);
            $products[] = $product;
        }
        $this->em->flush();
        return $products;
    }
}
