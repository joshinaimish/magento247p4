<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Nxtech\BestSeller\Block;

class BestSeller extends \Magento\Framework\View\Element\Template
{
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        protected \Magento\Sales\Model\ResourceModel\Report\Bestsellers\CollectionFactory $collectionFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    public function getBestSellerData($category_ids)
    {
        $bestSellerProdcutCollection = $this->collectionFactory->create()
            ->setModel('Magento\Catalog\Model\Product')
            ->setPeriod('year')
            ->join(['secondTable' => 'catalog_category_product'], 'sales_bestsellers_aggregated_monthly.product_id = secondTable.product_id', ['category_id' => 'secondTable.category_id'])
            ->addFieldToFilter('category_id', $category_ids);

        return $bestSellerProdcutCollection;
    }
}