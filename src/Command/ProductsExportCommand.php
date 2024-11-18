<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Product;
use App\Service\ExporterInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:products-export', description: 'Export products to the CSV file')]
class ProductsExportCommand extends Command
{
    public function __construct(
        private readonly ExporterInterface $exporterService,
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }
    protected function configure(): void
    {
        $this
            ->addArgument('products', InputArgument::REQUIRED)
        ;
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ids = $input->getArgument('products');
        $products = $this->entityManager->getRepository(Product::class)->findProducts($ids);
        try {
            $link = $this->exporterService->export($products);
            $output->writeln(sprintf('Successfully exported products to CSV file: %s', $link));
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln(sprintf('CSV Export failed  %s', $e->getMessage()));
            return Command::FAILURE;
        }
    }
}
