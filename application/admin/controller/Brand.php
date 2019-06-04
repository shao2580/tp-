<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\model\Brand as brandModel; 
use app\admin\controller\Common;

class Brand extends Common
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {   
        $page = input('page')??1;
        $limit = input('limit')??config('pageSize');

        $count = brandModel::count();
        $data = brandModel::order('brand_id','desc')->page($page,$limit)->select();
       if (request()->isAjax()) {
           $result = [
                'code'=>0,
                'msg'=>'',
                'count'=>$count,
                'data'=>$data
           ];
           echo json_encode($result);die;
       }
        
        
        return view();
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function add()
    {
        return view();
    }

    


    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function doadd(Request $request)
    {
       $post = $request->post(); 
       // dump($post);die;
       
       /*// PHP验证
        $validate = new \app\admin\validate\Brand;
        if (!$validate->check($post)) {
            return view('add',['data'=>$post,'error'=>$validate->getError()]);
        }*/

       $brandModel = new brandModel;
      $res = $brandModel->allowField(true)->save($post);
       if ($res) {
           $this->redirect('Brand/index');
       }
    }
    /**
     *  监听是否显示操作
     * [ajaxupd description]
     * @return [type] [description]
     */
    public function ajaxupd(){
        if (request()->isAjax()) {
            $brand_id = input('get.brand_id');
            $field = input('get.key');
            $value = input('get.value');

            $data[$field] = $value==1?0:1;

            $brandModel = new brandModel;
            $res = $brandModel->save($data,['brand_id'=>$brand_id]);
            if ($res) {
                echo 1;
            }else{
                echo 0;
            }
        }
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        if ($id) {
            $data = brandModel::get($id);
            $this->assign('data',$data);
            return view();
        }
        
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        if (!intval($id)) {
            $this->error('非法请求');
        }
        $post = $request->post();
        $brandModel = new brandModel;
        $res = $brandModel->allowField(true)->save($post,['brand_id'=>$id]);
       if ($res) {
           $this->redirect('Brand/index');
       }
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete()
    {
        if (request()->isAjax()) {
           $brand_id = input('brand_id');

           $res = brandModel::destroy($brand_id); 
           if ($res) {
               echo json_encode(['code'=>0,'msg'=>'删除成功']);
           }else{
               echo json_encode(['code'=>1,'msg'=>'发送未知错误,删除失败']);
           }
        }
    }
}
