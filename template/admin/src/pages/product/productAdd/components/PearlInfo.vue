<template>
  <el-row :gutter="24">
    <el-col :span="24">
      <el-divider content-position="left">珍珠属性</el-divider>
      <el-form-item label="珍珠来源：">
        <el-select v-model="pearl.pearl_source" clearable placeholder="请选择" style="width: 320px">
          <el-option v-for="o in sourceList" :key="o.v" :value="o.v" :label="o.t" />
        </el-select>
        <span class="tip">走筛选用，不进分类树。</span>
      </el-form-item>
    </el-col>

    <el-col :span="12">
      <el-form-item label="珠径：">
        <el-select v-model="pearl.pearl_size" clearable placeholder="如 7-8mm" style="width: 240px">
          <el-option v-for="s in sizeList" :key="s" :value="s" :label="s" />
        </el-select>
      </el-form-item>
    </el-col>

    <el-col :span="12">
      <el-form-item label="珠形：">
        <el-radio-group v-model="pearl.pearl_shape">
          <el-radio-button v-for="o in shapeList" :key="o.v" :label="o.v">{{ o.t }}</el-radio-button>
        </el-radio-group>
      </el-form-item>
    </el-col>

    <el-col :span="12">
      <el-form-item label="光泽：">
        <el-radio-group v-model="pearl.luster">
          <el-radio-button v-for="o in lusterList" :key="o.v" :label="o.v">{{ o.t }}</el-radio-button>
        </el-radio-group>
      </el-form-item>
    </el-col>

    <el-col :span="12">
      <el-form-item label="颜色：">
        <el-radio-group v-model="pearl.color">
          <el-radio-button v-for="o in colorList" :key="o.v" :label="o.v">{{ o.t }}</el-radio-button>
        </el-radio-group>
      </el-form-item>
    </el-col>

    <el-col :span="12">
      <el-form-item label="表面等级：">
        <el-radio-group v-model="pearl.surface_grade">
          <el-radio-button label="AAA">AAA</el-radio-button>
          <el-radio-button label="AA">AA</el-radio-button>
          <el-radio-button label="A">A</el-radio-button>
        </el-radio-group>
      </el-form-item>
    </el-col>

    <el-col :span="12">
      <el-form-item label="托材：">
        <el-select v-model="pearl.metal_type" clearable style="width: 220px">
          <el-option v-for="o in metalList" :key="o.v" :value="o.v" :label="o.t" />
        </el-select>
      </el-form-item>
    </el-col>

    <el-col :span="12">
      <el-form-item label="产地：">
        <el-input v-model="pearl.origin" placeholder="如 日本·三重县" style="width: 240px" />
      </el-form-item>
    </el-col>

    <el-col :span="12">
      <el-form-item label="项链长度(cm)：">
        <el-input-number v-model="pearl.strand_length_cm" :min="0" :max="200" :step="5" />
        <span class="tip">仅项链类填</span>
      </el-form-item>
    </el-col>

    <el-col :span="12">
      <el-form-item label="耳饰针型：">
        <el-radio-group v-model="pearl.pin_type">
          <el-radio-button label="silver_pin">925银针</el-radio-button>
          <el-radio-button label="k_gold_pin">K金针</el-radio-button>
          <el-radio-button label="ear_clip">耳夹</el-radio-button>
        </el-radio-group>
      </el-form-item>
    </el-col>

    <el-col :span="12">
      <el-form-item label="证书机构：">
        <el-select v-model="pearl.cert_org" clearable style="width: 160px">
          <el-option value="NGTC" label="NGTC" />
          <el-option value="GIA" label="GIA" />
          <el-option value="CMA" label="CMA" />
          <el-option value="GUILD" label="GUILD" />
        </el-select>
        <el-input v-model="pearl.cert_no" placeholder="证书编号" style="width: 240px; margin-left: 8px" />
      </el-form-item>
    </el-col>

    <el-col :span="24">
      <el-form-item label="场景标签：">
        <el-checkbox-group v-model="sceneArr" @change="onSceneChange">
          <el-checkbox label="daily">日常通勤</el-checkbox>
          <el-checkbox label="gift_mom">送妈妈</el-checkbox>
          <el-checkbox label="date">约会</el-checkbox>
          <el-checkbox label="wedding">婚礼</el-checkbox>
        </el-checkbox-group>
        <span class="tip">勾选后首页场景入口会命中本商品</span>
      </el-form-item>
    </el-col>
  </el-row>
</template>

<script>
export default {
  name: 'PearlInfo',
  props: {
    formValidate: { type: Object, required: true },
  },
  data() {
    return {
      sourceList: [
        { v: 'akoya', t: 'Akoya' },
        { v: 'tahitian', t: '大溪地' },
        { v: 'south_sea', t: '南洋' },
        { v: 'nanhai', t: '南海' },
        { v: 'freshwater_edison', t: '爱迪生（有核淡水）' },
        { v: 'freshwater_no_nucleus', t: '无核淡水' },
      ],
      sizeList: ['5-6mm', '6-7mm', '7-8mm', '8-9mm', '9-10mm', '10-11mm', '11-12mm', '12-13mm', '13mm+'],
      shapeList: [
        { v: 'round', t: '正圆' },
        { v: 'near_round', t: '近圆' },
        { v: 'oval', t: '椭圆' },
        { v: 'drop', t: '水滴' },
        { v: 'baroque', t: '巴洛克' },
        { v: 'button', t: '扁圆' },
      ],
      lusterList: [
        { v: 'very_strong', t: '极强光' },
        { v: 'strong', t: '强光' },
        { v: 'good', t: '亮光' },
        { v: 'weak', t: '弱光' },
      ],
      colorList: [
        { v: 'white', t: '白' },
        { v: 'pink', t: '粉' },
        { v: 'gold', t: '金' },
        { v: 'purple', t: '紫' },
        { v: 'black', t: '黑' },
        { v: 'multi', t: '混彩' },
      ],
      metalList: [
        { v: 's925', t: 'S925银' },
        { v: 'k10', t: 'K10金' },
        { v: 'k14', t: 'K14金' },
        { v: 'k18', t: 'K18金' },
        { v: 'gold_plated', t: '包金' },
        { v: 'copper_plated', t: '铜镀金' },
      ],
    };
  },
  computed: {
    pearl() {
      if (!this.formValidate.pearl) {
        this.$set(this.formValidate, 'pearl', {
          pearl_source: '', pearl_size: '', pearl_shape: '',
          luster: '', color: '', surface_grade: '', metal_type: '',
          origin: '', cert_no: '', cert_org: '',
          strand_length_cm: 0, pin_type: '', scene_tags: '',
        });
      }
      return this.formValidate.pearl;
    },
    sceneArr: {
      get() {
        const s = this.pearl.scene_tags || '';
        return s ? s.split(',').map(x => x.trim()).filter(Boolean) : [];
      },
      set(arr) {
        this.pearl.scene_tags = (arr || []).join(',');
      },
    },
  },
  methods: {
    onSceneChange(v) {
      this.sceneArr = v;
    },
  },
};
</script>

<style scoped>
.tip {
  margin-left: 12px;
  color: #999;
  font-size: 12px;
}
</style>
