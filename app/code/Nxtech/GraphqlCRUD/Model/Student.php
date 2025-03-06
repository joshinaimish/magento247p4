<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Nxtech\GraphqlCRUD\Model;

use Magento\Framework\Model\AbstractModel;
use Nxtech\GraphqlCRUD\Api\Data\StudentInterface;

class Student extends AbstractModel implements StudentInterface
{

    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init(\Nxtech\GraphqlCRUD\Model\ResourceModel\Student::class);
    }

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->getData(self::ID);
    }

    /**
     * @inheritDoc
     */
    public function setId($Id)
    {
        return $this->setData(self::ID, $Id);
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return $this->getData(self::NAME);
    }

    /**
     * @inheritDoc
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * @inheritDoc
     */
    public function getCity()
    {
        return $this->getData(self::CITY);
    }

    /**
     * @inheritDoc
     */
    public function setCity($city)
    {
        return $this->setData(self::CITY, $city);
    }

    /**
     * @inheritDoc
     */
    public function getDegree()
    {
        return $this->getData(self::DEGREE);
    }

    /**
     * @inheritDoc
     */
    public function setDegree($degree)
    {
        return $this->setData(self::DEGREE, $degree);
    }
}

