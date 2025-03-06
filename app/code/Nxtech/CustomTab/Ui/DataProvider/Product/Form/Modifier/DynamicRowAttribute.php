<?php
namespace Nxtech\CustomTab\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Ui\Component\Form\Field;
use Magento\Ui\Component\Form\Element\Input;
use Magento\Ui\Component\DynamicRows;
use Magento\Ui\Component\Container;
use Magento\Ui\Component\Form\Element\DataType\Text;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory as AttributeSetCollection;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Ui\Component\Form\Element\Select;
use Nxtech\CustomTab\Model\System\Config\BlockList;

class DynamicRowAttribute extends AbstractModifier
{
    public const PRODUCT_ATTRIBUTE_CODE = 'dynamic_tab';  //dynamic_row_attribute
    public const FIELD_IS_DELETE = 'is_delete';
    public const FIELD_SORT_ORDER_NAME = 'sort_order';
    /**
     * Dependency Initilization
     *
     * @param LocatorInterface $locator
     * @param AttributeSetCollection $attributeSetCollection
     * @param \Magento\Framework\Serialize\SerializerInterface $serializer
     * @param ArrayManager $arrayManager
     */
    public function __construct(
        private LocatorInterface $locator,
        protected AttributeSetCollection $attributeSetCollection,
        protected \Magento\Framework\Serialize\SerializerInterface $serializer,
        protected ArrayManager $arrayManager,
        protected BlockList $blockList
    ) {
    }
    /**
     * Modify Data
     *
     * @param array $data
     * @return array
     */
    public function modifyData(array $data)
    {
        $fieldCode = self::PRODUCT_ATTRIBUTE_CODE;
        $model = $this->locator->getProduct();
        $modelId = $model->getId();
        $highlightsData = $model->getDynamicTab();
        if ($highlightsData) {
            $highlightsData = $this->serializer->unserialize($highlightsData, true);
            $path = $modelId . '/' . self::DATA_SOURCE_DEFAULT . '/' . $fieldCode;
            $data = $this->arrayManager->set($path, $data, $highlightsData);
        }
        return $data;
    }
    /**
     * Modify Meta
     *
     * @param array $meta
     * @return array
     */
    public function modifyMeta(array $meta)
    {
        $highlightsPath = $this->arrayManager->findPath(
            self::PRODUCT_ATTRIBUTE_CODE,
            $meta,
            null,
            'children'
        );
        if ($highlightsPath) {
            $meta = $this->arrayManager->merge(
                $highlightsPath,
                $meta,
                $this->initHighlightFieldStructure($meta, $highlightsPath)
            );
            $meta = $this->arrayManager->set(
                $this->arrayManager->slicePath($highlightsPath, 0, -3)
                . '/' . self::PRODUCT_ATTRIBUTE_CODE,
                $meta,
                $this->arrayManager->get($highlightsPath, $meta)
            );
            $meta = $this->arrayManager->remove(
                $this->arrayManager->slicePath($highlightsPath, 0, -2),
                $meta
            );
        }
        return $meta;
    }
    /**
     * Get attraction highlights dynamic rows structure
     *
     * @param array $meta
     * @param string $highlightsPath
     * @return array
     */
    protected function initHighlightFieldStructure($meta, $highlightsPath)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'componentType' => 'dynamicRows',
                        'label' => __('Custom Dynamic Tab'),
                        'renderDefaultRecord' => false,
                        'recordTemplate' => 'record',
                        'dataScope' => '',
                        'dndConfig' => [
                            'enabled' => false,
                        ],
                        'disabled' => false,
                        'sortOrder' =>
                            $this->arrayManager->get($highlightsPath . '/arguments/data/config/sortOrder', $meta),
                    ],
                ],
            ],
            'children' => [
                'record' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'componentType' => Container::NAME,
                                'isTemplate' => true,
                                'is_collection' => true,
                                'component' => 'Magento_Ui/js/dynamic-rows/record',
                                'dataScope' => '',
                            ],
                        ],
                    ],
                    'children' => [
                        'title' => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'formElement' => Input::NAME,
                                        'componentType' => Field::NAME,
                                        'dataType' => Text::NAME,
                                        'sortOrder' => 10,
                                        'label' => __('Title'),
                                        'dataScope' => 'title',
                                        'require' => '1',
                                    ],
                                ],
                            ],
                        ],
                        'block' => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'formElement' => Select::NAME,
                                        'componentType' => Field::NAME,
                                        'dataType' => Text::NAME,
                                        'dataScope' => 'block',
                                        'label' => __('Block'),
                                        'options' => $this->blockList->getAllOptions(),
                                        'value' => 0,
                                        'sortOrder' => 30,
                                        'additionalClasses' => 'select-block-list',
                                    ],
                                ],
                            ],
                        ],
                        'actionDelete' => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'componentType' => 'actionDelete',
                                        'dataType' => Text::NAME,
                                        'label' => '',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}