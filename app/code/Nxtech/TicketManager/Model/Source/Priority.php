<?php
namespace Nxtech\TicketManager\Model\Source;

use \Magento\Framework\Option\ArrayInterface;

class Priority implements ArrayInterface
{

    public function toOptionArray()
    {
        return [
            ['value' => 1, 'label' => __('High')],
            ['value' => 2, 'label' => __('Medium')],
            ['value' => 3, 'label' => __('Low')]
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