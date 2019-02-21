<?php
namespace Admin\Model;
use Think\Model;
class GoodsModel extends Model
{
    // 添加时调用create方法允许接收的字段
    protected $insertFields = 'goods_name,market_price,shop_price,is_on_sale,goods_desc,brand_id,cat_id,type_id,promote_price,promote_start_date,promote_end_date,is_new,is_best,is_hot,sort_num,is_floor';
    // 修改时调用create方法允许接收的字段
    protected $updateFields = 'id,goods_name,market_price,shop_price,is_on_sale,goods_desc,brand_id,cat_id,type_id,promote_price,promote_start_date,promote_end_date,is_new,is_best,is_hot,sort_num,is_floor';
    //定义验证规则
    protected $_validate = array(
        array('goods_name', 'require', '商品名称不能为空！', 1),
        array('cat_id', 'require', '必须选取主分类！', 1),
        array('market_price', 'currency', '市场价格必须是货币类型！', 1),
        array('shop_price', 'currency', '本店价格必须是货币类型！', 1),
    );

    // 这个方法在添加之前会自动被调用 --》 钩子方法
    // 第一个参数：表单中即将要插入到数据库中的数据->数组
    // &按引用传递：函数内部要修改函数外部传进来的变量必须按钮引用传递，除非传递的是一个对象,因为对象默认是按引用传递的
    protected function _before_insert(&$data, $option)
    {
        /**************** 处理LOGO *******************/
        // 判断有没有选择图片
        if ($_FILES['logo']['error'] == 0) {
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize = 1024 * 1024; // 1M
            $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath = './Public/Uploads/'; // 设置附件上传根目录
            $upload->savePath = 'Goods/'; // 设置附件上传（子）目录
            // 上传文件
            $info = $upload->upload();
            if (!$info) {
                // 获取失败原因把错误信息保存到 模型的error属性中，然后在控制器里会调用$model->getError()获取到错误信息并由控制器打印
                $this->error = $upload->getError();
                return FALSE;
            } else {
                /**************** 生成缩略图 *****************/
                // 先拼成原图上的路径
                $logo = $info['logo']['savepath'] . $info['logo']['savename'];
                // 拼出缩略图的路径和名称
                $mbiglogo = $info['logo']['savepath'] . 'mbig_' . $info['logo']['savename'];
                $biglogo = $info['logo']['savepath'] . 'big_' . $info['logo']['savename'];
                $midlogo = $info['logo']['savepath'] . 'mid_' . $info['logo']['savename'];
                $smlogo = $info['logo']['savepath'] . 'sm_' . $info['logo']['savename'];
                $image = new \Think\Image();
                // 打开要生成缩略图的图片
                $image->open('./Public/Uploads/' . $logo);
                // 生成缩略图
                $image->thumb(700, 700)->save('./Public/Uploads/' . $mbiglogo);
                $image->thumb(350, 350)->save('./Public/Uploads/' . $biglogo);
                $image->thumb(130, 130)->save('./Public/Uploads/' . $midlogo);
                $image->thumb(50, 50)->save('./Public/Uploads/' . $smlogo);

                /**************** 把路径放到表单中 *****************/
                $data['logo'] = $logo;
                $data['mbig_logo'] = $mbiglogo;
                $data['big_logo'] = $biglogo;
                $data['mid_logo'] = $midlogo;
                $data['sm_logo'] = $smlogo;
            }
        }
        // 获取当前时间并添加到表单中这样就会插入到数据库中
        $data['addtime'] = date('Y-m-d H:i:s', time());
        // 我们自己来过滤这个字段
        $data['goods_desc'] = removeXSS($_POST['goods_desc']);
    }


    //实现获取列表数据及分页导航
    public function search($per_page = 3)
    {
        /*******搜索过滤*******/
        $where = array();
        //商品名称
        $goods_name = I('get.goods_name');
        if ($goods_name) {
            $where['goods_name'] = array('like', "%$goods_name%");
        }
        //商品价格
        $fp = I('get.fp');
        $tp = I('get.tp');
        if ($fp && $tp) {
            $where['shop_price'] = array('between', array($fp, $tp));
        } elseif ($fp) {
            $where['shop_price'] = array('egt', $fp);
        } elseif ($tp) {
            $where['shop_price'] = array('elt', $tp);
        }

        //是否上架
        $ios = I('get.is_on_sale');
        if ($ios) {
            $where['is_on_sale'] = array('eq', $ios);
        }

        //时间
        $ft = I('get.ft');
        $tt = I('get.tt');
        if ($fp && $tp) {
            $where['addtime'] = array('between', array($ft, $tt));
        } elseif ($fp) {
            $where['addtime'] = array('egt', $ft);
        } elseif ($tp) {
            $where['addtime'] = array('elt', $tt);
        }

        //品牌
        $brand_id = I('get.brand_id');
        if ($brand_id) {
            $where['brand_id'] = array('eq', $brand_id);
        }

        //分类
        $cat_id = I('get.cat_id');
        if ($cat_id) {

            $gids = D('goods')->getGoodsByCid($cat_id);
            $per_page = count($gids);
            $where['a.id'] = array('in',$gids);

        }

        /***************排序**************/
        $default_odby = 'id';
        $default_way = 'desc';
        $odby = I('get.odby');
        if ($odby == 'id_asc') {
            $default_way = 'asc';
        } elseif ($odby == 'time_desc') {
            $default_odby = 'addtime';
        } elseif ($odby == 'time_asc') {
            $default_odby = 'addtime';
            $default_way = 'asc';
        }

        /***************分页****************/
        //数据总条数
        $count = $this->where($where)->count();
        //创建分页对象
        $page_obj = new \Think\Page($count, $per_page);
        //设计分页导航样式
        $page_obj->setConfig('next', '下一页');
        $page_obj->setConfig('prev', '上一页');
        //生产分页导航
        $navigation = $page_obj->show();
        //取出某页数据
        $data = $this->order("$default_odby $default_way")
            ->field('a.*,b.brand_name,c.cat_name,GROUP_CONCAT(e.cat_name SEPARATOR "<br>") ext_cat_name')
            ->alias('a')
            ->join('LEFT JOIN __BRAND__ b ON a.brand_id=b.id
                             LEFT JOIN __CATEGORY__ c on a.cat_id=c.id
                             LEFT JOIN __GOODS_CAT__ d on a.id=d.goods_id
                             LEFT JOIN __CATEGORY__ e on e.id=d.cat_id
                     ')
            ->where($where)
            ->limit($page_obj->firstRow . ',' . $page_obj->listRows)
            ->group('a.id')
            ->select();
        //返回数据

        return array(
            'data' => $data,   //一页的数据
            'navigation' => $navigation   //导航
        );
    }

    protected function _before_update(&$data, $option)
    {

        //要修改的商品ID
        $id = I('get.id');

        /********处理商品属性*********/
        $ga_model = D('goods_attr');
        $goods_attr_ids = I('post.goods_attr_ids');
        $attr_val = I('post.attr_val');
        $_i = 0;
        foreach($attr_val as $k=>$v){
            //对属性值进行去重
            $v = array_unique($v);
            foreach ($v as $v1){
                $ga_model = D('goods_attr');
                //说明是新加的商品属性值
                if($goods_attr_ids[$_i]==""){
                    $ga_model->add(array(
                        'attr_value'=>$v1,
                        'attr_id'=>$k,
                        'goods_id'=>$id
                    ));

                }
                //说明是修改原来的商品属性值
                else{
                    $ga_model->where(array('id'=>array('eq',$goods_attr_ids[$_i])))->setField('attr_value',$v1);
                }
                $_i++;
            }


        }


        /**************** 处理会员价格 *******************/
        $mp_model = D('member_price');
        $mp_model->where(array('goods_id'=>array('eq',$id)))->delete();
        $mp = I('post.member_price');
        foreach ($mp as $k => $p) {
            $_p = (float)$p;
            if ($_p > 0) {
                $mp_model->add(
                    array(
                        'price' => $p,
                        'level_id' => $k,
                        'goods_id' => $id

                    )
                );
            }
        }

        /**************** 处理扩展分类 *******************/
        $gc_model = D('goods_cat');
        $gc_model->where(array('goods_id'=>array('eq',$id)))->delete();
        $ext_cat_ids = I('post.ext_cat_ids');
        foreach ($ext_cat_ids as $ext_cat_id) {
            if (empty($ext_cat_id))
                continue;

            $gc_model->add(array(
                'cat_id' => $ext_cat_id,
                'goods_id' => $id
            ));


        }

        /**************** 处理LOGO *******************/
        // 判断有没有选择图片
        if ($_FILES['logo']['error'] == 0) {
            $ret = uploadOne('logo', 'Goods', array(
                array(700, 700),
                array(350, 350),
                array(130, 130),
                array(50, 50),
            ));
            $data['logo'] = $ret['images'][0];
            $data['mbig_logo'] = $ret['images'][1];
            $data['big_logo'] = $ret['images'][2];
            $data['mid_logo'] = $ret['images'][3];
            $data['sm_logo'] = $ret['images'][4];


            /**************删除旧图片************************/

            $old_logos = $this->field('logo,sm_logo,mid_logo,big_logo,mbig_logo')->find($id);
            deleteImage($old_logos);


        }


        // 我们自己来过滤这个字段
        $data['goods_desc'] = removeXSS($_POST['goods_desc']);
    }

    protected function _before_delete($options)
    {



        //要删除的商品的ID
        $id = $options['where']['id'];

        //删除与商品相关的库存量记录
        $gn_model = D('goods_number');
        $gn_model->where(array('goods_id'=>array('eq',$id)))->delete();

        //删除商品属性
        D('goods_attr')->where(array('goods_id'=>array('eq',$id)))->delete();
        //删除旧图片文件
        $old_logos = $this->field('logo,sm_logo,mid_logo,big_logo,mbig_logo')->find($id);
        deleteImage($old_logos);
        //删除会员价格
        D('member_price')->where(array('goods_id'=>array('eq',$id)))->delete();
        //删除扩展分类关系
        D('goods_cat')->where(array('goods_id'=>array('eq',$id)))->delete();

    }

    protected function _after_insert($data, $options)
    {
        $goods_id = $data['id'];

        /************处理商品属性************/
        $attr_val = I('post.attr_val');

        foreach($attr_val as $k=>$v){
            //对属性值进行去重
            $v = array_unique($v);
            foreach ($v as $v1){
                $ga_model = D('goods_attr');
                $ga_model->add(array(
                    'attr_value'=>$v1,
                    'attr_id'=>$k,
                    'goods_id'=>$goods_id
                ));
            }


        }


        /*************处理扩展分类*************/
        $ext_cat_ids = I('post.ext_cat_ids');

        foreach ($ext_cat_ids as $ext_cat_id) {
            $gc_model = D('goods_cat');
            if (empty($ext_cat_id))
                continue;

            $gc_model->add(array(
                'cat_id' => $ext_cat_id,
                'goods_id' => $goods_id
            ));


        }


        /*************处理会员价格*************/
        $model = D('MemberPrice');
        $mp = I('post.member_price');
        foreach ($mp as $k => $p) {
            $_p = (float)$p;
            if ($_p > 0) {
                $model->add(
                    array(
                        'price' => $p,
                        'level_id' => $k,
                        'goods_id' => $goods_id

                    )
                );
            }
        }


    }

    //获取以某一个类作为主类和扩展类所有的商品
    public function getGoodsByCid($cid)

    {
        $model = D("Admin/category");
        $children_ids = $model->getChildren($cid);
        $children_ids[] = $cid;
        //主分类商品
        $gids = $this->field('id')->where(array('cat_id'=>array('in',$children_ids)))->select();
        //扩展分类商品
        $ext_gids = D('goods_cat')->field('DISTINCT goods_id id')->where(array('cat_id'=>array('in',$children_ids)))->select();

        //对主分类 扩展分类重复的商品ID去重
        if($gids && $ext_gids)
            $gids = array_merge($gids,$ext_gids);
        elseif (!$gids)
            $gids = $ext_gids;


        $ids = array();
        foreach ($gids as $gid){
            if(!in_array($gid['id'],$ids)){
                $ids[] = $gid['id'];
            }
    }

        return $ids;


    }

    public function getPromoteGoods($limit=5){
        $now = date('Y-m-d H:i:s');
        $data = $this->field('promote_price,goods_name,id,mid_logo')->where(array(
            'promote_start_date'=>array('lt',$now),
            'promote_end_date'=>array('gt',$now),
            'is_on_sale'=>'是'
        ))->limit($limit)->order('sort_num asc')->select();

        return $data;

    }


    public function getHotGoods($limit=5){

        $data = $this->field('shop_price,goods_name,id,mid_logo')->where(array(
            'is_hot' => array('eq','是'),
            'is_on_sale'=> array('eq','是')
        ))->limit($limit)->order('sort_num asc')->select();

        return $data;

    }

    public function getBestGoods($limit=5){

        $data = $this->field('shop_price,goods_name,id,mid_logo')->where(array(
            'is_best' => array('eq','是'),
            'is_on_sale'=> array('eq','是')
        ))->limit($limit)->order('sort_num asc')->select();

        return $data;

    }

    public function getNewGoods($limit=5){

        $data = $this->field('shop_price,goods_name,id,mid_logo')->where(array(
            'is_new' => array('eq','是'),
            'is_on_sale'=> array('eq','是')
        ))->limit($limit)->order('sort_num asc')->select();

        return $data;

    }

    //获取商品实际购买价格
    public function getTruePrice($gid){
        $level_id = session('level_id');

        //判断商品是否正在促销
        $today = date('Y-m-d H:i');
        $pp = $this->field('promote_price')->where(array(
            'id' => array('eq',$gid),
            'promote_start_date' => array('lt',$today),
            'promote_end_date' => array('gt',$today),
        ))->find();

        //说明登录了
        if($level_id){
            $mp_model = D('Admin/member_price');
            $mp = $mp_model->field('price')->where(array(
                'goods_id' => array('eq',$gid),
                'level_id' => array('eq',$level_id)
            ))->find();
            //若有会员价格则返回。否则返回本店价格
            if($mp){
                //若商品正在促销，返回会员价格和促销价格较低者
                if($pp){
                    return min($pp['promote_price'],$mp['price']);
                }

                return $mp['price'];
            }else{
                if($pp){
                    return $pp['promote_price'];
                }

                $goods = $this->field('shop_price')->where(array(
                    'id' => array('eq',$gid),
                    'is_on_sale' => array('eq','是')
                ))->find();
                return $goods['shop_price'];
            }

        }

        else{
            //没有登录时
            if($pp){
                return $pp['promote_price'];
            }

            $goods = $this->field('shop_price')->where(array(
                'id' => array('eq',$gid),
                'is_on_sale' => array('eq','是')
            ))->find();
            return $goods['shop_price'];

        }




    }



    public function cat_search($catId, $pageSize = 60)
    {
        /*************** 搜索 *************************/
        // 根据分类ID搜索出这个分类下商品的ID
        $goodsId = $this->getGoodsByCid($catId);
        $where['a.id'] = array('in', $goodsId);
        // 品牌
        $brandId = I('get.brand_id');
        if($brandId)
            $where['a.brand_id'] = array('eq', (int)$brandId);
        // 价格
        $price = I('get.price');
        if($price)
        {
            $price = explode('-', $price);
            $where['a.shop_price'] = array('between', $price);
        }

        /******************************************* 商品搜索开始 ************************************************/
        $gaModel = D('goods_attr');
        $attrGoodsId = NULL;  // 根据每个属性搜索出来的商品的ID
        // 根据属性搜索 : 循环所有的参数找出属性的参数进行查询
        foreach ($_GET as $k => $v)
        {
            // 如果变量是以attr_开头的说明是一个属性的查询, 格式：attr_1/黑色-颜色
            if(strpos($k, 'attr_') === 0)
            {
                // 先解析出属性ID和属性值
                $attrId = str_replace('attr_', '', $k); // 属性id
                // 先取出最后一个-往后的字符串
                $attrName = strrchr($v, '-');
                $attrValue = str_replace($attrName, '', $v);
                // 根据属性ID和属性值搜索出这个属性值下的商品id：并返回一个字符串格式：1,2,3,4,5,6,7
                $gids = $gaModel->field('GROUP_CONCAT(goods_id) gids')->where(array(
                    'attr_id' => array('eq', $attrId),
                    'attr_value' => array('eq', $attrValue),
                ))->find();
                // 判断是有商品
                if($gids['gids'])
                {
                    $gids['gids'] = explode(',', $gids['gids']);
                    // 说明是搜索的第一个属性
                    if($attrGoodsId === NULL)
                        $attrGoodsId = $gids['gids'];  // 先暂存起来
                    else
                    {
                        // 和上一个属性搜索出来的结果求集
                        $attrGoodsId = array_intersect($attrGoodsId, $gids['gids']);
                        // 如果已经没有商品满足条件就不用再考虑下一个属性了
                        if(empty($attrGoodsId))
                        {
                            $where['a.id'] = array('eq', 0);
                            break;
                        }
                    }
                }
                else
                {
                    // 前几次的交集结果清空
                    $attrGoodsId = array();
                    // 如果这个属性下没有商品就应该向where中添加一个不可能满足的条件，这样后面取商品时就取不出来了！
                    $where['a.id'] = array('eq', 0);
                    // 取出循环，不用再查询下一个属性了
                    break;
                }
            }
        }
        // 判断如果循环求次之后这个数组还不为空说明这些就是满足所有条件的商品id
        if($attrGoodsId)
            $where['a.id'] = array('IN', $attrGoodsId);
        /******************************************* 商品搜索结束 ************************************************/



        /**************** 翻页 *********************/
        // 取出总的记录数，以及所有的商品ID的字符串
        //$count = $this->alias('a')->where($where)->count();  // 这个只能取总记录数，改成下面这行，即取总记录数，又取出了商品ID
        $count = $this->alias('a')->field('COUNT(a.id) goods_count,GROUP_CONCAT(a.id) goods_id')->where($where)->find();
        // 把商品ID返回
        $data['goods_id'] = explode(',', $count['goods_id']);

        $page = new \Think\Page($count['goods_count'], $pageSize);
        // 配置翻页的样式
        $page->setConfig('prev', '上一页');
        $page->setConfig('next', '下一页');
        $data['page'] = $page->show();

        /*********************** 排序 ********************/
        $oderby = 'xl';    // 默认
        $oderway = 'desc'; // 默认
        $odby = I('get.odby');
        if($odby)
        {
            if($odby == 'addtime')
                $oderby = 'a.addtime';
            if(strpos($odby, 'price_') === 0)
            {
                $oderby = 'a.shop_price';
                if($odby == 'price_asc')
                    $oderway = 'asc';
            }
        }

        /**************** 取数据 ********************/
        $data['data'] = $this->alias('a')
            ->field('a.id,a.goods_name,a.mid_logo,a.shop_price,SUM(b.goods_number) xl')
            ->join('LEFT JOIN __ORDER_GOODS__ b 
				 ON (a.id=b.goods_id 
				      AND 
				     b.order_id IN(SELECT id FROM __ORDER__ WHERE pay_status="是"))')
            ->where($where)
            ->group('a.id')
            ->limit($page->firstRow.','.$page->listRows)
            ->order("$oderby $oderway")
            ->select();

        return $data;
    }

    /**
     * 获取某个关键字下某一页的商品
     *
     */
    public function key_search($key, $pageSize = 60)
    {
        /*************** 搜索 *************************/

        //$goodsId = $this->getGoodsIdByCatId($catId);

        // 根据关键字【商品名称、商品描述、商品属性值】取出商品ID
        $goodsId = $this->alias('a')
            ->field('GROUP_CONCAT(DISTINCT a.id) gids')
            ->join('LEFT JOIN __GOODS_ATTR__ b ON a.id=b.goods_id')
            ->where(array(
                'a.is_on_sale' => array('eq', '是'),
                'a.goods_name' => array('exp', " LIKE '%$key%' OR a.goods_desc LIKE '%$key%' OR attr_value LIKE '%$key%'"),
            ))
            ->find();
        $goodsId = explode(',', $goodsId['gids']);

        $where['a.id'] = array('in', $goodsId);
        // 品牌
        $brandId = I('get.brand_id');
        if($brandId)
            $where['a.brand_id'] = array('eq', (int)$brandId);
        // 价格
        $price = I('get.price');
        if($price)
        {
            $price = explode('-', $price);
            $where['a.shop_price'] = array('between', $price);
        }

        /******************************************* 商品搜索开始 ************************************************/
        $gaModel = D('goods_attr');
        $attrGoodsId = NULL;  // 根据每个属性搜索出来的商品的ID
        // 根据属性搜索 : 循环所有的参数找出属性的参数进行查询
        foreach ($_GET as $k => $v)
        {
            // 如果变量是以attr_开头的说明是一个属性的查询, 格式：attr_1/黑色-颜色
            if(strpos($k, 'attr_') === 0)
            {
                // 先解析出属性ID和属性值
                $attrId = str_replace('attr_', '', $k); // 属性id
                // 先取出最后一个-往后的字符串
                $attrName = strrchr($v, '-');
                $attrValue = str_replace($attrName, '', $v);
                // 根据属性ID和属性值搜索出这个属性值下的商品id：并返回一个字符串格式：1,2,3,4,5,6,7
                $gids = $gaModel->field('GROUP_CONCAT(goods_id) gids')->where(array(
                    'attr_id' => array('eq', $attrId),
                    'attr_value' => array('eq', $attrValue),
                ))->find();
                // 判断是有商品
                if($gids['gids'])
                {
                    $gids['gids'] = explode(',', $gids['gids']);
                    // 说明是搜索的第一个属性
                    if($attrGoodsId === NULL)
                        $attrGoodsId = $gids['gids'];  // 先暂存起来
                    else
                    {
                        // 和上一个属性搜索出来的结果求集
                        $attrGoodsId = array_intersect($attrGoodsId, $gids['gids']);
                        // 如果已经没有商品满足条件就不用再考虑下一个属性了
                        if(empty($attrGoodsId))
                        {
                            $where['a.id'] = array('eq', 0);
                            break;
                        }
                    }
                }
                else
                {
                    // 前几次的交集结果清空
                    $attrGoodsId = array();
                    // 如果这个属性下没有商品就应该向where中添加一个不可能满足的条件，这样后面取商品时就取不出来了！
                    $where['a.id'] = array('eq', 0);
                    // 取出循环，不用再查询下一个属性了
                    break;
                }
            }
        }
        // 判断如果循环求次之后这个数组还不为空说明这些就是满足所有条件的商品id
        if($attrGoodsId)
            $where['a.id'] = array('IN', $attrGoodsId);
        /******************************************* 商品搜索结束 ************************************************/



        /**************** 翻页 *********************/
        // 取出总的记录数，以及所有的商品ID的字符串
        //$count = $this->alias('a')->where($where)->count();  // 这个只能取总记录数，改成下面这行，即取总记录数，又取出了商品ID
        $count = $this->alias('a')->field('COUNT(a.id) goods_count,GROUP_CONCAT(a.id) goods_id')->where($where)->find();
        // 把商品ID返回
        $data['goods_id'] = explode(',', $count['goods_id']);

        $page = new \Think\Page($count['goods_count'], $pageSize);
        // 配置翻页的样式
        $page->setConfig('prev', '上一页');
        $page->setConfig('next', '下一页');
        $data['page'] = $page->show();

        /*********************** 排序 ********************/
        $oderby = 'xl';    // 默认
        $oderway = 'desc'; // 默认
        $odby = I('get.odby');
        if($odby)
        {
            if($odby == 'addtime')
                $oderby = 'a.addtime';
            if(strpos($odby, 'price_') === 0)
            {
                $oderby = 'a.shop_price';
                if($odby == 'price_asc')
                    $oderway = 'asc';
            }
        }

        /**************** 取数据 ********************/
        $data['data'] = $this->alias('a')
            ->field('a.id,a.goods_name,a.mid_logo,a.shop_price,SUM(b.goods_number) xl')
            ->join('LEFT JOIN __ORDER_GOODS__ b 
				 ON (a.id=b.goods_id 
				      AND 
				     b.order_id IN(SELECT id FROM __ORDER__ WHERE pay_status="是"))')
            ->where($where)
            ->group('a.id')
            ->limit($page->firstRow.','.$page->listRows)
            ->order("$oderby $oderway")
            ->select();

        return $data;
    }

}








