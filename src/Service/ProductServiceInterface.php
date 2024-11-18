<?php

namespace App\Service;

interface ProductServiceInterface
{
    public function getAllProducts(): array;
    public function createProducts(array $dtos): array;
}
