<?php
namespace Nxtech\CustomTab\Model;

use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Framework\Registry;
use \Magento\Framework\Serialize\Serializer\Json;

class TabConfig
{
    public function __construct(
        protected BlockRepositoryInterface $blockRepository,
        protected Registry $registry,
        protected Json $json
    ) {
    }
    public function getTabs()
    {
        $options = $tabData = [];
        $product = $this->registry->registry('current_product');
        if ($product) {
            $tabContent = $product->getDynamicTab();
            if ($tabContent) {
                $tabData = $this->json->unserialize($product->getDynamicTab());
            }
        }

        if ($tabData && count($tabData) > 0) {
            $sort = 0;
            foreach ($tabData as $cmsBlock) {
                $blockId = $cmsBlock['block'];
                $title = $cmsBlock['title'];
                $block = $this->blockRepository->getById($blockId);
                $content = $block->getContent();
                $options[] = [
                    'title' => $title,
                    'value' => $content,
                    'sortOrder' => $sort++,
                ];
            }
        }
        return $options;
    }
}