<?php

declare(strict_types=1);

namespace Nxtech\Blog\Model\Resolver;

use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Nxtech\Blog\Api\BlogRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class DeleteBlogPost implements ResolverInterface
{
    private $blogRepository;

    public function __construct(BlogRepositoryInterface $blogRepository)
    {
        $this->blogRepository = $blogRepository;
    }

    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (!isset($args['id'])) {
            throw new GraphQlInputException(__('Blog ID is required.'));
        }

        try {
            // Delete blog post by ID
            $this->blogRepository->deleteById((int) $args['id']);

            return [
                'success' => true,
                'message' => __('Blog post ' . (int) $args['id'] . ' deleted successfully.')
            ];
        } catch (NoSuchEntityException $e) {
            return [
                'success' => false,
                'message' => __('Blog post ' . (int) $args['id'] . ' not found.')
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('Could not delete blog post.' . (int) $args['id'])
            ];
        }
    }
}
