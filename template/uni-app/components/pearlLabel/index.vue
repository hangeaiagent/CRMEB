<template>
	<view v-if="labels.length" class="pearl-label acea-row row-middle">
		<text v-for="(l, i) in labels" :key="i" class="chip">
			<text v-if="i !== 0" class="sep">·</text>
			{{ l }}
		</text>
	</view>
</template>

<script>
const SHAPE = { round: '正圆', near_round: '近圆', oval: '椭圆', baroque: '巴洛克', drop: '水滴', button: '扁圆' };
const LUSTER = { very_strong: '极强光', strong: '强光', good: '亮光', weak: '弱光' };
const COLOR = { white: '白色', pink: '粉色', gold: '金色', purple: '紫色', black: '黑色', multi: '混彩' };
const SOURCE = {
	freshwater_no_nucleus: '无核淡水',
	freshwater_edison: '爱迪生',
	akoya: 'Akoya',
	south_sea: '南洋',
	tahitian: '大溪地',
	nanhai: '南海',
};

export default {
	name: 'pearlLabel',
	props: {
		pearl: { type: [Object, null], default: null },
		// compact: 列表卡片只展示 3 个关键字段（尺寸·光泽·珠形）
		// full: 详情页可多展示 来源/颜色/等级
		mode: { type: String, default: 'compact' },
	},
	computed: {
		labels() {
			const p = this.pearl;
			if (!p) return [];
			const out = [];
			if (p.pearl_size) out.push(p.pearl_size);
			if (LUSTER[p.luster]) out.push(LUSTER[p.luster]);
			if (SHAPE[p.pearl_shape]) out.push(SHAPE[p.pearl_shape]);
			if (this.mode === 'full') {
				if (SOURCE[p.pearl_source]) out.unshift(SOURCE[p.pearl_source]);
				if (COLOR[p.color]) out.push(COLOR[p.color]);
				if (p.surface_grade) out.push(p.surface_grade);
			}
			return out;
		},
	},
};
</script>

<style scoped lang="scss">
.pearl-label {
	flex-wrap: wrap;
	font-size: 22rpx;
	color: #a48a5c;
	margin-top: 8rpx;
	line-height: 1.2;

	.chip {
		display: inline-flex;
		align-items: center;
	}

	.sep {
		margin: 0 10rpx;
		color: #d7c49a;
	}
}
</style>
