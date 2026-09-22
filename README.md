# SN Store — 通用 WooCommerce 模板主题

黑白 Apple 风格的 Storefront 子主题（`Template: storefront`），为「一套模板复制多个站点」设计：
所有站点差异（品牌、联系方式、首页板块、公告文案）全部在后台在线修改，无需改代码。

## 环境要求

- WordPress 6.0+ / WooCommerce 7.0+（父主题 Storefront 需已安装）
- PHP 7.4+

## 安装

1. 上传 `snstore` 文件夹到 `wp-content/themes/`（或后台上传 snstore.zip）
2. 启用主题 —— 激活时会自动完成：
   - 创建 8 个页面：Shipping Policy / Privacy Policy / Warranty Policy / Return & Refund
     Policy / Terms and Conditions / About Us / Contact Us / Payment Methods（已存在的同名页面不会被覆盖）
   - 创建 Blog 页并设为文章页
   - 创建 3 个菜单（Main Menu、Footer — Customer Service、Footer — Company）并指派到菜单位
   - 设置固定链接为 /%postname%/

## 每站复制清单（新站 5 分钟上线流程）

1. **外观 → 自定义 → Site Info**：填品牌名、邮箱、电话、地址、营业时间（不填邮箱则自动用 `info@当前域名`）
2. **设置 → 常规**：站点标题；**外观 → 自定义 → 站点身份**：可上传 Logo（默认用品牌名文字）
3. **外观 → 自定义 → Announcement Bar**：改公告文案（留空隐藏）
4. **外观 → 自定义 → Homepage**：按站点商品情况调整 Hero 轮播与 6 个板块槽位
5. **外观 → 菜单**：按站点分类调整 Main Menu
6. 商品分类设置分类缩略图（首页「分类瓷砖」和商店页使用）

## 后台修改位置速查

| 要改什么 | 在哪里 |
|---|---|
| 品牌 / 邮箱 / 电话 / 地址 / 营业时间 | 自定义 → Site Info — brand & contact |
| 顶部公告文案 | 自定义 → Announcement Bar |
| Hero 轮播（5 slide：图/标题/副题/按钮） | 自定义 → Homepage → Hero Banner |
| 服务条 4 项（图标 + 文字） | 自定义 → Homepage → Service Bar |
| 首页 6 个板块槽位 | 自定义 → Homepage → Homepage Section 1~6 |
| 页脚简介 / 社交 / 支付图标 / 版权 | 自定义 → Footer |
| 页脚两栏链接 | 外观 → 菜单（Footer — Customer Service / Footer — Company） |

## 站点信息变量（全站唯一数据源）

任何可编辑文本里都可以使用以下 token，渲染时自动替换：

- `{{brand}}` 品牌名（Site Info，缺省取站点标题）
- `{{domain}}` 当前域名（自动取数据库站点 URL，无需设置）
- `{{site_url}}` 完整站点 URL（自动）
- `{{email}}` `{{phone}}` `{{address}}` `{{hours}}` 联系信息（Site Info）
- `{{year}}` 当前年份

改一处全站生效：页脚、政策页正文、政策页底部 CONTACT 盒、联系页、公告栏。

## 政策页

- 模板「Policy Page」：正文 + 底部自动 CONTACT 信息盒（EMAIL / ADDRESS / PHONE /
  SERVICE HOURS 两列布局，字段为空自动隐藏）
- 模板「Contact Page」：联系信息卡 + 留言表单（表单提交发送到 Site Info 邮箱，
  收件邮箱为空时发给管理员邮箱）
- 8 个页面的默认文案随主题内置（英文通用条款），品牌/域名/联系方式均为 token，
  激活即有完整内容；后续可直接在页面编辑器里修改任意内容（改过内容的页面不会被激活逻辑覆盖）

## 首页板块槽位说明

每个槽位可独立启用并选择类型：

- **Product row**：数据源（最新 / 精选 / 促销 / 按分类 / 按标签）、数量、列数（2~5）、布局（网格 / 横向滚动）
- **Promo banner**：背景图（缺省渐变）+ 标题 + 文案 + 按钮
- **Category tiles**：最多 4 个分类图卡（逗号分隔分类 slug，留空自动取前 4 个分类）
- **Blog posts**：最新文章
- **Newsletter**：邮箱订阅（订阅者存于 `sn_newsletter_list` option）
- **Rich text**：自由 HTML 内容

数据源为空时商品行自动回退「最新商品」，整个板块完全无内容时自动隐藏。

## Hero 轮播默认图

主题内置 3 张 AI 生成的默认轮播图（`assets/img/hero/hero-1~3.jpg`，1920×820），
覆盖保温杯/随行杯、帆布包/双肩包、玻璃器皿三类商品场景，新站启用即有完整轮播。
每张图配默认文案（Welcome / New Collection / Mid-Season Sale），均可在
自定义 → Homepage → Hero Banner 中替换图片或修改文案（第 4、5 张默认关闭）。

促销横幅（Promo banner 槽位）默认使用内置通用背景图 `assets/img/promo-sale.jpg`，
可在槽位设置里替换。

## 动态产品 token（博客/富文本通用）

任何文章、页面、富文本槽位里可用以下 token，渲染时实时解析为当前站点商品
（按上架时间从新到旧取第 N 个；商品增减后自动跟随，无需改文章）：

- `{{product_image:N}}` 第 N 个商品的图片 URL（主图 → 相册图 → 内置兜底图）
- `{{product_name:N}}` 商品名
- `{{product_link:N}}` 商品链接
- `{{product_price:N}}` 价格文本

主题激活时自动创建 3 篇通用博客（Everyday Essentials / How to Choose Well /
Care & Keep），全部用上述 token 配图，任何站点开箱即用；文章归入 "Journal" 分类。
博客卡片无特色图时自动取正文第一张图；首页分类瓷砖无缩略图时自动取该分类
下随机商品的图片。注意：用编辑器手写这些 token 保存时若被安全过滤吞掉，
属 WordPress kses 行为，可在用户侧用插件放开 unfiltered_html。

## 商品详情页

- 左画廊（缩略列 + 主图切换）右购买区（数量步进器）
- 按钮：Add to Cart（正常加购）+ **Buy Now**（直接跳转结算页，简单/变量商品均支持）
- 相关商品 4 列（与全站商品卡同一样式）

## SEO

- 主题自带轻量 SEO 层（`inc/sn-seo.php`）：meta description、Open Graph/Twitter
  分享标签、WebSite/Organization JSON-LD、面包屑结构化数据；检测到 Yoast /
  Rank Math / AIOSEO / SEOPress 时自动让位，不再输出重复标签
- 商品详情页恢复输出 WooCommerce 的 Product 结构化数据（富摘要）
- 首页两个商品模块完全随机（每次刷新不同商品组合）；商店页排序不受影响
- 字体本地预加载（`<link rel="preload">`）+ `font-display: swap`
- **CF 忽略查询字符串的站点升级 CSS/JS 方法**：主题 enqueue 会优先加载
  `snstore-{版本号}.css/js`（如 `snstore-1.1.9.js`，见 `sn_asset_url()`），
  没有版本化副本时回退 `snstore.css/js?ver=`。在该类 CDN 上升级主题时，
  复制一份 `snstore-{新版本号}.css/js` 即可强制全量刷新
- 商品详情页选择属性后，组标签后显示所选值（如 "Color: Dark Gull Gray"），
  清除（Clear）后自动隐藏

## 维护要点

- 主题 slug：`snstore`，函数前缀：`sn_`，CSS 前缀：`sn-`，文本域：`snstore`
- **商品属性必须是全局属性（taxonomy）**：导入工具生成的"自定义属性"会让变体
  无法选择（Woo 11 已不支持自定义属性匹配）。症状：详情页胶囊点不动、加购按钮
  灰色。用 `_ref/live_convert_attributes.php`（改 wp-load 路径即可复用，幂等）
  一键把所有变体商品的自定义属性转成 pa_* 全局属性
- 商品详情主图 `object-fit: contain`（180px 内边距 + 浅灰底）：竖图/横图都完整
  显示不裁切；缩略图仍为方形裁切
- 主要文件：`inc/sn-site-info.php`（变量）、`inc/sn-customizer.php`（后台设置）、
  `inc/sn-policy-content.php`（政策默认内容）、`inc/sn-install.php`（激活自动化）、
  `inc/sn-template-tags.php`（商品卡/图标/板块渲染）
- 商品卡组件：`sn_product_card()`，全站（商店/首页板块/搜索/相关商品）共用
- 字体：Inter 可变字体本地 woff2（`assets/fonts/`），无外链依赖；Swiper 未使用（轮播为原生 JS）
