<?php


function smarty_function_staticshelf ($aParam) 
{
// {staticshelf rooturl=$conf.rooturl}
	
	$rooturl  =  $aParam['rooturl'];
	
	$staticShelves = Core_Shelf::getStaticShelves();
	$output = '<ul class="staticshelflist">';
	
	for($i = 0, $cnt = count($staticShelves); $i < $cnt; $i++)
	{
		$output .= '<li id="staticshelf'.$staticShelves[$i]->id.'"><a href="'.$rooturl.'book?category='.$staticShelves[$i]->id.'" title="'.$staticShelves[$i]->name.'">'.$staticShelves[$i]->name.'</a></li>';
	}
	
	$output .= '</ul>';
	
	return $output;
}//
?>