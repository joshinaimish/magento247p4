<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Nxtech\TicketManager\Model\ResourceModel\TicketChat;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{

    /**
     * @inheritDoc
     */
    protected $_idFieldName = 'ticketchat_id';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            \Nxtech\TicketManager\Model\TicketChat::class,
            \Nxtech\TicketManager\Model\ResourceModel\TicketChat::class
        );
    }
}

