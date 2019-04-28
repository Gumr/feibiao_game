<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<style type="text/css">
    .table>tbody>tr>td{vertical-align: middle; }
</style>
<div class="clearfix">
<ul class="nav nav-tabs">
    <li class="active"><a href="<?php  echo $this->createWebUrl('goods')?>">物品列表</a></li>
    <li><a href="<?php  echo $this->createWebUrl('goods_post')?>">添加物品</a></li>
</ul>
<div class="clearfixcon">
<div class="panel panel-default">
    <div class="panel-body">
        <form action="" method="get" class="form-horizontal" role="form">
            <div class="panel panel-default">
                <div class="panel-heading">筛选</div>
                <div class="panel-body">
                        <input type="hidden" name="c" value="site">
                        <input type="hidden" name="a" value="entry">
                        <input type="hidden" name="m" value="hc_doudou">
                        <input type="hidden" name="do" value="goods">
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">商品名称</label>
                            <div class="col-xs-12 col-sm-6 col-lg-6">
                                <input class="form-control" name="keyword" type="text" value="<?php  echo $keyword;?>" placeholder="请输入商品名称">
                            </div>
                            <div class=" col-xs-12 col-sm-4 col-lg-4">
                                <button class="btn btn-default"><i class="fa fa-search"></i> 搜索</button>
                            </div>
                        </div>
                </div>
            </div>
        </form>
        <div class="table-responsive panel-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="all"></th>                
                        <th>ID</th>
                        <th>商品图片</th>
                        <th>商品名称</th>
                        <th>商品型号</th>
                        <th>专柜价格</th>
                        <th>闯关价格</th>
                        <th>商品类型</th>
                        <th style="text-align:center;">操作</th>
                    </tr>
                </thead>
                <tbody  class="che">
                    <?php  if(is_array($list)) { foreach($list as $index => $item) { ?>
                    <tr>
                        <td><input type="checkbox" value="<?php  echo $item['id'];?>"></td>
                        <td class="text-left"><?php  echo $item['id'];?></td>
                        <td class="text-left"><img src="<?php  echo $item['thumb'];?>" style="width: 50px;"></td>
                        <td class="text-left"><?php  echo $item['title'];?></td>
                        <td class="text-left"><?php  echo $item['model'];?></td>
                        <td class="text-left"><?php  echo $item['storeprice'];?></td>
                        <td class="text-left"><?php  echo $item['price'];?></td>
                        <td class="text-left"><?php echo $item['category'] == 2 ? '虚拟' : '实物'?></td>
                        <td class="text-center">
							<a href="<?php  echo $this->createWebUrl('goods_post',array('id'=>$item['id']))?>" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="详情"><i class="fa fa-edit"></i></a>
                            <a class="btn btn-default btn-sm" href="<?php  echo $this->createWebUrl('Goods_post',array('act'=>'del','id'=>$item['id']))?>" onclick="return confirm('确认删除此商品吗？');return false;" title="删除">删</a>
						</td>
                    </tr>
                    <?php  } } ?>
                </tbody>
            </table>
            <?php  echo $page;?>
            <div style="margin-top: 20px;    margin-left: -16px;">
                <input id="submit" type="submit" value="删除" class="btn btn-danger">
            </div>
        </div>
    </div>
</div>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>

<script type="text/javascript">
  
$(function () {
    //全选或全不选
    $("#all").click(function(){   
        if(this.checked){   
            $(":checkbox").prop("checked", true);  
        }else{   
            $(":checkbox").prop("checked", false);
        }   
    }); 
    
    //获取选中选项的值
    $("#submit").click(function(){
        var val = '';
        $(".che :checkbox").each(function(i){
            if($(this).is(":checked")){
                val += $(this).val() + ",";
            }
        });
        $.ajax({
            url:"<?php  echo $this->createWebUrl('Goods_post',array('act'=>moredel));php?>",
            data:{ids:val},
            type:"post",
            dataType:"json",
            success:function(data){
                alert('删除成功');
                location.reload();
            }
        });
    });
    
});

</script>