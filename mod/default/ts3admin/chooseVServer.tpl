<!-- fancybox 
<script type="text/javascript" src="js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" type="text/css" href="js/fancybox/jquery.fancybox-1.3.4.css" />
-->

<!-- category and table styles -->
<style type="text/css">
	tr.row { height:24px; }
	tr.highlight_row { height:24px; }
	div.category, div.maxcategory {
		border:1px solid darkgrey;
		padding:2px 2px 2px 2px;
		width:400px;	
		margin-bottom:20px;
	}
	div.maxcategory {
		width:100%;	
	}
	div.categoryHead {
		position:relative;
		border:1px solid darkgrey;
		padding:2px 2px 2px 2px;	
		background-color:#lightgrey;
		font-weight:bold;
	}
	div.toggleCategoryHoder {
		position:absolute;
		top:2px;
		width:100%;
		text-align:right;
	}
	tr.selected_row td{
		background-color:lightblue;	
		height:24px;
	}
	
	a img{
		border:0px;	
	}
</style>

<!-- Std. vars and functions -->
<script type="text/javascript">
	var loading = "{$lang.loading}";
	var imgsrc="{$imgsrc}";
	
	<!--{literal}
	function toggleCategory(catelement, fasthide) {
		var cat = catelement.getAttribute("cat");
		if(fasthide) {
			if(document.getElementById(cat+"tbl")!=null)$("#"+cat+"tbl").hide();
		}else{
			$("#"+cat+"tbl").fadeToggle("slow","swing");
		}
		var closed = catelement.getAttribute("closed");
		var imgelement = catelement.getElementsByTagName("img")[0];
		if(closed=="1") {
			catelement.setAttribute("closed","0");
			imgelement.src=imgsrc+"/toggle_minus.png";
			document.cookie = cat+"closed=0;";
		}else{
			catelement.setAttribute("closed","1");
			imgelement.src=imgsrc+"/toggle_plus.png";
			document.cookie = cat+"closed=1;";
		}
	}
	
	function toggleCategoryByCookie() {
		var links = document.getElementsByTagName("a");
		for (link in links) {
			link = links[link];
			if(typeof(link)!="object") continue;
			var cat = link.getAttribute("cat");	
			if(cat!=null) {
				if(getCookieValue(cat+"closed",0)==1)
					toggleCategory(link,true);
			}
		}
	}
	{/literal}-->
</script>



<script type="text/javascript">
	 var sid = "{$sid}";
	 
	<!--{literal}	
    function selectVServer(id) {
		document.cookie = "lastSelectedVServerID="+id+";";
		document.getElementById("vserverselector").value=id;
        document.getElementById("vserverContent").innerHTML=loading;
		$("#vserverContent").load("ajax_request.php?mod=ts3admin&file=manageVServer.ajax&sid="+sid+"&vsid="+id);
    }
    {/literal}-->
</script>

<div style="position:absolute; width:100%; text-align:right; top:24px;" >
	{$lang.vserver}: 
    <select id="vserverselector" onchange="javascript: selectVServer(this.value); return false;" style="width:150px;">
    {section name=i loop=$vservers}
        <option value="{$vservers[i].id}">{$vservers[i].virtualserver_name}</option>
    {/section}
    </select>
</div>
<div id="vserverContent">
</div>
        
        
<script type="text/javascript"> 
	var serverid = {$vservers[0].id};
	var last = getCookieValue("lastSelectedVServerID",serverid);
	{section name=i loop=$vservers}
        if({$vservers[i].id}==last)
			serverid=last;
    {/section}
	<!--{literal}
        selectVServer(serverid);
	{/literal}-->
</script>