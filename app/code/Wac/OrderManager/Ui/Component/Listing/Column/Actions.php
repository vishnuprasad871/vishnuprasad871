<?php

namespace Wac\OrderManager\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;

class Actions extends Column
{
    const URL_PATH_STATUS_CHANGE = 'ordermanager/products/statuschange';

    protected $urlBuilder;

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
        \Magento\Framework\UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
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
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                if (isset($item['entity_id'])) {
                    $actionLabel = ($item['status'] == 1 ? 'Disable' : 'Enable');
                    $item[$this->getData('name')] = [
                        'status_change' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_PATH_STATUS_CHANGE,
                                [
                                    'id' => $item['entity_id']
                                ]
                            ),
                            'label' => $actionLabel
                        ]
                    ];
                }
            }
        }

        return $dataSource;
    }
}
