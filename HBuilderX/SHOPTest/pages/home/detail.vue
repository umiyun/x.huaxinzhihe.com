<template>
	<view>
		<view class="cu-bar bg-black">
			<view class="action" @tap="goIndex">
				<text class="cuIcon-homefill text-gray"></text> 首页
			</view>
			<view class="content text-bold">{{detail.goods_name}}</view>
		</view>

		<hswiper :url="this.siteinfo.urlImg" :banner="detail.imgs"></hswiper>

		<view class="d_title">
			<view class="text-lg text-bold">{{detail.goods_name}}</view>
			<view>
				<text class="text-lg text-bold sys_color">￥{{detail.goods_price}}</text>
				<text class="text-sm text-gray decoration_line goods_cost">￥{{detail.goods_cost}}</text>
			</view>
			<button class="icon-share" open-type="share">
				<text class="lg text-gray cuIcon-share"></text>
			</button>
		</view>
		<view class="d_desc">
			<view class="dd_title text-center sys_color text-bold text-lg">
				<text>商品详情</text>
			</view>
			<view>
				<rich-text :nodes="detail.goods_details"></rich-text>
			</view>
		</view>

		<view class="box">
			<view class="cu-bar bg-white tabbar border shop">
				<button class="action" open-type="contact">
					<view class="cuIcon-service text-gray">
					</view>
					客服
				</button>
				<view class="action">
					<view class="cuIcon-cart">
					</view>
					购物车
				</view>
				<view class="bg-orange submit" @tap="showModal" data-target="bottomModal" data-buy="cart" >加入购物车</view>
				<view class="bg-red submit" @tap="showModal" data-target="bottomModal" data-buy="buy" >立即订购</view>
			</view>
		</view>
		<!-- 弹窗 -->
		<view class="cu-modal bottom-modal" :class="modalName=='bottomModal'?'show':''">
			<view class="cu-dialog">
				<view class="cu-bar bg-white">
					<image class="cb_img" :src="siteinfo.urlImg+detail.lb_img"></image>
					<view class="cb_price">
						<view class="sys_color text-left">￥{{price}}</view>
						<view v-if="!hasChoose">未选择</view>
						<view v-else>
							<text style="margin-right: 10rpx;">已选择</text> 
							<!-- <block v-for="(item,index) in currentIndex" :key="index" v-if="item"> 
							<block v-if="index>0&&index<item.length">+</block> -->
							<text>{{specConn}}</text>
							<!-- </block> -->
						</view>
					</view>
					<view class="action text-blue cuIcon-close" @tap="hideModal"></view>
				</view>
				<view class="padding-xl p_cols">
					<view v-for="(item,index) in detail.spec" :key="index" class="pc_col">
						<view class="p_name"><text>{{item.name}}</text>:</view>
						<view class="p_title">
							<text v-for="(item2,index2) in item.title" 
							:key="index2" 
							:data-index="index"
							:data-title="item2"
							:class="(currentIndex[index]==item2)?'active pt_title':'pt_title'"	
							 @tap="labelItemTap">{{item2}}</text>
						</view>
					</view>
					<view class="pc_col">
						<view class="p_name"><text>数量</text>:</view>
						<view class="p_title">
							<uni-number-box :min="1" :max="9" @change='childChange'></uni-number-box>
						</view>
					</view>
				</view>
				<view class="cd_submit">
					<text @tap="cdSubmit">确认</text>
				</view>
			</view>
		</view>

	</view>
</template>

<script>
	import hswiper from "../../components/hswiper.vue";
	import uniNumberBox from "@/components/uni-number-box.vue";
	export default {
		components: {
			hswiper,
			uniNumberBox
		},
		data() {
			return {
				siteinfo: this.siteinfo,
				detail: {},
				dotStyle: false,
				towerStart: 0,
				direction: '',
				modalName: '',
				currentIndex: [],
				spec: [],
				hasChoose:false,
				price:0,
				getSpec:{},
				buyType:'',
				specConn:'',
				count:1
			}
		},
		onLoad(option) {
			this.ajaxDetail(option.id);
			// 初始化towerSwiper 传已有的数组名即可
		},
		onShareAppMessage(res) {
			var that = this;
			if (res.from === 'button') { // 来自页面内分享按钮
				uni.share({
					provider: "weixin",
					scene: "WXSenceTimeline",
					type: 0,
					// href: "http://uniapp.dcloud.io/",
					title: that.detail.goods_name,
					summary: "我正在使用HBuilderX开发uni-app，赶紧跟我一起来体验！",
					// imageUrl: "https://img-cdn-qiniu.dcloud.net.cn/uniapp/images/uni@2x.png",
					success: function(res) {
						console.log("success:" + JSON.stringify(res));
					},
					fail: function(err) {
						console.log("fail:" + JSON.stringify(err));
					}
				});
			}
			return {
				title: that.detail.goods_name,
				path: '/pages/index/detail?id=' + that.detail.id
			}
		},
		methods: {
			ajaxDetail(id) {
				var that = this;
				uni.request({
					url: this.siteinfo.url,
					data: {
						do: 'GoodsDetails',
						id: id
					},
					success: (res) => {
						that.detail = res.data.data;
						that.spec = res.data.data.spec;
						that.price=res.data.data.goods_price;
					}
				});
			},
			showModal(e) {
				this.modalName = e.currentTarget.dataset.target;
				this.buyType=e.currentTarget.dataset.buy;
			},
			hideModal(e) {
				this.modalName = null
			},
			childChange(e){
				this.count=e;
			},
			labelItemTap(e){
				
				var currentIndex=this.currentIndex.slice();
				var index=e.currentTarget.dataset.index;
				var title=e.currentTarget.dataset.title;
				if(currentIndex[index]==title){
					return false;
				}
				var ajaxPrice=true;
				var spec='';
				
				currentIndex[index]=title;
				this.currentIndex=currentIndex;
				this.hasChoose=true;
				
				for(var i in currentIndex){
					if(!currentIndex[i]){
						ajaxPrice=false;
					}else{
						spec+='+'+currentIndex[i];
					}
				}
				if(ajaxPrice&&currentIndex.length==this.spec.length){
					this.ajaxPrice(spec.slice(1));
				}
				this.specConn=spec.slice(1);
			},
			// 计算价格
			ajaxPrice(spec){
				var that=this;
				uni.request({
					url: this.siteinfo.url, //仅为示例，并非真实接口地址。
					data: {
						do: 'GetSpecPrice',
						goods_id: that.detail.id,
						spec:spec
					},
					success: (res) => {
						if(res.data.data){
							that.price = res.data.data.price;
							that.getSpec=res.data.data;
						}
					}
				});
			},
			// 确认按钮
			cdSubmit(){
				var that=this;
				if(that.getSpec.id){
					if(that.buyType=='cart'){//加入购物车
						that.addShopCart();
					}else{
						uni.navigateTo({
							url: "../home/order?gid=" + that.detail.id + '&spec_id=' + that.getSpec.id + '&spec=' + that.specConn + '&spec_price=' + that.detail.goods_price+'&num='+that.count,
						})
					}
				}else{
					uni.showToast({
					    title: '请选择规格',
						icon:'none',
					    duration: 2000
					});
				}
			},
			addShopCart(){
				
				var that=this;
				uni.request({
				  url: that.siteinfo.url,
				  data: {
					do: 'checkGoods',
					gid: that.detail.id,
					spec_id:that.getSpec.id,
					spec:that.specConn,
					num: that.count
				  }
				}).then(data=>{
					 var [error, res]  = data;
					 console.log(res.data);
					 if(res.data){
						uni.request({
							url: that.siteinfo.url, 
							data: {
								do: 'AddShopCart',
								gid: that.detail.id,
								spec_value:'',
								spec_value1:'',
								price:that.price,
								gname:that.detail.goods_name,
								pic:that.detail.lb_imgs,
								spec_id:that.getSpec.id,
								spec:that.specConn,
								inn_id:3
							},
							success: (res) => {
								if(res.data.errno==0){
									uni.showToast({
										icon:'success',
										title:'加入购车成功',
										duration:2000
									})
									setTimeout(function() {
										that.modalName = null
									}, 2000);
								}else{
									uni.showToast({
										icon:'none',
										title:res.data.message,
										duration:2000
									});
								}
							}
						});					 	
					 }
				})
			},
			goIndex(){
				uni.redirectTo({
					url:"../index/index"
				})
			}
		}
	}
</script>

<style>
	.d_title {
		background: #fff;
		padding: 20rpx;
		position: relative;
	}

	.d_title>view:first-child {
		margin-bottom: 10rpx;
	}

	.decoration_line {
		text-decoration: line-through;
	}

	.goods_cost {
		margin-left: 16rpx;
	}

	.icon-share {
		position: absolute;
		top: 10rpx;
		right: 10rpx;
		z-index: 2;
		font-size: 36rpx;
		width: 60rpx;
		height: 60rpx;
		background: #eee;
		text-align: center;
		border-radius: 50%;
		line-height: 60rpx;
		border: 1px solid #eee;
		padding: 0;
	}

	button::after {
		border: none;
	}

	.d_desc {
		background: #fff;
		margin-top: 20rpx;
		margin-bottom: 100rpx;
	}

	.dd_title {
		border-bottom: 1px solid #eee;
	}

	.dd_title text {
		border-bottom: 2px solid #ff7800;
		padding: 16rpx 10%;
		display: inline-block;
	}

	.box {
		position: fixed;
		bottom: 0;
		left: 0;
		width: 100%;
		z-index: 2;
	}

	.cu-modal.show {
		overflow: initial;
	}

	.cu-dialog {
		position: relative;
		overflow: initial;
		background: #fff;
	}

	.cu-dialog .cuIcon-close {
		position: absolute;
		right: 0;
		top: 0;
		color: #888;
		font-size: 40rpx;
	}

	.cb_img {
		position: absolute;
		width: 200rpx;
		height: 200rpx;
		top: -80rpx;
		left: 20rpx;
		border-radius: 10rpx;
		border: 6rpx solid #fff;
	}

	.cb_price {
		margin-left: 240rpx;
		margin-top: 10rpx;
	}

	.p_cols {
		padding: 20rpx;
		margin: 10rpx 0;
		border-bottom: 1rpx solid #eee;
		border-top: 1rpx solid #eee;
		text-align: left;
		background: #fff;
	}

	.p_cols .pt_title {
		background: #f8f8f8;
		display: inline-block;
		padding: 10rpx 30rpx;
		text-align: center;
		margin-right: 20rpx;
		margin-bottom: 20rpx;
	}

	.p_name {
		margin-bottom: 6rpx;
	}

	.p_cols .active {
		background: #ff7800;
		color: #fff;
	}

	.pc_col {
		border-bottom: 1rpx solid #eee;
		padding: 10rpx 0;
	}

	.cd_submit {
		text-align: center;
		margin-bottom: 20rpx;

	}

	.cd_submit text {
		display: inline-block;
		background: #ff7800;
		color: #fff;
		width: 40%;
		text-align: center;
		padding: 18rpx;
	}

	.uni-numbox {
		transform: scale(0.9);
		margin-top: 10rpx;
	}
</style>
