<?php

/**
 * Paginate duoi dang javascript
 * vd: <a href="javascript:void(0)" onclick="{$urlFormat}">3</a>
 * 	$urlFormat: chuoi chua ::page:: de thay the cho vi tri cua page
 *
 * @param unknown_type $aParam
 * @return unknown
 */
function smarty_function_paginateajax ($aParam) 
{
// {paginate count=30 curr=1 max=10 lang=langArr url=http://here.com/page-::PAGE::}

  $nPageCnt  =  $aParam['count'];
  $nCurrPage  =   $aParam['curr'];
  $nMaxPage  =   $aParam['max'];
  $lang		= $aParam['lang'];
  $sUrl = $aParam['urlformat'];	//chua #page# de thay the cho so trang se navigate
  
  if($nPageCnt == 0)
  	return '';
  
  //set default lang
  if(count($lang) < 1)
  {
  		$lang = array('first' => 'First',
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
  
  //$lang['previous'] = 'Previous';
  //$lang['next']		= 'Next';
  
  	$sOut    =   '';

  	$bDrewDots = true;
  
	if($bDrewDots)
		$dotsString = '<span class="paginate_dot">...</span>';
	else 
		$dotsString = '';
  

  	if ($nPageCnt > $nMaxPage) 
  	{

		if (1 > ($nCurrPage - ($nMaxPage /2))) 
    	{
      		$nStart = 1;
      		$nEnd  = $nMaxPage;
    	} 
	    elseif ($nPageCnt < ($nCurrPage + ($nMaxPage /2))) 
	    {
	      	$nStart = $nPageCnt - $nMaxPage;
	      	$nEnd  = $nPageCnt;
	    } 
	    else 
	    {
	      	$nStart = $nCurrPage - ($nMaxPage / 2);
	      	$nEnd  = $nCurrPage + ($nMaxPage / 2);
	    }//if
      
  	} 
  	else 
  	{
    	$nStart = 1;
    	$nEnd  = $nPageCnt;
  	}//if
  
  	$nStart = (int)($nStart);
	$nEnd = (int)($nEnd);
	  	
  	for ($a = $nStart; $a <= $nEnd; $a++) 
  	{
       
      	if ($a == $nCurrPage)
        	$sOut .= '<a class="number current" title="Current Page">'.$a.'</a>';
      	else
        	$sOut .= '<a class="number" href="javascript:void(0);" onclick="' . str_replace('::PAGE::', $a, $sUrl) . '" title="'.$lang['pageTooltip'].' '.$a.'">'.$a.'</a>';

  	}//for
  
  	if ($nStart > 3) 
  	{
  
  		
    	$sOut = '
    	<a class="number" href="javascript:void(0);" onclick="' . str_replace('::PAGE::', 1, $sUrl) . '" title="'.$lang['pageTooltip'].' 1">1</a>
    	<a class="number" href="javascript:void(0);" onclick="' . str_replace('::PAGE::', 2, $sUrl) . '" title="'.$lang['pageTooltip'].' 2">2</a>
    	'.$dotsString.' 	' . $sOut;

  	}//if
  
  	if ($nEnd < ($nPageCnt - 3)) 
  	{
  
    	$sOut .= $dotsString . '
    		<a class="number" href="javascript:void(0);" onclick="' . str_replace('::PAGE::', $nPageCnt - 1, $sUrl) . '" title="'.$lang['pageTooltip'].' ' . ($nPageCnt - 1) . '">' . ($nPageCnt-1) . '</a>
    	<a class="number" href="javascript:void(0);" onclick="' . str_replace('::PAGE::', $nPageCnt, $sUrl) . '" title="'.$lang['pageTooltip'].' ' .$nPageCnt . '">' .$nPageCnt . '</a>' ;

  		// die($sOut);
  	}//if
  
  	
  	//insert previous/next button
  	//insert previous/next button
  	if ($nCurrPage > 1)
   		$sOut = '<a class="number" href="javascript:void(0)" onclick="' . str_replace('::PAGE::', $nCurrPage - 1, $sUrl) . '"  title="'.$lang['previousTooltip'].'">'.$lang['previous'].'</a>' . $sOut;
    

  	if ($nCurrPage < $nPageCnt)
    	$sOut .= '<a href="javascript:void(0)" onclick="' . str_replace('::PAGE::', $nCurrPage + 1, $sUrl) . '" class="number numbernext" title="'.$lang['nextTooltip'].'">'.$lang['next'].'</a>';

    	
    	
    	
    //insert first/last button
     if ($nCurrPage > 2)
    	$sOut = '<a href="javascript:void(0)" onclick="' . str_replace('::PAGE::', 1, $sUrl) . '" title="'.$lang['firstTooltip'].'">'.$lang['first'].'</a>' . $sOut;
    
	//do not show Last page
  	//if($nCurrPage < ($nPageCnt-1))
  		//$sOut .= '<a href="javascript:void(0)" onclick="' . str_replace('::PAGE::', $nPageCnt, $sUrl) . '" title="'.$lang['lastTooltip'].'">'.$lang['last'].'</a>';

   		


    
  	$sOut = '<div class="pagination">'.$sOut.'</div>';
  
  	return $sOut;
    
}//smarty_pagenate
?>