<?php

namespace Wac\OrderManager\Block;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Api\StoreRepositoryInterface;
use Magento\CatalogInventory\Model\Stock\StockItemRepository;

class Products extends Template
{
    protected $storeId;

    protected $productCollectionFactory;

    protected $storeRepository;

    public function __construct(
        Context $context,
        CollectionFactory $productCollectionFactory,
        StockItemRepository $stockItemRepository,
        StoreRepositoryInterface $storeRepository,
        array $data = []
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->stockItemRepository = $stockItemRepository;
        $this->storeRepository = $storeRepository;
        parent::__construct($context, $data);
    }

    public function getLoadedProductCollection()
    {
        $storeId = 0;
        $storeCode = $this->getRequest()->getParam('store', null);
        $searchQuery = $this->getRequest()->getParam('q', null);
        if ($storeCode) {
            try {
                $store = $this->storeRepository->get($storeCode);
                $storeId = $store->getId();
            } catch (\Exception $e) {
                $storeId = 0;
            }
        }
        $collection = $this->productCollectionFactory->create();
        $collection->setFlag('has_stock_status_filter', true);
        $collection->setStoreId($storeId);
        $collection->addFieldToSelect(['status', 'name', 'sku', 'entity_id','rack_number']);
        if (!empty($searchQuery)) {
            $collection->addFieldToFilter(
                [
                    ['attribute' => 'name', 'like' => '%' . $searchQuery . '%'],
                    ['attribute' => 'sku', 'like' => '%' . $searchQuery . '%']
                ]
            );
        }
        //$collection->addStoreFilter($storeId);

        return $collection;
    }

    public function getStore()
    {
        $storeCode = $this->getRequest()->getParam('store', null);
        return $storeCode;
    }
    
    public function getQuery()
    {
        $query = $this->getRequest()->getParam('q', '');
        return $query;
    }
    public function getStockItemInformation($productId)
    {
        return $this->stockItemRepository->get($productId);
    }
}
