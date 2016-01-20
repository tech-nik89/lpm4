<script type="text/javascript" language="javascript">
	
	var urls = new Array();
	var durations = new Array();
	
	var interval;
	var currentIndex;
	
	{foreach from=$list item=mod}
		urls.push('{$mod.url}');
		durations.push({$mod.duration});
	{/foreach}
	
	function runBeamer() {
		if (urls.length > 0)
			nextMod(0);
	}
	
	function nextMod(i) {
		if (i < urls.length)
			var j = i + 1;
		else
			var j = 0;
		
		currentIndex = i;
		
		$('#beamer_content').load(urls[i] + '&tpl=none');
		window.clearInterval(interval);
		interval = window.setInterval('nextMod('+j+')', durations[i] * 1000);
	}
	
	$(document).ready(function() {
		runBeamer();
	});
	
</script>

<div id="beamer_content">
	&nbsp;
</div>