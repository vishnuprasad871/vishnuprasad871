<?php

namespace Wac\OrderManager\Ui\DataProvider;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollection;
use Magento\Framework\App\Request\Http;
use Magento\Store\Api\StoreRepositoryInterface;

class ProductList extends \Magento\Catalog\Ui\DataProvider\Product\ProductDataProvider
{
    /**
     * Product collection
     *
     * @var \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    protected $collection;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_registry;
    
    protected $request;

    protected $storeRepository;

    /**
     * Construct
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param ProductCollection $productCollection
     * @param CollectionFactory $collectionFactory
     * @param HelperData $helperData
     * @param \Magento\Framework\Registry $registry
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        ProductCollection $productCollection,
        \Magento\Framework\Registry $registry,
        Http $request,
        StoreRepositoryInterface $storeRepository,
        array $addFieldStrategies = [],
        array $addFilterStrategies = [],
        array $meta = [],
        array $data = []
    ) {
        $this->request = $request;
        $this->storeRepository= $storeRepository;
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $productCollection,
            $addFieldStrategies,
            $addFilterStrategies,
            $meta,
            $data
        );
        $storeCode = $request->getParam('store', null);
        $store = $this->getStoreFromCode($storeCode);

        $collectionData = $productCollection->create();
        $collectionData->setFlag('has_stock_status_filter', true);
        $collectionData->addFieldToSelect(['name', 'sku', 'status']);               
        if ($store) {
            $collectionData->addWebsiteFilter([$store]);
        }
        $this->collection = $collectionData;
        $this->addFieldStrategies = $addFieldStrategies;
        $this->addFilterStrategies = $addFilterStrategies;
    }

    protected function getStoreFromCode($storeCode)
    {
        if (empty($storeCode)) {
            return null;
        }
        try {
            $store = $this->storeRepository->get($storeCode);
            return $store->getWebsiteId();
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            throw new \Exception(
                __('No such store exist with this code.')
            );            
        }
        return null;
    }
}
