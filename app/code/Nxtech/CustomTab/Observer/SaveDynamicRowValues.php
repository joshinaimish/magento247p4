<?php
namespace Nxtech\CustomTab\Observer;

use Nxtech\CustomTab\Ui\DataProvider\Product\Form\Modifier\DynamicRowAttribute;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;

class SaveDynamicRowValues implements ObserverInterface
{
    /**
     * Dependency Initilization
     *
     * @param RequestInterface $request
     * @param \Magento\Framework\Serialize\SerializerInterface $serializer
     */
    public function __construct(
        protected RequestInterface $request,
        protected \Magento\Framework\Serialize\SerializerInterface $serializer,
    ) {
    }
    /**
     * Execute
     *
     * @param Observer $observer
     * @return this
     */
    public function execute(Observer $observer)
    {
        /** @var $product \Magento\Catalog\Model\Product */
        $product = $observer->getEvent()->getDataObject();
        $wholeRequest = $this->request->getPost();
        $post = $wholeRequest['product'];
        if (empty($post)) {
            $post = !empty($wholeRequest['variables']['product']) ? $wholeRequest['variables']['product'] : [];
        }
        $highlights = isset(
            $post[DynamicRowAttribute::PRODUCT_ATTRIBUTE_CODE]
        ) ? $post[DynamicRowAttribute::PRODUCT_ATTRIBUTE_CODE] : '';
        $product->setDynamicTab($highlights);
        $requiredParams = ['title', 'block'];
        if (is_array($highlights)) {
            $highlights = $this->removeEmptyArray($highlights, $requiredParams);
            $product->setDynamicTab($this->serializer->serialize($highlights));
        }
    }
    /**
     * Function to remove empty array from the multi dimensional array
     *
     * @param array $attractionData
     * @param array $requiredParams
     * @return array
     */
    private function removeEmptyArray($attractionData, $requiredParams)
    {
        $requiredParams = array_combine($requiredParams, $requiredParams);
        $reqCount = count($requiredParams);
        foreach ($attractionData as $key => $values) {
            $values = array_filter($values);
            $intersectCount = count(array_intersect_key($values, $requiredParams));
            if ($reqCount !== $intersectCount) {
                unset($attractionData[$key]);
            }
        }
        return $attractionData;
    }
}