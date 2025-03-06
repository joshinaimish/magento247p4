<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Nxtech\CustomShipping\Model\Resolver;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Shipping\Model\ShippingMethodManagement;
use Magento\Quote\Model\QuoteRepository;
use Magento\Framework\Exception\LocalizedException;
use Magento\Checkout\Model\Session;


class AvailableShippingMethods implements ResolverInterface
{

    /**
     * @var ShippingMethodManagement
     */
    protected $shippingMethodManagement;

    /**
     * @var QuoteRepository
     */
    protected $quoteRepository;

    /**
     * @var Session
     */
    protected $checkoutSession;

    /**
     * Constructor
     *
     * @param ShippingMethodManagement $shippingMethodManagement
     * @param QuoteRepository $quoteRepository
     * @param Session $checkoutSession
     */
    public function __construct(
        ShippingMethodManagement $shippingMethodManagement,
        QuoteRepository $quoteRepository,
        Session $checkoutSession
    ) {
        $this->shippingMethodManagement = $shippingMethodManagement;
        $this->quoteRepository = $quoteRepository;
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * @inheritdoc
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        // Get the cart ID from the context (assuming guest or customer)
        //$cartId = $this->checkoutSession->getQuote()->getId();
        $cartId = $args['cart_id'];

        if (!$cartId) {
            throw new LocalizedException(__('No cart found'));
        }

        // Get the available shipping methods for the cart
        $quote = $this->quoteRepository->get($cartId);

        if (!$quote->getId()) {
            throw new LocalizedException(__('No cart found'));
        }

        $shippingMethods = $this->shippingMethodManagement->getAvailableShippingMethods($quote);

        // Format the result to match the GraphQL schema
        $methods = [];
        foreach ($shippingMethods as $method) {
            $methods[] = [
                'carrier_code' => $method->getCarrierCode(),
                'carrier_title' => $method->getCarrierTitle(),
                'method_code' => $method->getMethodCode(),
                'method_title' => $method->getMethodTitle(),
                'amount' => [
                    'value' => $method->getAmount(),
                    'currency' => $quote->getQuoteCurrencyCode()
                ],
                'price_excl_tax' => [
                    'value' => $method->getPriceExclTax(),
                    'currency' => $quote->getQuoteCurrencyCode()
                ],
                'price_incl_tax' => [
                    'value' => $method->getPriceInclTax(),
                    'currency' => $quote->getQuoteCurrencyCode()
                ],
                'error_message' => $method->getErrorMessage()
            ];
        }

        return $methods;
    }
}


