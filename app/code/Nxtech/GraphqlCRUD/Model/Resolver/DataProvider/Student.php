<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Nxtech\GraphqlCRUD\Model\Resolver\DataProvider;

class Student
{

    public function __construct()
    {

    }

    public function getStudent()
    {
        $data = [];
        $data['student_id'] = "1";
        $data['name'] = "Naimish";
        return $data;
    }
}

