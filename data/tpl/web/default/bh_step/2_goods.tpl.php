<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('header', TEMPLATE_INCLUDEPATH)) : (include template('header', TEMPLATE_INCLUDEPATH));?>
<link href="./resource/css/app.css" rel="stylesheet">
<ul class="nav nav-tabs">
    <li class="active"><a href="javascript:;">商品管理</a></li>
    <li class=""><a href="<?php  echo $this->createWeburl('goods', array('op' => 'addshow'));?>">添加商品</a></li>
</ul>

<form action="./index.php" method="get" class="form-horizontal">
    <input type="hidden" name="c" value="site">
    <input type="hidden" name="a" value="entry">
    <input type="hidden" name="m" value="bh_step">
    <input type="hidden" name="do" value="goods">

    <div class="form-group">
        <label class="col-xs-12 col-sm-1 control-label">商品名称</label>
        <div class="col-sm-2 col-xs-12">
            <input type="text" class="form-control" name="goods_name"  value="<?php  echo $goods_name;?>" />
        </div>
        <label class="col-xs-12 col-sm-1 control-label">商品分类</label>
        <div class="col-sm-2 col-xs-12">
            <select class="select valid" name="category_id" size="1" aria-invalid="false" style="width: 150px;">
                <option value="0">所有分类</option>
                <?php  if(is_array($category)) { foreach($category as $item) { ?>
                <option value="<?php  echo $item['id'];?>" <?php  if($category_id == $item['id']) { ?>selected="selected"<?php  } ?>><?php  echo $item['category_name'];?></option>
                <?php  } } ?>
            </select>
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
                <th style="width:20px;">id</th>
                <th style="width:80px;">商品名称</th>
                <th style="width:70px;">所属分类</th>
                <th style="width:60px;">类型</th>
                <th style="width:55px;">兑换方式</th>
                <th style="width:85px;">需要活力币/步数</th>
                <th style="width:85px;">兑换金额</th>
                <th style="width:60px;">需要邀请好友数</th>
                <th style="width:80px;">商品库存方式</th>
                <th style="width:60px;">库存/每天提供数量</th>
                <th style="width:80px;">每人允许兑换数量</th>
                <th style="width:80px;">排序</th>
                <th style="width:35px;">状态</th>
                <th style="width:80px;">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php  if(is_array($list)) { foreach($list as $item) { ?>
            <tr>
                <td><?php  echo $item['id'];?></td>
                <td><?php  echo $item['goods_name'];?></td>
                <td><?php  echo $item['category']['category_name'];?></td>
                <td><?php echo $item['type'] == 1? '实物' : ($item['type'] == 2 ? '虚拟' : '红包')?></td>
                <td><?php  echo $exchange_types[$item['exchange_type']]?></td>
                <td><?php  echo $item['exchange_number'];?></td>
                <td><?php  echo $item['money'];?></td>
                <td><?php  echo $item['invitation_number'];?></td>
                <td><?php echo $item['inventory_type'] == 1 ? '每天限量提供' : '总计'?></td>
                <td><?php  echo $item['inventory'];?></td>
                <td><?php  echo $item['allow_number'];?></td>
                <td><input type="text" name="sort" class="sort" data-id="<?php  echo $item['id'];?>" value="<?php  echo $item['sort'];?>" style="width:30px;"></td>
                <td><?php echo $item['status'] == 1 ? '上架' : '下架'?></td>
                <td>
                    <a href="<?php  echo $this->createWeburl('goods', array('op' => 'editshow', 'id' => $item['id']));?>">编辑</a>
                    <a href="javascript:;" data-id="<?php  echo $item['id'];?>" class="delete">删除</a>
                    <a href="<?php  echo $this->createWeburl('add_order', array('op' => 'list', 'id' => $item['id']));?>">兑换记录</a>
                    <?php  if($item['type'] == 2 ) { ?>
                    <a href="<?php  echo $this->createWeburl('goods_fictitious', array('op' => 'fictitious', 'id' => $item['id']));?>">虚拟管理</a>
                    <?php  } ?>
                </td>
            </tr>
            <?php  } } ?>
            </tbody>
        </table>
    </div>
    <?php  echo $pager;?>
</div>
<script type="application/javascript">
    $(function () {
        $('.delete').click(function () {
            if (!confirm('确认要删除该商品')) {
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

        $('.sort').blur(function () {
            var sort = $(this).val();
            var  id = $(this).data('id');
            $.post('', {id:id,sort:sort, op:'sort'}, function (data) {
                if (data.status == 1) {
                    location.href = location.href;
                } else {
                    alert('置顶失败');
                }

            }, 'json')
        });

        $('.settop').click(function () {
            id = $(this).data('id');
            $.post('', {id:id, op:'settop'}, function (data) {
                if (data.status == 1) {
                    location.href = location.href;
                } else {
                    alert('置顶失败');
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