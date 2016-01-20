	<div class="headline">{$lang.faq}</div>

			{section name=i loop=$list}
				
				{literal}<script type="text/javascript">
				$(document).ready(function() {
					$('#faq_q_{/literal}{$list[i].id}{literal}').fadeIn(500);
					$('#faq_q_{/literal}{$list[i].id}{literal}').click(function(){
					$('#faq_a_{/literal}{$list[i].id}{literal}').fadeToggle();
					});
				});
				</script>{/literal}
				
				<div id="faq_q_{$list[i].id}" style="display:none;cursor:pointer;" >
					<p class="highlight_row" style=";padding:10px 0 10px 10px;"><img src="media/icons/bullet_go.png" alt="->" style="padding-right:5px"/>&nbsp;{$list[i].question}</p>
				</div>
				<div id="faq_a_{$list[i].id}" style="display:none;">
					<p style="padding:0 0 0 10px;">{$list[i].answer}</p>
				</div>
				
			{/section}
			
			<noscript>
				{section name=i loop=$list}
							
					<div>
						<p class="highlight_row" style="padding:10px 0 10px 10px;"><img src="media/icons/bullet_go.png" alt="->" style="padding-right:5px"/>&nbsp;{$list[i].question}</p>
					</div>
					<div>
						<p style="padding:0 0 0 10px;">{$list[i].answer}</p>
					</div>
				
				{/section}
			</noscript>
		
		

		