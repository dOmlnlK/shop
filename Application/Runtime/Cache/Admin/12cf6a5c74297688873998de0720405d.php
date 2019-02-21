<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>ECSHOP 管理中心 - <?php echo $_page_title;?> </title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="/Public/Admin/Styles/general.css" rel="stylesheet" type="text/css" />
    <link href="/Public/Admin/Styles/main.css" rel="stylesheet" type="text/css" />
</head>
<body>
<h1>
    <span class="action-span"><a href="<?php echo $_page_btn_link?>"><?php echo $_page_btn_name?></a>
    </span>
    <span class="action-span1"><a href="<?php echo U('lst')?>">ECSHOP 管理中心</a></span>
    <span id="search_id" class="action-span1"> - <?php echo $_page_title?> </span>
    <div style="clear:both"></div>
</h1>

<!--内容-->

<?php $model = D('privilege');$model->getMenu();?>
<div class="form-div">
    <form action="/index.php/Admin/Goods/lst" name="searchForm">
        <img src="/Public/Admin/Images/icon_search.gif" width="26" height="22" border="0" alt="search" />

        <!--主分类-->
        <p>
            <span>分类：</span>
            <select name="cat_id">
                <option value="0">请选择</option>
                <?php foreach($cats as $cat):?>
                <?php if($cat['id']==I('get.cat_id')):?>
                <option value="<?php echo ($cat["id"]); ?>" selected="selected">
                <?php else:?>
                <option value="<?php echo ($cat["id"]); ?>">
                <?php endif?>
                <?php echo str_repeat('-',4*$cat['level']); echo ($cat["cat_name"]); ?></option>
                <?php endforeach?>
            </select>
        </p>


        <!--品牌-->
        <p>
            <span>所属品牌：</span>
            <?php buildSelect('brand','brand_id','brand_name','id',I('get.brand_id'))?>
        </p>
        <!-- 商品名称 -->
        <p>
            <label for="goods_name">商品名称：</label>
            <input name="goods_name" id="goods_name" value="<?php echo I('get.goods_name');?>" >
        </p>

        <!-- 价格范围 -->
        <p>
            <span>价格范围：</span>
            <input name="fp" size="8"value="<?php echo I('get.fp');?>"/>到<input name="tp" size="8"value="<?php echo I('get.tp');?>"/>
        </p>

        <!-- 是否上架 -->
        <p>
            <span>是否上架：</span>
            <?php $ios=I('get.is_on_sale')?>
            <input type="radio" name="is_on_sale" value='' <?php if($ios=='') echo 'checked=checked'?> />全部</input>
            <input type="radio" name="is_on_sale" value='是' <?php if($ios=='是') echo 'checked=checked'?> />上架</input>
            <input type="radio" name="is_on_sale" value='否' <?php if($ios=='否') echo 'checked=checked'?> />下架</input>
        </p>

        <!-- 添加时间 -->
        <p>
            <span>时间范围：</span>
            <input name="ft" id='ft' size="10" value="<?php echo I('get.ft');?>"/>到<input name="tt" id='tt' size="10" value="<?php echo I('get.tt');?>"/>
        </p>

        <!-- 排序方式 -->
        <p>
            <span>排序方式：</span>
            <?php $odby=I('get.odby')?>
            <input onclick="this.parentNode.parentNode.submit()" type="radio" name="odby" value='id_desc' <?php if($odby=='id_desc') echo 'checked=checked'?> />id降序</input>
            <input onclick="this.parentNode.parentNode.submit()" type="radio" name="odby" value='id_asc' <?php if($odby=='id_asc') echo 'checked=checked'?> />id升序</input>
            <input onclick="this.parentNode.parentNode.submit()" type="radio" name="odby" value='time_desc' <?php if($odby=='time_desc') echo 'checked=checked'?> />时间降序</input>
            <input onclick="this.parentNode.parentNode.submit()" type="radio" name="odby" value='time_asc' <?php if($odby=='time_asc') echo 'checked=checked'?> />时间升序</input>
        </p>

        <input type="submit" value="提交"/>
    </form>
</div>

<!-- 商品列表 -->
<form method="post" action="" name="listForm" onsubmit="">
    <div class="list-div" id="listDiv">
        <table cellpadding="3" cellspacing="1">
            <tr>
                <th>编号</th>
                <th>主分类</th>
                <th>扩展分类</th>
                <th>品牌名称</th>
                <th>商品名称</th>
                <th>商品图标</th>
                <th>市场价格</th>
                <th>本店价格</th>
                <th>上架</th>
                <th>添加时间</th>
                <th>操作</th>
            </tr>
            <?php foreach($data['data'] as $k =>$val):?>
            <tr class="tron">
                <td align="center"><?php echo ($val["id"]); ?></td>
                <td align="center"><?php echo ($val["cat_name"]); ?></td>
                <td align="center"><?php echo ($val["ext_cat_name"]); ?></td>
                <td align="center"><?php echo ($val["brand_name"]); ?></td>
                <td align="center" class="first-cell"><span><?php echo ($val["goods_name"]); ?></span></td>
                <td align="center"><?php showPic($val['sm_logo'])?></td>
                <td align="center"><span><?php echo ($val["market_price"]); ?></span></td>
                <td align="center"><span><?php echo ($val["shop_price"]); ?></span></td>
                <td align="center"><span><?php echo ($val["is_on_sale"]); ?></span></td>
                <td align="center"><span><?php echo ($val["addtime"]); ?></span></td>
                <td align="center">
                <a href="<?php echo U('goods_num?id='.$val['id']) ?>" target="_blank" title="库存量"><img src="/Public/Admin/Images/icon_view.gif" width="16" height="16" border="0" /></a>
                <a href="<?php echo U('edit?id='.$val['id']) ?>" title="编辑"><img src="/Public/Admin/Images/icon_edit.gif" width="16" height="16" border="0" /></a>
                <a href="<?php echo U('delete?id='.$val['id'])?>" onclick="return confirm('确认删除该商品？')" title="回收站"><img src="/Public/Admin/Images/icon_trash.gif" width="16" height="16" border="0" /></a></td>
            </tr>
            <?php endforeach ?>
        </table>

    <!-- 分页开始 -->
        <table id="page-table" cellspacing="0">
            <tr>
                <td width="80%">&nbsp;</td>
                <td align="center" nowrap="true">
                    <?php echo ($data["navigation"]); ?>
                </td>
            </tr>
        </table>
    <!-- 分页结束 -->
    </div>
</form>



<script src="/Public/umeditor1_2_2-utf8-php/third-party/jquery.min.js"></script>
<link href="/Public/datetimepicker/jquery-ui-1.9.2.custom.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" charset="utf-8" src="/Public/datetimepicker/jquery-ui-1.9.2.custom.min.js"></script>
<script type="text/javascript" charset="utf-8" src="/Public/datetimepicker/datepicker-zh_cn.js"></script>
<link rel="stylesheet" media="all" type="text/css" href="/Public/datetimepicker/time/jquery-ui-timepicker-addon.min.css" />
<script type="text/javascript" src="/Public/datetimepicker/time/jquery-ui-timepicker-addon.min.js"></script>
<script type="text/javascript" src="/Public/datetimepicker/time/i18n/jquery-ui-timepicker-addon-i18n.min.js"></script>
<script src="/Public/Admin/Js/tron.js"></script>
<script>
    $.timepicker.setDefaults($.timepicker.regional['zh-CN']);
    $("#ft").datepicker();
    $("#tt").datepicker();
</script>

</html>


<div id="footer">
    共执行 9 个查询，用时 0.025161 秒，Gzip 已禁用，内存占用 3.258 MB<br />
    版权所有 &copy; 2005-2012 上海商派网络科技有限公司，并保留所有权利。</div>
</body>
</html>