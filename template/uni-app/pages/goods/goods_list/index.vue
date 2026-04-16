<template>
	<view class="wrapper" :style="colorStyle">
		<view class='productList'>
			<view class='search bg-color acea-row row-between-wrapper'>
				<view class='input acea-row row-between-wrapper'><text class='iconfont icon-sousuo'></text>
					<input :placeholder='$t(`搜索商品名称`)' placeholder-class='placeholder' confirm-type='search'
						name="search" :value='where.keyword' @confirm="searchSubmit"></input>
				</view>
				<view class='iconfont' :class='is_switch==true?"icon-pailie":"icon-tupianpailie"' @click='Changswitch'>
				</view>
			</view>

			<view class='nav acea-row row-middle'>
				<view class='item line1' :class='title ? "font-num":""' @click='set_where(1)'>
					{{title ? $t(title) : $t(`默认`)}}
				</view>
				<view class='item' @click='set_where(2)'>
					{{$t(`价格`)}}
					<image v-if="price==1" src='../../../static/images/up.png'></image>
					<image v-else-if="price==2" src='../../../static/images/down.png'></image>
					<image v-else src='../../../static/images/horn.png'></image>
				</view>
				<view class='item' @click='set_where(3)'>
					{{$t(`销量`)}}
					<image v-if="stock==1" src='../../../static/images/up.png'></image>
					<image v-else-if="stock==2" src='../../../static/images/down.png'></image>
					<image v-else src='../../../static/images/horn.png'></image>
				</view>
				<!-- down -->
				<view class='item' :class='nows ? "font-color":""' @click='set_where(4)'>{{$t(`新品`)}}</view>
			</view>
			<!-- 珍珠 demo: 品质筛选条 -->
			<scroll-view scroll-x="true" class="pearl-filter-bar" show-scrollbar="false">
				<view class="pearl-filter-group">
					<text class="label">珠源</text>
					<view v-for="o in sourceOptions" :key="o.v"
						class="chip" :class="{ on: where.pearl_source === o.v }"
						@click="togglePearl('pearl_source', o.v)">{{ o.t }}</view>
				</view>
				<view class="pearl-filter-group">
					<text class="label">珠形</text>
					<view v-for="o in shapeOptions" :key="o.v"
						class="chip" :class="{ on: where.pearl_shape === o.v }"
						@click="togglePearl('pearl_shape', o.v)">{{ o.t }}</view>
				</view>
				<view class="pearl-filter-group">
					<text class="label">颜色</text>
					<view v-for="o in colorOptions" :key="o.v"
						class="chip" :class="{ on: where.pearl_color === o.v }"
						@click="togglePearl('pearl_color', o.v)">{{ o.t }}</view>
				</view>
				<view class="pearl-filter-group">
					<text class="label">场景</text>
					<view v-for="o in sceneOptions" :key="o.v"
						class="chip scene" :class="{ on: where.scene_tag === o.v }"
						@click="togglePearl('scene_tag', o.v)">{{ o.t }}</view>
				</view>
			</scroll-view>
			<scroll-view :scroll-top="scrollTop" scroll-y="true" class="scroll-Y" @scroll="scroll"
				@scrolltolower="scrolltolower">
				<view class='list acea-row row-between-wrapper' :class='is_switch==true?"":"on"'>
					<view class='item' :class='is_switch==true?"":"on"' hover-class='none'
						v-for="(item,index) in productList" :key="index" @click="godDetail(item)">
						<view class='pictrue' :class='is_switch==true?"":"on"'>
							<image :src='item.image' :class='is_switch==true?"":"on"'></image>
							<span class="pictrue_log_class"
								:class="is_switch === true ? 'pictrue_log_big' : 'pictrue_log'"
								v-if="item.activity && item.activity.type === '1' && $permission('seckill')">{{$t(`秒杀`)}}</span>
							<span class="pictrue_log_class"
								:class="is_switch === true ? 'pictrue_log_big' : 'pictrue_log'"
								v-if="item.activity && item.activity.type === '2' && $permission('bargain')">{{$t(`砍价`)}}</span>
							<span class="pictrue_log_class"
								:class="is_switch === true ? 'pictrue_log_big' : 'pictrue_log'"
								v-if="item.activity && item.activity.type === '3' && $permission('combination')">{{$t(`拼团`)}}</span>
						</view>
						<view class='text' :class='is_switch==true?"":"on"'>
							<view class='name line2'>{{item.store_name}}</view>
							<pearl-label :pearl="item.pearl" />
							<view class='money pearl-price' :class='is_switch==true?"":"on"'>{{$t(`￥`)}}<text
									class='num'>{{item.price}}</text></view>
							<view class='vip acea-row row-between-wrapper' :class='is_switch==true?"":"on"'>
								<view class='vip-money' v-if="item.vip_price && item.vip_price > 0">
									{{$t(`￥`)}}{{item.vip_price}}
									<image src='../../../static/images/vip.png'></image>
								</view>
								<view v-else></view>
								<view>{{$t(`已售`)}} {{item.sales}}{{$t(item.unit_name) || $t(`件`)}}</view>
							</view>
						</view>
					</view>
					<view class='loadingicon acea-row row-center-wrapper' v-if='productList.length > 0'>
						<text class='loading iconfont icon-jiazai' :hidden='loading==false'></text>{{loadTitle}}
					</view>
				</view>

			</scroll-view>

		</view>
		<view class='noCommodity' v-if="productList.length==0 && where.page > 1">
			<view class='emptyBox'>
				<image :src="imgHost + '/statics/images/no-thing.png'"></image>
				<view class="tips">{{$t(`暂无商品，去看点别的吧`)}}</view>
			</view>
			<recommend :hostProduct="hostProduct"></recommend>
		</view>
		<!-- #ifndef MP -->
		<home></home>
		<!-- #endif -->
		<view v-if="scrollTopShow" class="back-top" @click="goTop">
			<text class="iconfont icon-xiangshang"></text>
		</view>
	</view>
</template>

<script>
	import home from '@/components/home';
	import {
		getProductslist,
		getProductHot
	} from '@/api/store.js';
	import recommend from '@/components/recommend';
	import PearlLabel from '@/components/pearlLabel/index.vue';
	import {
		mapGetters
	} from "vuex";
	import {
		goShopDetail
	} from '@/libs/order.js'
	import {
		HTTP_REQUEST_URL
	} from '@/config/app';
	import colors from '@/mixins/color.js';
	export default {
		computed: mapGetters(['uid']),
		components: {
			recommend,
			home,
			PearlLabel
		},
		mixins: [colors],
		data() {
			return {
				imgHost: HTTP_REQUEST_URL,
				productList: [],
				is_switch: true,
				where: {
					sid: 0,
					keyword: '',
					priceOrder: '',
					salesOrder: '',
					news: 0,
					page: 1,
					limit: 20,
					cid: 0,
					scene_tag: '',
					pearl_source: '',
					pearl_shape: '',
					pearl_color: '',
				},
				sourceOptions: [
					{ v: 'akoya', t: 'Akoya' },
					{ v: 'tahitian', t: '大溪地' },
					{ v: 'south_sea', t: '南洋' },
					{ v: 'freshwater_edison', t: '爱迪生' },
					{ v: 'freshwater_no_nucleus', t: '淡水' },
				],
				shapeOptions: [
					{ v: 'round', t: '正圆' },
					{ v: 'near_round', t: '近圆' },
					{ v: 'baroque', t: '巴洛克' },
					{ v: 'oval', t: '椭圆' },
					{ v: 'drop', t: '水滴' },
				],
				colorOptions: [
					{ v: 'white', t: '白' },
					{ v: 'pink', t: '粉' },
					{ v: 'gold', t: '金' },
					{ v: 'purple', t: '紫' },
					{ v: 'black', t: '黑' },
					{ v: 'multi', t: '混彩' },
				],
				sceneOptions: [
					{ v: 'gift_mom', t: '送妈妈' },
					{ v: 'wedding',  t: '婚嫁' },
					{ v: 'daily',    t: '日常通勤' },
					{ v: 'date',     t: '约会礼物' },
					{ v: 'work',     t: '职场' },
				],
				price: 0,
				stock: 0,
				nows: false,
				loadend: false,
				loading: false,
				loadTitle: this.$t(`加载更多`),
				title: '',
				hostProduct: [],
				hotPage: 1,
				hotLimit: 10,
				hotScroll: false,
				scrollTop: 0,
				old: {
					scrollTop: 0
				},
				scrollTopShow: false
			};
		},
		onLoad: function(options) {
			this.where.cid = options.cid || 0;
			this.where.coupon_category_id = options.coupon_category_id || '';
			this.$set(this.where, 'sid', options.sid || 0);
			this.title = options.title ? decodeURIComponent(options.title) : '';
			this.$set(this.where, 'keyword', options.searchValue || '');
			this.$set(this.where, 'productId', options.productId || '');
			this.$set(this.where, 'scene_tag', options.scene_tag || '');
			this.get_product_list();
		},
		methods: {
			scroll(e) {
				this.scrollTopShow = e.detail.scrollTop > 150
				this.old.scrollTop = e.detail.scrollTop
			},
			goTop(e) {
				// 解决view层不同步的问题
				this.scrollTop = this.old.scrollTop
				this.$nextTick(() => {
					this.scrollTop = 0
				});
			},
			// 去详情页
			godDetail(item) {
				goShopDetail(item, this.uid).then(res => {
					uni.navigateTo({
						url: `/pages/goods_details/index?id=${item.id}`
					})
				})
			},
			Changswitch: function() {
				let that = this;
				that.is_switch = !that.is_switch
			},
			togglePearl(key, value) {
				this.$set(this.where, key, this.where[key] === value ? '' : value);
				this.$set(this.where, 'page', 1);
				this.productList = [];
				this.loadend = false;
				this.get_product_list(true);
			},
			searchSubmit: function(e) {
				let that = this;
				that.$set(that.where, 'keyword', e.detail.value);
				that.loadend = false;
				that.$set(that.where, 'page', 1)
				this.get_product_list(true);
			},
			/**
			 * 获取我的推荐
			 */
			get_host_product: function() {
				let that = this;
				if (that.hotScroll) return
				getProductHot(
					that.hotPage,
					that.hotLimit,
				).then(res => {
					that.hotPage++
					that.hotScroll = res.data.length < that.hotLimit
					that.hostProduct = that.hostProduct.concat(res.data)
					// that.$set(that, 'hostProduct', res.data)
				});
			},
			//点击事件处理
			set_where: function(e) {
				switch (e) {
					case 1:
						// #ifdef H5
						return history.back();
						// #endif
						// #ifndef H5
						return uni.navigateBack({
							delta: 1,
						})
						// #endif
						break;
					case 2:
						if (this.price == 0) this.price = 1;
						else if (this.price == 1) this.price = 2;
						else if (this.price == 2) this.price = 0;
						this.stock = 0;
						break;
					case 3:
						if (this.stock == 0) this.stock = 1;
						else if (this.stock == 1) this.stock = 2;
						else if (this.stock == 2) this.stock = 0;
						this.price = 0
						break;
					case 4:
						this.nows = !this.nows;
						break;
				}
				this.loadend = false;
				this.$set(this.where, 'page', 1);
				this.get_product_list(true);
			},
			//设置where条件
			setWhere: function() {
				if (this.price == 0) this.where.priceOrder = '';
				else if (this.price == 1) this.where.priceOrder = 'asc';
				else if (this.price == 2) this.where.priceOrder = 'desc';
				if (this.stock == 0) this.where.salesOrder = '';
				else if (this.stock == 1) this.where.salesOrder = 'asc';
				else if (this.stock == 2) this.where.salesOrder = 'desc';
				this.where.news = this.nows ? 1 : 0;
			},
			//查找产品
			get_product_list: function(isPage) {
				let that = this;
				that.setWhere();
				if (that.loadend) return;
				if (that.loading) return;
				if (isPage === true) that.$set(that, 'productList', []);
				that.loading = true;
				that.loadTitle = '';
				getProductslist(that.where).then(res => {
					let list = res.data;
					let productList = that.$util.SplitArray(list, that.productList);
					let loadend = list.length < that.where.limit;
					that.loadend = loadend;
					that.loading = false;
					that.loadTitle = loadend ? that.$t(`没有更多内容啦~`) : that.$t(`加载更多`);
					that.$set(that, 'productList', productList);
					that.$set(that.where, 'page', that.where.page + 1);
					if (!that.productList.length) this.get_host_product();
				}).catch(err => {
					that.loading = false;
					that.loadTitle = that.$t(`加载更多`);
				});
			},
			scrolltolower() {
				if (this.productList.length > 0) {
					this.get_product_list();
					uni.$emit('scroll');
				} else {
					this.get_host_product();
					uni.$emit('scroll');
				}
			}
		},
		onPullDownRefresh() {},
		onReachBottom() {},
		// 滚动监听
		onPageScroll(e) {
			// 传入scrollTop值并触发所有easy-loadimage组件下的滚动监听事件
			uni.$emit('scroll');
		},
	}
</script>

<style scoped lang="scss">
	.scroll-Y {
		margin-top: 86rpx;
		height: calc(100vh - 43rpx);
	}

	.wrapper {
		position: relative;
		max-height: 100vh;
		overflow: hidden;

		.back-top {
			position: absolute;
			right: 40rpx;
			bottom: 60rpx;
			width: 60rpx;
			height: 60rpx;
			border-radius: 50%;
			display: flex;
			justify-content: center;
			align-items: center;
			border: 1rpx solid #ccc;
			background-color: #fff;

			.icon-xiangshang {
				color: #ccc;
				font-weight: bold;
			}
		}
	}

	.productList .search {
		width: 100%;
		height: 86rpx;
		padding-left: 23rpx;
		box-sizing: border-box;
		position: fixed;
		left: 0;
		top: 0;
		z-index: 9;
	}

	.productList .search .input {
		width: 640rpx;
		height: 60rpx;
		background-color: #fff;
		border-radius: 50rpx;
		padding: 0 20rpx;
		box-sizing: border-box;
	}

	.productList .search .input input {
		width: 548rpx;
		height: 100%;
		font-size: 26rpx;
	}

	.productList .search .input .placeholder {
		color: #999;
	}

	.productList .search .input .iconfont {
		font-size: 35rpx;
		color: #555;
	}

	.productList .search .icon-pailie,
	.productList .search .icon-tupianpailie {
		color: #fff;
		width: 62rpx;
		font-size: 40rpx;
		height: 86rpx;
		line-height: 86rpx;
	}

	.productList .nav {
		height: 86rpx;
		color: #454545;
		position: fixed;
		left: 0;
		width: 100%;
		font-size: 28rpx;
		background-color: #fff;
		margin-top: 86rpx;
		top: 0;
		z-index: 9;
	}

	.productList .nav .item {
		width: 25%;
		text-align: center;
	}

	.productList .nav .item.font-color {
		font-weight: bold;
	}

	.productList .nav .item image {
		width: 15rpx;
		height: 19rpx;
		margin-left: 10rpx;
	}

	.productList .list {
		padding: 0 20rpx 30rpx 20rpx;
		margin-top: 86rpx;
	}

	.productList .list.on {
		background-color: #fff;
		border-top: 1px solid #f6f6f6;
	}

	.productList .list .item {
		width: 345rpx;
		margin-top: 20rpx;
		background-color: #fff;
		border-radius: 8rpx;
		border: 1rpx solid #E0D8CF;
		overflow: hidden;
		transition: all 0.2s;
	}

	.productList .list .item.on {
		width: 100%;
		display: flex;
		border-bottom: 1rpx solid #f6f6f6;
		padding: 30rpx 0;
		margin: 0;
	}

	.productList .list .item .pictrue {
		position: relative;
		width: 100%;
		height: 345rpx;

	}

	.productList .list .item .name {
		line-height: 42rpx;
		height: 84rpx;
	}

	.productList .list .item .pictrue.on {
		width: 180rpx;
		height: 180rpx;
	}

	.productList .list .item .pictrue image {
		width: 100%;
		height: 100%;
		border-radius: 0;
	}

	.productList .list .item .pictrue image.on {
		border-radius: 6rpx;
	}

	.productList .list .item .text {
		padding: 22rpx 22rpx 26rpx;
		font-size: 28rpx;
		color: #1C1917;
		font-family: 'STSong', 'Songti SC', 'Noto Serif SC', serif;
	}

	.productList .list .item .text.on {
		width: 508rpx;
		padding: 0 0 0 22rpx;
	}

	.productList .list .item .text .money {
		font-size: 26rpx;
		font-weight: 600;
		color: #B8924A;
		margin-top: 10rpx;
		font-family: 'STSong', 'Songti SC', 'Noto Serif SC', serif;
	}

	.productList .list .item .text .money.on {
		margin-top: 50rpx;
	}

	.productList .list .item .text .money .num {
		font-size: 34rpx;
	}

	.productList .list .item .text .vip {
		font-size: 22rpx;
		color: #aaa;
		margin-top: 7rpx;
	}

	.productList .list .item .text .vip.on {
		margin-top: 12rpx;
	}

	.productList .list .item .text .vip .vip-money {
		font-size: 24rpx;
		color: #282828;
		font-weight: bold;
		display: flex;
		align-items: center;
	}

	.productList .list .item .text .vip .vip-money image {
		width: 64rpx;
		height: 26rpx;
		margin-left: 4rpx;
	}

	.noCommodity {
		background-color: #fff;
		padding-bottom: 30rpx;

		.emptyBox {
			text-align: center;
			padding-top: 20rpx;

			.tips {
				color: #aaa;
				font-size: 26rpx;
			}

			image {
				width: 414rpx;
				height: 304rpx;
			}
		}
	}

	.pearl-filter-bar {
		white-space: nowrap;
		background: #FAF7F2;
		padding: 18rpx 20rpx;
		border-bottom: 1rpx solid #E0D8CF;
	}

	.pearl-filter-group {
		display: inline-flex;
		align-items: center;
		margin-right: 28rpx;

		.label {
			font-size: 22rpx;
			color: #7A6E68;
			letter-spacing: 2rpx;
			margin-right: 14rpx;
		}

		.chip {
			display: inline-block;
			padding: 8rpx 22rpx;
			margin-right: 10rpx;
			font-size: 22rpx;
			color: #3D3530;
			background: #fff;
			border: 1rpx solid #E0D8CF;
			border-radius: 4rpx;
			letter-spacing: 1rpx;

			&.on {
				background: #B8924A;
				color: #fff;
				border-color: #B8924A;
			}

			&.scene {
				color: #4A6741;
				background: #E8F0E6;
				border-color: #D4E1D2;

				&.on {
					background: #4A6741;
					color: #fff;
					border-color: #4A6741;
				}
			}
		}
	}
</style>
