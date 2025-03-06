<?php
namespace Nxtech\RequestForQuote\Model;

use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\Escaper;
//use Magento\Framework\Mail\Template\TransportBuilder;
use Nxtech\RequestForQuote\Model\Mail\TransportBuilder;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Psr\Log\LoggerInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;

//use Nxtech\RequestForQuote\Model\Mail\TransportBuilder;

class Common extends \Magento\Framework\Model\AbstractModel
{

    //const RFQ_EMAIL_TEMPLATE = 'rfq_general_rfq_email_template';
    const RFQ_EMAIL_TEMPLATE = 'rfq/general/rfq_email_template';
    const RFQ_SENDER_NAME = 'trans_email/ident_support/name';
    const RFQ_SENDER_EMAIL = 'trans_email/ident_support/email';

    public function __construct(
        protected StateInterface $inlineTranslation,
        protected Escaper $escaper,
        protected TransportBuilder $transportBuilder,
        protected ScopeConfigInterface $scopeConfig,
        protected LoggerInterface $logger,
        protected ProductRepositoryInterface $productRepository
    ) {
    }
    public function sendEmail($data)
    {
        $pdfFile = '/var/www/html/magento247-p1/pub/media/requestquote/sample.pdf';
        $fileContent = file_get_contents($pdfFile);
        $fileName = 'joshi.pdf';
        $senderEmail = $this->scopeConfig->getValue(self::RFQ_SENDER_EMAIL, ScopeInterface::SCOPE_STORE);
        $senderName = $this->scopeConfig->getValue(self::RFQ_SENDER_NAME, ScopeInterface::SCOPE_STORE);
        $templateId = $this->scopeConfig->getValue(self::RFQ_EMAIL_TEMPLATE, ScopeInterface::SCOPE_STORE);
        $productName = $this->getProduct($data['product_id']);
        $data['product_name'] = $productName;
        $toEmail = $data['email'];


        try {
            $this->inlineTranslation->suspend();
            $sender = [
                'name' => $this->escaper->escapeHtml($senderName),
                'email' => $this->escaper->escapeHtml($senderEmail),
            ];
            $transport = $this->transportBuilder
                ->setTemplateIdentifier($templateId)
                ->setTemplateOptions(
                    [
                        'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                        'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                    ]
                )
                ->setTemplateVars($data)
                ->setFrom($sender)
                ->addAttachment($fileContent, $fileName)
                ->addTo($toEmail)
                ->getTransport();
            $transport->sendMessage();
            $this->inlineTranslation->resume();
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
        }
    }

    public function getProduct($productId)
    {
        $product = $this->productRepository->getById($productId);
        return $product->getName();
    }
}