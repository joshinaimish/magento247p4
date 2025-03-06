<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Nxtech\GraphqlCRUD\Model\Resolver;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class Student implements ResolverInterface
{
    /*
      public function __construct(
          DataProvider\Student $studentDataProvider
      ) {
          $this->studentDataProvider = $studentDataProvider;
      } */

    /**
     * @inheritdoc
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        // $studentData = $this->studentDataProvider->getStudent();
        $studentData = [];
        $studentData['id'] = 1;
        $studentData['name'] = 'Naimish Joshi';
        $studentData['city'] = "Ahmedabad";
        $studentData['degree'] = "MCA";
        return $studentData;
    }
}

