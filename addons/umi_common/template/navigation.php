<style>
    .p_navigation{
        position: fixed;
        bottom: 15%;
        right: 0;
        z-index: 5;
        font-size: 12px;
    }
    .p_tousu,.p_shangjia,.p_user,.p_making,.p_poster{
        position: relative;
        background: #f3f3f3;
        height: 40px;
        font-size: 12px;
        padding: 0px 2px 0px 15px;
        line-height: 20px;
        margin-bottom: 5px;
        display: block;
    }
    .p_navigation .icon-baoming,.p_navigation .icon-kefu,.p_navigation .icon-man,.p_navigation .icon-dianhua1,.p_navigation .icon-tubiao-qiapian{
        font-size: 18px;
        color: #fff;
        background: #ff6e40;
        border-radius: 50%;
        padding: 6px;
        margin-right: 4px;
        position: absolute;
        right: 20px;
        border: 4px solid #f3f3f3;
        width: 40px;
        height: 40px;
        line-height: 20px;
    }
    .p_navigation .icon-kefu{
        background:rgba(66, 177, 64, 1);
    }
    .p_navigation .p_shangjia{
        color: rgba(66, 177, 64, 1);
    }
    .p_navigation .icon-baoming{
        background: #aaa;
    }
    .p_navigation .icon-man{
        background:#00A1F3;
    }
    .p_poster{
        color: #ff6e40;
    }
    .p_user{
        color: #00A1F3;
    }
    .icon-dianhua1{
        background:#101010;
    }
    .p_making{
        color: #101010;
    }
    .p_tousu{
        color: #aaa;
    }
</style>

<div class="p_navigation">

    <?php defined('IN_IA') or exit('Access Denied');?><?php
    if($setting_activity&&$setting_activity['making_status2']==1&&$activity['is_copyright']==0) { ?>
    <a class="p_making" href="<?php  echo $_W['siteroot'];?>index.php?c=entry&i=<?php  echo $_W['uniacid'];?>&do=making&case_id=<?php  echo $activity_umi['case_id'];?>&m=umi_activitys&module=<?php  echo $_W['current_module']['name'];?>">
        <i class="iconfont icon-dianhua1"></i>
        <span>创建<br>活动</span>
    </a>
    <?php  } ?>

    <?php
    if($setting['shop_default']==1&&$shop) {//默认商家信息
        $activity['shop_name'] = $shop['realname'];
        $activity['shop_mobile'] = $shop['mobile'];
        $activity['shop_province'] = $shop['province'];
        $activity['shop_city'] = $shop['city'];
        $activity['shop_district'] = $shop['district'];
        $activity['shop_address'] = $shop['address'];
        $activity['shop_code'] = $shop['qrcode'];
    }
    ?>

    <div class="p_shangjia" data-name="<?php  echo $activity['shop_name'];?>" data-mobile="<?php  echo $activity['shop_mobile'];?>" data-address="<?php  echo $activity['shop_province'];?> <?php  echo $activity['shop_city'];?> <?php  echo $activity['shop_district'];?> <?php  echo $activity['shop_address'];?>" data-code="<?php  echo tomedia($activity['shop_code'])?>" onclick="openConcat(this)">
        <i class="iconfont icon-kefu"></i>
        <span>联系<br>商家</span>
    </div>
    <a class="p_user" href="<?php echo $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=user&m='.$_GPC['m']?>">
        <i class="iconfont icon-man"></i>
        <span>个人<br>中心</span>
    </a>
    <?php if(!empty($activity['bg'])){?>
    <a class="p_poster" href="javascript:;" onclick="hecheng()">
        <i class="iconfont icon-tubiao-qiapian"></i>
        <span>邀请<br>卡</span>
    </a>

    <?php }?>
    <?php if($setting['a_complain']=='1'){?>
    <a href="<?php echo $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=index&op=page_complain&activity_id='.$activity['id'].'&m='.$_GPC['m']?>" class="p_absolute p_tousu">
        <i class="iconfont icon-baoming"></i>
        <span>投诉<br>建议</span>
    </a>
    <?php }?>
</div>