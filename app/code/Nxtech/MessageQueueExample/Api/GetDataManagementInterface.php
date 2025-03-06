<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Nxtech\MessageQueueExample\Api;

interface GetDataManagementInterface
{

    /**
     * POST for getData api
     * @param int $sku
     * @return string
     */
    public function getGetData($sku);
}
