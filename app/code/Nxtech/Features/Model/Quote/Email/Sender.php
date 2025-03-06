<?php

namespace Nxtech\Features\Model\Quote\Email;

use Cart2Quote\Quotation\Model\Quote\Email\AbstractSender as OriginalAbstractSender;
use Cart2Quote\Quotation\Model\Quote\Email\Sender\QuoteSenderInterface;

class Sender extends OriginalAbstractSender implements QuoteSenderInterface
{
    /**
     * Override the prepareTemplate method
     *
     * @param \Cart2Quote\Quotation\Model\Quote $quote
     * @param mixed|null $internalEmail
     * @return void
     */
    protected function prepareTemplate(\Cart2Quote\Quotation\Model\Quote $quote, $internalEmail)
    {
        // Custom logic for preparing the template
        // Example: Log a message or modify the behavior
        $logger = \Magento\Framework\App\ObjectManager::getInstance()->get(\Psr\Log\LoggerInterface::class);
        $logger->debug('Custom prepareTemplate called.');
        exit('called');
        // Optionally, call the parent method if needed
        // parent::prepareTemplate($quote, $internalEmail);
    }
}