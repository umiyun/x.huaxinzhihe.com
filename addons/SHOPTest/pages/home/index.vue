<template name="home">
	<view>		
		<hswiper :banner="datas.banner"></hswiper>
		<view class="cu-list grid" :class="['col-' + gridCol,gridBorder?'':'no-border']">
			<view class="cu-item" v-for="(item,index) in datas.icons" :key="index" v-if="index<gridCol*2">
				<!-- <view :class="['cuIcon-' + item.cuIcon,'text-' + item.color]">
					<view class="cu-tag badge" v-if="item.badge!=0">
						<block v-if="item.badge!=1">{{item.badge}}</block>
					</view>
				</view> -->
				<image class="l_img" :src="siteinfo.urlImg+item.pic"></image>
				<text>{{item.title}}</text>
			</view>
		</view>
		
		<view class="session">
			<view class="session-title">
				<view>新品推荐</view>
			</view>
			<view class="list flex justify-between flex-wrap">
				<view v-for="(item,index) in goods" :key="index" class="cu-card case">
					<view class="cu-item shadow" @click="goDetail(item.id)">
						<view class="image">
							<image :src="siteinfo.urlImg+item.lb_img"
							 mode="widthFix"></image>
						</view>
						<view class="cu-list">
							<view class="c_gname text-grey">{{item.goods_name}}</view>
							<view class="c_gprice text-red flex justify-between">￥{{item.goods_price}}</view>
						</view>
					</view> 
				</view>
			</view>
		</view>
		<view class="cu-load bg-grey over"></view>
	</view>
	
</template>

<script>
	import hswiper from "../../components/hswiper.vue";
	export default {
		components: {
			hswiper
		},
		name: "home",
		data() {
			return {
				datas:{},
				goods:[],
				siteinfo:this.siteinfo,
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
			// 初始化towerSwiper 传已有的数组名即可
		},
		methods: {
			tabSelect(e) {
				this.TabCur = e.currentTarget.dataset.id;
				this.scrollLeft = (e.currentTarget.dataset.id - 1) * 60
			},
			AjaxData(){
				var that=this;
				uni.request({
				    url: this.siteinfo.url, //仅为示例，并非真实接口地址。
				    data: {
				       do: 'getCustomize',
				       inn_id: 3,
				    },
				    success: (res) => {
				   
						that.datas = res.data;
				    }
				});
			},
			AjaxGoods(){
				var that=this;
				uni.request({
				    url: this.siteinfo.url, //仅为示例，并非真实接口地址。
				    data: {
				       do: 'TypeGoodList',
				       inn_id: 3,
				    },
				    success: (res) => {
				      
						that.goods = res.data;
				    }
				});
			},
			goDetail(id){ 
				uni.navigateTo({
					url:"../home/detail?id="+id
				})
			}
			
		}
	}
</script>

<style>
	.nav .cu-item{
		font-weight: 900;
	}
	.l_img{
		width: 90rpx;
		height: 90rpx;
		margin: 0 auto;
	}
	
	.session {
		background: #fff;
		margin-top: 20rpx;
		padding-bottom: 30rpx;
	}
	.session-title{
		text-align: center;
	}
	.session-title:before,.session-title:after {
		content: " ";
		width: 120rpx;
		height: 2rpx;
		background-image: -webkit-linear-gradient(180deg,rgb(255,120,0)0%,rgb(255,255,255)100%);
		margin-right: 20rpx;
	}
	.session-title:after {
		margin-left: 20rpx;
		background-image: -webkit-linear-gradient(0deg,rgb(255,120,0)0%,rgb(255,255,255)100%);
	}
	
	.list{
		
	}
	.list>view{
		width: 48%;
	}
	.cu-card>.cu-item{
		border-radius: 10rpx;
		margin: 10rpx;
	}
	.cu-list{
		margin-top: 20rpx;
		padding: 0rpx 10rpx;
		height: 128rpx;
		position: relative;
	}
	.c_gname{
		font-size: 26rpx;
		line-height: 34rpx;
		width: 322rpx;
		display: -webkit-box!important;
		overflow: hidden;
		text-overflow: ellipsis;
		word-break: break-all;
		-webkit-line-clamp: 2;
		-webkit-box-orient: vertical;
	}
	.c_gprice{
		position: absolute;
		bottom: 0;
		left: 0;
	}
</style>
