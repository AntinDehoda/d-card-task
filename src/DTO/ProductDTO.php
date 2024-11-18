<?php
namespace App\DTO;
use App\Entity\Product;

final class ProductDTO implements \JsonSerializable
{
    public function __construct(
        private readonly ?int $id,
        private readonly string $title,
        private readonly float $price,
        private readonly ?string $imageUrl,
        private readonly ?string $productUrl,
    ) {
    }

    public static function fromEntity(Product $product): self
    {
        return new self(
            id: $product->getId(),
            title: $product->getTitle(),
            price: $product->getPrice(),
            imageUrl: $product->getImageUrl(),
            productUrl: $product->getProductUrl()
        );
    }
    public static function toEntity(ProductDTO $product): Product
    {
        $newProduct = new Product();
        $newProduct->setTitle($product->title);
        $newProduct->setPrice($product->price);
        $newProduct->setImageUrl($product->imageUrl);
        $newProduct->setProductUrl($product->productUrl);
        return $newProduct;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'price' => $this->price,
            'image_url' => $this->imageUrl,
            'product_url' => $this->productUrl,
        ];
    }

    public function isValid(): bool
    {
        return $this->title && $this->price;
    }
    public static function toArray(Product $product): array
    {
        return [
            $product->getId(),
            $product->getTitle(),
            $product->getPrice(),
            $product->getImageUrl(),
            $product->getProductUrl()
        ];
    }
}
