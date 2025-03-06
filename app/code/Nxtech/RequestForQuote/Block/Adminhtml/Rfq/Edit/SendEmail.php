<?php
namespace Nxtech\RequestForQuote\Block\Adminhtml\Rfq\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class SendEmail extends GenericButton implements ButtonProviderInterface
{
    public function getButtonData()
    {
        return [
            'label' => __('SendEmail'),
            'class' => 'action-secondary',
            'on_click' => 'deleteConfirm(\'' . __(
                'Are you sure you want to send email?'
            ) . '\', \'' . $this->getSendEmailUrl() . '\')',
            'sort_order' => 10
        ];
    }

    /**
     * Get URL for delete button
     *
     * @return string
     */
    public function getSendEmailUrl()
    {
        return $this->getUrl('*/*/sendemail', ['id' => $this->getModelId()]);
    }
}