<?php
namespace Nxtech\RequestForQuote\Block\Adminhtml\Rfq;

use Magento\Backend\Block\Template;
use Nxtech\RequestForQuote\Model\Common;
use Nxtech\RequestForQuote\Api\RfqRepositoryInterface;

class CustomTab extends Template
{
    protected $_template = 'rfq_custom_tab.phtml';


    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        protected Common $common,
        protected RfqRepositoryInterface $rfqRepositoryInterface,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    public function getRfqInfo()
    {
        $rfqId = $this->getRequest()->getParam('id');
        $model = $this->rfqRepositoryInterface->get($rfqId);
        $productId = $model->getProductId();
        return $this->common->getProduct($productId);
    }

    public function getInfo()
    {
        return "called template";
    }

}