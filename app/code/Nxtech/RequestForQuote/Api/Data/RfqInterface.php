<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Nxtech\RequestForQuote\Api\Data;

interface RfqInterface
{

    const FILE_ATTACHMENT = 'file_attachment';
    const UPDATED_AT = 'updated_at';
    const NAME = 'name';
    const EMAIL = 'email';
    const TERM_CONDITION = 'term_condition';
    const DESCRIPTION = 'description';
    const COUNTRY = 'country';
    const PHONE = 'phone';
    const CREATED_AT = 'created_at';
    const ID = 'id';

    /**
     * Get id
     * @return string|null
     */
    public function getId();

    /**
     * Set id
     * @param string $id
     * @return \Nxtech\RequestForQuote\Rfq\Api\Data\RfqInterface
     */
    public function setId($id);

    /**
     * Get name
     * @return string|null
     */
    public function getName();

    /**
     * Set name
     * @param string $name
     * @return \Nxtech\RequestForQuote\Rfq\Api\Data\RfqInterface
     */
    public function setName($name);

    /**
     * Get email
     * @return string|null
     */
    public function getEmail();

    /**
     * Set email
     * @param string $email
     * @return \Nxtech\RequestForQuote\Rfq\Api\Data\RfqInterface
     */
    public function setEmail($email);

    /**
     * Get country
     * @return string|null
     */
    public function getCountry();

    /**
     * Set country
     * @param string $country
     * @return \Nxtech\RequestForQuote\Rfq\Api\Data\RfqInterface
     */
    public function setCountry($country);

    /**
     * Get phone
     * @return string|null
     */
    public function getPhone();

    /**
     * Set phone
     * @param string $phone
     * @return \Nxtech\RequestForQuote\Rfq\Api\Data\RfqInterface
     */
    public function setPhone($phone);

    /**
     * Get description
     * @return string|null
     */
    public function getDescription();

    /**
     * Set description
     * @param string $description
     * @return \Nxtech\RequestForQuote\Rfq\Api\Data\RfqInterface
     */
    public function setDescription($description);

    /**
     * Get term_condition
     * @return string|null
     */
    public function getTermCondition();

    /**
     * Set term_condition
     * @param string $termCondition
     * @return \Nxtech\RequestForQuote\Rfq\Api\Data\RfqInterface
     */
    public function setTermCondition($termCondition);

    /**
     * Get file_attachment
     * @return string|null
     */
    public function getFileAttachment();

    /**
     * Set file_attachment
     * @param string $fileAttachment
     * @return \Nxtech\RequestForQuote\Rfq\Api\Data\RfqInterface
     */
    public function setFileAttachment($fileAttachment);

    /**
     * Get created_at
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set created_at
     * @param string $createdAt
     * @return \Nxtech\RequestForQuote\Rfq\Api\Data\RfqInterface
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
     * @return \Nxtech\RequestForQuote\Rfq\Api\Data\RfqInterface
     */
    public function setUpdatedAt($updatedAt);
}

