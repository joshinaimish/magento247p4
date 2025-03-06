<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Nxtech\RequestForQuote\Block;

use \Magento\Store\Model\StoreManagerInterface;

class RequestForQuote extends \Magento\Framework\View\Element\Template
{

    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context  $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        protected \Magento\Framework\Registry $registry,
        public \Magento\Directory\Model\ResourceModel\Country\CollectionFactory $countryCollectionFactory,
        public \Magento\Directory\Model\CountryFactory $countryFactory,
        protected StoreManagerInterface $storeManager,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    public function getProduct()
    {
        return $this->registry->registry('current_product');
    }
    public function getCountryList()
    {
        $countryCollection = $this->countryCollectionFactory->create()->loadByStore();
        $countries = [];
        foreach ($countryCollection->getData() as $country) {
            $code = $country['country_id'];
            $name = $this->getCountryName($country['country_id']);

            $countries[$code] = $name;
        }
        return $countries;
    }
    public function getCountryName($countryCode)
    {
        $country = $this->countryFactory->create()->loadByCode($countryCode);
        return $country->getName();
    }
    public function getCustomUrl()
    {
        return $this->storeManager->getStore()->getUrl('rfq/index/save');
        ;
    }
    public function getProductName()
    {
        return $this->getData('product_name');
    }
}
