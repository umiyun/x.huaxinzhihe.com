<template>
	<view>
		<cu-custom bgColor="bg-black" :isBack="true"><block slot="backText">返回</block><block slot="content">{{item.title}}</block></cu-custom>
		
		<swiper class="screen-swiper" :class="dotStyle?'square-dot':'round-dot'" :indicator-dots="true" :circular="true"
		 :autoplay="true" interval="5000" duration="500">
			<swiper-item v-for="(item,index) in detail.imgs" :key="index">
				<image :src="siteinfo.urlImg+item" mode="aspectFill"></image>
			</swiper-item>
		</swiper>
		
		<view>
			<view>
				<view>11</view>
				<view>112</view>
			</view>
			<button>分享</button>
		</view>
		<view>
			<view>
				<view>商品详情</view>
			</view>
			<view></view>
		</view>
		
		
		
	</view>
</template>

<script>
	export default {
		data() {
			return {
				siteinfo:this.siteinfo,
				detail:{},
				dotStyle: false,
				towerStart: 0,
				direction: '',
			}
		},
		onLoad(option) {
			this.ajaxDetail(option.id);
			console.log(option)
			// 初始化towerSwiper 传已有的数组名即可
		},
		methods: {
			ajaxDetail(id){
				var that=this;
				uni.request({
				    url: this.siteinfo.url, //仅为示例，并非真实接口地址。
				    data: {
				       do: 'GoodsDetails',
				       id: id
				    },
				    success: (res) => {
						that.detail = res.data.data;
				    }
				});
			},
			// towerSwiper触摸开始
			TowerStart(e) {
				this.towerStart = e.touches[0].pageX
			},
			
			// towerSwiper计算方向
			TowerMove(e) {
				this.direction = e.touches[0].pageX - this.towerStart > 0 ? 'right' : 'left'
			},
			
			// towerSwiper计算滚动
			TowerEnd(e) {
				let direction = this.direction;
				let list = this.swiperList;
				if (direction == 'right') {
					let mLeft = list[0].mLeft;
					let zIndex = list[0].zIndex;
					for (let i = 1; i < this.swiperList.length; i++) {
						this.swiperList[i - 1].mLeft = this.swiperList[i].mLeft
						this.swiperList[i - 1].zIndex = this.swiperList[i].zIndex
					}
					this.swiperList[list.length - 1].mLeft = mLeft;
					this.swiperList[list.length - 1].zIndex = zIndex;
				} else {
					let mLeft = list[list.length - 1].mLeft;
					let zIndex = list[list.length - 1].zIndex;
					for (let i = this.swiperList.length - 1; i > 0; i--) {
						this.swiperList[i].mLeft = this.swiperList[i - 1].mLeft
						this.swiperList[i].zIndex = this.swiperList[i - 1].zIndex
					}
					this.swiperList[0].mLeft = mLeft;
					this.swiperList[0].zIndex = zIndex;
				}
				this.direction = ""
				this.swiperList = this.swiperList
			},
		}
	}
</script>

<style>

</style>
