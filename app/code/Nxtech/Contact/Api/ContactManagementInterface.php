<?php
namespace Nxtech\Contact\Api;

interface ContactManagementInterface
{
    /**
     * Save Contact
     * @param string $name
     * @param string $email
     * @param string $message
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function submitForm($name, $email, $message);
}