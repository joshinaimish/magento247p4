<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Nxtech\TicketManager\Model\TicketManager;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Nxtech\TicketManager\Model\ResourceModel\TicketManager\CollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

class DataProvider extends AbstractDataProvider
{

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var array
     */
    protected $loadedData;
    /**
     * @inheritDoc
     */
    protected $collection;


    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        private StoreManagerInterface $storeManager,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * @inheritDoc
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();

        foreach ($items as $model) {
            $this->loadedData[$model->getId()] = $model->getData();
            if ($model->getAttachment()) {
                $m['attachment'][0]['name'] = $model->getAttachment();
                $m['attachment'][0]['url'] = $this->getMediaUrl($model->getAttachment());
                $fullData = $this->loadedData;
                $this->loadedData[$model->getId()] = array_merge($fullData[$model->getId()], $m);
            }
        }
        $data = $this->dataPersistor->get('ticketmanager');

        if (!empty($data)) {
            $model = $this->collection->getNewEmptyItem();
            $model->setData($data);
            $this->loadedData[$model->getId()] = $model->getData();
            $this->dataPersistor->clear('ticketmanager');
        }

        return $this->loadedData;
    }
    public function getMediaUrl($path = '')
    {
        $mediaUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'wysiwyg/ticketmanager/' . $path;
        return $mediaUrl;
    }

    /**
     * @inheritDoc
     */
    /*  public function getData()
     {
         if (isset($this->loadedData)) {
             return $this->loadedData;
         }
         $items = $this->collection->getItems();
         foreach ($items as $model) {
             $this->loadedData[$model->getId()] = $model->getData();
         }
         $data = $this->dataPersistor->get('ticketmanager');

         if (!empty($data)) {
             $model = $this->collection->getNewEmptyItem();
             $model->setData($data);
             $this->loadedData[$model->getId()] = $model->getData();
             $this->dataPersistor->clear('ticketmanager');
         }

         return $this->loadedData;
     } */
}

