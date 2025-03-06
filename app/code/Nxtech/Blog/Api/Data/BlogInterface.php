<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Nxtech\Blog\Api\Data;

interface BlogInterface
{

    const AUTHER = 'auther';
    const UPDATED_AT = 'updated_at';
    const CATEGORY = 'category';
    const ID = 'id';
    const CONTENT = 'content';
    const TITLE = 'title';
    const CREATED_AT = 'created_at';
    const STATUS = 'status';

    /**
     * Get id
     * @return string|null
     */
    public function getId();

    /**
     * Set id
     * @param string $id
     * @return \Nxtech\Blog\Blog\Api\Data\BlogInterface
     */
    public function setId($id);

    /**
     * Get title
     * @return string|null
     */
    public function getTitle();

    /**
     * Set title
     * @param string $title
     * @return \Nxtech\Blog\Blog\Api\Data\BlogInterface
     */
    public function setTitle($title);

    /**
     * Get content
     * @return string|null
     */
    public function getContent();

    /**
     * Set content
     * @param string $content
     * @return \Nxtech\Blog\Blog\Api\Data\BlogInterface
     */
    public function setContent($content);

    /**
     * Get category
     * @return string|null
     */
    public function getCategory();

    /**
     * Set category
     * @param string $category
     * @return \Nxtech\Blog\Blog\Api\Data\BlogInterface
     */
    public function setCategory($category);

    /**
     * Get auther
     * @return string|null
     */
    public function getAuther();

    /**
     * Set auther
     * @param string $auther
     * @return \Nxtech\Blog\Blog\Api\Data\BlogInterface
     */
    public function setAuther($auther);

    /**
     * Get status
     * @return string|null
     */
    public function getStatus();

    /**
     * Set status
     * @param string $status
     * @return \Nxtech\Blog\Blog\Api\Data\BlogInterface
     */
    public function setStatus($status);

    /**
     * Get created_at
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set created_at
     * @param string $createdAt
     * @return \Nxtech\Blog\Blog\Api\Data\BlogInterface
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
     * @return \Nxtech\Blog\Blog\Api\Data\BlogInterface
     */
    public function setUpdatedAt($updatedAt);
}

