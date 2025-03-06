<?php
namespace Nxtech\FreeGift\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Catalog\Model\ProductRepository;
use Magento\Checkout\Model\Cart;
use Magento\Framework\Exception\LocalizedException;

class UpdateCartItem implements ObserverInterface
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

        $freeGiftSku = 'gift';
        $freeProduct = $this->productRepository->get($freeGiftSku);
        $freeProductId = $freeProduct->getId();
        $quote = $this->cart->getQuote();
        $total = (float) 350;
        $quote->collectTotals();
        //$quoteTotal = (float) $quote->getGrandTotal();
        $quoteTotal = (float) $quote->getSubtotal();

        // Check if the free product is already in the cart
        $freeProductInCart = false;
        foreach ($quote->getAllItems() as $item) {
            if ($item->getProductId() == $freeProductId) {
                $freeProductInCart = true;
                break;
            }
        }

        if ($quoteTotal >= $total && !$freeProductInCart) {

            $params = [
                'form_key' => $this->formKey->getFormKey(),
                'product' => $freeProductId,
                'qty' => 1
            ];

            try {
                $this->cart->addProduct($freeProduct, $params);
                $this->cart->save();
                $quote->save();
            } catch (LocalizedException $e) {
                // Handle localized exception
            } catch (\Exception $e) {
                // Handle generic exception
            }
        } elseif ($quoteTotal < $total && $freeProductInCart) {

            // Remove free product from the cart if the threshold is no longer met
            foreach ($quote->getAllItems() as $item) {
                if ($item->getProductId() == $freeProductId) {
                    $this->cart->removeItem($item->getItemId())->save();
                    break;
                }
            }
        }
    }
}
