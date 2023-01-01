<?php

namespace Wac\OrderManager\Controller\Products;

use Magento\Framework\App\Action\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\Controller\ResultFactory; 
use Magento\Store\Api\StoreRepositoryInterface;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;

class Addtologs extends Action implements \Magento\Framework\App\CsrfAwareActionInterface
{
    protected $storeManager;

    protected $productFactory;

    protected $storeRepository;

    protected $jsonHelper;

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
        JsonHelper $jsonHelper,
        StoreRepositoryInterface $storeRepository
    ) {
        $this->storeManager = $storeManager;
        $this->productFactory = $productFactory;
        $this->productStockRepository = $productStockRepository;
        $this->_stockStateInterface = $stockStateInterface;
        $this->_stockRegistry = $stockRegistry;
        $this->storeRepository = $storeRepository;
        $this->jsonHelper = $jsonHelper;
        parent::__construct($context);
    }

    public function execute()
    {
        $storeId = 0;
        $post = $this->getRequest()->getPostValue();

        $book_id = (int)$post['book_id'];
       
        $product=$this->productFactory->create()->load($book_id); 
        $stockItem = $this->_stockRegistry->getStockItemBySku($product->getSku());
        $stockItem->setQty(0);
        $stockItem->setIsInStock(false); 
        $this->_stockRegistry->updateStockItemBySku($product->getSku(), $stockItem);
        $customer_name = $post['customer_name'];
        $card_number = $post['card_number'];
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        $this->messageManager->addSuccessMessage(
            __(' Added to Logs Sucessfully')
        );
       
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        return $resultRedirect;
    }
        /**
     * @param RequestInterface $request
     *
     * @return bool|null
     */
    public function validateForCsrf(RequestInterface $request): ?bool
    {
        return true;
    }

    /**
     * @param RequestInterface $request
     *
     * @return InvalidRequestException|null
     */
    public function createCsrfValidationException(RequestInterface $request): ?InvalidRequestException
    {
        return null;
    }
}
