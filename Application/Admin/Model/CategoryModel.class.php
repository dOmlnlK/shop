<?php
namespace Admin\Model;
use Think\Model;
class CategoryModel extends Model
{
    protected $insertFields = array('cat_name', 'parent_id','is_floor');
    protected $updateFields = array('id', 'cat_name', 'parent_id','is_floor');
    protected $_validate = array(
        array('cat_name', 'require', '分类名称不能为空！', 1, 'regex', 3),
    );

    public function getTree(){
        $model = D('category');
        $data = $model->select();

//        return $data;
        return $this->_getLeaf($data);
    }

    private function _getLeaf($data,$parent_id=0,$level=0){
        static $tree = array();

        foreach ($data as $cat){
            if($cat['parent_id']==$parent_id){
                $cat['level']=$level;
                $tree[] = $cat;
                $this->_getLeaf($data,$cat['id'],$lev=$level+1);
            }
        }
        return $tree;

    }

    public function getChildren($id){


        $model = D('Category');
        $data = $model->select();
        return $this->_getChildren($data,$id);

    }

    private function _getChildren($data,$id){
        static $children_ids = array();

        foreach($data as $cat){
            if($cat['parent_id']==$id){
                $children_ids[] = $cat['id'];
                $this->_getChildren($data,$cat['id']);
            }
        }
        return $children_ids;
    }

    protected function _before_delete($options){
        $children_ids = $this->getChildren($options['where']['id']);
        $children_ids = implode(',',$children_ids);
        $children_ids.=$options['where']['id'];
        $model = new \Think\Model();  //防止发生死循环，使用基类模型
        $model->table('__CATEGORY__')->delete($children_ids);
    }


    //为前台分类导航提供数据
    public function getCatData(){
        //如果有缓存直接返回数据
        $nav_data = S('nav_data');
        if($nav_data){
            return $nav_data;
        }

        //先取出所有分类
        $all = $this->select();
        $nav_data = array();

        foreach ($all as $k=>$v){
            if($v['parent_id']==0){
                //获取三层分类
                foreach ($all as $k1=>$v1){
                    if($v1['parent_id']==$v['id']){
                        foreach ($all as $k2=>$v2){
                            if($v2['parent_id']==$v1['id']){
                                $v1['children'][] = $v2;
                            }
                        }
                        $v['children'][] = $v1;
                    }
                }

                $nav_data[] = $v;
            }


        }

        //把数组缓存
        S('nav_data',$nav_data,86400);
        return $nav_data;

    }


    //获取推荐到楼层的分类数据
    public function getFloorData(){

        //判断是否有缓存
        if(S('floor_data'))
            return S('floor_data');

        //推荐楼层顶级分类
        $cat_list = $this -> where(array(
            'parent_id'=>array('eq',0),
            'is_floor'=>array('eq','是')

        ))->select();

        //获取顶级分类下未被推荐楼层的二级分类
        foreach($cat_list as $k=>&$v){

            $v['sub_cat'] = $this -> where(array(
                'parent_id'=>array('eq',$v['id']),
                'is_floor'=>array('eq','否')

            ))->select();
        }

        //获取顶级分类下被推荐楼层的二级分类
        foreach($cat_list as $k=>&$v){

            $v['rec_sub_cat'] = $this -> where(array(
                'parent_id'=>array('eq',$v['id']),
                'is_floor'=>array('eq','是')

            ))->select();
        }

        //获取推荐楼层的子分类下的所有商品
        $goods_model = D('Admin/Goods');
        $rec_gids = array();
        foreach ($cat_list as $k->$v){
            foreach ($v['rec_sub_cat'] as &$v1){
                $gids = $goods_model->getGoodsByCid($v1['id']);
                $rec_gids[] = $gids;
                $v1['goods'] = $goods_model->field('id,mid_logo,goods_name,shop_price')->where(array(
                    'id' => array('in',$gids),
                    'is_on_sale'=>array('eq',"是")
                ))->order('sort_num asc')->limit(8)->select();
            }
        }

        /***************取出推荐楼层商品的品牌******************/
        $_rec_gids = array();
        foreach ($rec_gids as $gid){
            $_rec_gids = array_merge($_rec_gids,$gid);
        }

        $brand_list = $goods_model->alias('a')->field('DISTINCT b.*')->join(
            'LEFT JOIN __BRAND__ b on a.brand_id=b.id'
        )->where(array('a.id'=>array('in',$_rec_gids)))->select();

        $floor_data = array('cat_list'=>$cat_list,'brand_list'=>$brand_list);
        //将数据添加缓存
        S('floor_data',$floor_data);
        return $floor_data;
    }

    public function getParentCid($cid){
        static $cids = array();
        $cids[] = $cid;
        $cat = $this->field('parent_id')->find($cid);
        if($cat['parent_id']>0){

            $this->getParentCid($cat['parent_id']);
        }

        return $cids;
    }


    /**
     * 根据当前搜索出来的商品来计算筛选条件
     */
    public function getSearchConditionByGoodsId($goodsId)
    {
        $ret = array();  // 返回的数组

        $goodsModel = D('Admin/Goods');
        // 先取出这个分类下所有商品的id
//        $goodsId = $goodsModel->getGoodsByCid($catId);

        /***************** 品牌 ********************/
        // 根据商品ID取出品牌ID再连品牌表取出品牌名称
        $ret['brand'] = $goodsModel->alias('a')
            ->field('DISTINCT brand_id,b.brand_name,b.logo')
            ->join('LEFT JOIN __BRAND__ b ON a.brand_id=b.id')
            ->where(array(
                'a.id' => array('in', $goodsId),
                'a.brand_id' => array('neq', 0),
            ))->select();

        /***************** 价格区间段 *****************/
        $sectionCount = 6;  // 默认分几段
        // 取出这个分类下最大和最小的价格
        $priceInfo = $goodsModel->field('MAX(shop_price) max_price,MIN(shop_price) min_price')
            ->where(array(
                'id' => array('in', $goodsId),
            ))->find();

        // 最大价和最小价的区间
        $priceSection = $priceInfo['max_price'] - $priceInfo['min_price'];
        // 分类下商品的数量
        $goodsCount = count($goodsId);
        // 只有商品数量有这些时价格才分段
        if($goodsCount > 1)
        {
            // 根据最大价和最小价的差值计算分几段
            if($priceSection < 100)
                $sectionCount = 2;
            elseif ($priceSection < 1000)
                $sectionCount = 4;
            elseif ($priceSection < 10000)
                $sectionCount = 6;
            else
                $sectionCount = 7;
            // 根据这些段数分段
            $pricePerSection = ceil($priceSection / $sectionCount);  // 每段的范围
            $price = array();   // 存放最终的分段数据
            $firstPrice = 0;   // 第一个价格段的开始价格
            // 循环放每个段
            for($i=0; $i<$sectionCount; $i++)
            {
                // 每段结束的价格
                $_tmpEnd = $firstPrice+$pricePerSection;
                // 把结束的价格取整
                $_tmpEnd = ((ceil($_tmpEnd / 100)) * 100 - 1);
                $price[] = $firstPrice . '-' . $_tmpEnd;
                // 计算下一个的价格段的开始价格
                $firstPrice = $_tmpEnd+1;
            }
            // 放到返回的数组中
            $ret['price'] = $price;
        }

        /***************** 商品属性 ********************/
        $gaModel = D('goods_attr');
        $gaData = $gaModel->alias('a')
            ->field('DISTINCT a.attr_id,a.attr_value,b.attr_name')
            ->join('LEFT JOIN __ATTRIBUTE__ b ON a.attr_id=b.id')
            ->where(array(
                'a.goods_id' => array('in', $goodsId),
                'a.attr_value' => array('neq', ''),
            ))
            ->select();
        // 处理这个属性数组：把属性相同的放到一起用属性名称做为下标-》2维转3维
        $_gaData = array();
        foreach ($gaData as $k => $v)
        {
            $_gaData[$v['attr_name']][] = $v;
        }
        // 放到返回数组中
        $ret['gaData'] = $_gaData;

        // 返回数组
        return $ret;
    }


}

