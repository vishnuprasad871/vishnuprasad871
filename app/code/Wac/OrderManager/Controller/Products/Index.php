<?php

namespace Wac\OrderManager\Controller\Products;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\Controller\ResultFactory;
use Magento\Store\Model\StoreManagerInterface;


class Index extends \Magento\Framework\App\Action\Action
{
    protected $pageFactory;

    /**
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        StoreManagerInterface $storeManager,
        PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->customerSession = $customerSession;
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    public function execute()
    {


        $resultPage = $this->resultPageFactory->create();
        return $resultPage;
    }





}
