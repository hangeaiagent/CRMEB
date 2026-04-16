<template>
	<view class="scene-entry">
		<view class="scene-title">
			<text class="zh">场景购珠</text>
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
				{ tag: 'daily',    zh: '日常通勤', en: 'Daily',   icon: 'icon-shijian', bg: 'linear-gradient(135deg, #F8F5EE, #E8DCC4)' },
				{ tag: 'gift_mom', zh: '送给妈妈', en: 'For Mom',  icon: 'icon-aixin',   bg: 'linear-gradient(135deg, #FCEDE6, #F5C8B3)' },
				{ tag: 'date',     zh: '约会心动', en: 'Date',     icon: 'icon-xingzuo', bg: 'linear-gradient(135deg, #F3E8F8, #D9BCE6)' },
				{ tag: 'wedding',  zh: '婚礼场合', en: 'Wedding',  icon: 'icon-huangguan', bg: 'linear-gradient(135deg, #F0E5D4, #D4B583)' },
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
	padding: 30rpx 24rpx;
	border-radius: 24rpx;
}

.scene-title {
	display: flex;
	align-items: baseline;
	margin-bottom: 24rpx;
	padding-left: 8rpx;

	.zh {
		font-size: 32rpx;
		font-weight: 600;
		color: #2c2418;
		letter-spacing: 2rpx;
	}

	.en {
		margin-left: 16rpx;
		font-size: 22rpx;
		color: #b5a580;
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
		width: 96rpx;
		height: 96rpx;
		border-radius: 28rpx;
		display: flex;
		align-items: center;
		justify-content: center;
		box-shadow: 0 6rpx 16rpx rgba(201, 169, 110, 0.15);

		.iconfont {
			font-size: 44rpx;
			color: #8a6d3b;
		}
	}

	.zh-label {
		margin-top: 14rpx;
		font-size: 24rpx;
		color: #2c2418;
	}

	.en-label {
		margin-top: 4rpx;
		font-size: 18rpx;
		color: #b5a580;
		letter-spacing: 1rpx;
	}
}
</style>
