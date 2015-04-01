<?php

function smarty_function_cms_banner($params)
{
    return \Model\Cms\Banner::cmsBanner($params);
}
