<template>
	<view class="pearl-cat-nav">
		<view class="title-row">
			<text class="zh">精选珍珠</text>
			<text class="sep">·</text>
			<text class="en">匠心臻品</text>
		</view>
		<scroll-view scroll-x="true" class="cat-scroll" show-scrollbar="false">
			<view class="cat-tabs">
				<view v-for="c in cats" :key="c.key" class="cat-tab" :class="{ active: active === c.key }" @click="go(c)">
					{{ c.zh }}
				</view>
			</view>
		</scroll-view>
	</view>
</template>

<script>
export default {
	name: 'pearlCategoryNav',
	data() {
		return {
			active: 'all',
			cats: [
				{ key: 'all',     zh: '全部商品', kw: '' },
				{ key: 'necklace', zh: '珍珠项链', kw: '项链' },
				{ key: 'earring',  zh: '耳饰耳钉', kw: '耳' },
				{ key: 'bracelet', zh: '手链手镯', kw: '手链' },
				{ key: 'pendant',  zh: '吊坠胸针', kw: '吊坠' },
				{ key: 'set',      zh: '礼品套装', kw: '套装' },
				{ key: 'skincare', zh: '珍珠护肤', kw: '珍珠粉' },
			],
		};
	},
	methods: {
		go(c) {
			this.active = c.key;
			if (c.key === 'all') {
				uni.switchTab({ url: '/pages/goods_cate/goods_cate' }).catch(() => {
					uni.navigateTo({ url: '/pages/goods/goods_list/index?title=' + encodeURIComponent('全部珍珠') });
				});
				return;
			}
			uni.navigateTo({
				url: '/pages/goods/goods_list/index?title=' + encodeURIComponent(c.zh) + '&searchValue=' + encodeURIComponent(c.kw),
			});
		},
	},
};
</script>

<style scoped lang="scss">
.pearl-cat-nav {
	background: #fff;
	margin: 20rpx 20rpx 0;
	border-radius: 24rpx;
	padding: 28rpx 0 8rpx;
	border: 1rpx solid #E0D8CF;
}

.title-row {
	padding: 0 32rpx 18rpx;
	display: flex;
	align-items: baseline;
	font-family: 'STSong', 'Songti SC', 'Noto Serif SC', serif;

	.zh {
		font-size: 32rpx;
		color: #1C1917;
		letter-spacing: 3rpx;
	}

	.sep {
		color: #B8924A;
		margin: 0 10rpx;
	}

	.en {
		font-size: 24rpx;
		color: #B8924A;
		letter-spacing: 2rpx;
	}
}

.cat-scroll {
	white-space: nowrap;
	border-top: 1rpx solid #F2EDE4;
}

.cat-tabs {
	display: inline-flex;
	padding: 0 16rpx;
}

.cat-tab {
	padding: 22rpx 28rpx;
	font-size: 26rpx;
	letter-spacing: 2rpx;
	color: #7A6E68;
	border-bottom: 4rpx solid transparent;
	white-space: nowrap;

	&.active {
		color: #B8924A;
		font-weight: 500;
		border-bottom-color: #B8924A;
	}
}
</style>
