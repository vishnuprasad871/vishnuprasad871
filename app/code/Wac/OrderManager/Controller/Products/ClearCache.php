<?php

namespace Wac\OrderManager\Controller\Products;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Cache\Manager as CacheManager;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Cache\Frontend\Pool;

class ClearCache extends Action
{
    protected $cacheManager;

    protected $cacheTypeList;

    protected $cacheFrontendPool;

    public function __construct(
        Context $context,
        CacheManager $cacheManager,
        TypeListInterface $cacheTypeList,
        Pool $cacheFrontendPool
    ) {
        $this->cacheManager = $cacheManager;
        $this->cacheTypeList = $cacheTypeList;
        $this->cacheFrontendPool = $cacheFrontendPool;

        parent::__construct($context);
    }

    public function execute()
    {        
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        //$this->cacheManager->flush($this->cacheManager->getAvailableTypes());

        $_types = [
            'config',
            'block_html',
            'full_page',
            'common_page_data_push'
        ];

        foreach ($_types as $type) {
            $this->cacheTypeList->cleanType($type);
        }
        foreach ($this->cacheFrontendPool as $cacheFrontend) {
            $cacheFrontend->getBackend()->clean();
        }

        $this->messageManager->addSuccessMessage(
            __('Successfully cleared cache.')
        );

        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        return $resultRedirect;
    }
}
