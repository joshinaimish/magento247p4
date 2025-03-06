<?php
namespace Nxtech\Blog\Ui\Component\Listing\Blog;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Nxtech\Blog\Model\Source\PageListOptions;

class Page extends \Magento\Ui\Component\Listing\Columns\Column
{


    protected $pageList;
    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        PageListOptions $pageList,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->pageList = $pageList;
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as &$item) {
                $attributeValue = $item[$fieldName];

                // Convert comma-separated values to array
                $attributeValuesArray = explode(',', $attributeValue);
                // Fetch label for each option
                $options = [];

                foreach ($attributeValuesArray as $value) {
                    $options[] = $this->pageList->getOptionText($value);
                }
                $item[$fieldName] = implode(', ', $options);
            }
        }

        return $dataSource;
    }
}