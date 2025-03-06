<?php
namespace Nxtech\ConsoleCommand\Model;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Nxtech\ConsoleCommand\Model\Product;
class Import extends Command
{
    const SKU = 'sku';

    public function __construct(
        protected Product $product
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('nxtech:product:export');
        $this->setDescription('Product Export Command.');
        $this->addOption(
            self::SKU,
            null,
            InputOption::VALUE_REQUIRED,
            'Sku'
        );

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($sku = $input->getOption(self::SKU)) {
            $product = $this->product->getProductBySku($sku);
            $output->writeln('<info>Product is: `' . $product->getName() . '`</info>');
            //$output->writeln('<info>Product Data: ' . print_r($product->getData(), true) . '</info>');
            return 1;
        }

        $output->writeln('<info>Please add sku</info>');
        return 0;
    }
}