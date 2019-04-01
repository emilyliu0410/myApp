<?php
defined('UFM_RUN') or die('No direct script access allowed.');

class AuthorIndexController extends UBaseContentListController
{
    var $model;

    function __construct($fm)
    {
        parent::__construct($fm);
        $this->model = new UAuthor();
    }

    protected function getPageInfo($id)
    {
        $page_info = $this->model->getAuthorInfoById($id);
        $page_info->id = $page_info->author_id;
        $page_info->name = $page_info->author_name;
        return $page_info;
    }

    protected function getArticles($searchConditions)
    {
        return $this->model->getAuthorArticles($searchConditions);
    }

    protected function getStandardConditionsKey()
    {
        return 'author_id';
    }

    protected function getPageUrlPath()
    {
        return '/author/index';
    }
}