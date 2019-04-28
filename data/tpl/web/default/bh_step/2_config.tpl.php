<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('header', TEMPLATE_INCLUDEPATH)) : (include template('header', TEMPLATE_INCLUDEPATH));?>
<link rel="stylesheet" href="<?php  echo $siteRoot;?>addons/<?php  echo $modular_name;?>/template/kindeditor/themes/default/default.css" />
<link rel="stylesheet" href="<?php  echo $siteRoot;?>addons/<?php  echo $modular_name;?>/template/kindeditor/plugins/code/prettify.css" />
<script charset="utf-8" src="<?php  echo $siteRoot;?>addons/<?php  echo $modular_name;?>/template/kindeditor/kindeditor-all.js"></script>
<script charset="utf-8" src="<?php  echo $siteRoot;?>addons/<?php  echo $modular_name;?>/template/kindeditor/lang/zh-CN.js"></script>
<script charset="utf-8" src="<?php  echo $siteRoot;?>addons/<?php  echo $modular_name;?>/template/kindeditor/plugins/code/prettify.js"></script>
<ul class="nav nav-tabs">
    <li <?php  if($op == 'ordinary') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWeburl('config', array('op' => 'ordinary'));?>">常用设置</a></li>
    <li <?php  if($op == 'signin') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWeburl('config', array('op' => 'signin'));?>">签到设置</a></li>
    <li <?php  if($op == 'bag') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWeburl('config', array('op' => 'bag'));?>">红包设置</a></li>
    <li <?php  if($op == 'currency') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWeburl('config', array('op' => 'currency'));?>">了解活力币</a></li>
    <li <?php  if($op == 'activity') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWeburl('config', array('op' => 'activity'));?>">活动说明</a></li>
    <li <?php  if($op == 'share') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWeburl('config', array('op' => 'share'));?>">分享设置</a></li>
    <li <?php  if($op == 'ui') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWeburl('config', array('op' => 'ui'));?>">ui自定义</a></li>
    <li <?php  if($op == 'withdrawal') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWeburl('config', array('op' => 'withdrawal'));?>" class="follow_cash">公众号提现设置</a></li>
    <?php  if($isDomain ==1) { ?>
    <li <?php  if($op == 'homeGuide') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWeburl('homeGuide');?>" class="follow_cash">首页引导</a></li>
    <?php  } ?>
</ul>
<form action="" method="post" class="form-horizontal" id="myform" role="form" enctype="multipart/form-data">

    <?php  if($op == 'signin') { ?>
    <?php  if(is_array($kyes['signin'])) { foreach($kyes['signin'] as $index => $item) { ?>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">第<?php  echo $index;?>天签到奖励活力币:</label>
            <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
                <input class="form-control" name="<?php  echo $item['key'];?>" placeholder="第<?php  echo $index;?>天签到奖励" value="<?php  $v = $item['key'];$v=$$v;echo $v['value'];?>" type="text">
            </div>
        </div>
    <?php  } } ?>
    <?php  } else if($op == 'bag') { ?>

        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">红包开关</label>
            <div class="col-sm-9 col-xs-12">
                <label class="radio-inline">
                    <input type="radio" name="bag_switch" value="2" <?php  if($bag_switch['value'] == 2) { ?>checked<?php  } ?> /> 关闭
                </label>
                <label class="radio-inline">
                    <input type="radio" name="bag_switch" value="1" <?php  if($bag_switch['value'] == 1) { ?>checked<?php  } ?> />开启
                </label>
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">红包金额范围:</label>
            <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12" style="float: left">
                <input class="form-control" name="ordinary_bag_start" style="width:20%;float: left" placeholder="单位元" value="<?php  echo $ordinary_bag_start['value'];?>" type="text">
                <input class="form-control" name="ordinary_bag_end" style="width:20%;float: left" placeholder="单位元" value="<?php  echo $ordinary_bag_end['value'];?>" type="text">
            </div>
        </div>

    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">新用户的第一个红包:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12" style="float: left">
            <input class="form-control" name="new_ordinary_bag_start" style="width:20%;float: left" placeholder="单位元" value="<?php  echo $new_ordinary_bag_start['value'];?>" type="text">
            <input class="form-control" name="new_ordinary_bag_end" style="width:20%;float: left" placeholder="单位元" value="<?php  echo $new_ordinary_bag_end['value'];?>" type="text">
        </div>
    </div>

    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">普通红包第一个红包:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12" style="float: left">
            <input class="form-control" name="ordinary_bag_start_one" style="width:20%;float: left" placeholder="单位元" value="<?php  echo $ordinary_bag_start_one['value'];?>" type="text">
            <input class="form-control" name="ordinary_bag_end_one" style="width:20%;float: left" placeholder="单位元" value="<?php  echo $ordinary_bag_end_one['value'];?>" type="text">
        </div>
    </div>
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">普通红包第二个红包:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12" style="float: left">
            <input class="form-control" name="ordinary_bag_start_two" style="width:20%;float: left" placeholder="单位元" value="<?php  echo $ordinary_bag_start_two['value'];?>" type="text">
            <input class="form-control" name="ordinary_bag_end_two" style="width:20%;float: left" placeholder="单位元" value="<?php  echo $ordinary_bag_end_two['value'];?>" type="text">
        </div>
    </div>
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">普通红包第三个红包:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12" style="float: left">
            <input class="form-control" name="ordinary_bag_start_three" style="width:20%;float: left" placeholder="单位元" value="<?php  echo $ordinary_bag_start_three['value'];?>" type="text">
            <input class="form-control" name="ordinary_bag_end_three" style="width:20%;float: left" placeholder="单位元" value="<?php  echo $ordinary_bag_end_three['value'];?>" type="text">
        </div>
    </div>
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">普通红包第四个红包:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12" style="float: left">
            <input class="form-control" name="ordinary_bag_start_four" style="width:20%;float: left" placeholder="单位元" value="<?php  echo $ordinary_bag_start_four['value'];?>" type="text">
            <input class="form-control" name="ordinary_bag_end_four" style="width:20%;float: left" placeholder="单位元" value="<?php  echo $ordinary_bag_end_four['value'];?>" type="text">
        </div>
    </div>
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">普通红包第五个红包:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12" style="float: left">
            <input class="form-control" name="ordinary_bag_start_five" style="width:20%;float: left" placeholder="单位元" value="<?php  echo $ordinary_bag_start_five['value'];?>" type="text">
            <input class="form-control" name="ordinary_bag_end_five" style="width:20%;float: left" placeholder="单位元" value="<?php  echo $ordinary_bag_end_five['value'];?>" type="text">
        </div>
    </div>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">红包初始领取人数:</label>
            <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
                <input class="form-control" name="bag_initial_number" placeholder="红包初始领取人数" value="<?php  echo $bag_initial_number['value'];?>" type="text">
            </div>
        </div>
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">红包总金额:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <input class="form-control" name="bag_total_amount" placeholder="红包总金额" value="<?php  echo $bag_total_amount['value'];?>" type="text">
        </div>
    </div>
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">当日提现上限:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <input class="form-control" name="bag_daily_upper" placeholder="当日提现上限" value="<?php  echo $bag_daily_upper['value'];?>" type="text">
        </div>
    </div>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">消耗活力币:</label>
            <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
                <input class="form-control" name="bag_currency" placeholder="消耗活力币" value="<?php  echo $bag_currency['value'];?>" type="text">
            </div>
        </div>
        <!--<div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">当天步数要求:</label>
            <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
                <input class="form-control" name="bag_step_number" placeholder="当天步数要求" value="<?php  echo $bag_step_number['value'];?>" type="text">
            </div>
        </div>-->


        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">后面四个红包消耗活力币</label>
            <div class="col-sm-9 col-xs-12">
                <label class="radio-inline">
                    <input type="radio" name="bag_currency_last" value="2" <?php  if($bag_currency_last['value'] == 2) { ?>checked<?php  } ?> /> 不消耗
                </label>
                <label class="radio-inline">
                    <input type="radio" name="bag_currency_last" value="1" <?php  if($bag_currency_last['value'] == 1) { ?>checked<?php  } ?> />消耗
                </label>
            </div>
        </div>

        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">第1个冷却时间:</label>
            <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
                <input class="form-control" name="bag_one_cooling" placeholder="单位:小时" value="<?php  echo $bag_one_cooling['value'];?>" type="text">
            </div>
        </div>

        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">第2个冷却时间:</label>
            <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
                <input class="form-control" name="bag_two_cooling" placeholder="单位:小时" value="<?php  echo $bag_two_cooling['value'];?>" type="text">
            </div>
        </div>

        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">第3个冷却时间:</label>
            <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
                <input class="form-control" name="bag_three_cooling" placeholder="单位:小时" value="<?php  echo $bag_three_cooling['value'];?>" type="text">
            </div>
        </div>


        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">第4个冷却时间:</label>
            <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
                <input class="form-control" name="bag_four_cooling" placeholder="单位:小时" value="<?php  echo $bag_four_cooling['value'];?>" type="text">
            </div>
        </div>

        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">邀请多少人解锁一个:</label>
            <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
                <input class="form-control" name="bag_invitations_number" placeholder="邀请多少人解锁一个红包" value="<?php  echo $bag_invitations_number['value'];?>" type="text">
            </div>
        </div>

        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">提醒领红包模板id:</label>
            <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
                <input class="form-control" name="bag_template_id" placeholder="提醒领红包模板消息id" value="<?php  echo $bag_template_id['value'];?>" type="text">
            </div>
        </div>

        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">红包模板消息发送时间:</label>
            <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
                <select name="bag_send_hour">
                    <?php  for ($i = 1; $i < 24; $i++){?>
                    <option <?php  if($bag_send_hour['value'] == $i) { ?>selected="selected"<?php  } ?> value="<?php  echo $i;?>"><?php  echo $i;?></option>
                    <?php  } ?>
                </select>点
                <select name="bag_send_minute">
                    <?php  for ($i = 0; $i < 60; $i++){?>
                    <option <?php  if($bag_send_minute['value'] == $i) { ?>selected="selected"<?php  } ?> value="<?php  echo $i;?>"><?php  echo $i;?></option>
                    <?php  } ?>
                </select>分
            </div>
        </div>



    <span id="cash_explain">
        <?php  if(is_array($cash_explain['value'])) { foreach($cash_explain['value'] as $key => $item) { ?>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label"><?php  if($key == 0) { ?>提现说明:<?php  } ?></label>
            <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
                <input class="form-control" style="width:80%;float:left" name="cash_explain[]" placeholder="提现说明" value="<?php  echo $item;?>" type="text">
                <?php  if($key == 0) { ?>
                <button type="button" style="margin-left: 5px;" class="btn btn-primary" id="add_cash_explain">增加</button>
                <?php  } else { ?>
                <button type="button" style="margin-left: 5px;" class="btn btn-primary" onclick="delete_cash_explain(this)">删除</button>
                <?php  } ?>
            </div>
        </div>
        <?php  } } ?>
    </span>
    <script type="application/javascript">

        $(function () {
            $('#add_cash_explain').click(function () {
                var html = '<div class="form-group">' +
                    '<label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>' +
                    '<div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">' +
                    '<input class="form-control" style="width:80%;float:left" name="cash_explain[]" placeholder="提现说明" value="" type="text">' +
                    '<button type="button" style="margin-left: 5px;" class="btn btn-primary" onclick="delete_cash_explain(this)">删除</button>' +
                    '</div>' +
                    '</div>';
                $('#cash_explain').append(html);
            });
        });
        function delete_cash_explain(t) {
            $(t).parent().parent().remove();
        }
    </script>
    <?php  } else if($op == 'withdrawal') { ?>
    <?php  if($follow_cash == true ) { ?>
    <div class="withdrawal" id="withdrawal">
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">是否开启公众号提现:</label>
        <div class="col-sm-9 col-xs-12">
            <label class="radio-inline">
                <input type="radio" name="follow_cash_switch" value="1" <?php  if($follow_cash_switch['value'] == 1) { ?>checked<?php  } ?> /> 关闭
            </label>
            <label class="radio-inline">
                <input type="radio" name="follow_cash_switch" value="2" <?php  if($follow_cash_switch['value'] == 2) { ?>checked<?php  } ?> />开启
            </label>
        </div>
    </div>
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">验证口令方式:</label>
        <div class="col-sm-9 col-xs-12">
            <label class="radio-inline">
                <input type="radio" name="follow_cash_verification" value="1" <?php  if($follow_cash_verification['value'] == 1) { ?>checked<?php  } ?> /> 当天第一次提现验证口令
            </label>
            <label class="radio-inline">
                <input type="radio" name="follow_cash_verification" value="2" <?php  if($follow_cash_verification['value'] == 2) { ?>checked<?php  } ?> />每次提现都需要验证口令
            </label>
        </div>
    </div>
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">提现口令:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <input class="form-control" name="follow_cash_pwd" placeholder="提现口令" value="<?php  echo $follow_cash_pwd['value'];?>" type="text">
        </div>
    </div>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">关注提现图片:</label>
            <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
                <?php  echo tpl_form_field_image('follow_cash_image', getImage($follow_cash_image['value'], true), getImage($follow_cash_image['value']));?>
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">大小:678 * 736</label>
            </div>
        </div>
    </div>
    <?php  } else { ?>
    <div class="withdrawal_no" id="withdrawal_no">
        <div class="form-group">
            <div class="col-xs-1 col-sm-5 col-md-5 control-label" style="color: red">尊敬的用户您好,你当前的版本不支持公众号提现</div>
        </div>
    </div>
    <?php  } ?>
    <?php  } else if($op == 'currency') { ?>
    <script>
        KindEditor.ready(function(K) {
        htmlEditor = K.create(
            'textarea[name="realize_currency"]',
            {
                uploadJson : "<?php  echo $this->createWebUrl('editorUpload');?>",
                fileManagerJson : '<?php  echo $siteRoot;?>addons/<?php  echo $modular_name;?>/template/kindeditor/php/file_manager_json.php',
                height : 300,
                width : '90%',
// fontSizeTable:['21px','20px','17px','18px','16px','14px','12px'],
                resizeType : 0,//1
                allowPreviewEmoticons : true,
                allowImageUpload : true,
                urlType:'domain',
                formatUploadUrl:false
            });
        });

    </script>
        <textarea name="realize_currency" style="width:900px;height:400px;visibility:hidden;"><?php  echo $realize_currency['value'];?></textarea>
    <?php  } else if($op == 'activity') { ?>
    <script>
        KindEditor.ready(function(K) {
            htmlEditor = K.create(
                'textarea[name="activity"]',
                {
                    uploadJson : "<?php  echo $this->createWebUrl('editorUpload');?>",
                    fileManagerJson : '<?php  echo $siteRoot;?>addons/<?php  echo $modular_name;?>/template/kindeditor/php/file_manager_json.php',
                    height : 300,
                    width : '90%',
                    resizeType : 0,//1
                    allowPreviewEmoticons : true,
                    allowImageUpload : true,
                    urlType:'domain',
                    formatUploadUrl:false
                });
        });
    </script>
    <textarea name="activity" style="width:900px;height:400px;visibility:hidden;"><?php  echo $activity['value'];?></textarea>
    <?php  } else if($op == 'share') { ?>

    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">特殊替换:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12" style="margin-top:8px">
            输入{name}会自动替换成昵称
        </div>
    </div>

    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">普通分享文字:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <input class="form-control" name="share_text" placeholder="普通分享文字" value="<?php  echo $share_text['value'];?>" type="text">
        </div>
    </div>
    <div class="form-group" id="ling-1">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">分享图片:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <?php  echo tpl_form_field_image('share_image', getImage($share_image['value'], true), getImage($share_image['value']));?>
        </div>
    </div>

    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">红包分享文字:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <input class="form-control" name="share_red_packet_text" placeholder="红包分享文字" value="<?php  echo $share_red_packet_text['value'];?>" type="text">
        </div>
    </div>
    <div class="form-group" id="ling-2">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">分享图片:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <?php  echo tpl_form_field_image('share_red_packet_image', getImage($share_red_packet_image['value'], true), getImage($share_red_packet_image['value']));?>
        </div>
    </div>

    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">排行榜分享文字:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <input class="form-control" name="share_top_text" placeholder="排行榜分享文字" value="<?php  echo $share_top_text['value'];?>" type="text">
        </div>
    </div>
    <div class="form-group" id="ling-2">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">排行榜分享图片:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <?php  echo tpl_form_field_image('share_top_text_image', getImage($share_top_text_image['value'], true), getImage($share_top_text_image['value']));?>
        </div>
    </div>
    <?php  } else if($op == 'ui') { ?>

    <script src="<?php  echo $siteRoot;?>addons/bh_step/template/js/colpick.js" type="text/javascript"></script>
    <link rel="stylesheet" href="<?php  echo $siteRoot;?>addons/bh_step/template/js/colpick.css" type="text/css"/>
    <div class="form-group" id="ling-2—1">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">步数日志banner:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <?php  echo tpl_form_field_image('step_log_banner', getImage($step_log_banner['value'], true), getImage($step_log_banner['value']));?>
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">大小:375 * 96</label>
        </div>
    </div>
    <div class="form-group" id="ling-3">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">开第一个红包背景图:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <?php  echo tpl_form_field_image('home_red_pack_bg', getImage($home_red_pack_bg['value'], true), getImage($home_red_pack_bg['value']));?>
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">大小:684 * 846</label>
        </div>
    </div>
    <div class="form-group" id="ling-4">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">背景图:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <?php  echo tpl_form_field_image('home_background_image', getImage($home_background_image['value'], true), getImage($home_background_image['value']));?>
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">大小:616 * 750</label>
        </div>
    </div>
    <div class="form-group" id="ling-5">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">悬浮金币图片:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <?php  echo tpl_form_field_image('home_suspension_coin_img', getImage($home_suspension_coin_img['value'], true), getImage($home_suspension_coin_img['value']));?>
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">大小:84 * 84</label>
        </div>
    </div>
    <div class="form-group" id="ling-6">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">首页我的活力币背景图:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <?php  echo tpl_form_field_image('home_my_coin_image', getImage($home_my_coin_image['value'], true), getImage($home_my_coin_image['value']));?>
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">大小:309 * 309</label>
        </div>
    </div>
    <div class="form-group" id="ling-7">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">步数换礼顶部图片:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <?php  echo tpl_form_field_image('shop_top_image', getImage($shop_top_image['value'], true), getImage($shop_top_image['value']));?>
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">大小:1500*560</label>
        </div>
    </div>
    <div class="form-group" id="ling-8">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">首页邀请领活力币图标:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <?php  echo tpl_form_field_image('home_invitation_coin', getImage($home_invitation_coin['value'], true), getImage($home_invitation_coin['value']));?>
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">大小:98 * 98</label>
        </div>
    </div>
    <div class="form-group" id="ling-9">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">首页开红包背景图:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <?php  echo tpl_form_field_image('home_redpack_open_image', getImage($home_redpack_open_image['value'], true), getImage($home_redpack_open_image['value']));?>
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">大小:712 * 202</label>
        </div>
    </div>
    <div class="form-group" id="ling-10">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">首页发红包背景图:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <?php  echo tpl_form_field_image('home_redpack_send_image', getImage($home_redpack_send_image['value'], true), getImage($home_redpack_send_image['value']));?>
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">大小:712 * 202</label>
        </div>
    </div>
    <div class="form-group" id="ling-11">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">排行榜上方图片:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <?php  echo tpl_form_field_image('rank_top_image', getImage($rank_top_image['value'], true), getImage($rank_top_image['value']));?>
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">大小:750 * 270</label>
        </div>
    </div>
    <div class="form-group" id="ling-12">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">海报图标:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <?php  echo tpl_form_field_image('poster_icon', getImage($poster_icon['value'], true), getImage($poster_icon['value']));?>
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">大小:72*72</label>
        </div>
    </div>
    <div class="form-group" id="ling-13">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">海报背景:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <?php  echo tpl_form_field_image('poster_background', getImage($poster_background['value'], true), getImage($poster_background['value']));?>
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">大小:670*1030</label>
        </div>
    </div>
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">排行榜左边榜单名称:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <input class="form-control" style="width: 30%;float: left" name="left_list_name" placeholder="排行榜左边榜单名称" value="<?php  echo $left_list_name['value'];?>" type="text">
        </div>
    </div>
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">排行榜右边榜单名称:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <input class="form-control" style="width: 30%;float: left" name="right_list_name" placeholder="排行榜右边榜单名称" value="<?php  echo $right_list_name['value'];?>" type="text">
        </div>
    </div>
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">步数日志描述1:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <input class="form-control" style="width: 60%;float: left" name="step_journal_one" placeholder="步数日志描述1" value="<?php  echo $step_journal_one['value'];?>" type="text">
        </div>
    </div>
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">步数日志描述2:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <input class="form-control" style="width: 60%;float: left" name="step_journal_two" placeholder="步数日志描述2" value="<?php  echo $step_journal_two['value'];?>" type="text">
        </div>
    </div>
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">首页悬浮金币内文字颜色:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <input class="form-control" style="width: 30%;float: left" id="home_suspension_coin_color" name="home_suspension_coin_color" placeholder="首页悬浮金币内文字颜色" value="<?php  echo $home_suspension_coin_color['value'];?>" type="text">
            <button id="picker1" class="btn btn-primary" style="margin-left: 10px" type="button">设置颜色</button>
        </div>
    </div>

    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">首页悬浮金币描述文字颜色:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <input class="form-control" style="width: 30%;float: left" id="home_suspension_coin_describe_color" name="home_suspension_coin_describe_color" placeholder="首页悬浮金币描述文字颜色" value="<?php  echo $home_suspension_coin_describe_color['value'];?>" type="text">
            <button id="picker2" class="btn btn-primary" style="margin-left: 10px" type="button">设置颜色</button>
        </div>
    </div>
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">首页今日步数文字颜色:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <input class="form-control" style="width: 30%;float: left" id="home_today_step_color" name="home_today_step_color" placeholder="首页今日步数文字颜色" value="<?php  echo $home_today_step_color['value'];?>" type="text">
            <button id="picker3" class="btn btn-primary" style="margin-left: 10px" type="button">设置颜色</button>
        </div>
    </div>
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">首页今日步数步数颜色:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <input class="form-control" style="width: 30%;float: left" id="home_today_step_num_color" name="home_today_step_num_color" placeholder="首页今日步数步数颜色" value="<?php  echo $home_today_step_num_color['value'];?>" type="text">
            <button id="picker4" class="btn btn-primary" style="margin-left: 10px" type="button">设置颜色</button>
        </div>
    </div>
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label"> 首页我的活力币文字颜色:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <input class="form-control" style="width: 30%;float: left" id="home_my_coin_color" name="home_my_coin_color" placeholder=" 首页我的活力币文字颜色" value="<?php  echo $home_my_coin_color['value'];?>" type="text">
            <button id="picker5" class="btn btn-primary" style="margin-left: 10px" type="button">设置颜色</button>
        </div>
    </div>
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">首页邀请好友记得渐变初始值:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <input class="form-control" style="width: 30%;float: left" id="home_share_start_color" name="home_share_start_color" placeholder="首页邀请好友记得渐变初始值" value="<?php  echo $home_share_start_color['value'];?>" type="text">
            <button id="picker6" class="btn btn-primary" style="margin-left: 10px" type="button">设置颜色</button>
        </div>
    </div>

    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">首页邀请好友记得渐变结束值:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <input class="form-control" style="width: 30%;float: left" id="home_share_end_color" name="home_share_end_color" placeholder="首页邀请好友记得渐变结束值" value="<?php  echo $home_share_end_color['value'];?>" type="text">
            <button id="picker7" class="btn btn-primary" style="margin-left: 10px" type="button">设置颜色</button>
        </div>
    </div>
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">首页邀请好友即得文字颜色:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <input class="form-control" style="width: 30%;float: left" id="home_share_color" name="home_share_color" placeholder="首页邀请好友即得文字颜色" value="<?php  echo $home_share_color['value'];?>" type="text">
            <button id="picker8" class="btn btn-primary" style="margin-left: 10px" type="button">设置颜色</button>
        </div>
    </div>
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">首页领红包文字颜色:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <input class="form-control" style="width: 30%;float: left" id="home_redpack_color" name="home_redpack_color" placeholder="首页领红包文字颜色" value="<?php  echo $home_redpack_color['value'];?>" type="text">
            <button id="picker9" class="btn btn-primary" style="margin-left: 10px" type="button">设置颜色</button>
        </div>
    </div>
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">首页了解活力币文字颜色:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <input class="form-control" style="width: 30%;float: left" id="home_understand_coin_color" name="home_understand_coin_color" placeholder="首页了解活力币文字颜色" value="<?php  echo $home_understand_coin_color['value'];?>" type="text">
            <button id="picker10" class="btn btn-primary" style="margin-left: 10px" type="button">设置颜色</button>
        </div>
    </div>
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">首页签到天数背景渐变初始值:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <input class="form-control" style="width: 30%;float: left" id="home_sigin_start_color" name="home_sigin_start_color" placeholder="首页签到天数背景渐变初始值" value="<?php  echo $home_sigin_start_color['value'];?>" type="text">
            <button id="picker11" class="btn btn-primary" style="margin-left: 10px" type="button">设置颜色</button>
        </div>
    </div>
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">首页签到天数背景渐变结束值:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <input class="form-control" style="width: 30%;float: left" id="home_sigin_end_color" name="home_sigin_end_color" placeholder="首页签到天数背景渐变结束值" value="<?php  echo $home_sigin_end_color['value'];?>" type="text">
            <button id="picker12" class="btn btn-primary" style="margin-left: 10px" type="button">设置颜色</button>
        </div>
    </div>
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">首页签到文字颜色:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <input class="form-control" style="width: 30%;float: left" id="home_sigin_color" name="home_sigin_color" placeholder="首页签到文字颜色" value="<?php  echo $home_sigin_color['value'];?>" type="text">
            <button id="picker13" class="btn btn-primary" style="margin-left: 10px" type="button">设置颜色</button>
        </div>
    </div>
    <script type="application/javascript">
        $('#picker1').colpick({
            submit: false,
            onChange: function (color, color2) {
                jQuery("#home_suspension_coin_color").val('#' + color2);
            }
        });
        $('#picker2').colpick({
            submit: false,
            onChange: function (color, color2) {
                jQuery("#home_suspension_coin_describe_color").val('#' + color2);
            }
        });
        $('#picker3').colpick({
            submit: false,
            onChange: function (color, color2) {
                jQuery("#home_today_step_color").val('#' + color2);
            }
        });
        $('#picker4').colpick({
            submit: false,
            onChange: function (color, color2) {
                jQuery("#home_today_step_num_color").val('#' + color2);
            }
        });
        $('#picker5').colpick({
            submit: false,
            onChange: function (color, color2) {
                jQuery("#home_my_coin_color").val('#' + color2);
            }
        });
        $('#picker6').colpick({
            submit: false,
            onChange: function (color, color2) {
                jQuery("#home_share_start_color").val('#' + color2);
            }
        });
        $('#picker7').colpick({
            submit: false,
            onChange: function (color, color2) {
                jQuery("#home_share_end_color").val('#' + color2);
            }
        });
        $('#picker8').colpick({
            submit: false,
            onChange: function (color, color2) {
                jQuery("#home_share_color").val('#' + color2);
            }
        });
        $('#picker9').colpick({
            submit: false,
            onChange: function (color, color2) {
                jQuery("#home_redpack_color").val('#' + color2);
            }
        });
        $('#picker10').colpick({
            submit: false,
            onChange: function (color, color2) {
                jQuery("#home_understand_coin_color").val('#' + color2);
            }
        });
        $('#picker11').colpick({
            submit: false,
            onChange: function (color, color2) {
                jQuery("#home_sigin_start_color").val('#' + color2);
            }
        });
        $('#picker12').colpick({
            submit: false,
            onChange: function (color, color2) {
                jQuery("#home_sigin_end_color").val('#' + color2);
            }
        });
        $('#picker13').colpick({
            submit: false,
            onChange: function (color, color2) {
                jQuery("#home_sigin_color").val('#' + color2);
            }
        });
    </script>

    <?php  } else { ?>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">小程序名:</label>
            <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
                <input class="form-control" name="xcx_title" placeholder="小程序名" value="<?php  echo $xcx_title['value'];?>" type="text">
            </div>
        </div>

    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">审核模式:</label>
        <div class="col-sm-9 col-xs-12">
            <label class="radio-inline">
                <input type="radio" name="audit_pattern" value="1" <?php  if($audit_pattern['value'] == 1) { ?>checked<?php  } ?> />关闭
            </label>
            <label class="radio-inline">
                <input type="radio" name="audit_pattern" value="2" <?php  if($audit_pattern['value'] == 2) { ?>checked<?php  } ?> /> 开启
            </label>

        </div>
    </div>

    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">多少金额提现需要审核:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <input class="form-control" name="cash_reviewed" placeholder="多少金额提现需要审核" value="<?php  echo $cash_reviewed['value'];?>" type="text">
        </div>
    </div>


    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">金币名称:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <input class="form-control" name="currency_name" placeholder="金币名称" value="<?php  echo $currency_name['value'];?>" type="text">
        </div>
    </div>
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">连续多少天达到最高步数拉黑:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <input class="form-control" name="member_continuous_day" placeholder="连续多少天达到最高步数拉黑" value="<?php  echo $member_continuous_day['value'];?>" type="text">
        </div>
    </div>
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">商品详情流量主id:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <input class="form-control" name="goods_flow_group" placeholder="商品详情流量主id" value="<?php  echo $goods_flow_group['value'];?>" type="text">
        </div>
    </div>
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">个人中心流量主id:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <input class="form-control" name="my_center_group" placeholder="个人中心流量主id" value="<?php  echo $my_center_group['value'];?>" type="text">
        </div>
    </div>
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">授权第一段文字:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <input class="form-control" name="authorization_first" placeholder="授权第一段文字" value="<?php  echo $authorization_first['value'];?>" type="text">
        </div>
    </div>
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">授权第二段文字:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <input class="form-control" name="authorization_two" placeholder="授权第二段文字" value="<?php  echo $authorization_two['value'];?>" type="text">
        </div>
    </div>
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">分享到5个群获得福利:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <input class="form-control" name="home_bottom_font" placeholder="每天上限分享至5个群，次日可重新分享" value="<?php  echo $home_bottom_font['value'];?>" type="text">
        </div>
    </div>
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">分享至群活的福利:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <input class="form-control" name="home_bottom_share" placeholder="分享至群活的福利" value="<?php  echo $home_bottom_share['value'];?>" type="text">
        </div>
    </div>
    <!--<div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">阿拉丁APPkey:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <input class="form-control" name="ald_key" placeholder="阿拉丁APPkey" value="<?php  echo $ald_key['value'];?>" type="text">
        </div>
    </div>-->

    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">首页是否开启授权弹窗:</label>
        <div class="col-sm-9 col-xs-12">
            <label class="radio-inline">
                <input type="radio" name="author_show" value="1" <?php  if($author_show['value'] == 1) { ?>checked<?php  } ?> />开启
            </label>
            <label class="radio-inline">
                <input type="radio" name="author_show" value="2" <?php  if($author_show['value'] == 2) { ?>checked<?php  } ?> /> 不开启
            </label>

        </div>
    </div>
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">联系我们弹框引导:</label>
        <div class="col-sm-9 col-xs-12">
            <label class="radio-inline">
                <input type="radio" name="contantus_pop" value="1" <?php  if($contantus_pop['value'] == 1) { ?>checked<?php  } ?> />不开启
            </label>
            <label class="radio-inline">
                <input type="radio" name="contantus_pop" value="2" <?php  if($contantus_pop['value'] == 2) { ?>checked<?php  } ?> /> 开启
            </label>

        </div>
    </div>
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">是否开启线下核销:</label>
        <div class="col-sm-9 col-xs-12">
            <label class="radio-inline">
                <input type="radio" name="open_under_line" value="2" <?php  if($open_under_line['value'] == 2) { ?>checked<?php  } ?> /> 关闭
            </label>
            <label class="radio-inline">
                <input type="radio" name="open_under_line" value="1" <?php  if($open_under_line['value'] == 1) { ?>checked<?php  } ?> />开启
            </label>
        </div>
    </div>

    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">红包自动开启提醒:</label>
        <div class="col-sm-9 col-xs-12">
            <label class="radio-inline">
                <input type="radio" name="bag_remind_switch" value="2" <?php  if($bag_remind_switch['value'] == 2) { ?>checked<?php  } ?> /> 关闭
            </label>
            <label class="radio-inline">
                <input type="radio" name="bag_remind_switch" value="1" <?php  if($bag_remind_switch['value'] == 1) { ?>checked<?php  } ?> />开启
            </label>
        </div>
    </div>
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">签到自动开启提醒:</label>
        <div class="col-sm-9 col-xs-12">
            <label class="radio-inline">
                <input type="radio" name="sigin_remind_switch" value="2" <?php  if($sigin_remind_switch['value'] == 2) { ?>checked<?php  } ?> /> 关闭
            </label>
            <label class="radio-inline">
                <input type="radio" name="sigin_remind_switch" value="1" <?php  if($sigin_remind_switch['value'] == 1) { ?>checked<?php  } ?> />开启
            </label>
        </div>
    </div>
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">apiclient_cert:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <input class="form-control" name="_apiclient_cert" placeholder="apiclient_cert" type="file">
            <input class="form-control" value="<?php  echo $apiclient_cert['value'];?>" name="apiclient_cert" disabled="disabled">
        </div>
    </div>
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">apiclient_key:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <input class="form-control" name="_apiclient_key" placeholder="apiclient_key" type="file">
            <input class="form-control" value="<?php  echo $apiclient_key['value'];?>" name="apiclient_key" disabled="disabled">
        </div>
    </div>

        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">首次提现金额:</label>
            <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
                <input class="form-control" name="first_presentation" placeholder="首次提现金额" value="<?php  echo $first_presentation['value'];?>" type="text">
            </div>
        </div>

        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">后续提现金额:</label>
            <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
                <input class="form-control" name="follow_up_presentation" placeholder="授权奖励活力币" value="<?php  echo $follow_up_presentation['value'];?>" type="text">
            </div>
        </div>

        <!--<div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">关注公众号励活力币:</label>
            <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
                <input class="form-control" name="follow_currency" placeholder="关注公众号励活力币" value="<?php  echo $follow_currency['value'];?>" type="text">
            </div>
        </div>-->

        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">邀请可获得金币范围:</label>
            <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12" style="float: left">
                <input class="form-control" name="invitation_currency_start" style="width:20%;float: left" placeholder="" value="<?php  echo $invitation_currency_start['value'];?>" type="text">
                <input class="form-control" name="invitation_currency_end" style="width:20%;float: left" placeholder="" value="<?php  echo $invitation_currency_end['value'];?>" type="text">
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">每天邀请有效数:</label>
            <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
                <input class="form-control" name="invitation_effective_number" placeholder="每天邀请有效数" value="<?php  echo $invitation_effective_number['value'];?>" type="text">
            </div>
        </div>

        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">分享到群可获得活跃币:</label>
            <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
                <input class="form-control" name="share_group_currency" placeholder="分享到群可获得活跃币" value="<?php  echo $share_group_currency['value'];?>" type="text">
            </div>
        </div>
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">普通用户多少步转化1活力币:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <input class="form-control" name="nomal_echange_currency" placeholder="普通用户多少步转化1活力币" value="<?php  echo $nomal_echange_currency['value'];?>" type="text">
        </div>
    </div>
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">VIP用户多少步转化1活力币:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <input class="form-control" name="vip_echange_currency" placeholder="VIP用户多少步转化1活力币" value="<?php  echo $vip_echange_currency['value'];?>" type="text">
        </div>
    </div>

        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">多少步转换1活力币:</label>
            <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
                <input class="form-control" name="step_currency" placeholder="多少步转换1活力币" value="<?php  echo $step_currency['value'];?>" type="text">
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">一次最多可砍步数:</label>
            <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
                <input class="form-control" name="cut_step_max" placeholder="一次最多可砍步数" value="<?php  echo $cut_step_max['value'];?>" type="text">
            </div>
        </div>
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">每天有效步数上限:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <input class="form-control" name="effective_step_currency" placeholder="可用来兑换活力币的步数" value="<?php  echo $effective_step_currency['value'];?>" type="text">
        </div>
    </div>
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">兑换步数商品好友步数占比例:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <input class="form-control" name="friend_progress" placeholder="兑换步数商品好友步数占比例" value="<?php  echo $friend_progress['value'];?>" type="text">
        </div>
    </div>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">提醒签到模板id:</label>
            <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
                <input class="form-control" name="signin_template_id" placeholder="提醒签到模板id" value="<?php  echo $signin_template_id['value'];?>" type="text">
            </div>
        </div>
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">商品发货模板消息id:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <input class="form-control" name="send_message_id" placeholder="商品发货模板消息id" value="<?php  echo $send_message_id['value'];?>" type="text">
        </div>
    </div>
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">首次授权奖励活跃币</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <input class="form-control" name="author_currency" placeholder="首次授权奖励活跃币" value="<?php  echo $author_currency['value'];?>" type="text">
        </div>
    </div>
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">签到模板消息发送时间:</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <select name="sign_send_hour">
                <?php  for ($i = 1; $i < 24; $i++){?>
                <option <?php  if($sign_send_hour['value'] == $i) { ?>selected="selected"<?php  } ?> value="<?php  echo $i;?>"><?php  echo $i;?></option>
                <?php  } ?>
            </select>点
            <select name="sign_send_minute">
                <?php  for ($i = 0; $i < 60; $i++){?>
                <option <?php  if($sign_send_minute['value'] == $i) { ?>selected="selected"<?php  } ?> value="<?php  echo $i;?>"><?php  echo $i;?></option>
                <?php  } ?>
            </select>分
        </div>
    </div>

    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">定时任务</label>
        <div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">
            <?php  echo $crontab;?>
        </div>
    </div>


    <?php  if($isDomain ==1) { ?>
     <span id="system_notice">
        <?php  if(is_array($system_notice['value'])) { foreach($system_notice['value'] as $key => $item) { ?>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label"><?php  if($key == 0) { ?>系统公告:<?php  } ?></label>
            <div class="col-sm-6 col-lg-8 col-md-6 col-xs-6">
                  <input class="form-control" style="width:80%;float:left" name="system_notice[]" placeholder="系统公告" value="<?php  echo $item;?>" type="text">
                <?php  if($key == 0) { ?>
                <button type="button" style="margin-left: 5px;" class="btn btn-primary" id="add_system_notice">增加</button>
                <?php  } else { ?>
                <button type="button" style="margin-left: 5px;" class="btn btn-primary" onclick="delete_system_notice(this)">删除</button>
                <?php  } ?>
            </div>
        </div>
        <?php  } } ?>
    </span>
    <script type="application/javascript">
        $(function () {
            $('#add_system_notice').click(function () {
                var html = '<div class="form-group">' +
                        '<label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>' +
                        '<div class="col-sm-7 col-lg-8 col-md-8 col-xs-12">' +
                        '<input class="form-control" style="width:80%;float:left" name="system_notice[]" placeholder="系统公告" value="" type="text">' +
                        '<button type="button" style="margin-left: 5px;" class="btn btn-primary" onclick="delete_system_notice(this)">删除</button>' +
                        '</div>' +
                        '</div>';
                $('#system_notice').append(html);
            });
        });
        function delete_system_notice(t) {
            $(t).parent().parent().remove();
        }
    </script>
    <?php  } ?>
    <?php  } ?>
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
        <div class="" >
            <input type="hidden" name="op" value="<?php  echo $op;?>">
            <input type="submit" name="submit" id="submit" value="保存" class="btn btn-primary">
        </div>
    </div>
</form>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('footer', TEMPLATE_INCLUDEPATH)) : (include template('footer', TEMPLATE_INCLUDEPATH));?>

