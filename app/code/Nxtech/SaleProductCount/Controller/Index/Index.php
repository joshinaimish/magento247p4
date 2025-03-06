<?php
namespace Nxtech\SaleProductCount\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Filesystem\DirectoryList;

class Index extends Action
{
    protected $resultPageFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        public \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        public \Magento\Sales\Model\ResourceModel\Order\Item\CollectionFactory $orderItemCollectionFactory,
        public \Magento\Framework\Filesystem $fileSystem

    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {

        //$productCollectionFactory = $objectManager->get('Magento\Catalog\Model\ResourceModel\Product\CollectionFactory');
        //$orderItemCollectionFactory = $objectManager->get('Magento\Sales\Model\ResourceModel\Order\Item\CollectionFactory');
        //$fileSystem = $objectManager->get('Magento\Framework\Filesystem');
        $directory = $this->fileSystem->getDirectoryWrite(DirectoryList::VAR_DIR);
        $filePath = 'export/product_sales.csv';

        $productCollection = $this->productCollectionFactory->create();
        $productCollection->addAttributeToSelect(['entity_id', 'sku', 'name']);

        $orderItemCollection = $this->orderItemCollectionFactory->create();
        $orderItemCollection->getSelect()->reset(\Zend_Db_Select::COLUMNS)
            ->columns(['product_id', 'total_qty_ordered' => 'SUM(qty_ordered)'])
            ->group('product_id');

        $salesData = [];
        foreach ($orderItemCollection as $item) {
            $salesData[$item->getProductId()] = $item->getTotalQtyOrdered();
        }

        $csvData = [];
        $csvData[] = ['Product ID', 'SKU', 'Name', 'Sales Count'];

        foreach ($productCollection as $product) {
            $productId = $product->getId();
            $sku = $product->getSku();
            $name = $product->getName();
            $salesCount = isset($salesData[$productId]) ? $salesData[$productId] : 0;
            $csvData[] = [$productId, $sku, $name, $salesCount];
        }

        $directory->writeFile($filePath, '');
        $csvFile = fopen($directory->getAbsolutePath($filePath), 'w');
        foreach ($csvData as $row) {
            fputcsv($csvFile, $row);
        }
        fclose($csvFile);

        echo "CSV file generated: " . $directory->getAbsolutePath($filePath) . PHP_EOL;
        exit;
        //return $this->resultPageFactory->create();
    }
}
