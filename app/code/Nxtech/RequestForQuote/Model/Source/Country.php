<?php
namespace Nxtech\RequestForQuote\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;
use \Magento\Framework\Option\ArrayInterface;

class Country implements ArrayInterface
{

    public function __construct(
        public \Magento\Directory\Model\ResourceModel\Country\CollectionFactory $countryCollectionFactory,
        public \Magento\Directory\Model\CountryFactory $countryFactory,
        array $data = []
    ) {

    }
    public function toOptionArray()
    {
        $countryCollection = $this->countryCollectionFactory->create()->loadByStore();
        $countries = [];
        foreach ($countryCollection->getData() as $country) {
            $code = $country['country_id'];
            $name = $this->getCountryName($country['country_id']);
            $countries[] = [
                'value' => $code,
                'label' => __($name)
            ];
        }
        return $countries;
    }
    public function getCountryName($countryCode)
    {
        $country = $this->countryFactory->create()->loadByCode($countryCode);
        return $country->getName();
    }

    /**
     * Get a text for option value
     *
     * @param string|integer $value
     * @return string|bool
     */
    public function getOptionText($value)
    {
        foreach ($this->toOptionArray() as $option) {
            if ($option['value'] == $value) {
                return $option['label'];
            }
        }
        return false;
    }
}