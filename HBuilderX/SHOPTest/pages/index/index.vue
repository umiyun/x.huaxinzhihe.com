<template>
	<view>
		<home v-if="url_name=='首页'"></home>
		<activitys v-if="url_name=='商店'"></activitys> 
		<user v-if="url_name=='我的'"></user>
		<view style="height: 100rpx;"></view>
		
		<view class="cu-bar tabbar bg-white shadow foot">
			<view v-for="(item,index) in tabs" :key="index" class="action" @click="NavChange" :data-is_url="item.is_url" :data-url_name="item.url_name">
				<view class='cuIcon-cu-image'>
					<image :src="PageCur==item.is_url?(item.attachurl+item.clickafter_icon):(item.attachurl+item.clickago_icon)"></image>
				</view>
				<view :class="PageCur==item.is_url?'sys_color':'text-gray'">{{item.title}}</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				siteinfo:this.siteinfo,
				url_name:'',
				isIpx:!1,
				PageCur: '',
				tabs:[],
				Customize:{}
			}
		},
		onLaunch: function() {
			
		},
		onShow: function() {
			var that=this;
			uni.request({
				url: that.siteinfo.url,
				data: {
					do:'getCustomize',
					inn_id:3
				},
				success: res => {
					that.tabs=res.data.tab;
					that.PageCur=res.data.tab[0]['is_url'];
					that.url_name='首页';
					that.Customize=res.data
				}
			});
			uni.request({
				url: that.siteinfo.url,
				data: {do:'system'},
				success: res => {
					uni.setStorage({
					    key: 'setting',
					    data: res.data
					});
				}
			});
		},
		methods: {
			NavChange: function(e) {
				this.PageCur = e.currentTarget.dataset.is_url;
				this.url_name = e.currentTarget.dataset.url_name;
			}
		},
		onShareAppMessage(res) {
		    return {
		      title: uni.getStorageSync('setting')['index_title'],
		      path: '../home/index'
		    }
		}
	}
</script>

<style>
	.isIpx {
	    height: 200rpx;
	}
</style>
