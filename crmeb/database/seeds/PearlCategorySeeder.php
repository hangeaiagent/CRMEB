<?php

use think\migration\Seeder;
use think\facade\Db;

/**
 * AKOYA/珍珠垂直电商的商品分类 seed。
 * 插入 store_category 主树（按品类）。珍珠来源在扩展表里做筛选字段，不进分类树。
 */
class PearlCategorySeeder extends Seeder
{
    public function run()
    {
        $now = time();

        $tree = [
            ['珍珠首饰', 100, [
                ['项链', 99],
                ['手链', 98],
                ['耳环耳钉', 97],
                ['戒指', 96],
                ['胸针', 95],
                ['发饰', 94],
            ]],
            ['套装礼盒', 90, [
                ['母亲节套装', 89],
                ['婚嫁套装', 88],
                ['情侣套装', 87],
                ['自用日常套装', 86],
            ]],
            ['散珠原料', 80, [
                ['按克散珠', 79],
                ['按串散珠', 78],
                ['B2B批发', 77],
            ]],
            ['珍珠护肤', 70, [
                ['珍珠粉', 69],
                ['珍珠面膜', 68],
                ['精华液', 67],
                ['口服珍珠粉', 66],
            ]],
            ['珍珠文创', 60, [
                ['摆件', 59],
                ['挂件', 58],
                ['钥匙扣', 57],
                ['文玩', 56],
            ]],
            ['定制服务', 50, [
                ['个性刻字', 49],
                ['选珠定制', 48],
                ['尺寸修改', 47],
                ['礼物包装', 46],
            ]],
        ];

        Db::startTrans();
        try {
            foreach ($tree as $top) {
                [$name, $sort, $children] = $top;
                $pid = $this->upsertCate(0, $name, $sort, $now);
                foreach ($children as $child) {
                    [$cname, $csort] = $child;
                    $this->upsertCate($pid, $cname, $csort, $now);
                }
            }
            Db::commit();
        } catch (\Throwable $e) {
            Db::rollback();
            throw $e;
        }
    }

    private function upsertCate(int $pid, string $name, int $sort, int $now): int
    {
        $existing = Db::name('store_category')
            ->where('pid', $pid)
            ->where('cate_name', $name)
            ->value('id');

        if ($existing) {
            return (int)$existing;
        }

        return (int)Db::name('store_category')->insertGetId([
            'pid'       => $pid,
            'cate_name' => $name,
            'sort'      => $sort,
            'pic'       => '',
            'big_pic'   => '',
            'is_show'   => 1,
            'add_time'  => $now,
        ]);
    }
}
