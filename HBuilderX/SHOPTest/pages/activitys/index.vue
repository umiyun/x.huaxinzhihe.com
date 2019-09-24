<template name="activitys">
	<view>
		<cu-custom bgColor="bg-black"><block slot="content">{{url_name}}</block></cu-custom>
		<scroll-view scroll-x class="bg-white nav" scroll-with-animation :scroll-left="scrollLeft">
			<view class="cu-item" 
			:class="index==TabCur?'sys_color':''" 
			v-for="(item,index) in tabs" 
			:key="index" @tap="tabSelect" 
			:data-id="item.id"
			:data-index="index"
			>
				{{item.type_name}}
			</view>
		</scroll-view>
		<goodsList :goods="goods"></goodsList>
		
	</view>

</template>

<script>
	import goodsList from "../../components/goodsList.vue";
	export default {
		components: {
			goodsList
		},
		data() {
			return {
				TabCur: 0,
				scrollLeft: 0,
				tabs:[],
				goods:[]
			}
		},
		onLoad(){
			this.ajaxTab();
		},
		
		methods: {
			tabSelect(e) {
				this.TabCur = e.currentTarget.dataset.index;
				this.scrollLeft = (e.currentTarget.dataset.index - 1) * 60;
				this.ajaxList(e.currentTarget.dataset.id);
			},
			ajaxTab(){
				var that=this;
				uni.request({
					url: this.siteinfo.url,
					data: {
						do:'Type'
					},
					success: res => {
						that.tabs=res.data;
						that.ajaxList(res.data[0]['id']);
					},
				});
			},
			ajaxList(tid){
				var that=this;
				uni.request({
					url: this.siteinfo.url,
					data: {
						do:'TypeGoodList',
						tid:tid,
						inn_id:3
					},
					success: res => {
						that.goods=res.data
					},
				});
			}
		}
	}
</script>

<style>

</style>
