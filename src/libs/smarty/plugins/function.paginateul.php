<?php


function smarty_function_paginateul($aParam)
{
// {paginate count=30 curr=1 max=10 lang=langArr url=http://here.com/page-::PAGE::}


    $nPageCnt = $aParam['count'];
    $nCurrPage = $aParam['curr'];
    $nMaxPage = $aParam['max'];
    $sUrl = $aParam['url'];
    $lang = $aParam['lang'];


    if ($nPageCnt == 0 || $nPageCnt == 1) {
        return '';
    }

    //set default lang
    if (count($lang) < 1) {
        $lang = array(
            'first' => 'First',
            'previous' => 'Previous',
            'next' => 'Next',
            'last' => 'Last',
            'firstTooltip' => 'Go to First Page',
            'previousTooltip' => 'Go to Previous Page',
            'nextTooltip' => 'Go to Next Page',
            'lastTooltip' => 'Go to Last page',
            'pageTooltip' => 'Go to page'
        );
    }

    $sOut = '';

    $bDrewDots = true;

    if ($bDrewDots) {
        $dotsString = '<span class="paginate_dot">...</span>';
    } else {
        $dotsString = '';
    }


    if ($nPageCnt > $nMaxPage) {

        if (1 > ($nCurrPage - ($nMaxPage / 2))) {
            $nStart = 1;
            $nEnd = $nMaxPage;
        } elseif ($nPageCnt < ($nCurrPage + ($nMaxPage / 2))) {
            $nStart = $nPageCnt - $nMaxPage;
            $nEnd = $nPageCnt;
        } else {
            $nStart = $nCurrPage - ($nMaxPage / 2);
            $nEnd = $nCurrPage + ($nMaxPage / 2);
        }//if

    } else {
        $nStart = 1;
        $nEnd = $nPageCnt;
    }//if

    for ($a = $nStart; $a <= $nEnd; $a++) {

        if ($a == $nCurrPage) {
            $sOut .= '<li><a class="actived number current" title="Trang hiện tại">' . $a . '</a></li>';
        } else {
            $sOut .= '<li><a class="number" href="'
                . str_replace('::PAGE::', $a, $sUrl) . '" title="' . $lang['pageTooltip']
                . ' ' . $a . '">' . $a . '</a></li>';
        }

    }//for

    if ($nStart > 3) {


        $sOut = '
    	<li><a class="number" href="' . str_replace('::PAGE::', 1, $sUrl)
            . '" title="' . $lang['pageTooltip'] . ' 1">1</a></li>
    	<li><a class="number" href="' . str_replace('::PAGE::', 2, $sUrl)
            . '" title="' . $lang['pageTooltip'] . ' 2">2</a></li>
    	' . $dotsString . '
    	' . $sOut;

    }//if

    if ($nEnd < ($nPageCnt - 3)) {

        $sOut .= $dotsString . '
    	<li><a class="number" href="' . str_replace('::PAGE::', $nPageCnt - 1, $sUrl) . '" title="'
            . $lang['pageTooltip'] . ' ' . ($nPageCnt - 1) . '">' . ($nPageCnt - 1) . '</a></li>
    	<li><a class="number" href="' . str_replace('::PAGE::', $nPageCnt, $sUrl) . '" title="'
            . $lang['pageTooltip'] . ' ' . $nPageCnt . '">' . $nPageCnt . '</a></li>
    	';

        // die($sOut);
    }//if


    //insert previous/next button
    if ($nCurrPage > 1) {
        $sOut = '<li><a class="number" href="' . str_replace('::PAGE::', $nCurrPage - 1, $sUrl) . '"  title="'
            . $lang['previousTooltip'] . '">' . $lang['previous'] . '</a></li>' . $sOut;
    }


    if ($nCurrPage < $nPageCnt) {
        $sOut .= '<li><a href="' . str_replace('::PAGE::', $nCurrPage + 1, $sUrl)
            . '" class="number numbernext" title="' . $lang['nextTooltip'] . '">' . $lang['next'] . ' &raquo;</a></li>';
    }


    //insert first/last button
    if ($nCurrPage > 2) {
        $sOut = '<li><a href="' . str_replace('::PAGE::', 1, $sUrl) . '" title="'
            . $lang['firstTooltip'] . '">' . $lang['first'] . '</a></li>' . $sOut;
    }

//disable last page
    if ($nCurrPage < ($nPageCnt - 1) && false) {
        $sOut .= '<li><a href="' . str_replace('::PAGE::', $nPageCnt, $sUrl) . '" title="'
            . $lang['lastTooltip'] . '">' . $lang['last'] . '</a></li>';
    }


    $sOut = '<div class="paginat"><ul class="pagination">' . $sOut . '</ul></div>';

    //not include /page-1 in url
    $sOut = preg_replace('/\/page-1([^\d])/', '\1', $sOut);

    return $sOut;

}
