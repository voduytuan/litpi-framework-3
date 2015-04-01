<?php

function smarty_function_cms_page($params)
{
    return \Model\Cms\Page::cmsPage($params);
}
