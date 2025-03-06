<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Nxtech\TicketManager\Controller\Adminhtml\TicketManager;

use Magento\Framework\Controller\ResultFactory;

class Upload extends \Magento\Backend\App\Action
{

	/**
	 * Upload constructor.
	 * @param \Magento\Backend\App\Action\Context $context
	 * @param \Nxtech\TicketManager\Model\ImageUploader $imageUploader
	 */
	public function __construct(
		\Magento\Backend\App\Action\Context $context,
		protected \Nxtech\TicketManager\Model\ImageUploader $imageUploader
	) {
		parent::__construct($context);
		$this->imageUploader = $imageUploader;
	}

	/**
	 * @return mixed
	 */
	public function _isAllowed()
	{
		return $this->_authorization->isAllowed('Nxtech_TicketManager::upload');
	}

	/**
	 * @return mixed
	 */
	public function execute()
	{
		try {
			$result = $this->imageUploader->saveFileToTmpDir('attachment');
			$result['cookie'] = [
				'name' => $this->_getSession()->getName(),
				'value' => $this->_getSession()->getSessionId(),
				'lifetime' => $this->_getSession()->getCookieLifetime(),
				'path' => $this->_getSession()->getCookiePath(),
				'domain' => $this->_getSession()->getCookieDomain(),
			];
		} catch (\Exception $e) {
			$result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
		}
		return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
	}
}