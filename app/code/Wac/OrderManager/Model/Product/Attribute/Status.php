<?php

namespace Wac\OrderManager\Model\Product\Attribute;

use Magento\Framework\App\ResourceConnection;

class Status
{
    protected $resourceConnection;

    public function __construct(
        ResourceConnection $resourceConnection,
        $tableName = null,
        $attributeId = null
    ) {
        $this->resourceConnection = $resourceConnection;
        $this->tableName = $tableName;
        $this->attributeId = $attributeId;
    }

    public function getValue($productId, $storeId = 0)
    {
        $connection = $this->resourceConnection->getConnection();
        $table = $connection->getTableName($this->tableName);
        $query = $connection->select()->from($table)->columns('value')
            ->where('entity_id=?', $productId)
            ->where('attribute_id=?', $this->attributeId)
            ->where('store_id=?', $storeId);
        $result = $connection->fetchAll($query);
        if (count($result) > 0) {
            return $result[0]['value'];
        }
        $query = $connection->select()->from($table)->columns('value')
            ->where('entity_id=?', $productId)
            ->where('attribute_id=?', $this->attributeId)
            ->where('store_id=0');
        $result = $connection->fetchAll($query);

        return $result[0]['value'];
    }
}
