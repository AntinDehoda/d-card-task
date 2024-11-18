<?php

namespace App\Service;

use App\DTO\ProductDTO;
use Symfony\Component\Filesystem\Filesystem;

class ExporterProductsCsv implements ExporterInterface
{
    private const HEADERS = ['Title', 'Price', 'Image URL', 'Product URL'];

    public function __construct(
        private readonly string $exportDir
    ) {

    }

    public function export(array $data): string
    {
        $filename = sprintf('products_export_%s.csv', date('Y-m-d_His'));
        $filepath = sprintf('%s/%s', $this->exportDir, $filename);

        $filesystem = new Filesystem();
        if (!$filesystem->exists($this->exportDir)) {
            $filesystem->mkdir($this->exportDir);
        }

        $handle = fopen($filepath, 'w');

        // Write headers
        fputcsv($handle, self::HEADERS);

        // Write product data
        foreach ($data as $product) {
            fputcsv($handle, ProductDTO::toArray($product));
        }

        fclose($handle);

        return $filepath;
    }
}
