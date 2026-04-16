<?php

use think\migration\Seeder;
use think\facade\Db;

/**
 * 珍珠电商常用 SKU 规则模板，预置到 eb_store_product_rule。
 * 商户后台「规格模板」里可一键选用，不需要每次新建商品都手输 SKU。
 */
class PearlSkuRuleSeeder extends Seeder
{
    public function run()
    {
        $rules = [
            [
                'rule_name'  => '珍珠项链长度',
                'rule_value' => [
                    ['value' => '长度', 'detail' => ['40cm（锁骨链）', '45cm（标准）', '50cm（毛衣链）', '60cm（长款）']],
                ],
            ],
            [
                'rule_name'  => '珍珠耳饰针型',
                'rule_value' => [
                    ['value' => '针型', 'detail' => ['925银针', '14K金针', '18K金针', '耳夹（无耳洞）']],
                ],
            ],
            [
                'rule_name'  => '珍珠珠径',
                'rule_value' => [
                    ['value' => '珠径', 'detail' => ['5-6mm', '6-7mm', '7-8mm', '8-9mm', '9-10mm', '10-11mm', '11-12mm']],
                ],
            ],
            [
                'rule_name'  => '珍珠颜色',
                'rule_value' => [
                    ['value' => '颜色', 'detail' => ['白色', '粉色', '金色', '紫色', '黑色', '混彩']],
                ],
            ],
            [
                'rule_name'  => '珍珠珠形',
                'rule_value' => [
                    ['value' => '珠形', 'detail' => ['正圆', '近圆', '椭圆', '水滴', '巴洛克异形', '扁圆']],
                ],
            ],
            [
                'rule_name'  => '珍珠托材',
                'rule_value' => [
                    ['value' => '托材', 'detail' => ['S925银', 'K10金', 'K14金', 'K18金', '铜镀金', '包金']],
                ],
            ],
            [
                'rule_name'  => '珍珠综合（项链：长度+颜色）',
                'rule_value' => [
                    ['value' => '长度', 'detail' => ['40cm', '45cm', '50cm']],
                    ['value' => '颜色', 'detail' => ['白色', '粉色', '紫色']],
                ],
            ],
        ];

        foreach ($rules as $r) {
            $exists = Db::name('store_product_rule')->where('rule_name', $r['rule_name'])->value('id');
            if ($exists) continue;
            Db::name('store_product_rule')->insert([
                'rule_name'  => $r['rule_name'],
                'rule_value' => json_encode($r['rule_value'], JSON_UNESCAPED_UNICODE),
            ]);
        }
    }
}
