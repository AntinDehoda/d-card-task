<?php

namespace App\Service;

use App\DTO\ProductDTO;
use App\Exception\ProductsParseException;
use Symfony\Component\DomCrawler\Crawler;

use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ProductParser
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Parse products from a given URL
     * @throws ProductsParseException
     */
    public function parseProducts(string $url, array $selectors): array
    {
        $products = [];
        try {
            $response = $this->client->request('GET', $url);
            $html = $response->getContent();

            $crawler = new Crawler($html);
            $productNodes = $crawler->filterXPath($selectors['products']);

            $productNodes->each(function (Crawler $node) use (&$products, $selectors) {
                $product = new ProductDTO(
                    null,
                    $this->getNodeContent($node, $selectors['title']),
                    $this->cleanPrice(
                        $this->getNodeContent($node, $selectors['price'])
                    ),
                    $this->getNodeAttribute($node, $selectors['img'], 'src'),
                    $this->getNodeAttribute($node, $selectors['link'], 'href'),
                );
                if ($product->isValid()) {
                    $products[] = $product;
                }
            });

        } catch (\Exception|TransportExceptionInterface $e) {
            throw new ProductsParseException('Failed to parse products: ' . $e->getMessage());
        }
        return $products;
    }

    /**
     * Get content from a node using XPath
     */
    private function getNodeContent(Crawler $node, string $xpath): string
    {
        try {
            return trim($node->filterXPath($xpath)->first()->text());
        } catch (\Exception $e) {
            return '';
        }
    }

    /**
     * Get attribute from a node using XPath
     */
    private function getNodeAttribute(Crawler $node, string $xpath, string $attribute): string
    {
        try {
            return $node->filterXPath($xpath)->first()->attr($attribute);
        } catch (\Exception $e) {
            return '';
        }
    }

    /**
     * Clean price string (remove currency symbols, spaces, etc.)
     */
    private function cleanPrice(string $price): string
    {
        return preg_replace('/[^0-9.]/', '', $price);
    }
}
