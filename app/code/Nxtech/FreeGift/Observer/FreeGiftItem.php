<?php
namespace Nxtech\FreeGift\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Catalog\Model\ProductRepository;
use Magento\Checkout\Model\Cart;
use Magento\Framework\Exception\LocalizedException;

class FreeGiftItem implements ObserverInterface
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
        $freeProductInCart = false;

        // Check if the free product is already in the cart
        foreach ($quote->getAllItems() as $item) {
            if ($item->getProductId() == $freeProductId) {
                return;
            }
        }

        foreach ($quote->getAllVisibleItems() as $item) {
            if ($item->getProduct()->getDelivery() == $delivery) {
                $hasEligibleProduct = true;
                break;
            }
        }

        if ($hasEligibleProduct) {
            $params = [
                'form_key' => $this->formKey->getFormKey(),
                'product' => $freeProductId,
                'qty' => 1
            ];
            try {
                //$freeProduct = $this->productRepository->getById($freeProductId);
                $this->cart->addProduct($freeProduct, $params);
                $this->cart->save();
                $quote->save();
            } catch (LocalizedException $e) {
                // Handle localized exception
            } catch (\Exception $e) {
                // Handle generic exception
            }
        }
    }
}
