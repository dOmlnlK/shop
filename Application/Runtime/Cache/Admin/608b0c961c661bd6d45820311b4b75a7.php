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


<style>
    #add_cat_btn {
        width: 50px;
        background-color: black;
        color: white;
        cursor: pointer;
        border-radius: 5px
    }
</style>


<div class="tab-div">
    <div id="tabbar-div">
        <p>
            <span class="tab-front form_btn">通用信息</span>
            <span class="tab-back form_btn">商品描述</span>
            <span class="tab-back form_btn">会员价格</span>
            <span class="tab-back form_btn">商品属性</span>
            <span class="tab-back form_btn">商品相册</span>
        </p>
    </div>
    <div id="tabbody-div">
        <form enctype="multipart/form-data" action="/index.php/Admin/Goods/edit/id/3.html" method="post">
            <table width="90%" class="attr-table" align="center">
                <tr>
                    <td class="label">主分类：</td>
                    <td>
                        <select name="cat_id">
                            <?php foreach($cats as $cat):?>
                            <?php if($cat['id']==$data['cat_id']):?>
                            <option value="<?php echo ($cat["id"]); ?>" selected="selected">
                                <?php else:?>
                            <option value="<?php echo ($cat["id"]); ?>">
                                <?php endif?>
                                <?php echo str_repeat('-',4*$cat['level']); echo ($cat["cat_name"]); ?>
                            </option>
                            <?php endforeach?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td class="label">
                        扩展分类：
                    </td>

                    <td id="ext_cats">
                        <div id='add_cat_btn' onclick="$('#ext_cats').append($('#ext_cats').find('li').eq(0).clone())">
                            添加一个
                        </div>
                        <?php if($ext_cat_ids):?>
                        <?php foreach($ext_cat_ids as $ext_cat_id):?>
                        <li>
                            <select name="ext_cat_ids[]">
                                <option value="">请选择</option>
                                <?php foreach($cats as $cat):?>
                                <?php if($cat['id']==$ext_cat_id['cat_id']):?>
                                <option value="<?php echo ($cat["id"]); ?>" selected="selected">
                                    <?php else:?>
                                <option value="<?php echo ($cat["id"]); ?>">
                                    <?php endif?>
                                    <?php echo str_repeat('-',4*$cat['level']); echo ($cat["cat_name"]); ?>
                                </option>
                                <?php endforeach?>
                            </select>

                        </li>
                        <?php endforeach?>
                        <?php else:?>
                        <li>
                            <select name="ext_cat_ids[]">
                                <option value="">请选择</option>
                                <?php foreach($cats as $cat):?>
                                <option value="<?php echo ($cat["id"]); ?>"><?php echo str_repeat('-',4*$cat['level']);?>
                                    <?php echo ($cat["cat_name"]); ?>
                                </option>
                                <?php endforeach?>
                            </select>
                        </li>
                        <?php endif?>
                    </td>
                </tr>


                <tr>
                    <td class="label">所属品牌：</td>
                    <td>
                        <?php ?>
                        <?php buildSelect('brand','brand_id','brand_name','id',$data['brand_id'])?>

                    </td>
                </tr>
                <tr>

                    <td class="label">商品名称：</td>
                    <td><input type="text" name="goods_name" size="60" value="<?php echo ($data["goods_name"]); ?>"/>
                        <span class="require-field">*</span></td>
                </tr>
                <tr>
                    <td class="label">LOGO：</td>
                    <td>
                        <img src="/Public/Uploads/<?php echo ($data["sm_logo"]); ?>">
                        <input type="file" name="logo" size="60"/>
                    </td>

                </tr>
                <tr>
                    <td class="label">市场售价：</td>
                    <td>
                        <input type="text" name="market_price" value="<?php echo ($data["market_price"]); ?>" size="20"/>
                        <span class="require-field">*</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">本店售价：</td>
                    <td>
                        <input type="text" name="shop_price" value="<?php echo ($data["shop_price"]); ?>" size="20"/>
                        <span class="require-field">*</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">是否上架：</td>
                    <td>
                        <?php $ios = $data['is_on_sale']?>
                        <input type="radio" name="is_on_sale" value="是" <?php if($ios=='是') echo 'checked=checked'?>/> 是
                        <input type="radio" name="is_on_sale" value="否" <?php if($ios=='否') echo 'checked=checked'?>/> 否
                    </td>
                </tr>

                <tr>
                    <td class="label">促销价格：</td>
                    <td>
                        价格：￥<input type="text" name="promote_price" size="8" value="<?php echo ($data["promote_price"]); ?>">元&nbsp;&nbsp;
                        开始时间：<input id="promote_start_date" type="text" value="<?php echo ($data["promote_start_date"]); ?>" name="promote_start_date">
                        结束时间：<input id="promote_end_date" type="text" value="<?php echo ($data["promote_end_date"]); ?>" name="promote_end_date">
                    </td>
                </tr>

                <tr>
                    <td class="label">是否新品：</td>
                    <td>
                        <input type="radio" name="is_new" value="是" <?php if($data['is_new']=='是') echo 'checked=checked'?> /> 是
                        <input type="radio" name="is_new" value="否" <?php if($data['is_new']=='否') echo 'checked=checked'?>/> 否
                    </td>
                </tr>

                <tr>
                    <td class="label">是否精品：</td>
                    <td>
                        <input type="radio" name="is_best" value="是" <?php if($data['is_best']=='是') echo 'checked=checked'?> /> 是
                        <input type="radio" name="is_best" value="否" <?php if($data['is_best']=='否') echo 'checked=checked'?>/> 否
                    </td>
                </tr>

                <tr>
                    <td class="label">是否热卖：</td>
                    <td>
                        <input type="radio" name="is_hot" value="是" <?php if($data['is_hot']=='是') echo 'checked=checked'?> /> 是
                        <input type="radio" name="is_hot" value="否" <?php if($data['is_hot']=='否') echo 'checked=checked'?>/> 否
                    </td>
                </tr>

                <tr>
                    <td class="label">是否推荐到楼层：</td>
                    <td>
                        <input type="radio" name="is_floor" value="是" <?php if($data['is_floor']=='是') echo 'checked=checked'?> /> 是
                        <input type="radio" name="is_floor" value="否" <?php if($data['is_floor']=='否') echo 'checked=checked'?>/> 否
                    </td>
                </tr>

                <tr>
                    <td class="label">权重：</td>
                    <td>
                        <input type="text" name="sort_num" size="8" value="<?php echo ($data['sort_num']); ?>">
                    </td>
                </tr>

                <!--商品描述-->
                <table width="100%" class="attr-table" align="center" style="display: none">
                    <tr>

                        <td>
                            <textarea id="goods_desc" name="goods_desc"><?php echo ($data["goods_desc"]); ?></textarea>
                        </td>
                    </tr>

                </table>


                <!--会员价格-->
                <table width="90%" class="attr-table" align="center" style="display: none">
                    <?php foreach($mls as $ml):?>
                    <tr>
                        <td class="label"><?php echo ($ml["level_name"]); ?>:</td>

                        <td>
                            <?php if($mp):?>
                            <?php foreach($mp as $p):?>
                            <?php if($ml['id']==$p['level_id']):?>
                            ￥<input type="text" name="member_price[<?php echo $ml['id']?>]" value="<?php echo ($p["price"]); ?>"
                                    size="8"/>元

                            <?php endif?>
                            <?php endforeach?>
                            <?php else:?>
                            ￥<input type="text" name="member_price[<?php echo $ml['id']?>]" size="8"/>元
                            <?php endif?>
                        </td>
                    </tr>
                    <?php endforeach?>
                </table>

                <!--商品属性-->
                <table width="90%" class="attr-table" align="center" style="display: none">
                    <tr>
                        <td class="label">商品类型：</td>
                        <td>
                            <?php buildSelect('type','type_id','type_name','id',$data['type_id'])?>

                            <ul id="attr_list">
                                <?php  $occur_attr = array();foreach($attr_list as $attr):?>
                                <li>
                                    <input type="hidden" id="goods_attr_ids" name='goods_attr_ids[]'
                                           value="<?php echo ($attr["goods_attr_id"]); ?>">
                                    <?php if($attr['attr_option_values']):?>
                                    <?php if($attr['attr_type']=='可选'):?>

                                    <?php if(!in_array($attr['attr_name'],$occur_attr)):?>
                                    <?php $opt = '+';$occur_attr[]=$attr['attr_name']?>
                                    <?php else:?>
                                    <?php $opt='-'?>
                                    <?php endif?>

                                    <a href='#' onclick='addNewAttr(this)'>[<?php echo ($opt); ?>]</a>
                                    <?php endif?>

                                    <?php echo ($attr["attr_name"]); ?>:
                                    <select name='attr_val[<?php echo ($attr["id"]); ?>][]'>
                                        <option value=''>请选择...</option>
                                        <?php foreach(explode(',',$attr['attr_option_values']) as $v):?>
                                        <?php if($v==$attr['attr_value']):?>
                                        <option value="<?php echo ($v); ?>" selected="selected"><?php echo ($v); ?></option>
                                        <?php else:?>
                                        <option value="<?php echo ($v); ?>"><?php echo ($v); ?></option>
                                        <?php endif?>
                                        <?php endforeach?>
                                    </select>
                                    <?php else:?>
                                    <?php echo ($attr["attr_name"]); ?>:
                                    <input type='text' name='attr_val[<?php echo ($attr["id"]); ?>][]'
                                           value="<?php echo $attr['attr_value'];?>">
                                    <?php endif?>
                                </li>


                                <?php endforeach?>
                            </ul>
                        </td>

                    </tr>
                </table>

                <!--商品相册-->
                <table width="90%" class="attr-table" align="center" style="display: none">
                    <tr>
                        <td>相册</td>
                    </tr>
                </table>
            </table>
            <input type="hidden" name="id" value="<?php echo I('get.id');?>"/>
            <div class="button-div">
                <input type="submit" value=" 确定 " class="button"/>
                <input type="reset" value=" 重置 " class="button"/>
            </div>
        </form>
    </div>
</div>
<!-- 引入时间插件 -->
<script type="text/javascript" src="/Public/umeditor1_2_2-utf8-php/third-party/jquery.min.js"></script>
<link href="/Public/datetimepicker/jquery-ui-1.9.2.custom.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" charset="utf-8" src="/Public/datetimepicker/jquery-ui-1.9.2.custom.min.js"></script>
<script type="text/javascript" charset="utf-8" src="/Public/datetimepicker/datepicker-zh_cn.js"></script>
<link rel="stylesheet" media="all" type="text/css" href="/Public/datetimepicker/time/jquery-ui-timepicker-addon.min.css" />
<script type="text/javascript" src="/Public/datetimepicker/time/jquery-ui-timepicker-addon.min.js"></script>
<script type="text/javascript" src="/Public/datetimepicker/time/i18n/jquery-ui-timepicker-addon-i18n.min.js"></script>
<script>
    // 添加时间插件
    $.timepicker.setDefaults($.timepicker.regional['zh-CN']);  // 设置使用中文

    $("#promote_start_date").datetimepicker();
    $("#promote_end_date").datetimepicker();
</script>


<!--导入在线编辑器 -->
<link href="/Public/umeditor1_2_2-utf8-php/themes/default/css/umeditor.css" type="text/css" rel="stylesheet">
<script type="text/javascript" charset="utf-8" src="/Public/umeditor1_2_2-utf8-php/umeditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/Public/umeditor1_2_2-utf8-php/umeditor.min.js"></script>
<script type="text/javascript" src="/Public/umeditor1_2_2-utf8-php/lang/zh-cn/zh-cn.js"></script>
<script>
    UM.getEditor('goods_desc', {
        initialFrameWidth: "100%",
        initialFrameHeight: 350
    });
</script>


<!--信息表单切换-->
<script>
    $(".form_btn").click(function () {
        $(this).removeClass('tab-back').addClass("tab-front");
        $(this).siblings().removeClass('tab-front').addClass("tab-back")
        var index = $(this).index();
        $('.attr-table').eq(index).show().siblings('table').hide()
    })


    //属性可选值下拉框的增减
    function addNewAttr(ele) {
        //获取当前a标签所在的li标签
        var li = $(ele).parent();

        if ($(ele).text() == '[+]') {
            var newli = $(li).clone();
            //将新加的属性框的+号变成-
            newli.find('a').text('[-]');
            //把新加的li下的goods_attr_id请空
            newli.find('#goods_attr_ids').val("");
            $(li).after(newli);
        }
        if ($(ele).text() == '[-]') {
            var gaid = $(li).find('#goods_attr_ids').val();
            if (gaid == '') {
                $(li).remove();
            } else {
                if(confirm('确认删除？与该属性相关的库存量也将被删除！')){
                    $.ajax({
                        type:"GET",
                        url:"<?php echo U('ajaxDelAttr?id='.$data['id'],'',false)?>"+"/gaid/"+gaid,
                        success:function (data) {

                            $(li).remove();
                        }
                    })
                }
           }
        }


    }


</script>
























<div id="footer">
    共执行 9 个查询，用时 0.025161 秒，Gzip 已禁用，内存占用 3.258 MB<br />
    版权所有 &copy; 2005-2012 上海商派网络科技有限公司，并保留所有权利。</div>
</body>
</html>