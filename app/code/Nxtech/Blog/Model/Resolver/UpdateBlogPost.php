<?php

declare(strict_types=1);

namespace Nxtech\Blog\Model\Resolver;

use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Nxtech\Blog\Api\BlogRepositoryInterface;
use Nxtech\Blog\Model\ResourceModel\Blog\CollectionFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Stdlib\DateTime\DateTime;

class UpdateBlogPost implements ResolverInterface
{
    private $blogRepository;
    private $blogCollectionFactory;
    private $dateTime;

    public function __construct(
        BlogRepositoryInterface $blogRepository,
        CollectionFactory $blogCollectionFactory,
        DateTime $dateTime
    ) {
        $this->blogRepository = $blogRepository;
        $this->blogCollectionFactory = $blogCollectionFactory;
        $this->dateTime = $dateTime;
    }

    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (!isset($args['id'])) {
            throw new GraphQlInputException(__('Blog ID is required.'));
        }

        try {
            // Get the blog post by ID
            $blog = $this->blogRepository->get($args['id']);

            // Update only provided fields
            if (isset($args['title'])) {
                $blog->setTitle($args['title']);
            }
            if (isset($args['content'])) {
                $blog->setContent($args['content']);
            }
            if (isset($args['category'])) {
                $blog->setCategory($args['category']);
            }
            if (isset($args['status'])) {
                $blog->setStatus((int) $args['status']);
            }

            // Update `updated_at` timestamp
            //$blog->setUpdatedAt($this->dateTime->gmtDate());

            // Save the updated blog post
            $this->blogRepository->save($blog);

            return [
                'id' => (int) $blog->getId(),
                'title' => $blog->getTitle(),
                'content' => $blog->getContent(),
                'category' => $blog->getCategory(),
                'status' => (int) $blog->getStatus(),
                'created_at' => $blog->getCreatedAt(),
                'updated_at' => $blog->getUpdatedAt(),
            ];
        } catch (NoSuchEntityException $e) {
            throw new GraphQlInputException(__('Blog post not found.'));
        }
    }
}
