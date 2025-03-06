<?php
namespace Nxtech\Contact\Api\Data;

interface ContactInterface
{

    const NAME = 'name';
    /**
     * Get name
     * @return string|null
     */
    public function getName();

    /**
     * Set name
     * @param string $name
     * @return \Nxtech\Contact\Api\Data\ContactInterface
     */
    public function setName($name);
}
