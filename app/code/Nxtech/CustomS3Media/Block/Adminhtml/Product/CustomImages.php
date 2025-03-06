<?php
namespace Nxtech\CustomS3Media\Block\Adminhtml\Product;

use Magento\Backend\Block\Template\Context;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Registry;


class CustomImages extends \Magento\Backend\Block\Template
{
    public const S3_URL = 'https://d2rdfqk6u5busa.cloudfront.net/amazon_main_images/';
    /**
     * Block template.
     *
     * @var string
     */
    protected $_template = 'custom_images.phtml';
    public function __construct(
        Context $context,
        protected ProductRepositoryInterface $productRepository,
        protected Registry $registry,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }



    public function getS3Url()
    {
        $product = $this->registry->registry('current_product');
        if ($product) {
            return $product->getData('s3_url'); // Fetching the custom attribute "s3_url"
        }
        return null;
    }

    public function getCustomS3URL()
    {
        return self::S3_URL;
    }

}