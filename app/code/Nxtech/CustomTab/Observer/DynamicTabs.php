<?php
namespace Nxtech\CustomTab\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Nxtech\CustomTab\Model\TabConfig;

class DynamicTabs implements ObserverInterface
{
    const PARENT_BlOCK_NAME = 'product.info.details';
    const RENDERING_TEMPLATE = 'Nxtech_CustomTab::tab.phtml';
    public function __construct(public TabConfig $tabs)
    {
    }
    public function execute(Observer $observer)
    {
        $layout = $observer->getLayout();
        $blocks = $layout->getAllBlocks();

        foreach ($blocks as $key => $block) {
            if ($block->getNameInLayout() == self::PARENT_BlOCK_NAME) {
                $tabs = $this->tabs->getTabs();
                if (isset($tabs) && count($tabs) > 0) {
                    foreach ($this->tabs->getTabs() as $key => $tab) {
                        if ($tab['value'] != "") {
                            $block->addChild(
                                $key,
                                \Magento\Catalog\Block\Product\View::class,
                                [
                                    'template' => self::RENDERING_TEMPLATE,
                                    'title' => $tab['title'],
                                    'jsLayout' => [
                                        $tab
                                    ]
                                ]
                            );
                        }
                    }
                }
            }
        }
    }
}