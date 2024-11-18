<?php

declare(strict_types=1);

namespace App\Command;

use App\Exception\ProductsParseException;
use App\Service\ProductParser;
use App\Service\ProductServiceInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

#[AsCommand(name: 'app:parse-links', description: 'Parse links')]
class ProductsParseCommand extends Command
{
    public function __construct(
        private readonly ProductServiceInterface $productService,
        private readonly ProductParser $parser,
        private readonly string $projectDir,
        private readonly ContainerInterface $container
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $sources = $this->container->getParameter('parse_sources')['url'];
        $selectors = $this->container->getParameter('parse_sources')['selectors'];
        foreach ($sources as $source) {
            try {
                $products = $this->parser->parseProducts($source, $selectors);

            } catch (ProductsParseException $e) {
                $output->writeln('Products not found! Message: ' . $e->getMessage());
                return Command::FAILURE;
            }
            $entities = $this->productService->createProducts($products);
            if (\count($entities)) {
                $ids =\implode(',',
                    array_map(
                        fn($product) => $product->getId(),
                        $entities
                    )
                );
                $output->writeln(sprintf(
                    'Parsed products id\'s : %s',
                    $ids
                ));
                // Export products with ids to CSV file
                $this->startExportProcess($ids, $output);
            } else {
                $output->writeln('No products parsed');
            }

        }

        return Command::SUCCESS;
    }

    private function startExportProcess(string $product_ids, OutputInterface $output): void
    {

        $process = new Process([
            'php',
            'bin/console',
            'app:products-export',
            $product_ids
        ]);
        $process->setWorkingDirectory($this->projectDir);

        try {
            $process->run();
            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }
            $process_message = $process->getOutput();

        } catch (ProcessFailedException $exception) {
            $process_message = $exception->getMessage();
        }
        $output->writeln($process_message);
    }
}
