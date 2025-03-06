<?php

declare(strict_types=1);

namespace Nxtech\CustomGraphql\Model\Resolver;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;

class S3UrlAttribute implements ResolverInterface
{
    /**
     * Resolve the GraphQL field
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        if (!isset($value['model']) || !$value['model'] instanceof ProductInterface) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Product model not found'));
        }

        /** @var ProductInterface $product */
        $product = $value['model'];

        // Fetch the s3_url attribute
        return $product->getCustomAttribute('s3_url')
            ? $product->getCustomAttribute('s3_url')->getValue()
            : null;
    }
}
