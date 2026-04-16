<template>
	<view class="scene-entry">
		<view class="scene-title">
			<text class="zh">场景购珠</text>
			<text class="sep">·</text>
			<text class="en">Pick pearls for the moment</text>
		</view>
		<view class="scene-grid">
			<view v-for="s in scenes" :key="s.tag" class="scene-card" @click="go(s.tag)">
				<view class="icon-wrap" :style="{ background: s.bg }">
					<text class="iconfont" :class="s.icon"></text>
				</view>
				<view class="zh-label">{{ s.zh }}</view>
				<view class="en-label">{{ s.en }}</view>
			</view>
		</view>
	</view>
</template>

<script>
export default {
	name: 'sceneEntry',
	data() {
		return {
			scenes: [
				{ tag: 'daily',    zh: '日常通勤', en: 'Daily',   icon: 'icon-shijian',   bg: 'linear-gradient(135deg, #FAF7F2, #F2EDE4)' },
				{ tag: 'gift_mom', zh: '送给妈妈', en: 'For Mom',  icon: 'icon-aixin',     bg: 'linear-gradient(135deg, #F5EDD8, #E8D4C0)' },
				{ tag: 'date',     zh: '约会心动', en: 'Date',     icon: 'icon-xingzuo',   bg: 'linear-gradient(135deg, #F8F0E8, #C9A08A)' },
				{ tag: 'wedding',  zh: '婚礼场合', en: 'Wedding',  icon: 'icon-huangguan', bg: 'linear-gradient(135deg, #F5EDD8, #D4AF72)' },
			],
		};
	},
	methods: {
		go(tag) {
			uni.navigateTo({
				url: `/pages/goods/goods_list/index?scene_tag=${tag}&title=${encodeURIComponent(this.scenes.find(s => s.tag === tag).zh)}`,
			});
		},
	},
};
</script>

<style scoped lang="scss">
.scene-entry {
	background: #fff;
	margin: 20rpx 20rpx 0;
	padding: 32rpx 28rpx;
	border-radius: 24rpx;
	border: 1rpx solid #E0D8CF;
}

.scene-title {
	display: flex;
	align-items: baseline;
	margin-bottom: 28rpx;
	padding-left: 8rpx;
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
		font-size: 22rpx;
		color: #B8924A;
		letter-spacing: 2rpx;
		font-style: italic;
	}
}

.scene-grid {
	display: grid;
	grid-template-columns: repeat(4, 1fr);
	gap: 16rpx;
}

.scene-card {
	display: flex;
	flex-direction: column;
	align-items: center;

	.icon-wrap {
		width: 104rpx;
		height: 104rpx;
		border-radius: 28rpx;
		display: flex;
		align-items: center;
		justify-content: center;
		border: 1rpx solid rgba(184, 146, 74, 0.25);
		box-shadow: 0 6rpx 18rpx rgba(184, 146, 74, 0.1);

		.iconfont {
			font-size: 46rpx;
			color: #8A6D3B;
		}
	}

	.zh-label {
		margin-top: 14rpx;
		font-size: 24rpx;
		color: #1C1917;
		letter-spacing: 1rpx;
	}

	.en-label {
		margin-top: 4rpx;
		font-size: 18rpx;
		color: #B8924A;
		letter-spacing: 2rpx;
	}
}
</style>
