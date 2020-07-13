<?php
/**
 * Desc: 类文件描述
 * Date: 2020-07-13
 */
namespace demo\controller;

/**
 * @title 文章相关接口
 */
class index
{
    /**
     * @apitrue
     * @title 文章列表
     * @desc 接口描述
     * @method POST
     * @addr /api/article/list
     * @request id integer 是 文章ID
     * @request page integer 是 分页，默认为1
     * @response title integer 是 标题
     * @response content integer 是 内容
     * @responsetpl {recode:1, msg:'success', data:[]}
     * @sort 100
     */
    public function index()
    {

    }

    /**
     * @apitrue
     * @title 文章详情
     * @desc 文章详情接口描述
     * @method POST
     * @addr /api/article/info
     * @request id integer 是 文章ID1111
     * @request page integer 是 分页，默认为1
     * @response title integer 是 标题
     * @response content integer 是 内容
     * @responsetpl {recode:1, msg:'success', data:[]}
     * @sort 100
     */
    public function index2()
    {

    }

    /**
     * @apitrue
     * @title 新建文章
     * @desc 文章详情接口描述
     * @method POST
     * @addr /api/article/info
     * @request id integer 是 文章ID1111
     * @request page integer 是 分页，默认为1
     * @response title integer 是 标题
     * @response content integer 是 内容
     * @responsetpl {recode:1, msg:'success', data:[]}
     * @sort 100
     */
    public function add()
    {

    }

    /**
     * @apitrue
     * @title 删除文章
     * @desc 文章详情接口描述
     * @method POST
     * @addr /api/article/info
     * @request id integer 是 文章ID1111
     * @request page integer 是 分页，默认为1
     * @response title integer 是 标题
     * @response content integer 是 内容
     * @responsetpl {recode:1, msg:'success', data:[]}
     * @sort 100
     */
    public function del()
    {

    }
}
