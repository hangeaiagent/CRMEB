<?php

namespace app\services\product\product;

use app\dao\product\product\StoreProductPearlDao;
use app\services\BaseServices;

/**
 * 珍珠商品扩展字段 Service
 */
class StoreProductPearlServices extends BaseServices
{
    /**
     * 允许写入扩展表的字段白名单
     * 防止前端任意塞 key 到 SQL
     */
    protected $allowFields = [
        'pearl_source', 'pearl_size', 'pearl_shape', 'luster', 'color',
        'surface_grade', 'metal_type', 'origin', 'cert_no', 'cert_org',
        'nacre_thickness_mm', 'strand_length_cm', 'pin_type', 'scene_tags',
    ];

    public function __construct(StoreProductPearlDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * 按 product_id upsert 扩展属性。
     * $data 为空数组时不做操作；全空白字段直接忽略（避免误覆盖已填数据）。
     */
    public function savePearl(int $productId, ?array $data): void
    {
        if ($productId <= 0 || empty($data)) {
            return;
        }

        $clean = [];
        foreach ($this->allowFields as $f) {
            if (array_key_exists($f, $data)) {
                $clean[$f] = $data[$f];
            }
        }
        if (empty($clean)) {
            return;
        }

        $now = time();
        $exists = $this->dao->get(['product_id' => $productId]);

        if ($exists) {
            $clean['updated_at'] = $now;
            $this->dao->update(['product_id' => $productId], $clean);
        } else {
            $clean['product_id'] = $productId;
            $clean['created_at'] = $now;
            $clean['updated_at'] = $now;
            $this->dao->save($clean);
        }
    }

    /**
     * 按 product_id 读取扩展属性，返回数组或 null。
     */
    public function getPearl(int $productId): ?array
    {
        $row = $this->dao->get(['product_id' => $productId]);
        return $row ? $row->toArray() : null;
    }

    /**
     * 批量读取，返回 product_id => 扩展字段 数组。列表页避免 N+1。
     */
    public function mapByProductIds(array $productIds): array
    {
        if (empty($productIds)) return [];
        $rows = $this->dao->selectList([['product_id', 'IN', $productIds]], '*', 0, 0, '', [], false);
        $rows = is_object($rows) ? $rows->toArray() : (array)$rows;
        $out = [];
        foreach ($rows as $r) {
            $out[(int)$r['product_id']] = $r;
        }
        return $out;
    }

    /**
     * 商品删除时连带清理扩展属性。
     */
    public function deleteByProduct(int $productId): void
    {
        if ($productId <= 0) return;
        $this->dao->delete(['product_id' => $productId]);
    }
}
