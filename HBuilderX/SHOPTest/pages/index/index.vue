<template>
	<view>
		<home v-if="PageCur=='home'"></home>
		<activitys v-if="PageCur=='activitys'"></activitys> 
		<user v-if="PageCur=='user'"></user>
		<view style="height: 100rpx;"></view>
		<view class="cu-bar tabbar bg-white shadow foot">
			<view v-for="(item,index) in tabs" :key="index" class="action" @click="NavChange" :data-cur="item.cur">
				<view class='cuIcon-cu-image'>
					<image :src="PageCur==item.cur?(item.attachurl+item.clickafter_icon):(item.attachurl+item.clickago_icon)"></image>
				</view>
				<view :class="PageCur==item.cur?'sys_color':'text-gray'">{{item.title}}</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
		return {
				PageCur: 'home',
				tabs:[
					{
						attachurl: "http://x.umiyun.net/",
						clickafter_icon: "images/28/2019/03/Hu3UKvIKRIWTNKvmKmNFWuxPrNPXkF.png",
						clickago_icon: "images/28/2019/03/qxGaj0ZIcDaakjaGaMMJNTnLAWcGz2.png",
						cur:"home",
						title: "首页"
					},
					{
						attachurl: "http://x.umiyun.net/",
						clickafter_icon: "images/30/2019/06/ZiIzz2Iz0Y50539fFe95BFZB9eHXSn.png",
						clickago_icon: "images/30/2019/06/Tbp57SIb94N9uzpc5ZBs44PSKpPwJg.png",
						cur:"component",
						title: "商城"
					},
					{
						attachurl: "http://x.umiyun.net/",
						clickafter_icon: "images/28/2019/03/MXCFDlDFIdzpeP1uDl1IlDDDZxifIi.png",
						clickago_icon: "images/28/2019/03/p6Z8dSWEe5xXWsNS5Ts5szECHZ5Hd2.png",
						cur:"user",
						title: "我的"
					}
				]
			}
		},
		methods: {
			NavChange: function(e) {
				this.PageCur = e.currentTarget.dataset.cur
			}
		}
	}
</script>

<style>
	
</style>
