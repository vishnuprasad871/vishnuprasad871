<?php

namespace Wac\OrderManager\Controller\Products;

use Magento\Framework\App\Action\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\Controller\ResultFactory; 
use Magento\Store\Api\StoreRepositoryInterface;

class StatusChange extends \Magento\Framework\App\Action\Action
{
    protected $storeManager;

    protected $productFactory;

    protected $storeRepository;

    /**
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        ProductFactory $productFactory,
        \Magento\InventoryApi\Api\StockRepositoryInterface $productStockRepository,
        \Magento\CatalogInventory\Api\StockStateInterface $stockStateInterface,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry, 
        StoreRepositoryInterface $storeRepository
    ) {
        $this->storeManager = $storeManager;
        $this->productFactory = $productFactory;
        $this->storeRepository = $storeRepository;
        $this->_stockStateInterface = $stockStateInterface;
        $this->_stockRegistry = $stockRegistry;
        $this->productStockRepository = $productStockRepository;

        parent::__construct($context);
    }

    public function execute()
    {
        $storeId = 0;
        $book_id = $this->getRequest()->getParam('id', null);
        $storeCode = $this->getRequest()->getParam('store', null);
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        if ($storeCode) {
            try {
                $store = $this->storeRepository->get($storeCode);
                $storeId = $store->getId();
            } catch (\Exception $e) {
                $storeId = 0;
            }
        }  
        if ($book_id) {
            try {     
                $product=$this->productFactory->create()->load($book_id); 
                 
                $stockItem = $this->_stockRegistry->getStockItemBySku($product->getSku());
                $stockItem->setQty(1);
                $stockItem->setIsInStock(true); 
                $this->_stockRegistry->updateStockItemBySku($product->getSku(), $stockItem);
                $this->messageManager->addSuccessMessage(
                    __('Successfuly updated the status')
                );
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(
                    __($e->getMessage())
                );
            }            
        }
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        return $resultRedirect;
    }
}
