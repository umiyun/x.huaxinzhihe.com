<template>
	<view class="content">
		<cu-custom bgColor="bg-black" :isBack="true"><block slot="backText">返回</block><block slot="content">我的订单</block></cu-custom>
		
		<scroll-view scroll-x class="bg-white nav">
			<view class="flex text-center">
				<view class="cu-item flex-sub" :class="index==TabCur?'text-orange cur':''" v-for="(item,index) in navList" :key="index" @tap="tabSelect" :data-id="index">
					{{item.text}}
				</view>
			</view>
		</scroll-view>
		
		<view class="glist" v-for="(item,index) in list" :key="index">
			<view class="gl_orderno flex justify-between">
				<view>订单号:{{item.orderformid}}</view>
				<view v-if="item.order_status==0">待支付</view>
				<view v-if="item.order_status==1">待发货</view>
				<view v-if="item.order_status==2">已发货</view>
				<view v-if="item.order_status==3">已完成</view>
				<view v-if="item.order_status==4">退款中</view>
				<view v-if="item.order_status==5">已退款</view>
				<view v-if="item.order_status==6">退款拒绝</view>
				<view v-if="item.order_status==7">订单取消</view>
				<view v-if="item.order_status==8">待领取</view>
			</view>
		
			<view class="cu-list menu-avatar">
				<view class="cu-item " v-for="(item2,index2) in item.detail" :key="index2">
					<view class="cu-avatar radius lg">
						<image :src="siteinfo.urlImg+item2.pic"></image>
					</view>
					<view class="content">
						<view class="text-bold"><view class="text-cut">{{item2.gname}}</view></view>
						<view class="text-gray text-sm flex"> <view class="text-cut">{{item2.combine}}</view></view>
						<view class="sys_color text-bold text-sm flex"> <view class="text-cut">￥{{item2.total_price}}</view></view>
					</view>
					<view class="action">
						<view class="cu-tag sm">X{{item2.num}}</view>
					</view>
				</view>
			</view>
			<view class="gl_btns flex justify-end">
				<block v-if="item.order_status==0">
					<button class="cu-btn block line-gray" @click="toCancel(item.id)">取消订单</button>
					<button class="cu-btn block line-orange">立即支付</button>
				</block>
				<block v-if="item.order_status==1">
					<button class="cu-btn block line-orange">联系商家</button>
				</block>
				<block v-if="item.pin_buy_type==0&&item.order_status==2">
					<button class="cu-btn block line-blue" @click="toqueren(item.id)">确认收货</button>
					<button class="cu-btn block line-orange">联系商家</button>
				</block>
				<block v-if="item.order_status==3">
					<button class="cu-btn block line-gray" @click="toDel(item.id)">删除订单</button>
				</block>
				<block v-if="item.pin_buy_type==1&&item.order_status==8">
					<button class="cu-btn block line-blue">确认领取</button>
					<button class="cu-btn block line-orange">联系商家</button>
				</block>
			</view>
		</view>
		
	</view>
</template> 

<script>
	
	export default {
		components: {
			
		},
		data() {
			return {
				siteinfo:this.siteinfo,
				TabCur: 0,
				scrollLeft: 0,
				list:[],
				navList: [{
						state: 0,
						text: '全部'
					},
					{
						state: 1,
						text: '待付款'
					},
					{
						state: 2,
						text: '待收货'
					},
					{
						state: 3,
						text: '已发货'
					},
				],
			};
		},
		
		onLoad(options){
			this.TabCur=options.TabCur||0;
			this.loadData()
		},
		 
		methods: {
			tabSelect(e) {
				this.TabCur = e.currentTarget.dataset.id;
				this.scrollLeft = (e.currentTarget.dataset.id - 1) * 60;
				this.loadData();
			},
			//获取订单列表
			loadData(){
				var that=this;
				uni.request({
					url: this.siteinfo.url,
					data: {
						do: 'getMyorder',
						uid: uni.getStorageSync('openid'),
						index:this.TabCur
					},
					success: (res) => {
						if(res.data.length>0){
							that.list=res.data;
						}
					}
				});
			}, 
			//删除订单
			deleteOrder(index){
				uni.showLoading({
					title: '请稍后'
				})
				setTimeout(()=>{
					this.navList[this.tabCurrentIndex].orderList.splice(index, 1);
					uni.hideLoading();
				}, 600)
			},
			//取消订单
			toCancel(id,index){
				var that=this;
				uni.showModal({
					content: '确定取消该订单吗？',
					success: function (res) {
						if (res.confirm) {
							uni.request({
								url: that.siteinfo.url,
								data: {
									do: 'cancelOrder',
									uid: uni.getStorageSync('openid'),
									order_id:id
								},
								success: (res) => {
									uni.showToast({
										title: '订单取消成功'
									});
									that.loadData()
								},
							})
							
						}
					}
				});
			},
			//删除订单
			toDel(id){
				var that=this;
				uni.showModal({
					content: '订单删除后将不再显示',
					success: function (res) {
						if (res.confirm) {
							uni.request({
								url: that.siteinfo.url,
								data: {
									do: 'delOrder',
									uid: uni.getStorageSync('openid'),
									order_id:id
								},
								success: (res) => {
									uni.showToast({
										title: '订单删除成功'
									});
									that.loadData()
								},
							})
							
						}
					}
				});
			},
			toqueren(id){
				var that=this;
				uni.showModal({
					content: '确定收货',
					success: function (res) {
						if (res.confirm) {
							uni.request({
								url: that.siteinfo.url,
								data: {
									do: 'querenOrder',
									uid: uni.getStorageSync('openid'),
									order_id:id
								},
								success: (res) => {
									uni.showToast({
										title: '已确定收货'
									});
									that.loadData()
								},
							})
							
						}
					}
				});
			}
		},
	}
</script>

<style lang="scss">
	.glist{
		margin-top:20rpx;
		.gl_orderno{
			background: #fff;
			padding: 10rpx 20rpx;
			border-bottom: 1rpx solid #f3f3f3;
		}
		.cu-list.menu-avatar>.cu-item{
			height: 220rpx;
		}
		.cu-list.menu-avatar>.cu-item .content{
			left: 250rpx;
		}
		.cu-avatar.lg{
			width: 200rpx;
			height: 200rpx;
		}
		
		.cu-avatar{
			image{
				width: 100%;
				height: 100%;
			}
		}
		.gl_btns{
			background: #fff;
			padding:10rpx;
			.cu-btn{
				width: 25%;
				margin: 10rpx;
				margin-left: 20rpx;
			}
			
		}
		
	}
</style>
