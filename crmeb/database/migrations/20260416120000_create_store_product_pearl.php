<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateStoreProductPearl extends Migrator
{
    public function change()
    {
        $table = $this->table('store_product_pearl', [
            'id'          => false,
            'primary_key' => ['product_id'],
            'engine'      => 'InnoDB',
            'charset'     => 'utf8mb4',
            'collation'   => 'utf8mb4_unicode_ci',
            'comment'     => '珍珠商品扩展属性表',
        ]);

        $table
            ->addColumn('product_id', 'integer', ['signed' => false, 'comment' => '关联 eb_store_product.id'])
            ->addColumn('pearl_source', 'string', ['limit' => 32, 'default' => '', 'comment' => '珍珠来源: freshwater_no_nucleus/freshwater_edison/akoya/south_sea/tahitian/nanhai'])
            ->addColumn('pearl_size', 'string', ['limit' => 16, 'default' => '', 'comment' => '珠径区间，如 7-8mm'])
            ->addColumn('pearl_shape', 'string', ['limit' => 16, 'default' => '', 'comment' => '珠形: round/near_round/oval/baroque/button'])
            ->addColumn('luster', 'string', ['limit' => 16, 'default' => '', 'comment' => '光泽: strong/very_strong/weak'])
            ->addColumn('color', 'string', ['limit' => 16, 'default' => '', 'comment' => '颜色: white/pink/gold/black/multi'])
            ->addColumn('surface_grade', 'string', ['limit' => 8, 'default' => '', 'comment' => '表面等级: AAA/AA/A'])
            ->addColumn('metal_type', 'string', ['limit' => 16, 'default' => '', 'comment' => '托材: s925/k_gold/gold_plated/copper_plated'])
            ->addColumn('origin', 'string', ['limit' => 64, 'default' => '', 'comment' => '产地'])
            ->addColumn('cert_no', 'string', ['limit' => 64, 'default' => '', 'comment' => '证书编号'])
            ->addColumn('cert_org', 'string', ['limit' => 32, 'default' => '', 'comment' => '发证机构 NGTC/GIA/CMA'])
            ->addColumn('nacre_thickness_mm', 'decimal', ['precision' => 6, 'scale' => 2, 'default' => 0, 'comment' => '珠层厚度(mm)'])
            ->addColumn('strand_length_cm', 'integer', ['default' => 0, 'comment' => '项链长度(cm) 40/45/50'])
            ->addColumn('pin_type', 'string', ['limit' => 16, 'default' => '', 'comment' => '耳饰针型: silver_pin/ear_clip'])
            ->addColumn('scene_tags', 'string', ['limit' => 128, 'default' => '', 'comment' => '场景标签 CSV: daily,gift_mom,date,wedding'])
            ->addColumn('created_at', 'integer', ['signed' => false, 'default' => 0, 'comment' => '创建时间'])
            ->addColumn('updated_at', 'integer', ['signed' => false, 'default' => 0, 'comment' => '更新时间'])
            ->addIndex(['pearl_source'], ['name' => 'idx_pearl_source'])
            ->addIndex(['pearl_shape'], ['name' => 'idx_pearl_shape'])
            ->addIndex(['color'], ['name' => 'idx_color'])
            ->addIndex(['surface_grade'], ['name' => 'idx_surface_grade'])
            ->create();
    }
}
