var mouseX = 0, mouseY = 0;

jQuery(document).ready(function() {    
	$(document).mousemove(function(e)
	{       
		mouseX=e.pageX;
		mouseY=e.pageY;
	});  
}) 

function showTooltip(divId) {
	var divInfo = document.getElementById(divId);

	var left = mouseX + 10;
	var top = mouseY;
			
	divInfo.style.left = left+'px';
	divInfo.style.top = top+'px';
	divInfo.style.position = 'absolute';
	divInfo.style.display = 'block';
}

function hideTooltip(divId) {
	var divInfo = document.getElementById(divId);
	divInfo.style.display = 'none';
}

