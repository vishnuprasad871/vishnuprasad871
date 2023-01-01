<?php

namespace Wac\OrderManager\Ui\Component\Listing\Column;

/**
 * Class status
 */
class Status implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $status = [
            ['label' => 'Disabled', 'value' => 2],
            ['label' => 'Enabled', 'value' => 1]
        ];
        return $status;
    }
}
