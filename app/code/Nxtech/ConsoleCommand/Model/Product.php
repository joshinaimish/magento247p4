<?php
namespace Nxtech\ConsoleCommand\Model;

use Magento\Catalog\Api\ProductRepositoryInterface;
class Product
{

    public function __construct(
        protected ProductRepositoryInterface $productRepository
    ) {
    }

    public function getProductBySku($sku)
    {
        return $this->productRepository->get($sku);
    }
}