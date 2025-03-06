<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Nxtech\GraphqlCRUD\Api\Data;

interface StudentInterface
{

    const ID = 'id';
    const DEGREE = 'degree';
    const NAME = 'name';
    const CITY = 'city';

    /**
     * Get student_id
     * @return string|null
     */
    public function getId();

    /**
     * Set student_id
     * @param string $id
     * @return \Nxtech\GraphqlCRUD\Student\Api\Data\StudentInterface
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
     * @return \Nxtech\GraphqlCRUD\Student\Api\Data\StudentInterface
     */
    public function setName($name);

    /**
     * Get city
     * @return string|null
     */
    public function getCity();

    /**
     * Set city
     * @param string $city
     * @return \Nxtech\GraphqlCRUD\Student\Api\Data\StudentInterface
     */
    public function setCity($city);

    /**
     * Get Degree
     * @return string|null
     */
    public function getDegree();

    /**
     * Set Degree
     * @param string $degree
     * @return \Nxtech\GraphqlCRUD\Student\Api\Data\StudentInterface
     */
    public function setDegree($degree);
}

