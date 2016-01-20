<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		{$core.head}
		<title>{if $breadcrumbs != ''}{$breadcrumbs|strip_tags} | {/if}{$core.title}</title>
		<link rel="shortcut icon" href="{$image_path}/favicon.ico" />
		<link rel="stylesheet" href="{$css_path}/style.css" type="text/css" />
		<script type="text/javascript">
			var slowRange=50;
			var scrollSpeedMax=25;
			var scrollSpeedMin=100;
			var stoptime=2000;

			var windowHeight;
			var windowFullHeight;
			var direction=-1;
			
			var currentHeight;

			function scrollInit() {	 
				windowFullHeight=findObjY(document.getElementById('end'));
				windowHeight=getMaxHeight();
				
				if(!getAnchor('noscroll')){
					if(getAnchor('smooth')){
						pageScrollSmooth();
					} else {
						pageScroll();
					}
				}
			}
			
			function getAnchor(needle) {
				var anchors=location.href.split('#');
				for(i=1; i<anchors.length; i++) {
					if(anchors[i] == needle) {
						return true;
					}
				}
				return false;
			}

			function pageScrollSmooth() {
				currentHeight = getHeight();
				if(currentHeight<=slowRange) {
					scrollSpeed=Math.pow(scrollSpeedMin-scrollSpeedMax,(slowRange-currentHeight)/slowRange)+scrollSpeedMax;	
				} else if(currentHeight+windowHeight>windowFullHeight-slowRange) {
					scrollSpeed=Math.pow(scrollSpeedMin-scrollSpeedMax,(1-(windowFullHeight-(currentHeight+windowHeight))/slowRange))+scrollSpeedMax;	
				} else {
					scrollSpeed=scrollSpeedMax;
				}
				
				window.scrollBy(0, direction*1);
				
				if(direction == -1 && currentHeight <= 0) { // Hit top!
					direction=1;
					scrollSpeed=stoptime;
				}	
				if(direction == 1 && currentHeight+windowHeight >= windowFullHeight) { // Hit bottom!
					direction=-1;
					scrollSpeed=stoptime;
				}
				scrolldelay = setTimeout('pageScrollSmooth()', scrollSpeed); 
			}

			function pageScroll() {
				currentHeight = getHeight();
				
				window.scrollBy(0, direction*1);

				if(direction == -1 && currentHeight <= 0) { // Hit top!
					direction=1;
					scrollSpeed=stoptime;
				} else if(direction == 1 && currentHeight+windowHeight >= windowFullHeight) { // Hit bottom!
					direction=-1;
					scrollSpeed=stoptime;
				} else {
					scrollSpeed=scrollSpeedMax;
				}
				
				scrolldelay = setTimeout('pageScroll()', scrollSpeed); 
			}

			function getMaxHeight() {
				var height = 0;
				if(typeof(window.innerWidth) == 'number') {
					//Non-IE
					height = window.innerHeight;
				} else if(document.documentElement && (document.documentElement.clientWidth || document.documentElement.clientHeight)) {
					//IE 6+ in "standard compliant mode"
					height = document.documentElement.clientHeight;
				} else if(document.body && (document.body.clientWidth || document.body.clientHeight)) {
					//IE 4 compatible
					height = document.body.clientHeight;
				}  
				return height;
			}
			
			function getHeight() {
				var height = 0;
				if(typeof(window.innerWidth) == 'number') {
					//Non-IE
					height = window.pageYOffset;
				} else if(document.documentElement && (document.documentElement.clientWidth || document.documentElement.clientHeight)) {
					//IE 6+ in "standard compliant mode"
					height = document.documentElement.scrollTop;
				} else if(document.body && (document.body.clientWidth || document.body.clientHeight)) {
					//IE 4 compatible
					height = document.body.scrollTop;
				}  
				return height;
			}

			function findObjY(obj){
				var oPos = 0;
				if (obj.offsetParent){
					while (obj.offsetParent){
						oPos += obj.offsetTop
						obj = obj.offsetParent;
					}
				}
				else if (obj.y)
					oPos += obj.y;
				return oPos;
			} 	
		</script>
	</head>
	<body onLoad="scrollInit();" style="overflow:hidden;">
		<div id="content-n">
			<div>
				{if $path != ""}
					{include file="$path"}
				{else}
					{if $str_output != ""}
						{$str_output}
					{else}
						&nbsp;
					{/if}
				{/if}
			</div>
		</div>
	</body>
	<div id="end"></div>
</html>