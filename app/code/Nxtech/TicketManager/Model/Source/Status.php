<?php
namespace Nxtech\TicketManager\Model\Source;

use \Magento\Framework\Option\ArrayInterface;

class Status implements ArrayInterface
{

    public function toOptionArray()
    {
        return [
            ['value' => 1, 'label' => __('Open')],
            ['value' => 2, 'label' => __('Processing')],
            ['value' => 3, 'label' => __('Hold')],
            ['value' => 4, 'label' => __('Closed')]
        ];
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