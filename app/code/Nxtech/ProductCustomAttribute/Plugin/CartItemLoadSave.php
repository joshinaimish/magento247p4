<?php
namespace Nxtech\ProductCustomAttribute\Plugin;

use Magento\Quote\Api\Data\CartItemExtensionFactory;
use Magento\Quote\Api\Data\CartItemInterface;
use Magento\Quote\Model\Quote\Item\Repository as QuoteItemRepository;

class CartItemLoadSave
{
    public function __construct(protected CartItemExtensionFactory $extensionFactory)
    {
    }

    public function afterGetList(QuoteItemRepository $subject, $result)
    {
        foreach ($result as $cartItem) {
            $extensionAttributes = $cartItem->getExtensionAttributes();
            if ($extensionAttributes === null) {
                $extensionAttributes = $this->extensionFactory->create();
            }

            $delivery = $cartItem->getData('delivery');
            $extensionAttributes->setDelivery($delivery);
            $cartItem->setExtensionAttributes($extensionAttributes);
        }

        return $result;
    }

    /*public function beforeSave(QuoteItemRepository $subject, CartItemInterface $cartItem)
    {
        $extensionAttributes = $cartItem->getExtensionAttributes();
        if ($extensionAttributes && $extensionAttributes->getDelivery() !== null) {
            $cartItem->setData('delivery', $extensionAttributes->getDelivery());
        }
        return [$cartItem];
    }*/
}
