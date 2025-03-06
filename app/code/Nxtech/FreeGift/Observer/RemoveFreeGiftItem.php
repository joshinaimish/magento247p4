<?php
namespace Nxtech\FreeGift\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Catalog\Model\ProductRepository;
use Magento\Checkout\Model\Cart;
use Magento\Framework\Exception\LocalizedException;

class RemoveFreeGiftItem implements ObserverInterface
{
    protected $formKey;
    protected $cart;
    protected $productRepository;

    public function __construct(
        \Magento\Framework\Data\Form\FormKey $formKey,
        Cart $cart,
        ProductRepository $productRepository
    ) {
        $this->formKey = $formKey;
        $this->cart = $cart;
        $this->productRepository = $productRepository;
    }

    public function execute(Observer $observer)
    {

        $freeGiftSku = 'free-gift';
        $freeProduct = $this->productRepository->get($freeGiftSku);
        $freeProductId = $freeProduct->getId(); // Free product ID
        $quote = $this->cart->getQuote();
        $delivery = 1;
        $hasEligibleProduct = false;

        // Check if there is an eligible product in the cart
        foreach ($quote->getAllVisibleItems() as $item) {
            if ($item->getProduct()->getDelivery() == $delivery) {
                $hasEligibleProduct = true;
                break;
            }
        }

        if (!$hasEligibleProduct) {
            // Remove free product from the cart if it exists
            foreach ($quote->getAllItems() as $item) {
                if ($item->getProductId() == $freeProductId) {
                    $this->cart->removeItem($item->getItemId())->save();
                    break;
                }
            }
        }
    }
}
