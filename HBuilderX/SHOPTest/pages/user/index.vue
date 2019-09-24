<template name="user">
	<view class="center">
		<view class="center_top">
			<view class="mask"></view>
		</view>
		<view class="center_box_bg">
			<view class="profily">
				<view class="base" v-if="userInfo.avatarUrl">
					<view class="profily_header">
						<image class="icon" :src="userInfo.avatarUrl" mode="widthFix"></image>
					</view>
					<text>{{userInfo.nickName}}</text>
				</view>
				<view v-else>
					<button
						class=''
						open-type="getUserInfo"
						@getuserinfo="getuserinfo"
						withCredentials="true"
					>
					获取微信用户信息
					</button>
				</view>
				<view class="order_status">
					<view 
					class="status" 
					v-for="(item,index) in status" 
					:key="index"
					
					
					>
						<text class="icon" :class="item.url"></text>
						<text>{{item.name}}</text>
					</view>
				</view>
			</view>
			<view class="baiban">

			</view>
			<view class="center_menu">
				<view 
				class="menu_item" 
				v-for="item in menus" 
				:key="item.name"
				:data-key="item.key"
				@click="goNext"
				>
					<text :class="item.icon"></text>
					<text>{{item.name}}</text>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	import helper from '../../common/helper.js';  
	export default {
		data() {
			return {
				userInfo:helper.userInfo,
				status: [{
						key: 1,
						name: '待付款',
						url: 'cuIcon-pay'
					},
					{
						key: 2,
						name: '待发货',
						url: 'cuIcon-deliver'
					},
					{
						key: 3,
						name: '已发货',
						url: 'cuIcon-squarecheckfill'
					}
				],
				menus: [{
						name: '我的拼团',
						icon: 'cuIcon-peoplelist',
						key: 1,
					},
					{
						name: '地址管理',
						icon: 'cuIcon-squarecheckfill',
						key: 2,
					},
					{
						name: '我的地址',
						icon: 'cuIcon-location',
						key: 3,
					},
					{
						name: '我的购物车',
						icon: 'cuIcon-cart',
						key: 4,
					},
					{
						name: '选择门店',
						icon: 'cuIcon-shop',
						key: 5,
					},
					{
						name: '立即核销',
						icon: 'cuIcon-scan',
						key: 6,
					}

				]
			};
		},
		methods: {
			getuserinfo(){
				var that=this;
				wx.getUserInfo({
					success: function(t) {
						console.log(that)
						that.userInfo=t.userInfo;
						helper.userInfo=t.userInfo;
					}
				})
			},
			goNext(e) {
				var key=e.currentTarget.dataset.key;
				console.log(key)
				if(key==4){
					uni.navigateTo({
						url: "../cart/index"
					});
				}
			}
		},
		onLoad(){
		},
	}
</script>

<style lang="scss">
	page {
		height: 100%;
	}

	.profily,
	.profily_header {
		border-radius: 8px;
	}

	.center {
		height: 100%;

		&_top {
			height: 18%;
			// background: url('../../static/fumou-center-template/header.jpg') no-repeat 0% 50%;
			background-size: cover;
			height: 350rpx;
			background: #E6E6E6;
			.mask {
				background: rgba(0, 0, 0, .4);
				height: 100%;
			}
		}

		&_box_bg {
			background: #F9F9F9;
			position: relative;

			.profily {
				position: absolute;
				width: 90%;
				// border:1px solid #F7F7F7;
				margin: 0 auto;
				top: -100upx;
				left: 5%;
				background: #FEFEFE;
				padding: 35upx;
				box-sizing: border-box;
				box-shadow: 0px 2px 5px #EDEDED;
			}
		}
	}

	.base {
		display: flex;
		align-items: center;
		border-bottom: 2px solid #F6F6F6;
		padding-bottom: 35upx;
		position: relative;
		.profily_header {
			height: 120upx;
			width: 120upx;
			// background-image: url('../../static/fumou-center-template/header.jpg');
			background-size: 100%;
		}

		text {
			margin-left: 20px;
			font-size: 30upx;
		}
		
		// image{
		// 	position: absolute;
		// 	height: 40upx;
		// 	width: 40upx;
		// 	right: 0px;
		// 	top:0px;
		// }
	}

	.order_status {
		display: flex;
		justify-content: space-between;
		margin-top: 35upx;

		.status {
			width: 140upx;
			font-size: 24upx;
			text-align: center;
			letter-spacing: .5px;
			display: flex;
			flex-direction: column;
			.icon {
				font-size: 60rpx;
				margin: 0 auto;
				margin-bottom: 5px;
				
			}
		}
	}

	.baiban {
		background: #FEFEFE;
		height: 300upx;
	}

	.center_menu {
		width: 100%;
		display: inline-block;

		.menu_item {
			font-size: 28upx;
			letter-spacing: 1px;
			padding: 14px 5%;
			background: #FEFEFE;
			overflow: hidden;
			box-sizing: border-box;
			display: flex;
			align-items: center;
			position: relative;
			border-bottom: 1px solid #EFEFEF;

			&:hover {
				background: #F6F6F6 !important;
			}

			&::after {
				content: '';
				width: 30upx;
				height: 30upx;
				position: absolute;
				right: 5%;
				// background: url('../../static/fumou-center-template/right.png') no-repeat;
				background-size: 100%;
			}

			// text:nth-of-type(1) {
			// 	margin-left: 10px;
			// }

			image {
				width: 40upx;
				height: 40upx;
			}

			// &:nth-of-type(4) {
			// 	margin-top: 10px;
			// }
		}
	}
</style>
