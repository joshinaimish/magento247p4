<?php

declare(strict_types=1);

namespace Nxtech\Blog\Model\Resolver;

use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Nxtech\Blog\Api\BlogRepositoryInterface;
use Nxtech\Blog\Model\BlogFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Stdlib\DateTime\DateTime;

class CreateBlogPost implements ResolverInterface
{
    private $blogRepository;
    private $blogFactory;
    private $dateTime;

    public function __construct(
        BlogRepositoryInterface $blogRepository,
        BlogFactory $blogFactory,
        DateTime $dateTime
    ) {
        $this->blogRepository = $blogRepository;
        $this->blogFactory = $blogFactory;
        $this->dateTime = $dateTime;
    }

    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (!isset($args['title']) || !isset($args['content']) || !isset($args['auther']) || !isset($args['category']) || !isset($args['status'])) {
            throw new GraphQlInputException(__('Required fields are missing.'));
        }

        try {
            $blog = $this->blogFactory->create();
            $blog->setTitle($args['title']);
            $blog->setContent($args['content']);
            $blog->setAuther($args['auther']);
            $blog->setCategory($args['category']);
            $blog->setStatus((int) $args['status']);
            //$blog->setCreatedAt($this->dateTime->gmtDate());
            //$blog->setUpdatedAt($this->dateTime->gmtDate());

            $this->blogRepository->save($blog);

            return [
                'id' => (int) $blog->getId(),
                'title' => $blog->getTitle(),
                'content' => $blog->getContent(),
                'auther' => $blog->getAuther(),
                'category' => $blog->getCategory(),
                'status' => (int) $blog->getStatus(),
                'created_at' => $blog->getCreatedAt(),
                'updated_at' => $blog->getUpdatedAt(),
            ];
        } catch (CouldNotSaveException $e) {
            throw new GraphQlInputException(__('Could not save blog post: ' . $e->getMessage()));
        }
    }
}
