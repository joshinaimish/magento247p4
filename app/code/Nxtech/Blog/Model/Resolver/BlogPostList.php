<?php

declare(strict_types=1);

namespace Nxtech\Blog\Model\Resolver;

use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Nxtech\Blog\Api\BlogRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\Api\SortOrder;

class BlogPostList implements ResolverInterface
{
    private $blogRepository;
    private $searchCriteriaBuilder;
    private $filterBuilder;
    private $sortOrderBuilder;

    public function __construct(
        BlogRepositoryInterface $blogRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FilterBuilder $filterBuilder,
        SortOrderBuilder $sortOrderBuilder
    ) {
        $this->blogRepository = $blogRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->sortOrderBuilder = $sortOrderBuilder;
    }

    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $pageSize = $args['pageSize'] ?? 10;
        $currentPage = $args['currentPage'] ?? 1;

        $searchCriteria = $this->searchCriteriaBuilder
            ->setCurrentPage($currentPage)
            ->setPageSize($pageSize);

        // Filtering by title (if provided)
        if (!empty($args['filter']['title'])) {
            $filter = $this->filterBuilder
                ->setField('title')
                ->setValue('%' . $args['filter']['title'] . '%') // LIKE search
                ->setConditionType('like')
                ->create();
            $searchCriteria->addFilters([$filter]);
        }

        // Sorting by title (if provided)
        if (!empty($args['sort']['title'])) {
            $sortOrder = $this->sortOrderBuilder
                ->setField('title')
                ->setDirection($args['sort']['title'] === 'ASC' ? SortOrder::SORT_ASC : SortOrder::SORT_DESC)
                ->create();
            $searchCriteria->setSortOrders([$sortOrder]);
        }

        // Fetch blog post collection
        $result = $this->blogRepository->getList($searchCriteria->create());

        $totalCount = $result->getTotalCount();
        $totalPages = ceil($totalCount / $pageSize); // Calculate total pages

        return [
            'items' => $result->getItems(),
            'total_count' => $totalCount,
            'total_pages' => (int) $totalPages
        ];
    }

}
