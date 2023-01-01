<?php

namespace Wac\OrderManager\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Catalog\Model\ResourceModel\Product;
use Magento\Framework\Session\SessionManagerInterface;
use Wac\OrderManager\Model\Product\Attribute\StatusFactory as ProductStatusAttributeFactory;

class StoreStatus extends Column
{
    protected $productResource;

    protected $sessionManager;

    protected $productStatusAttrFactory;

    /**
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        Product $productResource,
        SessionManagerInterface $sessionManager,
        ProductStatusAttributeFactory $productStatusAttrFactory,
        array $components = [],
        array $data = []
    ) {
        $this->productResource = $productResource;
        $this->sessionManager = $sessionManager;
        $this->productStatusAttrFactory = $productStatusAttrFactory;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        $this->sessionManager->start();
        $storeId = $this->sessionManager->getStoreId();
        $storeId = (int)($storeId ? $storeId : 0);
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                if (isset($item['entity_id'])) {
                    if ($storeId) {
                        $item[$this->getData('name')] = $this->productStatusAttrFactory->create()->getValue(
                            $item['entity_id'],
                            $storeId
                        );
                        /*$this->productResource->getAttributeRawValue(
                            $item['entity_id'],
                            'status',
                            $storeId
                        );*/
                    }
                }
            }
        }

        return $dataSource;
    }
}
