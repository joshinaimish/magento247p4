<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Nxtech\TicketManager\Api\Data;

interface TicketManagerInterface
{

    const STATUS = 'status';
    const SUBJECT = 'subject';
    const UPDATED_AT = 'updated_at';
    const CATEGORY = 'category';
    const RATING = 'rating';
    const ID = 'id';
    const PRIORITY = 'priority';
    const CREATED_AT = 'created_at';
    const ATTACHMENT = 'attachment';
    const USER_ID = 'user_id';
    const USER_TYPE = 'user_type';
    /**
     * Get id
     * @return string|null
     */
    public function getId();

    /**
     * Set id
     * @param string $id
     * @return \Nxtech\TicketManager\TicketManager\Api\Data\TicketManagerInterface
     */
    public function setId($id);

    /**
     * Get category
     * @return string|null
     */
    public function getCategory();

    /**
     * Set category
     * @param string $category
     * @return \Nxtech\TicketManager\TicketManager\Api\Data\TicketManagerInterface
     */
    public function setCategory($category);

    /**
     * Get subject
     * @return string|null
     */
    public function getSubject();

    /**
     * Set subject
     * @param string $subject
     * @return \Nxtech\TicketManager\TicketManager\Api\Data\TicketManagerInterface
     */
    public function setSubject($subject);

    /**
     * Get priority
     * @return string|null
     */
    public function getPriority();

    /**
     * Set priority
     * @param string $priority
     * @return \Nxtech\TicketManager\TicketManager\Api\Data\TicketManagerInterface
     */
    public function setPriority($priority);

    /**
     * Get attachment
     * @return string|null
     */
    public function getAttachment();

    /**
     * Set attachment
     * @param string $attachment
     * @return \Nxtech\TicketManager\TicketManager\Api\Data\TicketManagerInterface
     */
    public function setAttachment($attachment);

    /**
     * Get user_id
     * @return string|null
     */
    public function getUserId();

    /**
     * Set user_id
     * @param string $userId
     * @return \Nxtech\TicketManager\TicketChat\Api\Data\TicketManagerInterface
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
     * @return \Nxtech\TicketManager\TicketChat\Api\Data\TicketManagerInterface
     */
    public function setUserType($userType);

    /**
     * Get status
     * @return string|null
     */
    public function getStatus();

    /**
     * Set status
     * @param string $status
     * @return \Nxtech\TicketManager\TicketManager\Api\Data\TicketManagerInterface
     */
    public function setStatus($status);

    /**
     * Get rating
     * @return string|null
     */
    public function getRating();

    /**
     * Set rating
     * @param string $rating
     * @return \Nxtech\TicketManager\TicketManager\Api\Data\TicketManagerInterface
     */
    public function setRating($rating);

    /**
     * Get created_at
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set created_at
     * @param string $createdAt
     * @return \Nxtech\TicketManager\TicketManager\Api\Data\TicketManagerInterface
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
     * @return \Nxtech\TicketManager\TicketManager\Api\Data\TicketManagerInterface
     */
    public function setUpdatedAt($updatedAt);
}

