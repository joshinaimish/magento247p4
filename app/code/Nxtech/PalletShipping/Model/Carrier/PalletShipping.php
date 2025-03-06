<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Nxtech\PalletShipping\Model\Carrier;

use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Rate\Result;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\File\Csv;

class PalletShipping extends \Magento\Shipping\Model\Carrier\AbstractCarrier implements
    \Magento\Shipping\Model\Carrier\CarrierInterface
{

    protected $_code = 'palletshipping';

    protected $_isFixed = true;

    protected $_rateResultFactory;

    protected $_rateMethodFactory;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory
     * @param \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
        protected \Psr\Log\LoggerInterface $logger,
        \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory,
        \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory,
        protected ProductRepositoryInterface $productRepository,
        protected Filesystem $filesystem,
        protected Csv $csv,
        array $data = []
    ) {
        $this->_rateResultFactory = $rateResultFactory;
        $this->_rateMethodFactory = $rateMethodFactory;
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function collectRates(RateRequest $request)
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }

        $shippingPrice = $this->getConfigData('price');
        // Read CSV data
        $palletData = [];
        $mediaDirectory = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
        $filePath = 'nxtech/pallet/pallet_rate.csv';
        $fullPath = $mediaDirectory->getAbsolutePath($filePath);

        // Check if file exists
        if (!file_exists($fullPath)) {
            $this->logger->error('CSV file not found: ' . $fullPath);
            return [];
        }

        // Read CSV file
        $data = $this->csv->getData($fullPath);

        // Convert CSV to an associative array
        $headers = array_shift($data); // First row as headers

        foreach ($data as $row) {
            //$result[$row[0]] = array_combine($headers, $row);
            $palletData[$row[0]] = array('pallet' => $row[1], 'charge' => $row[2]);
        }
        //print_r($palletData);
        // END CSV data
        // Set Custom Shipping rate
        $items = $request->getAllItems();
        $isPallet = false;
        $diffPalletTotal = $palletDeliveryCost = 0;
        foreach ($items as $item) {
            $productId = $item->getProduct()->getId();
            $sku = $item->getProduct()->getSku();
            $product = $this->productRepository->getById($productId);
            //echo $palletValue = $product->getCustomAttribute('pallet') ? $product->getCustomAttribute('pallet')->getValue() : 0;
            if ($product->getCustomAttribute('pallet')) {
                $isPallet = true;
                $productPallet = $product->getCustomAttribute('pallet') ? $product->getCustomAttribute('pallet')->getValue() : 0;
                $sheetPallet = $palletData[$sku]['pallet'];
                $charge = $palletData[$sku]['charge'];

                if ($productPallet < $sheetPallet) {
                    $diffPallet = $sheetPallet - $productPallet;
                    $diffPalletTotal += $charge;
                }
                // Get CSV Data
                // Get pallet and charge as per csv
                // If pallet is equal or less
                // Set new custom shipping rate
            }
        }
        $palletDeliveryCost = 120 + $diffPalletTotal;
        // Return false if there is no pallet product's in cart
        /* if (!$isPallet) {
            return false;
        } */
        ////////////

        //var_dump($shippingRate);
        // End Set Custom Shipping rate
        $result = $this->_rateResultFactory->create();

        if ($shippingPrice !== false) {
            $method = $this->_rateMethodFactory->create();

            $method->setCarrier($this->_code);
            $method->setCarrierTitle($this->getConfigData('title'));

            $method->setMethod($this->_code);
            $method->setMethodTitle($this->getConfigData('name'));

            if ($request->getFreeShipping() === true) {
                $shippingPrice = '0.00';
            }
            if ($isPallet) {
                $method->setPrice($palletDeliveryCost);
                $method->setCost($palletDeliveryCost);
            } else {
                $method->setPrice($shippingPrice);
                $method->setCost($shippingPrice);
            }
            $result->append($method);
        }

        return $result;
    }

    /**
     * getAllowedMethods
     *
     * @return array
     */
    public function getAllowedMethods()
    {
        return [$this->_code => $this->getConfigData('name')];
    }
}
