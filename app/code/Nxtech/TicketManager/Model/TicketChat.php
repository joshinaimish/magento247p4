<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Nxtech\TicketManager\Model;

use Magento\Framework\Model\AbstractModel;
use Nxtech\TicketManager\Api\Data\TicketChatInterface;

class TicketChat extends AbstractModel implements TicketChatInterface
{

    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init(\Nxtech\TicketManager\Model\ResourceModel\TicketChat::class);
    }

    /**
     * @inheritDoc
     */
    public function getTicketchatId()
    {
        return $this->getData(self::TICKETCHAT_ID);
    }

    /**
     * @inheritDoc
     */
    public function setTicketchatId($ticketchatId)
    {
        return $this->setData(self::TICKETCHAT_ID, $ticketchatId);
    }

    /**
     * @inheritDoc
     */
    public function getTicketId()
    {
        return $this->getData(self::TICKETID);
    }

    /**
     * @inheritDoc
     */
    public function setTicketId($ticketId)
    {
        return $this->setData(self::TICKETID, $ticketId);
    }

    /**
     * @inheritDoc
     */
    public function getUserId()
    {
        return $this->getData(self::USER_ID);
    }

    /**
     * @inheritDoc
     */
    public function setUserId($userId)
    {
        return $this->setData(self::USER_ID, $userId);
    }

    /**
     * @inheritDoc
     */
    public function getUserType()
    {
        return $this->getData(self::USER_TYPE);
    }

    /**
     * @inheritDoc
     */
    public function setUserType($userType)
    {
        return $this->setData(self::USER_TYPE, $userType);
    }

    /**
     * @inheritDoc
     */
    public function getTicketDescription()
    {
        return $this->getData(self::TICKET_DESCRIPTION);
    }

    /**
     * @inheritDoc
     */
    public function setTicketDescription($ticketDescription)
    {
        return $this->setData(self::TICKET_DESCRIPTION, $ticketDescription);
    }

    /**
     * @inheritDoc
     */
    public function getAttachment()
    {
        return $this->getData(self::ATTACHMENT);
    }

    /**
     * @inheritDoc
     */
    public function setAttachment($attachment)
    {
        return $this->setData(self::ATTACHMENT, $attachment);
    }

    /**
     * @inheritDoc
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * @inheritDoc
     */
    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }
}

