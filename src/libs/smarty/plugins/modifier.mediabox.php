<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty mediabox modifier plugin
 *
 * Type:     modifier<br>
 * Name:     mediabox<br>
 * Purpose:  select correct player for media file
 * @author   Vo Duy Tuan <tuanmaster2002@yahoo..com>
 * @return string
 */
function smarty_modifier_mediabox($mediaUrl, $templateFolder = 'default', $videoWidth = 400, $videoHeight = 170)
{
    $mediaPlayerFileType 		= array('WMA', 'WMV', 'ASF');
	$jwFlvMediaPlayerFileType 	= array('FLV', 'MP3', 'MP4', 'AAC');
	$flashPlayerFileType 		= array('SWF');
	$mediaBox 					= '';

	$extension = strtoupper(substr($mediaUrl, strrpos($mediaUrl, '.')+1));


	if(strlen($mediaUrl) == 0)
	{
		$mediaBox = '<div style="margin: 15px 0;font-size:14px;color:#aaaaaa;font-weight:bold;">[[THERE IS NO MEDIA FILE FOR THIS FILE]]</div>';
	}
	elseif(in_array($extension, $jwFlvMediaPlayerFileType))
	{
		//adjust size for video file
		if($extension == 'FLV' || $extension == 'MP4')
		{
			$videoWidth = 400;
			$videoHeight = 320;
		}

		$mediaBox = '<div id="mediaContainer"></div>
					<script type="text/javascript" src="'.$templateFolder.'/js/swfobject.js"></script>
					<script type="text/javascript">
						var mediaplayer = new SWFObject("'.$templateFolder.'/js/jwflvmediaplayer.swf","player","'.$videoWidth.'","'.$videoHeight.'","9");
						mediaplayer.addParam("allowfullscreen","true");
						mediaplayer.addParam("allowscriptaccess","always");
						mediaplayer.addParam("flashvars","file='.$mediaUrl.'&autostart=true");
					  	mediaplayer.write("mediaContainer");
					</script>
					';

	}
	elseif(in_array($extension, $mediaPlayerFileType))
	{
		//adjust size for video file
		if($extension == 'WMV')
		{
			$videoWidth = 400;
			$videoHeight = 320;
		}
		$mediaBox = '<OBJECT ID="MediaPlayer" WIDTH="'.$videoWidth.'" HEIGHT="'.$videoHeight.'" CLASSID="CLSID:22D6F312-B0F6-11D0-94AB-0080C74C7E95" STANDBY="Loading Windows Media Player components..." TYPE="application/x-oleobject">
						<PARAM NAME="FileName" VALUE="'.$mediaUrl.'">
						<PARAM name="autostart" VALUE="true">
						<PARAM name="ShowControls" VALUE="true">
						<param name="ShowStatusBar" value="true">
						<PARAM name="ShowDisplay" VALUE="false">
						<EMBED TYPE="application/x-mplayer2" SRC="'.$mediaUrl.'" NAME="MediaPlayer" WIDTH="'.$videoWidth.'" HEIGHT="'.$videoHeight.'" ShowControls="1" ShowStatusBar="1" ShowDisplay="0" autostart="0"> </EMBED>
					</OBJECT>
					';
	}
	elseif(in_array($extension, $flashPlayerFileType))
	{
		$videoWidth = 400;
		$videoHeight = 320;
		$mediaBox = '<div id="mediaContainer"></div>
					<script type="text/javascript" src="'.$templateFolder.'/js/swfobject.js"></script>
					<script type="text/javascript">
					  var mediaplayer = new SWFObject("'.$mediaUrl.'","mpl","'.$videoWidth.'","'.$videoHeight.'","9");
					  mediaplayer.write("mediaContainer");
					</script>
					';
	}
	else
	{
		$mediaBox = 'File Type(.'.$extension.') is not supported.';
	}


	return $mediaBox;
}

/* vim: set expandtab: */

?>
