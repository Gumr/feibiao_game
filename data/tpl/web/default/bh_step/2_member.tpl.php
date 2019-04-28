<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('header', TEMPLATE_INCLUDEPATH)) : (include template('header', TEMPLATE_INCLUDEPATH));?>
<link href="./resource/css/app.css" rel="stylesheet">
<ul class="nav nav-tabs">
    <li class="active"><a href="javascript:;">用户管理</a></li>
    <li class=""><a href="<?php  echo $this->createWebUrl('fictitious')?>">虚拟用户管理</a></li>

</ul>
<form action="./index.php" method="get" class="form-horizontal">
    <input type="hidden" name="c" value="site">
    <input type="hidden" name="a" value="entry">
    <input type="hidden" name="m" value="bh_step">
    <input type="hidden" name="do" value="member">
    <div class="form-group">
        <label class="col-xs-12 col-sm-1 control-label">用户昵称</label>
        <div class="col-sm-2 col-xs-12">
            <input type="text" class="form-control" name="nickname"  value="<?php  echo $nickname;?>" />
        </div>
        <label class="col-xs-12 col-sm-1 control-label">用户id</label>
        <div class="col-sm-2 col-xs-12">
            <input type="text" class="form-control" name="member_id"  value="<?php  echo $member_id;?>" />
        </div>
        <label class="col-xs-12 col-sm-1 control-label">用户状态</label>
        <div class="col-sm-2 col-xs-12">
           <select name="member_status">
               <option value="0"  <?php  if($member_status != 1 ||$member_status !=2 ) { ?> checkd <?php  } ?>>全部</option>
               <option value="1" <?php  if($member_status == 1 ) { ?> checkd <?php  } ?> >正常</option>
               <option value="2"  <?php  if($member_status == 2 ) { ?> checkd <?php  } ?>>拉黑</option>
           </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-xs-12 col-sm-1 control-label">时间选择</label>
        <div class="col-sm-3 col-xs-4">
            <?php  echo tpl_form_field_daterange('time', $time, $time = true)?>
        </div>
        <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
            <button type="submit" class="btn btn-default"><i class="fa fa-search"></i> 搜索</button>
        </div>
    </div>
</form>
<div class="panel panel-default">
    <div class="table-responsive panel-body">
        <table class="table table-hover">
            <thead class="navbar-inner">
            <tr>
                <th style="width:80px;">用户id</th>
                <th style="width:80px;">用户昵称</th>
                <th style="width:70px;">头像</th>
                <th style="width:80px;">账户余额<a href="<?php  echo $this->createWeburl('member', array('op' => 'money_sort'));?>">↑</a><a href="<?php  echo $this->createWeburl('member', array('op' => 'money_r_sort'));?>">↓</a></th>
                <th style="width:80px;">活力币<a href="<?php  echo $this->createWeburl('member', array('op' => 'currency_sort'));?>">↑</a><a href="<?php  echo $this->createWeburl('member', array('op' => 'currency_r_sort'));?>">↓</a></th>
                <th style="width:80px;">领取红包个数<a href="<?php  echo $this->createWeburl('member', array('op' => 'redPackets_sort'));?>">↑</a><a href="<?php  echo $this->createWeburl('member', array('op' => 'redPackets_r_sort'));?>">↓</a></th>
                <th style="width:80px;">邀请数量<a href="<?php  echo $this->createWeburl('member', array('op' => 'inviteNumber_sort'));?>">↑</a><a href="<?php  echo $this->createWeburl('member', array('op' => 'inviteNumber_r_sort'));?>">↓</a></th>
                <th style="width:70px;">邀请人</th>
                <th style="width:70px;">关联店铺</th>
                <th style="width:70px;">状态</th>
                <th style="width:70px;">注册时间</th>
                <th style="width:80px;">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php  if(is_array($list)) { foreach($list as $item) { ?>
            <tr>
                <td><?php  echo $item['id'];?></td>
                <td><?php  echo $item['nickname'];?></td>
                <td><img src="<?php  echo $item['head'];?>" width="39px" height="39px"></td>
                <td><?php  echo $item['money'];?></td>
                <td>
                    <?php  if($item['currency'] != '0.00') { ?>
                    <a href="<?php  echo $this->createWeburl('member', array('op' => 'currency', 'id' => $item['id']));?>">
                        <?php  echo $item['currency'];?>
                        </a>
                    <?php  } else { ?>
                    0
                    <?php  } ?>
                </td>
                <td>
                    <?php  if($item['red_packets']) { ?>
                    <a href="<?php  echo $this->createWeburl('member', array('op' => 'red_packets', 'id' => $item['id']));?>">
                        <?php  echo $item['red_packets'];?>
                    </a>
                    <?php  } else { ?>
                    0
                    <?php  } ?>
                </td>
                <td>
                    <?php  if($item['invite_number']) { ?>
                    <a href="<?php  echo $this->createWeburl('member', array('op' => 'invite_number', 'id' => $item['id']));?>">
                        <?php  echo $item['invite_number'];?>
                    </a>
                    <?php  } else { ?>
                    0
                    <?php  } ?>
                </td>
                <td><?php echo $item['invite_people']['nickname'] ? : "未邀请" ?></td>
                <td>
                    <?php  if($item['shop_id'] > 0) { ?>
                    <?php  echo $item['shop']['shop_name'];?>
                    <?php  } else { ?>
                    未绑定
                    <?php  } ?>
                </td>
                <td><?php echo $item['status'] == 1 ? '正常' : '拉黑'?></td>
                <td><?php  echo date('Y-m-d H:i:s', $item['add_time'])?></td>

                <td>
                    <?php  if($item['shop_id'] > 0) { ?>
                    <a href="javascript:;" class="cancel" data-id="<?php  echo $item['id'];?>">取消关联</a>
                    <?php  } else { ?>
                    <a href="<?php  echo $this->createWeburl('member', array('op' => 'shop', 'id' => $item['id']));?>">关联店铺</a>
                    <?php  } ?>


                    <?php  if($item['status'] ==1) { ?>
                    <a href="<?php  echo $this->createWeburl('member', array('op' => 'shielding', 'id' => $item['id']));?>">拉黑</a>
                    <?php  } else { ?>
                    <a href="<?php  echo $this->createWeburl('member', array('op' => 'cancel', 'id' => $item['id']));?>">取消拉黑</a>
                    <?php  } ?>
                    <a href="<?php  echo $this->createWeburl('member', array('op' => 'userTodayStep', 'id' => $item['id']));?>">查看步数</a>
                </td>
            </tr>
            <?php  } } ?>
            </tbody>
        </table>
    </div>

    <?php  echo $pager;?>
</div>
<div class="ding" style="text-align: left">总计用户<span style="color: red"><?php echo $total ? : 0?></span>,有效用户<span style="color: red"><?php echo $todayMember ? : 0?></span></div>
<script type="application/javascript">
    $(function () {
        $('.delete').click(function () {
            if (!confirm('确认要删除该问题')) {
                return false;
            }
            id = $(this).data('id');
            $.post('', {id:id, op:'delete'}, function (data) {
                if (data.status == 1) {
                    location.href = location.href;
                } else {
                    alert('删除失败');
                }

            }, 'json')
        });
        $('.cancel').click(function () {
            if (!confirm('确认要取消该关联店铺')) {
                return false;
            }
            id = $(this).data('id');
            $.post('', {id:id, op:'cancel_shop'}, function (data) {
                if (data.status == 1) {
                    location.href = location.href;
                } else {
                    alert('删除失败');
                }

            }, 'json')
        });

        $('.is_down').click(function () {
            if (!confirm('确认要' + $(this).html())) {
                return false;
            }
            id = $(this).data('id');
            $.post('', {id:id, op:'down'}, function (data) {
                if (data.status == 1) {
                    location.href = location.href;
                } else {
                    alert('设置失败');
                }

            }, 'json')
        });
    });

</script>