<?php 
/* 
 *  Role 控制器服务层
 *  @author wuchuheng
 *  @data 2019/05/16
 *  @email wuchuheng@163.com
 *  @blog  www.wuchuheng.com
 */
namespace app\api\service;

use app\api\model\Member; 
use app\api\service\Base;
use think\Db;
use think\facade\Request;
use app\api\model\MemberGroup;
use app\api\model\AuthGroup;
use think\facade\Config;
use think\facade\Env;
use MemberGroupAccess;
use app\api\model\Image;
  

class Role extends Base
{
    /**
     * 获取客服组数据树
     * :xxx  请求的数据用于添加客服分类用的:xxx这设计不符合rest规范,先将就下
     * @return obj
     * 
     */
    public function getAllUser($forAddMember = '')
    {
        $count           = Member::count();
        $count_no_count  = (new Member())->countNotBelong();
        $MemberGroup     = (new MemberGroup())->getGroup();
        $otherNode       = [
            'title'     => "未分组({$count_no_count})",
            'id'        => -1,
            'parentId'  => 0-1
          ];
        $subNode = $this->_arrToTree($MemberGroup->toArray());
        //用于添加会员的表单用
        if (Request::get('addMember'))  return $subNode;
        //返回，更新子节点
        if (Request::get('nodeId/d') > 0) return $subNode;
        $subNode[] = $otherNode; 
        //返回一级节点
        if (Request::get('nodeId/d') === 0 ) return $subNode;
        $data[] = [
          'title'    => "所有({$count})",
          'id'       => 0,
          'spread'   => true,
          'parentId' => 0,
          'children' => $subNode
        ];
        return $data;
    } 


    /**
     * 修改节点
     * @return    boolean
     */
     public function editGroup()
     {
        $id = Request::put('nodeId/d');
        $handle = MemberGroup::get($id);
        $handle->name = Request::put('editNodeName/s');
        $isSave = $handle->save();
        return $isSave;
     }


    /**
     * 添加客服组
     * @return  boolean    处理结果
     */
    public function AddGroup() 
    {
        $data['name'] = Request::param('nodeName');
        $pid = Request::param('parentId');
        if ($pid) {
          $parentNode =(new MemberGroup())->where("id = {$pid}")->field('id,path')->find();
          $data['path'] = $parentNode->path . '-' . $parentNode->id;
          $data['pid']  = $parentNode->id;
        }
        $data['name'] = Request::param('addNodeName');
        $isSave = (new MemberGroup())->create($data);
        return $isSave;
    }


    /**
      * 删除节点
      * @access public
      * @return boolean
      *
      */
    public function delGroup()
    {
        $id = Request::delete('nodeId/d');
        $isDel = MemberGroup::where('id', '=', $id)  
          ->whereOr('path', 'like', "%-{$id}%") 
          ->delete();
        return $isDel;
    }


    /**
     * 将数组遍历为数组树 
     * @arr     有子节点的目录树
     * @tree    遍历赋值的树
     * @return  array   
     *
     */ 
    protected function _arrToTree($items, $pid = 'parentId')
    {
         $map  = [];
         $tree = [];   
         foreach ($items as &$it){
           $el = &$it; 
           $el['title'] = $el['title'] . "(" .$el['count']. ")";
           unset($el['path']);
           unset($el['name']);
           unset($el['count']);
           unset($el['pid']);
           unset($el['fullpath']);
           $map[$it['id']] = &$it; }  //数据的ID名生成新的引用索引树
         foreach ($items as &$it){
           $parent = &$map[$it[$pid]];
           if($parent) {
             $parent['children'][] = &$it;
           }else{
             $tree[] = &$it;
           }
         }
         return $tree;
    }


    /**
     * 获取权限角色列表
     *
     */
    public function getRoleList()
    {
      //分页
      if (Request::has('limit', 'get') && Request::has('page', 'get')) {
      $list = (new AuthGroup())
        ->field('id,title,descript')
        ->append(['parentId'])
        ->order('id desc')
        ->paginate(Request::get('limit/d'));
        $total = $list->total();
        $list = $list->toArray();
        $list = $list['data'];
        return ['data'=>$list, 'count'=>$total];
      }
      $hasData = (new AuthGroup())
        ->field('id,title,descript')
        ->append(['parentId'])
        ->order('id desc')
        ->select();
      return $hasData;
    }


  /**
   *  添加新的成员
   *  @return   boolean 
   */
   public function addMember()
   {
     Db::startTrans();
     try {
       $img_id = $this->_saveIcon();
       if (Request::has('receives', 'post') && Request::post('receives/d') > 0 ) {
         $receives = Request::post('receives/d');
       } else {
         $result = (Db::name('config')->where('name', '=', 'receives')->field('value')->find());
         $receives = (int)$result['value'];
       }
       $uid = Db::name('member')->insertGetId([
         'username'      => Request::post('username/s'),
           'passwd'      => md5(Request::post('passwd/s')),
           'img_id'      => $img_id,
           'account'     => Request::post('account/s'),
           'receives'    => $receives,
           'nick_name'   => Request::post('nick_name/s'),
           'email'       => Request::post('email/s'),
           'phone'       => Request::post('phone/s'),
           'create_time' => time()
         ]);
       Db::name('authGroupAccess')->insert([
         'uid'      => $uid,
         'group_id' => Request::post('group_id/d')
       ]);
       Db::name('memberGroupAccess')->insert([
         'uid'             => $uid,
         'member_group_id' => Request::post('member_group_id/d')
       ]);
       Db::commit();
     } catch (\Exception $e) {
       return false;
       Db::rollback();
     }
     return true;
   }


    /**
     *  保存头像图片
     *  @return  $img_id  int  相册id 
     */
     protected function _saveIcon()
     {
       if (Request::has('file', 'post') && !base64_decode(Request::post('file/s'))) {
         $result = Db::name('config')->where('name','=', 'member_img_id')->field('value')->find();
         return (int)$result['value'];
       }
       $base64 = Request::post('file/s');
       preg_match("/^data:image\/(\w+);base64,/", $base64, $file_type);
       $base64 = substr($base64, strpos($base64, ',') + 1);
       $file_type = $file_type[1];
       $source = base64_decode($base64);
       $dir = Env::get('root_path') . "public/static/img/" . date('Y-m-d');
       if (!is_dir($dir)) mkdir($dir, 0766, true);
       $filename = $dir . '/' . uniqid() . '.' . $file_type;
       file_put_contents($filename, $source);
       $filename = str_replace(Env::get('root_path') . 'public', '', $filename);
       $img_id = Db::name('image')->insertGetId(['url'=>$filename, 'from'=>1]);
       return $img_id;
     }


    /**
     * 获取成员
     * :xxx  要采用远程关联并返回collection数据集
     */
     public function getMembers()
     {

       $limit  = Request::get('limit/d');
       $result = Member::with([
         'authAccess.authGroup',
         'memberGroupAccess.MemberGroup',
         'image'
       ])
       ->append(['auth_access'])
       ->order('uid desc')
       ->paginate($limit);
       $count = $result->total();
      foreach($result as $member) {
        $tmp['uid']         = $member->uid;
        $tmp['account']     = $member->account;
        $tmp['nick_name']   = $member->nick_name;
        $tmp['email']       = $member->email;
        $tmp['phone']       = $member->phone;
        $tmp['receives']    = $member->receives;
        $tmp['username']    = $member->username;
        $tmp['img']         = $member->image->url;
        $tmp['role']        = $member->auth_access->auth_group->title;
        $tmp['is_lock']     = $member->is_lock;
        $collection[] = $tmp;
      }
      return (object) array('count'=>$count, 'data'=>$collection);
     }


     /**
     *  修改成员
     *  @param  int   $uid    用户id
     *  @return boolean
     *
     */
     public function editMember(int $uid)
     {
       // 启动事务
       Db::startTrans();
       try {
         $member = Member::where('uid', '=', $uid)->find();
         //更新用户组
         if (Request::has('member_group_id')){
           $member->memberGroupAccess->member_group_id = Request::param('member_group_id/d', 'put');
           $member->memberGroupAccess
             ->allowField(true)
             ->save(Request::param());
         } 
         //更新权限组
         if (Request::has('group_id')) {
           $member->authGroupAccess->group_id = Request::param('group_id/d', 'put');
           $member->memberGroupAccess
             ->allowField(true)
             ->save(Request::param());
         }
         //更新成员信息
         $member->save(Request::param());
         Db::commit();
       } catch (\Exception $e) {
         // 回滚事务
         Db::rollback();
         return false;
       }
       return true;

     }
      

    
    /**
     * 删除成员
     * @param    $uid    init    成员uid 
     * @return   boolean
     */
     public function delMember(int $uid) 
		 {
          Db::startTrans();
          try{
         	  $user = Member::where('uid', '=', $uid)->find();
						Db::name('member_group_access')->where('uid','=', $user->uid)->delete();
						Db::name('auth_group_access')->where('uid','=', $user->uid)->delete();
            $Image = Image::get($user->img_id);
					  $Image->delete_time=date('Y-m-d H:i:s', time());
						$Image->save();
            $user->delete();
            Db::commit();
          } catch(\Exception $e){
            Db::rollback();
            return false;
          }
 					return true;
     }
}

