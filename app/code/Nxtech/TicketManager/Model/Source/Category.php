<?php
namespace Nxtech\TicketManager\Model\Source;

use \Magento\Framework\Option\ArrayInterface;

class Category implements ArrayInterface
{

    public function toOptionArray()
    {
        return [
            ['value' => 1, 'label' => __('Sales')],
            ['value' => 2, 'label' => __('Payment')],
            ['value' => 3, 'label' => __('Order')],
            ['value' => 4, 'label' => __('Shipping and Delivery')]
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