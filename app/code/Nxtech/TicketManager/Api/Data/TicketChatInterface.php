<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Nxtech\TicketManager\Api\Data;

interface TicketChatInterface
{

    const TICKETID = 'ticket_id';
    const UPDATED_AT = 'updated_at';
    const USER_ID = 'user_id';
    const USER_TYPE = 'user_type';
    const TICKETCHAT_ID = 'ticketchat_id';
    const CREATED_AT = 'created_at';
    const TICKET_DESCRIPTION = 'ticket_description';
    const ATTACHMENT = 'attachment';


    /**
     * Get ticketchat_id
     * @return string|null
     */
    public function getTicketchatId();

    /**
     * Set ticketchat_id
     * @param string $ticketchatId
     * @return \Nxtech\TicketManager\TicketChat\Api\Data\TicketChatInterface
     */
    public function setTicketchatId($ticketchatId);

    /**
     * Get ticketId
     * @return string|null
     */
    public function getTicketId();

    /**
     * Set ticketId
     * @param string $ticketId
     * @return \Nxtech\TicketManager\TicketChat\Api\Data\TicketChatInterface
     */
    public function setTicketId($ticketId);

    /**
     * Get user_id
     * @return string|null
     */
    public function getUserId();

    /**
     * Set user_id
     * @param string $userId
     * @return \Nxtech\TicketManager\TicketChat\Api\Data\TicketChatInterface
     */
    public function setUserId($userId);

    /**
     * Get user_type
     * @return string|null
     */
    public function getUserType();

    /**
     * Set user_type
     * @param string $userType
     * @return \Nxtech\TicketManager\TicketChat\Api\Data\TicketChatInterface
     */
    public function setUserType($userType);


    /**
     * Get ticket_description
     * @return string|null
     */
    public function getTicketDescription();

    /**
     * Set ticket_description
     * @param string $ticketDescription
     * @return \Nxtech\TicketManager\TicketChat\Api\Data\TicketChatInterface
     */
    public function setTicketDescription($ticketDescription);

    /**
     * Get attachment
     * @return string|null
     */
    public function getAttachment();

    /**
     * Set attachment
     * @param string $attachment
     * @return \Nxtech\TicketManager\TicketChat\Api\Data\TicketChatInterface
     */
    public function setAttachment($attachment);

    /**
     * Get created_at
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set created_at
     * @param string $createdAt
     * @return \Nxtech\TicketManager\TicketChat\Api\Data\TicketChatInterface
     */
    public function setCreatedAt($createdAt);

    /**
     * Get updated_at
     * @return string|null
     */
    public function getUpdatedAt();

    /**
     * Set updated_at
     * @param string $updatedAt
     * @return \Nxtech\TicketManager\TicketChat\Api\Data\TicketChatInterface
     */
    public function setUpdatedAt($updatedAt);
}

