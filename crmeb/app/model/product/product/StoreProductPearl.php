<?php

namespace app\model\product\product;

use crmeb\basic\BaseModel;
use crmeb\traits\ModelTrait;

/**
 * 珍珠商品扩展属性模型
 */
class StoreProductPearl extends BaseModel
{
    use ModelTrait;

    protected $pk = 'product_id';

    protected $name = 'store_product_pearl';
}
