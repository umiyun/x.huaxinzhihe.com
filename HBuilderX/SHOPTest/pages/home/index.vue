<template name="home">
	<view>
		<block v-for="(item_sys,index_sys) in index_layout" :key="index_sys">
			<hswiper v-if="item_sys.name=='banner'" :banner="datas.banner"></hswiper>
			<view v-if="item_sys.name=='icons'" class="cu-list grid" :class="['col-' + gridCol,gridBorder?'':'no-border']">
				<view class="cu-item" v-for="(item_icon,index_icon) in datas.icons" :key="index_icon" v-if="index_icon<gridCol*2">
					<image class="l_img" :src="siteinfo.urlImg+item_icon.pic"></image>
					<text>{{item_icon.title}}</text>
				</view>
			</view>
			<view class="session" v-if="item_sys.name=='xinpin'">
				<view class="session-title">
					<view>新品推荐</view>
				</view>
				<goodsList :goods="goods"></goodsList>
			</view>
		</block>
		<view class="cu-load bg-grey over"></view>
	</view>

</template>

<script>
	import hswiper from "../../components/hswiper.vue";
	import goodsList from "../../components/goodsList.vue";
	export default {
		components: {
			hswiper,goodsList
		},
		name: "home",
		data() {
			return {
				setting: {},
				index_layout: [],
				datas: {},
				goods: [],
				siteinfo: this.siteinfo,
				cardCur: 0,

				dotStyle: false,
				towerStart: 0,
				direction: '',

				modalName: null,
				gridCol: 4,
				gridBorder: false,
				menuBorder: false,
				menuArrow: false,
				menuCard: false,
				skin: false,
				listTouchStart: 0,
				listTouchDirection: null,
			};
		},
		onLoad() {
			this.AjaxData();
			this.AjaxGoods();
			var setting = uni.getStorageSync('setting');
			if (setting) {
				this.setting = setting;
				this.index_layout = JSON.parse(setting.index_layout);
				console.log(this.index_layout);
			}
		},
		onShow() {
			console.log('show')
		},
		methods: {
			tabSelect(e) {
				this.TabCur = e.currentTarget.dataset.id;
				this.scrollLeft = (e.currentTarget.dataset.id - 1) * 60
			},
			AjaxData() {
				var that = this;
				uni.request({
					url: this.siteinfo.url,
					header: {  
					    'content-type': 'application/x-www-form-urlencoded'  
					},  
					data: {
						do: 'getCustomize',
						inn_id: 3,
					},
					success: (res) => {
						that.datas = res.data;
					}
				});
			},
			AjaxGoods() {
				var that = this;
				uni.request({
					url: this.siteinfo.url,
					header: {
					    'content-type': 'application/x-www-form-urlencoded'  
					}, 
					data: {
						do: 'TypeGoodList',
						inn_id: 3,
						show_index: 1
					},
					success: (res) => {
						that.goods = res.data;
					}
				});
			},
			
		}
	}
</script>

<style>
	.nav .cu-item {
		font-weight: 900;
	}

	.l_img {
		width: 90rpx;
		height: 90rpx;
		margin: 0 auto;
	}

	.session {
		background: #fff;
		margin-top: 20rpx;
		padding-bottom: 30rpx;
	}

	.session-title {
		text-align: center;
	}

	.session-title:before,
	.session-title:after {
		content: " ";
		width: 120rpx;
		height: 2rpx;
		background-image: -webkit-linear-gradient(180deg, rgb(255, 120, 0)0%, rgb(255, 255, 255)100%);
		margin-right: 20rpx;
	}

	.session-title:after {
		margin-left: 20rpx;
		background-image: -webkit-linear-gradient(0deg, rgb(255, 120, 0)0%, rgb(255, 255, 255)100%);
	}

	.xp_img {
		width: 342rpx;
		height: 342rpx;
	}
</style>
