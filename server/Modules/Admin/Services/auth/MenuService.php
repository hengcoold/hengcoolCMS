<?php
// +----------------------------------------------------------------------
// | Name: hengcool管理系统 [ 为了快速搭建软件应用而生的，希望能够帮助到大家提高开发效率。 ]
// +----------------------------------------------------------------------
// | Copyright: (c) 2020~2021 https://www.lvacms.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed: 这是一个自由软件，允许对程序代码进行修改，但希望您留下原有的注释。
// +----------------------------------------------------------------------
// | Author: hengcool <260894671@qq.com>
// +----------------------------------------------------------------------
// | Version: V1
// +----------------------------------------------------------------------

/**
 * @Name
 * @Description
 * @Auther hengcool
 * @Date 2021/6/12 01:08
 */

namespace Modules\Admin\Services\auth;


use Modules\Admin\Models\AuthGroup as AuthGroupModel;
use Modules\Admin\Models\AuthRule as AuthRuleModel;
use Modules\Admin\Services\BaseApiService;
use Modules\Admin\Services\rule\RuleService;

class MenuService extends BaseApiService
{
    /**
     * @name 获取模块
     * @description
     * @author hengcool
     * @date 2021/6/20 11:34
     * @return JSON
     **/
    public function getModel(){
        $userInfo = (new TokenService())->my();
        $AuthRuleModel = AuthRuleModel::query()->where(['type'=>1])->select('id','path','icon','name');
        $data = [];
        if($userInfo['id'] === 1){
            $data = $AuthRuleModel->get()->toArray();
        }else{
            $adminRulesStr = AuthGroupModel::where('id',$userInfo['group_id'])->value('rules');
            if($adminRulesStr){
                $data = $AuthRuleModel->whereIn('id',explode('|',$adminRulesStr))->get()->toArray();
            }
        }
        return $this->apiSuccess('',$data);
    }
    /**
     * @name 获取左侧栏
     * @description
     * @author hengcool
     * @date 2021/6/12 1:10
     * @oaram id Int 模块id
     * @return JSON
     **/
    public function getMenu(int $id)
    {
        $data = [];
        $userInfo = (new TokenService())->my();
//        if($userInfo['id'] != 1){
        if($userInfo['group_id'] != 1){
            $adminRulesStr = AuthGroupModel::where('id',$userInfo['group_id'])->value('rules');
            if($adminRulesStr){
                $rule = AuthRuleModel::where('type','!=',1)->select('id','pid')->get()->toArray();
                $ruleArr = (new RuleService())->delSort($rule,$id);
                $adminRulesArr = explode('|',$adminRulesStr);
                $rulesArrayIntersect = array_intersect($ruleArr,$adminRulesArr);
                if(count($rulesArrayIntersect)){
//                    $rulesArrayIntersect[] = $id;
                    $data = AuthRuleModel::where('type','!=',1)->whereIn('id',$rulesArrayIntersect)->get()->toArray();
                }
            }
        }else{
            $rule = AuthRuleModel::where('type','!=',1)->select('id','pid')->get()->toArray();
            $ruleArr = (new RuleService())->delSort($rule,$id);
//            $ruleArr[] = $id;
            $data = AuthRuleModel::where('type','!=',1)->whereIn('id',$ruleArr)->get()->toArray();
        }
        return $this->apiSuccess('',$data);
    }
}
