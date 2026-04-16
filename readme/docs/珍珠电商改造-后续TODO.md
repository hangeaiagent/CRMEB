# 珍珠电商改造 - 后续 TODO

本轮（2026-04-16）已落地：

- `crmeb/database/migrations/20260416120000_create_store_product_pearl.php` — 珍珠扩展属性表
- `crmeb/database/seeds/PearlCategorySeeder.php` — 分类树 seed（按品类，6 个一级类目）
- `crmeb/crmeb/command/PearlImage.php` + `config/console.php` 注册 — Unsplash 合法拉图命令

## 执行顺序

```bash
cd crmeb
php think migrate:run                                    # 建扩展表
php think seed:run -s PearlCategorySeeder                # 灌分类
# .env 加 [UNSPLASH] access_key=xxx  （https://unsplash.com/developers 申请）
php think pearl:image --query "akoya pearl necklace" --count 10 --register
php think pearl:image --query "baroque pearl earring" --count 10 --register
php think pearl:image --query "freshwater pearl bracelet" --count 10 --register
```

## 本轮未实现（下一轮）

### 1. 开贝秀（UGC 爆款流量入口）
- 新表 `eb_pearl_opening_show`：user_id, order_id, video_url, images(json), pearl_size, weight_g, nice_count, audit_status, created_at
- 前端：uni-app 首页新增「开贝秀」tab，瀑布流视频+图片
- 后端：adminapi `controller/v1/pearl/OpeningShow.php` 审核
- 关联订单发货后触发拍摄引导

### 2. 珍珠百科 / 科普区
- 直接复用现有 `eb_article` + `eb_article_category`
- 新增一级文章分类「珍珠百科」，子类：鉴别真假 / 保养方法 / 光泽判断 / 搭配指南
- 前端商品详情页折叠展示「养护指南」，读对应分类最新 3 篇

### 3. 场景化入口（日常/送妈妈/约会/婚礼）
- 不加表。直接用 `eb_store_product_pearl.scene_tags` CSV 字段
- 前端 `/api/pearl/scene?tag=gift_mom` 新接口，按标签倒序销量
- 首页 banner 下方加 4 个场景卡片（diy 页面可视化拖拽）

### 4. 品质可视化标签（列表页）
- 商品卡片组件改造：price 上方新增一行「7-8mm · 强光 · 近圆」
- 读 `store_product_pearl` 对应字段，前端统一 formatter

### 5. SKU 扩展（尺寸/串长/针型）
- 不新建表。用现有 `store_product_rule` 规则模板预置：
  - 项链长度规则：40cm / 45cm / 50cm / 60cm
  - 耳饰针型规则：925银针 / 纯金针 / 耳夹
  - 珠径规则：5-6 / 6-7 / 7-8 / 8-9 / 9-10
- 建议放到 `PearlCategorySeeder` 之后再加一个 `PearlSkuRuleSeeder`

### 6. 珍珠来源分类视图
- 本轮只做了「按品类」主树。按来源（Akoya/南洋/大溪地/淡水）筛选走 `pearl_source` 扩展字段，不进 store_category
- 前端分类页左侧筛选增加「珍珠来源」多选组件

## 视觉风格（template/admin 和 uni-app 都要改）
- 主色：米白 #F8F5EE，点缀淡金 #C9A96E
- 字体：细衬线（Noto Serif SC / Source Han Serif）
- 避开传统珠宝的深红深金，参照阮仕珍珠
- uni-app `pages.json` 的 tabBar 图标要换

## 首页 banner 切换成珍珠风格（纯素材活）

本轮未改 banner 图片 —— 需要你准备 3~5 张珍珠风格图（建议 750×400，背景米白/珍珠白，模特佩戴/珠粒特写/礼盒场景任选）。落地步骤：

1. 登录 `http://localhost:8011/admin` → **设计** → **页面DIY** → **商城首页**
2. 找到顶部「轮播图」组件（swipers），逐张替换图片 URL（先走 **素材** → **上传**）
3. 文案建议：
   - 图1：主视觉 —— 「AKOYA 源自日本三重县 · 手工穿串」
   - 图2：场景 —— 「给妈妈的第一条珍珠 · 母亲节臻享」
   - 图3：工艺 —— 「7-8mm 强光正圆 · NGTC 鉴定」
4. **标题** 与 **分类金刚区** 的字体色号也可在 DIY 编辑器改，主色用 `#8a6d3b`（深金）

代码端已经把首页静态注入的「场景购珠」四卡放在 banner 下方（`pages/index/index.vue` 的 `<scene-entry />`），图换完整体就是珍珠风。

> 如果你想完全禁用 DIY 托管的商品推荐模块，只保留 banner + 场景入口 + 商品流，把 admin → 设计 → 页面DIY 里除了 swipers 以外的块拖掉即可。
