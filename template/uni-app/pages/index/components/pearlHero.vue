<template>
	<view class="pearl-hero">
		<view class="hero-bg"></view>
		<view class="hero-body">
			<view class="tag">北部湾南珠 · 匠心臻品</view>
			<view class="title">
				<text class="zh">天地孕育</text>
				<text class="zh accent">一粒珍珠</text>
			</view>
			<view class="sub">源自合浦海域的南珠，历经三年养殖，每一颗都承载海洋的温度与时光的耐心。</view>
			<view class="btn-row">
				<view class="btn-primary" @click="scrollToProducts">立即选购</view>
				<view class="btn-outline" @click="goStory">品牌故事</view>
			</view>
		</view>
		<view class="stats">
			<view class="stat" v-for="(s, i) in stats" :key="i">
				<text class="num">{{ s.num }}</text>
				<text class="label">{{ s.label }}</text>
			</view>
		</view>
	</view>
</template>

<script>
export default {
	name: 'pearlHero',
	data() {
		return {
			stats: [
				{ num: '67', label: '年养殖历史' },
				{ num: 'ISO', label: '国际质量认证' },
				{ num: '100%', label: '正品附证书' },
				{ num: '3000+', label: '品类在售' },
				{ num: '终身', label: '免费换线' },
				{ num: '7天', label: '无忧退换' },
			],
		};
	},
	methods: {
		scrollToProducts() {
			uni.pageScrollTo({ selector: '.scene-entry', duration: 300 });
		},
		goStory() {
			uni.showToast({ title: '品牌故事敬请期待', icon: 'none' });
		},
	},
};
</script>

<style scoped lang="scss">
.pearl-hero {
	position: relative;
	margin: 20rpx 20rpx 0;
	border-radius: 24rpx;
	overflow: hidden;
	color: #fff;
	background: linear-gradient(135deg, #1C1917 0%, #3D2E25 55%, #1C1917 100%);
}

.hero-bg {
	position: absolute;
	inset: 0;
	background: radial-gradient(ellipse at 75% 25%, rgba(212, 175, 114, 0.18) 0%, transparent 60%),
		radial-gradient(ellipse at 10% 90%, rgba(184, 146, 74, 0.12) 0%, transparent 55%);
	pointer-events: none;
}

.hero-body {
	position: relative;
	padding: 48rpx 36rpx 32rpx;
}

.tag {
	display: inline-block;
	font-size: 22rpx;
	letter-spacing: 6rpx;
	color: #D4AF72;
	border: 1rpx solid #B8924A;
	padding: 6rpx 20rpx;
	border-radius: 2rpx;
	margin-bottom: 28rpx;
}

.title {
	display: flex;
	flex-direction: column;
	line-height: 1.3;
	font-family: 'STSong', 'Songti SC', 'Noto Serif SC', serif;

	.zh {
		font-size: 64rpx;
		font-weight: 300;
		letter-spacing: 4rpx;
	}

	.accent {
		color: #D4AF72;
		font-weight: 600;
		margin-top: 4rpx;
	}
}

.sub {
	margin-top: 22rpx;
	font-size: 24rpx;
	color: rgba(255, 255, 255, 0.65);
	line-height: 1.8;
	max-width: 560rpx;
}

.btn-row {
	margin-top: 32rpx;
	display: flex;
	gap: 20rpx;
}

.btn-primary,
.btn-outline {
	padding: 16rpx 40rpx;
	font-size: 24rpx;
	letter-spacing: 4rpx;
	border-radius: 2rpx;
}

.btn-primary {
	background: #B8924A;
	color: #fff;
}

.btn-outline {
	border: 1rpx solid rgba(255, 255, 255, 0.4);
	color: #fff;
}

.stats {
	position: relative;
	display: grid;
	grid-template-columns: repeat(3, 1fr);
	gap: 2rpx;
	padding: 2rpx;
	background: rgba(184, 146, 74, 0.15);
	border-top: 1rpx solid rgba(184, 146, 74, 0.3);
}

.stat {
	display: flex;
	flex-direction: column;
	align-items: center;
	justify-content: center;
	padding: 26rpx 10rpx;
	background: rgba(28, 25, 23, 0.92);

	.num {
		font-family: 'STSong', 'Songti SC', 'Noto Serif SC', serif;
		font-size: 38rpx;
		font-weight: 600;
		color: #D4AF72;
		letter-spacing: 2rpx;
	}

	.label {
		margin-top: 6rpx;
		font-size: 20rpx;
		color: rgba(255, 255, 255, 0.5);
		letter-spacing: 2rpx;
	}
}
</style>
