<?php

namespace app\dao\product\product;

use app\dao\BaseDao;
use app\model\product\product\StoreProductPearl;

/**
 * 珍珠商品扩展 Dao
 */
class StoreProductPearlDao extends BaseDao
{
    protected function setModel(): string
    {
        return StoreProductPearl::class;
    }
}
