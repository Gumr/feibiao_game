<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('header', TEMPLATE_INCLUDEPATH)) : (include template('header', TEMPLATE_INCLUDEPATH));?>
<ul class="nav nav-tabs">
    <li class=""><a href="<?php  echo $this->createWeburl('goods');?>">商品管理</a></li>
    <li class="active"><a href="javascript:;">编辑商品</a></li>
</ul>
<form action="" method="post" class="form-horizontal" id="myform" role="form" enctype="multipart/form-data">


    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">商品名<span style="color: red">*</span>:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <input class="form-control" name="goods_name" placeholder="商品名" value="<?php  echo $goodsInfo['goods_name'];?>" type="text">
        </div>
    </div>
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">分享标题</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <input class="form-control" name="share_title" placeholder="分享标题" value="<?php  echo $goodsInfo['share_title'];?>" type="text">
        </div>
    </div>
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">所属店铺:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12" style="margin-top: 8px;">
            <?php  echo $goodsInfo['shop']['shop_name'];?>
        </div>
    </div>

    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">商品分类:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <select class="select valid" name="category_id" size="1" aria-invalid="false" style="width: 150px;">
                <?php  if(is_array($category)) { foreach($category as $item) { ?>
                <option <?php  if($item['id'] == $goodsInfo['category_id']) { ?> selected="selected" <?php  } ?> value="<?php  echo $item['id'];?>"><?php  echo $item['category_name'];?></option>
                <?php  } } ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">商品类型</label>
        <div class="col-sm-9 col-xs-12">
            <?php  if($goodsInfo['type'] == 1 ) { ?>
            <label class="radio-inline">
                <input type="radio" name="type" value="1"    checked/> 实物
            </label>
            <?php  } else if($goodsInfo['type'] == 2) { ?>
            <label class="radio-inline">
                <input type="radio" name="type" value="2"   checked/>虚拟商品
            </label>
            <?php  } else if($goodsInfo['type'] ==3 ) { ?>
            <label class="radio-inline">
                <input type="radio" name="type" value="3"   checked/>红包商品
            </label>
            <?php  } else if($goodsInfo['type'] ==5 ) { ?>
            <label class="radio-inline">
                <input type="radio" name="type" value="5" />VIP加成包
            </label>
            <?php  } else { ?>
            <label class="radio-inline">
                <input type="radio" name="type" value="4"   checked/>红包商品
            </label>
            <?php  } ?>
        </div>
    </div>

    <?php  if($goodsInfo['type'] == 2 ) { ?>
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">商品类型</label>
        <div class="col-sm-9 col-xs-12">
            <?php  if($goodsInfo['is_under_line'] == 1 ) { ?>
            <label class="radio-inline">
                <input type="radio" name="is_under_line" value="1"    checked/> 线上
            </label>
            <?php  } else { ?>
            <label class="radio-inline">
                <input type="radio" name="is_under_line" value="2"   checked/>线下核销
            </label>
            <?php  } ?>
        </div>
    </div>
    <?php  } ?>

    <?php  if($goodsInfo['type'] == 3 ) { ?>
    <div class="form-group" id="bag_money">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">红包金额<span style="color: red">*</span>:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <input class="form-control" name="bag_money" placeholder="红包金额" value="<?php  echo $goodsInfo['bag_money'];?>" type="text">
        </div>
    </div>
    <?php  } ?>
    <div class="form-group" id="the_third_party" <?php  if($goodsInfo['type'] != 4 ) { ?> style="display: none" <?php  } ?> >
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">第三方代发appid<span style="color: red">*</span>:</label>
            <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
                <input class="form-control" name="appid" placeholder="第三方代发appid" value="<?php  echo $goodsInfo['appid'];?>" type="text">
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">第三方代发路径</label>
            <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
                <input class="form-control" name="path" placeholder="第三方代发路径" value="<?php  echo $goodsInfo['path'];?>" type="text">
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">第三方代发参数</label>
            <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
                <input class="form-control" name="parameter" placeholder="第三方代发参数" value="<?php  echo $goodsInfo['parameter'];?>" type="text">
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">订单描述</label>
            <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
                <input class="form-control" name="order_explain" placeholder="订单描述" value="<?php  echo $goodsInfo['order_explain'];?>" type="text">
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">原价<span style="color: red">*</span>:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <input class="form-control" name="original_price" placeholder="原价" value="<?php  echo $goodsInfo['original_price'];?>" type="text">
        </div>
    </div>

    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">兑换需要连续签到天数<span style="color: red">*</span>:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <input class="form-control" name="sigin" placeholder="兑换需要连续签到天数" value="<?php  echo $goodsInfo['sigin'];?>" type="text">
        </div>
    </div>

    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">兑换方式</label>
        <div class="col-sm-9 col-xs-12">
            <label class="radio-inline">
                <input type="radio" name="exchange_type" value="1" <?php  if($goodsInfo['exchange_type'] == 1) { ?> checked <?php  } ?> /> 活力币兑换
            </label>
            <label class="radio-inline">
                <input type="radio" name="exchange_type" value="2" <?php  if($goodsInfo['exchange_type'] == 2) { ?> checked <?php  } ?> />当天自己与好友步数兑换
            </label>
            <label class="radio-inline">
                <input type="radio" name="exchange_type" value="3" <?php  if($goodsInfo['exchange_type'] == 3) { ?> checked <?php  } ?> />步数加钱
            </label>
            <label class="radio-inline">
                <input type="radio" name="exchange_type" value="4" <?php  if($goodsInfo['exchange_type'] == 4) { ?> checked <?php  } ?> />活力币加钱
            </label>
        </div>
    </div>

    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">兑换所需活力币或步数<span style="color: red">*</span>:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <input class="form-control" name="exchange_number" placeholder="兑换需要活力币或步数" value="<?php  echo $goodsInfo['exchange_number'];?>" type="text">
        </div>
    </div>

    <div class="form-group" id="money" <?php  if($goodsInfo['exchange_type'] < 3) { ?> style="display:none" <?php  } ?>>
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">兑换时所需要的金额<span style="color: red">*</span>:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <input class="form-control" name="money" placeholder="兑换时所需要的金额" value="<?php  echo $goodsInfo['money'];?>" type="text">
        </div>
    </div>

    <div class="form-group" id="ling-1">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">封面图片:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <?php  echo tpl_form_field_image('cover_image', getImage($goodsInfo['cover_image'], true), getImage($goodsInfo['cover_image_path']));?>

        </div>
    </div>

    <div class="form-group"  id="image-type">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">商品详情轮播图片:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12" style="padding-top:8px;">

            <?php  echo tpl_form_field_multi_image('images',$goodsInfo['image_data']);?>
        </div>
    </div>

    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">兑换后是否在首页显示</label>
        <div class="col-sm-9 col-xs-12">
            <label class="radio-inline">
                <input type="radio" name="is_exhibition" value="1" <?php  if($goodsInfo['is_exhibition'] == 1) { ?> checked <?php  } ?> /> 显示
            </label>
            <label class="radio-inline">
                <input type="radio" name="is_exhibition" value="2" <?php  if($goodsInfo['is_exhibition'] == 2) { ?> checked <?php  } ?> />不显示
            </label>
        </div>
    </div>


    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">是否包邮</label>
        <div class="col-sm-9 col-xs-12">
            <label class="radio-inline">
                <input type="radio" name="is_free" value="1" <?php  if($goodsInfo['is_free'] == 1) { ?> checked <?php  } ?> /> 包邮
            </label>
            <label class="radio-inline">
                <input type="radio" name="is_free" value="2" <?php  if($goodsInfo['is_free'] == 2) { ?> checked <?php  } ?> />不包邮
            </label>
            <label class="radio-inline">
                <input type="radio" name="is_free" value="3" <?php  if($goodsInfo['is_free'] == 3) { ?> checked <?php  } ?> />自提
            </label>
        </div>
    </div>


    <div class="form-group" id="free"  <?php  if($goodsInfo['is_free'] == 1) { ?> style="display:none" <?php  } ?> >
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">邮费<span style="color: red">*</span>:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <input class="form-control" name="free" placeholder="邮费" value="<?php  echo $goodsInfo['free'];?>" type="text">
        </div>
    </div>


    <div class="form-group" id="invitation_number"  <?php  if($goodsInfo['exchange_type'] != 1 && $goodsInfo['exchange_type'] != 4) { ?>style="display:none" <?php  } ?>>
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">兑换需要邀请多少好友:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <input class="form-control" name="invitation_number" placeholder="兑换需要邀请多少好友" value="<?php  echo $goodsInfo['invitation_number'];?>" type="text">
        </div>
    </div>

    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">库存方式</label>
        <div class="col-sm-9 col-xs-12">
            <label class="radio-inline">
                <input type="radio" name="inventory_type" value="1" <?php  if($goodsInfo['inventory_type'] == 1) { ?> checked <?php  } ?> /> 每天限量提供
            </label>
            <label class="radio-inline">
                <input type="radio" name="inventory_type" value="2" <?php  if($goodsInfo['inventory_type'] == 2) { ?> checked <?php  } ?> />总计
            </label>
        </div>
    </div>

    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">总库存或每天限量提供<span style="color: red">*</span>:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <input class="form-control" name="inventory" placeholder="总库存或每天限量提供数" value="<?php  echo $goodsInfo['inventory'];?>" type="text">
        </div>
    </div>


    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">每人允许兑换数<span style="color: red">*</span>:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <input class="form-control" name="allow_number" placeholder="每人允许兑换数" value="<?php  echo $goodsInfo['allow_number'];?>" type="text">
        </div>
    </div>
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">商品描述类型</label>
        <div class="col-sm-9 col-xs-12">
            <label class="radio-inline">
                <input type="radio" name="introduce_type" value="1" <?php  if($goodsInfo['introduce_type'] == 1) { ?> checked <?php  } ?>  /> 文字
            </label>
            <label class="radio-inline">
                <input type="radio" name="introduce_type" value="2"  <?php  if($goodsInfo['introduce_type'] == 2) { ?> checked <?php  } ?> />图片
            </label>
        </div>
    </div>
    <div class="form-group"  id="text-introduce" <?php  if($goodsInfo['introduce_type'] != 1) { ?> style="display: none" <?php  } ?>>
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">商品描述:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12" style="padding-top:8px;">
            <textarea name="introduce" rows="8" cols="80"><?php  echo $goodsInfo['introduce'];?></textarea>
        </div>
    </div>
    <div class="form-group"  id="image-introduce" <?php  if($goodsInfo['introduce_type'] != 2) { ?> style="display: none" <?php  } ?>>
        <label class="col-xs-12 col-sm-3 col-md-2 control-label" >商品描述:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12" style="padding-top:8px;">
            <?php  echo tpl_form_field_multi_image('introduce', $goodsInfo['introduce_image_data']);?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">上下架</label>
        <div class="col-sm-9 col-xs-12">
            <label class="radio-inline">
                <input type="radio" name="status" value="1" <?php  if($goodsInfo['status'] == 1) { ?> checked <?php  } ?>  /> 上架
            </label>
            <label class="radio-inline">
                <input type="radio" name="status" value="2"  <?php  if($goodsInfo['status'] == 2) { ?> checked <?php  } ?> />下架
            </label>
        </div>
    </div>


    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
        <div class="" >
            <input type="hidden" name="op" value="edit">
            <input type="submit" name="submit" id="submit" value="编辑" class="btn btn-primary">
        </div>
    </div>
</form>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('footer', TEMPLATE_INCLUDEPATH)) : (include template('footer', TEMPLATE_INCLUDEPATH));?>
<script type="application/javascript">

    $(function () {

        $("input[name='is_free']").click(function () {
            console.log($(this).val());
            if ($(this).val() == 1 || $(this).val() == 3) {
                $('#free').hide();
            } else {
                $('#free').show();
            }
        });

        $("input[name='exchange_type']").click(function () {
            var val= $(this).val();
            if (val == 1 || val == 4) {
                $('#invitation_number').show();
            } else {
                $('#invitation_number').hide();
            }
            if (val > 2) {
                $('#money').show();
            } else {
                $('#money').hide();
            }
        });

        $("input[name='introduce_type']").click(function () {
            if ($(this).val() == 1) {
                $('#text-introduce').show();
                $('#image-introduce').hide();
            } else {
                $('#image-introduce').show();
                $('#text-introduce').hide();
            }
        });

    });

</script>