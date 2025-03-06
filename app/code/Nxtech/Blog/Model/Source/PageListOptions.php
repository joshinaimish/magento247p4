<?php

namespace Nxtech\Blog\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;
use \Magento\Framework\Option\ArrayInterface;

class PageListOptions implements ArrayInterface
{

    public function toOptionArray()
    {
        return [
            ['value' => 1, 'label' => __('Test One')],
            ['value' => 2, 'label' => __('Test Two')],
            ['value' => 3, 'label' => __('Test Three')],
            ['value' => 4, 'label' => __('Test Four')]
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