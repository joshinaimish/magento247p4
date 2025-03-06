<?php

declare(strict_types=1);

namespace Nxtech\Blog\Model\Resolver;

use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Nxtech\Blog\Api\BlogRepositoryInterface;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Exception\NoSuchEntityException;

class Blog implements ResolverInterface
{
    private $blogRepository;
    private $categoryRepository;
    private $searchCriteriaBuilder;
    private $filterBuilder;

    public function __construct(
        BlogRepositoryInterface $blogRepository,
        CategoryRepositoryInterface $categoryRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FilterBuilder $filterBuilder
    ) {
        $this->blogRepository = $blogRepository;
        $this->categoryRepository = $categoryRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
    }

    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $searchCriteriaBuilder = $this->searchCriteriaBuilder;

        // Apply filter if 'id' is passed in GraphQL query
        if (isset($args['id'])) {
            $filter = $this->filterBuilder
                ->setField('id')
                ->setValue($args['id'])
                ->setConditionType('eq')
                ->create();
            $searchCriteriaBuilder->addFilters([$filter]);
        }

        $searchCriteria = $searchCriteriaBuilder->create();
        $blogList = $this->blogRepository->getList($searchCriteria);

        $blogPosts = [];
        foreach ($blogList->getItems() as $blog) {
            $categoryData = [];
            if ($blog->getCategory()) {
                try {
                    // Get category details using Magento's Category Repository
                    $category = $this->categoryRepository->get($blog->getCategory());
                    $categoryData = [
                        'category_id' => (int) $category->getId(),
                        'category_name' => $category->getName(),
                    ];
                } catch (NoSuchEntityException $e) {
                    // If category does not exist, return empty data
                    $categoryData = [
                        'category_id' => null,
                        'category_name' => null,
                    ];
                }
            }

            $blogPosts[] = [
                'id' => (int) $blog->getId(),
                'title' => $blog->getTitle(),
                'content' => $blog->getContent(),
                'status' => (int) $blog->getStatus(),
                'created_at' => $blog->getCreatedAt(),
                'updated_at' => $blog->getUpdatedAt(),
                'category' => $categoryData, // Include category details
            ];
        }

        return $blogPosts;
    }
}
