<?php
namespace Nxtech\CustomTab\Model\System\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;

class BlockList extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{

    public function __construct(
        protected ScopeConfigInterface $scopeConfig,
        protected BlockRepositoryInterface $blockRepository,
        protected SearchCriteriaBuilder $searchCriteriaBuilder

    ) {
    }

    public function getAllOptions()
    {

        $searchCriteria = $this->searchCriteriaBuilder->create();
        $cmsBlocks = $this->blockRepository->getList($searchCriteria)->getItems();
        $options = [];

        if ($cmsBlocks && count($cmsBlocks) > 0) {
            $options[0] = [
                'label' => 'Please select',
                'value' => ''
            ];
            foreach ($cmsBlocks as $cmsBlock) {
                $blockId = $cmsBlock->getId();
                $blockName = $cmsBlock->getTitle();
                $options[] = [
                    'label' => $blockName,
                    'value' => $blockId
                ];
            }
        }
        return $options;
    }
}