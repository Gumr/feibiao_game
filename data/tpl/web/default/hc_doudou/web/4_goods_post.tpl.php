<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<style type="text/css">
	.help-block{
		color: red !important;
	}
</style>
<div class="clearfix">
<ul class="nav nav-tabs">
	<li><a href="<?php  echo $this->createWebUrl('goods')?>">物品列表</a></li>
	<li class="active"><a href="<?php  echo $this->createWebUrl('goods_post')?>">添加物品</a></li>
</ul>
<div class="clearfix">
		<form action="" method="post" class="form-horizontal form" enctype="multipart/form-data" id="form2">
			<div class="panel panel-default" id="step1">
				<div class="panel-body">
					<div class="form-group">
						<label class="col-md-2 control-label">商品排序：</label>
						<div class="col-md-2">
							<input class="form-control" type="text" name="sort" value="<?php  echo $info['sort'];?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label">商品名称：</label>
						<div class="col-md-6">
							<input class="form-control" type="text" name="title" value="<?php  echo $info['title'];?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label">商品型号：</label>
						<div class="col-md-6">
							<input class="form-control" type="text" name="model" value="<?php  echo $info['model'];?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label">商品图片：</label>
						<div class="col-sm-6">
							<?php  echo tpl_form_field_image('thumb',$info['thumb']);?>
							<div class='help-block'>图片尺寸为正方形</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label">专柜价格：</label>
						<div class="col-md-2">
							<input class="form-control" type="text" name="storeprice" value="<?php  echo $info['storeprice'];?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label">闯关价格：</label>
						<div class="col-md-2">
							<input class="form-control" type="text" name="price" value="<?php  echo $info['price'];?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label"> 库存数量：</label>
						<div class="col-md-2">
							<input class="form-control" type="text" name="amount" value="<?php  echo $info['amount'];?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label">商品类型：</label>
						<div class="col-sm-9 col-xs-12">
							<label class="radio-inline">
								<input type="radio" name="type" value="1"  <?php  if($info['category'] == 1) { ?> checked <?php  } ?> /> 实物
							</label>
							<label class="radio-inline">
								<input type="radio" name="type" value="2" <?php  if($info['category'] == 2) { ?> checked <?php  } ?> />虚拟商品
							</label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label"></label>
						<div class="col-md-9">
							<?php  if(empty($info['id'])) { ?>
								<input type="hidden" name="act" value="add">
							<?php  } else { ?>
								<input type="hidden" name="act" value="edit">
								<input name="id" type="hidden" value="<?php  echo $info['id'];?>">
							<?php  } ?>
							<input name="submit" id="submit" type="submit" value="保存" class="btn btn-primary min-width">
						</div>
					</div>

				</div>

			</div>

		</form>

	</div>
</div>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>