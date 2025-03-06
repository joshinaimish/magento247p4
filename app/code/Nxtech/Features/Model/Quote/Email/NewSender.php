<?php

namespace Nxtech\Features\Model\Quote\Email;

class NewSender extends \Cart2Quote\Quotation\Model\Quote\Email\Sender\Sender
{
    use \Cart2Quote\Features\Traits\Model\Quote\Email\Sender\Sender {
        getSendEmailIdentifier as private traitGetSendEmailIdentifier;
        getEmailSentIdentifier as private traitGetEmailSentIdentifier;
        send as private traitSend;
        prepareTemplate as private traitPrepareTemplate;
        getPaymentHtml as private traitGetPaymentHtml;
    }
    /**
     * Override the prepareTemplate method
     *
     * @param \Cart2Quote\Quotation\Model\Quote $quote
     * @param mixed $internalEmail
     * @return void
     */
    public function prepareTemplate(\Cart2Quote\Quotation\Model\Quote $quote, $internalEmail = null)
    {
        // Custom logic for preparing the template
        $quote['user_id'] = 999;

        parent::prepareTemplate($quote, $internalEmail);
        // Optionally call the parent method if needed
        //$this->traitPrepareTemplate($quote, $internalEmail);
    }
}