<?php
namespace Home\Model;
use Think\Model;
class CommentModel extends Model
{
	protected $insertFields = array('goods_id','star','content');
	protected $updateFields = array('id','goods_id','star','content');
	protected $_validate = array(
		array('goods_id', 'require', '商品id不能为空！', 1),
        array('star', 'require', '评分不能为空!', 1),
        array('star', '1,2,3,4,5', '分值只能是1-5之间的数字！', 1, 'in'),
        array('content', '1,200', '评论必须在1-200个字符之间！', 1, 'length', 3),
	);


    protected function _before_insert(&$data, $options)
    {
        //判断是否登录
        if(!session('member')){

            $this->error = '请先登录在进行评论！';
            return false;
        }

        $data["member_id"] = session('mid');
        $data['addtime'] = date('Y-m-d H:i:s');

        //添加印象
        $impression = I('post.impression');
        $impression_id = I("post.impression_id");
        $impression_model = D('impression');

        if($impression_id){
            foreach ($impression_id as $k => $v){
                $impression_model->where(array(
                    'goods_id'=>I('post.goods_id'),
                    'yx_name'=>$v
                ))->setInc('yx_count',1);
            }

        }

        if($impression){
            //对印象数据过滤
            $impression = trim($impression);
            $impression = str_replace('，',',',$impression);
            $impression = explode(',',$impression);


            foreach ($impression as $k => $v){
                //先判断该印象是否已经存在
                $has = $impression_model->where(array(
                    'goods_id'=>I('post.goods_id'),
                    'yx_name'=>$v
                ))->find();

                if($has){
                    $impression_model->where(array(
                        'goods_id'=>I('post.goods_id'),
                        'yx_name'=>$v
                    ))->setInc('yx_count',1);

                }else{

                    $impression_model->add(array(
                        'goods_id'=>I('post.goods_id'),
                        'yx_name'=>$v,
                        'yx_count'=>1
                    ));

                }




            }

        }

    }


    /********************获取分页评论数据************************/
    public function search($gid,$pageSize=4){

        //获取该商品所有评论
        $comment_num = $this->where(array('goods_id'=>array('eq',$gid)))->count();
        //获取总分页
        $pageCount = (int)ceil($comment_num/$pageSize);

        /*******分页*********/
        $current_page = max('1',I('get.p','1'));   //获取当前页
        $offset = ceil(($current_page-1)*$pageSize);     //数据起始索引

        $data = $this->alias('a')
                    ->order('a.id DESC')
                    ->field('a.id,a.content,a.addtime,a.star,a.click_count,COUNT(b.id) reply_num,c.username')
                    ->join('LEFT JOIN __COMMENT_REPLY__ b on a.id=b.comment_id
                            LEFT JOIN __MEMBER__ c on c.id=a.member_id')
                    ->where(array('goods_id'=>array('eq',$gid)))
                    ->group('a.id')
                    ->limit("$offset,$pageSize")
                    ->select();


        //获取每条评论的所有回复
        $cr_model = D('comment_reply');
        foreach ($data as $k1=>&$v1){

            $v1['reply'] = $cr_model->alias('a')
                ->field('a.content,a.addtime,b.username')
                ->join("LEFT JOIN __MEMBER__ b on a.member_id=b.id")
                ->where(array(
                'comment_id' => $v1['id']
            ))->select();



        }




        /**********获取第一页评论时计算好评率返回并返回印象*************/
        if($current_page == 1) {
            $stars = $this->field('star')->where(array('goods_id' => $gid))->select();  //获取所有评分

            $hao = 0;
            $zhong = 0;
            $cha = 0;
            $total = 0;
            foreach ($stars as $k => $v) {
                $total += $v['star'];
                if ($v['star'] == 5 || $v['star'] == 4)
                    $hao += $v['star'];
                if ($v['star'] == 1 || $v['star'] == 2)
                    $cha += $v['star'];
                if ($v['star'] == 3)
                    $zhong += $v['star'];
            }

            $hao = round($hao / $total * 100, 2);
            $zhong = round($zhong / $total * 100, 2);
            $cha = round($cha / $total * 100, 2);

        }

        //获取印象
        $impression = D('impression')->field('yx_name,yx_count')->where(array('goods_id' => $gid))->select();



        return array('data'=>$data,'pageCount'=>$pageCount,
                'hao'=>$hao,
                "zhong"=>$zhong,
                "cha"=>$cha,
                'impression' => $impression

            );
    }

}

