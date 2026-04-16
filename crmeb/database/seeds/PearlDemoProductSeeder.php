<?php

use think\migration\Seeder;
use think\facade\Db;

/**
 * 珍珠电商 demo 商品 seed。
 * 依赖 PearlCategorySeeder 已执行（靠类目名称查 id，不依赖固定 id）。
 * 图片先占位，等商品图就位后单独替换。
 */
class PearlDemoProductSeeder extends Seeder
{
    const PLACEHOLDER = '/uploads/placeholder/pearl.jpg';

    public function run()
    {
        $now = time();

        $products = [
            [
                'name'      => '强光近圆淡水珍珠项链 9-10mm',
                'info'      => '妈妈的第一条珍珠项链，强光近圆，性价比之选',
                'cate_top'  => '珍珠首饰', 'cate_leaf' => '项链',
                'price'     => 399.00, 'ot_price' => 599.00, 'cost' => 180.00, 'stock' => 80,
                'is_hot' => 1, 'is_best' => 1,
                'pearl'     => [
                    'pearl_source' => 'freshwater_no_nucleus', 'pearl_size' => '9-10mm',
                    'pearl_shape'  => 'near_round', 'luster' => 'strong', 'color' => 'white',
                    'surface_grade' => 'AAA', 'metal_type' => 's925', 'origin' => '中国·诸暨',
                    'strand_length_cm' => 45, 'scene_tags' => 'daily,gift_mom',
                ],
            ],
            [
                'name'      => '天然混彩爱迪生珍珠项链 11-12mm',
                'info'      => '糖果色巴洛克，年轻人的不撞款项链',
                'cate_top'  => '珍珠首饰', 'cate_leaf' => '项链',
                'price'     => 599.00, 'ot_price' => 899.00, 'cost' => 260.00, 'stock' => 50,
                'is_new' => 1, 'is_good' => 1,
                'pearl'     => [
                    'pearl_source' => 'freshwater_edison', 'pearl_size' => '11-12mm',
                    'pearl_shape'  => 'baroque', 'luster' => 'strong', 'color' => 'multi',
                    'surface_grade' => 'AA', 'metal_type' => 's925', 'origin' => '中国·诸暨',
                    'strand_length_cm' => 45, 'scene_tags' => 'daily,date',
                ],
            ],
            [
                'name'      => '巴洛克异形珍珠耳坠 法式新中式风',
                'info'      => '小红书爆款，每颗独一无二',
                'cate_top'  => '珍珠首饰', 'cate_leaf' => '耳环耳钉',
                'price'     => 149.00, 'ot_price' => 229.00, 'cost' => 55.00, 'stock' => 200,
                'is_hot' => 1,
                'pearl'     => [
                    'pearl_source' => 'freshwater_no_nucleus', 'pearl_size' => '8-10mm',
                    'pearl_shape'  => 'baroque', 'luster' => 'strong', 'color' => 'white',
                    'surface_grade' => 'AA', 'metal_type' => 'k_gold', 'origin' => '中国·诸暨',
                    'pin_type' => 'silver_pin', 'scene_tags' => 'daily,date',
                ],
            ],
            [
                'name'      => '碎银子×淡水珍珠手链 S925',
                'info'      => '小红书原创风格，轻奢叠戴必备',
                'cate_top'  => '珍珠首饰', 'cate_leaf' => '手链',
                'price'     => 199.00, 'ot_price' => 299.00, 'cost' => 75.00, 'stock' => 150,
                'is_hot' => 1, 'is_new' => 1,
                'pearl'     => [
                    'pearl_source' => 'freshwater_no_nucleus', 'pearl_size' => '5-6mm',
                    'pearl_shape'  => 'near_round', 'luster' => 'strong', 'color' => 'white',
                    'surface_grade' => 'A', 'metal_type' => 's925', 'origin' => '中国·诸暨',
                    'scene_tags' => 'daily,date',
                ],
            ],
            [
                'name'      => 'Akoya 母亲节三件套礼盒（项链+耳钉+手链）',
                'info'      => '日本Akoya强光正圆，礼盒包装，送妈妈的体面之选',
                'cate_top'  => '套装礼盒', 'cate_leaf' => '母亲节套装',
                'price'     => 1299.00, 'ot_price' => 1899.00, 'cost' => 680.00, 'stock' => 30,
                'is_hot' => 1, 'is_best' => 1, 'is_good' => 1,
                'pearl'     => [
                    'pearl_source' => 'akoya', 'pearl_size' => '7-8mm',
                    'pearl_shape'  => 'round', 'luster' => 'very_strong', 'color' => 'white',
                    'surface_grade' => 'AAA', 'metal_type' => 's925', 'origin' => '日本·三重县',
                    'cert_org' => 'NGTC', 'strand_length_cm' => 45,
                    'scene_tags' => 'gift_mom,wedding',
                ],
            ],
            [
                'name'      => '纯珍珠粉 15g 外用美白珍珠粉',
                'info'      => '复购爆款，敷面膜/调水乳直出光',
                'cate_top'  => '珍珠护肤', 'cate_leaf' => '珍珠粉',
                'price'     => 79.00, 'ot_price' => 129.00, 'cost' => 28.00, 'stock' => 500,
                'is_benefit' => 1, 'is_new' => 1,
                'pearl'     => [
                    'pearl_source' => 'freshwater_no_nucleus', 'origin' => '中国·诸暨',
                    'scene_tags' => 'daily',
                ],
            ],
        ];

        Db::startTrans();
        try {
            foreach ($products as $p) {
                $catePid = (int)Db::name('store_category')->where('pid', 0)->where('cate_name', $p['cate_top'])->value('id');
                $cateId  = (int)Db::name('store_category')->where('pid', $catePid)->where('cate_name', $p['cate_leaf'])->value('id');
                if (!$catePid || !$cateId) {
                    throw new \RuntimeException("分类未找到: {$p['cate_top']}/{$p['cate_leaf']}，请先跑 PearlCategorySeeder");
                }

                $existing = Db::name('store_product')->where('store_name', $p['name'])->value('id');
                if ($existing) continue;

                $productId = (int)Db::name('store_product')->insertGetId([
                    'mer_id'        => 0,
                    'image'         => self::PLACEHOLDER,
                    'slider_image'  => json_encode([self::PLACEHOLDER], JSON_UNESCAPED_SLASHES),
                    'store_name'    => $p['name'],
                    'store_info'    => $p['info'],
                    'keyword'       => '珍珠,' . $p['cate_leaf'],
                    'cate_id'       => (string)$cateId,
                    'price'         => $p['price'],
                    'ot_price'      => $p['ot_price'],
                    'cost'          => $p['cost'],
                    'unit_name'     => '件',
                    'stock'         => $p['stock'],
                    'is_show'       => 1,
                    'is_hot'        => $p['is_hot']     ?? 0,
                    'is_benefit'    => $p['is_benefit'] ?? 0,
                    'is_best'       => $p['is_best']    ?? 0,
                    'is_new'        => $p['is_new']     ?? 0,
                    'is_good'       => $p['is_good']    ?? 0,
                    'add_time'      => $now,
                    'spec_type'     => 0,
                    'spu'           => str_pad((string)random_int(1, 9999999999999), 13, '0', STR_PAD_LEFT),
                    'temp_id'       => 1,
                    'logistics'     => '1,2',
                    'freight'       => 2,
                ]);

                Db::name('store_product_cate')->insert([
                    'product_id' => $productId,
                    'cate_id'    => $cateId,
                    'cate_pid'   => $catePid,
                    'add_time'   => $now,
                    'status'     => 1,
                ]);

                Db::name('store_product_attr_value')->insert([
                    'product_id' => $productId,
                    'suk'        => '默认',
                    'stock'      => $p['stock'],
                    'price'      => $p['price'],
                    'ot_price'   => $p['ot_price'],
                    'cost'       => $p['cost'],
                    'image'      => self::PLACEHOLDER,
                    'unique'     => substr(md5($productId . microtime(true)), 0, 8),
                    'is_show'    => 1,
                ]);

                // 单规格商品在 CRMEB 里也要求有 attr + attr_result，否则商品详情页报错。
                Db::name('store_product_attr')->insert([
                    'product_id'  => $productId,
                    'attr_name'   => '规格',
                    'attr_values' => '默认',
                    'type'        => 0,
                ]);
                $attrResult = json_encode([
                    'attr'  => [[
                        'value'       => '规格',
                        'detailValue' => '',
                        'attrHidden'  => '1',
                        'detail'      => ['默认'],
                    ]],
                    'value' => [[
                        'value1'        => '默认',
                        'detail'        => ['规格' => '默认'],
                        'pic'           => self::PLACEHOLDER,
                        'price'         => (string)$p['price'],
                        'cost'          => (string)$p['cost'],
                        'ot_price'      => (string)$p['ot_price'],
                        'stock'         => (string)$p['stock'],
                        'bar_code'      => '',
                        'weight'        => '0',
                        'volume'        => '0',
                        'brokerage'     => '0',
                        'brokerage_two' => '0',
                        '_checked'      => '1',
                        'id'            => '0',
                    ]],
                ], JSON_UNESCAPED_UNICODE);
                Db::name('store_product_attr_result')->insert([
                    'product_id'  => $productId,
                    'result'      => $attrResult,
                    'change_time' => $now,
                    'type'        => 0,
                ]);

                $pearl = array_merge([
                    'pearl_source' => '', 'pearl_size' => '', 'pearl_shape' => '',
                    'luster' => '', 'color' => '', 'surface_grade' => '',
                    'metal_type' => '', 'origin' => '', 'cert_no' => '', 'cert_org' => '',
                    'nacre_thickness_mm' => 0, 'strand_length_cm' => 0,
                    'pin_type' => '', 'scene_tags' => '',
                ], $p['pearl']);
                $pearl['product_id'] = $productId;
                $pearl['created_at'] = $now;
                $pearl['updated_at'] = $now;

                Db::name('store_product_pearl')->insert($pearl);
            }
            Db::commit();
        } catch (\Throwable $e) {
            Db::rollback();
            throw $e;
        }
    }
}
