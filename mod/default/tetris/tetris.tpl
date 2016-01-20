<div id="gamearea">
{literal}
<script type="text/javascript">
			
			window.setInterval('refreshPlayer()', 3000);
			
			madeaclick = false;
			
			function redirectPageSitter() {
				$("#gamearea").load("ajax_request.php?mod=tetris&file=gtfo");
			}
			
			chatfocused = false;
			
			function toggleFocus(value) {
				chatfocused = value;
			}
			
			score = 0;
			gameRunning = false;
			paused = false;
			linesCleared = 0;
			linesClearedOverall = 0;
			gameLevel = 0;
			allBlocks = new Array(0,0,0,0,0,0,0);
			attack = '';
			attacks = new Array();
			
			var gameLoop;
			
			function fieldToString() {
				var joined = new Array();
				for (y = 0; y < 19; y++) {
					joined[y] = f[y].join(','); 
				}
				alltogether = joined.join(';');
				return alltogether;
			}
			
			function refreshPlayer() {
				if (gameRunning) {
					alive = 1;
					alltogether = fieldToString();
				} else {
					alive = 0;
					alltogether = '';
				}
				
				if(attacks.length > 0) {
					attack = attacks.join('-');
				} 

				var uniquid = $('#uniquid').val();
				
				$("#player").load("ajax_request.php?mod=tetris&file=player&nickname={/literal}{$nick}{literal}&score="+score+"&level="+gameLevel+"&attack="+attack+"&alive="+alive+"&madeaclick="+madeaclick+"&uniquid="+uniquid+"&field="+alltogether);
				madeaclick = false;
				attacks = new Array();
				attack = 0;
				updateChat();
			}
			
			$(document).keypress(function(event) {

				/*
				if (event.which == 112) {
					paused = !paused;
					if (paused)
						$("#paused").html("Paused");
					else
						$("#paused").html("&nbsp;");
				}
				*/
				
				madeaclick = true;
				
				if (!gameRunning || paused)
					return;
				if(chatfocused) {
					return;
				}
				switch (event.which) {
					case 119: rotate(); break;
					case 97: moveLeft(); break;
					case 100: moveRight(); break;
					case 115: drop(); break;
					case 113: dropSlow(); break;
					
					case 105: rotate(); break;
					case 106: moveLeft(); break;
					case 108: moveRight(); break;
					case 117: drop(); break;
					case 107: dropSlow(); break;
				}
				switch (event.keyCode) {
					case 37: moveLeft(); break;
					case 38: rotate(); break;
					case 39: moveRight(); break;
					case 40: dropSlow(); break;
					case 13: drop(); break;
					
				}
			});
			
			curX = 3;
			curY = 0;
			curB = 0;
			curC = 1;
			nextB = 0;
			nextC = 1;
			
			f = new Array();
			
			block = new Array();
			curBlock = new Array(
				new Array(0, 0),
				new Array(0, 0),
				new Array(0, 0),
				new Array(0, 0)
			);
	
			block[0] = new Array(
				new Array(0, 0),
				new Array(0, 1),
				new Array(1, 0),
				new Array(1, 1)
			);
			block[1] = new Array(
				new Array(0, -1),
				new Array(0, 0),
				new Array(0, 1),
				new Array(1, 1)
			);
			block[2] = new Array(
				new Array(0, -1),
				new Array(0, 0),
				new Array(0, 1),
				new Array(1, -1)
			);
			block[3] = new Array(
				new Array(0, -1),
				new Array(0, 0),
				new Array(0, 1),
				new Array(1, 0)
			);
			block[4] = new Array(
				new Array(0, -1),
				new Array(0, 0),
				new Array(-1, 0),
				new Array(-1, 1)
			);
			block[5] = new Array(
				new Array(-1, -1),
				new Array(-1, 0),
				new Array(0, 0),
				new Array(0, 1)
			);
			block[6] = new Array(
				new Array(0, -1),
				new Array(0, 0),
				new Array(0, 1),
				new Array(0, 2)
			);
		
			function addScore(points) {
				score = score + points;
				$("#score").html(score);
			}
			
			function gameOver() {
				gameRunning = false;
				alltogether = fieldToString();
				$.post("ajax_request.php?mod=tetris&file=store_highscore&nickname={/literal}{$nick}{literal}&score="+score+"&lines="+linesClearedOverall+"&level="+gameLevel+"&field="+alltogether);
			}
			
			function clearAll() {
				for (y = 0; y < 20; y++) {
					f[y] = new Array();
					for (x = 0; x < 10; x++) {
						if (y == 19)
							f[y][x] = 1;
						else
							f[y][x] = 0;
					}
				}
				rePaintAll();
			}
			
			function sendStart() {
				$.post("ajax_request.php?mod=tetris&file=start&nickname={/literal}{$nick}{literal}");
			}
			
			function deleteMaster() {
				$.post("ajax_request.php?mod=tetris&file=deleteMaster&nickname={/literal}{$nick}{literal}");
			}
			
			function start(Seed) {
				clearAll();
				setLevel(1);
				score = 0;
				linesCleared = 0;
				linesClearedOverall = 0;
				$("#score").html("0");
				$("#linesCleared").html("0");
				allBlocks = new Array(0,0,0,0,0,0,0);
				nextB = -1;
				RandomNumber = new RandomNumberGenerator(Seed);
				newBlock();
				gameRunning = true;
				makeWindowBlink('Game Started!');
			}
			
			function setLevel(newLevel) {
				gameLevel = newLevel;
				interval = levelToInterval(newLevel);
				window.clearInterval(gameLoop);
				gameLoop = window.setInterval('render()', interval);
				$("#level").html(gameLevel);
			}
			
			function newBlock() {
				if(nextB >= 0) {
					curB = nextB;
					curC = nextC;
				} else {
					curB = getRandom(0, 6);
					curC = curB + 1;
				}
				
				nextB = getRandom(0, 6);
				nextC = nextB + 1;
				curX = 4;
				curY = 0;
				
				curBlock = new Array();
				for (i = 0; i < 4; i++) {
					curBlock[i] = new Array();
					curBlock[i][0] = block[curB][i][0];
					curBlock[i][1] = block[curB][i][1];
				}
				
				allBlocks[curB]+=1;
				updateBlockCount();
				setNextBlock(nextB, nextC);
			}
			
			function updateBlockCount() {
				for (i = 0; i < 7; i++) {
					$("#block_"+i).text(allBlocks[i]);
				}
			}
			
			function storeOldBlock() {
				paintBlock(curBlock);
				for (i = 0; i < 4; i++) {
					f[curBlock[i][0] + curY][curBlock[i][1] + curX] = curC;
				}
				checkAndDeleteRows();
			}
			
			function paintBlock(b) {
				for (i = 0; i < 4; i++) {
					setColor(b[i][1] + curX, b[i][0] + curY, numberToColor(curC));
				}
			}
			
			function rotate() {
				unPaint();
				bla = new Array();
				for (i = 0; i < 4; i++) {
					bla[i] = new Array(curBlock[i][1], curBlock[i][0] * -1);
				}
				
				if (!collision(bla, 0, 0)) {
					curBlock = bla;
				}
				doPaint();
			}
			
			function moveDown() {
				if (!collision(curBlock, 0, 1)) {
					curY++;
					return true;
				}
				else {
					storeOldBlock();
					newBlock();
					return false;
				}
			}
			
			function moveLeft() {
				if (!collision(curBlock, -1, 0)) {
					unPaint();
					curX--;
					doPaint();
				}
			}
			
			function moveRight() {
				if (!collision(curBlock, 1, 0)) {
					unPaint();
					curX++;
					doPaint();
				}
			}
			
			function drop() {
				unPaint();
				while (moveDown());
			}
			
			function dropSlow() {
				unPaint();
				moveDown();
				doPaint();	
			}
			
			function collision(newBlock, moveX, moveY) {
				
				for (i = 0; i < 4; i++) {
					// left wall
					if ((newBlock[i][1] + curX + moveX) < 0) {
						return true;
					}
					// right wall
					if ((newBlock[i][1] + curX + moveX) > 9) {
						return true;
					}
					// below
					if (f[newBlock[i][0] + curY + moveY][newBlock[i][1] + curX + moveX] != 0) {
						if (curY == 0) {
							gameOver();
							return true;
						}
						return true;
					}
				}
				return false;
			}
			
			function render() {
				if (!gameRunning || paused) {
					return;
				}
				unPaint();
				moveDown();
				doPaint();
			}
			
			function doPaint() {
				for (i = 0; i < 4; i++) {
					setColor(curBlock[i][1] + curX, curBlock[i][0] + curY, numberToColor(curC));
				}
			}
			
			function unPaint() {
				for (i = 0; i < 4; i++) {
					setColor(curBlock[i][1] + curX, curBlock[i][0] + curY, numberToColor(0));
				}
			}
			
			function checkAndDeleteRows() {
				forDelete = new Array();
				
				for (y = 0; y < 19; y++) {
					isFull = true;
					for (x = 0; x < 10; x++) {
						if (f[y][x] == 0) {
							isFull = false;
							break;
						}
					}
					if (isFull) {
						forDelete.push(y);
					}
				}
				
				if (forDelete.length > 0) {
					for (i = 0; i < forDelete.length; i++) {
						for (x = 0; x < 10; x++) {
							f[forDelete[i]][x] = 0;
						}
						for (y = forDelete[i]; y > 0; y--) {
							for (x = 0; x < 10; x++) {
								f[y][x] = f[y-1][x];
							}
						}
					}
					rePaintAll();
					
					addScore(Math.pow(forDelete.length, 2) * 100 * gameLevel);
					linesCleared = linesCleared + forDelete.length;
					linesClearedOverall = linesClearedOverall + forDelete.length;
					checkLevel();
					
					if (forDelete.length > 0) {
						attacks.push(forDelete.length);
					}
				}
			}
			
			function checkLevel() {
				if (linesCleared >= 10) {
					linesCleared = linesCleared - 10;
					setLevel(gameLevel + 1);
				}
				$("#linesCleared").html(linesClearedOverall);
			}
			
			function rePaintAll() {
				for (y = 0; y < 19; y++) {
					for (x = 0; x < 10; x++) {
						setColor(x, y, numberToColor(f[y][x]));
					}
				}
			}
			
			function setColor(x, y, color) {
				$("#" + x + "_" + y).css('background-color', color);
			}
			
			function setNextBlock(blockNr, blockColor) {
				for (y = 0; y < 4; y++) {
					for (x = 0; x < 3; x++) {
						for (i = 0; i < 4; i++) {
							if(block[blockNr][i][0]+1 == x & block[blockNr][i][1]+1 == y) {
								$("#next" + y + "_" + x).css('background-color', numberToColor(blockColor));
								break;
							} else {
								$("#next" + y + "_" + x).css('background-color', numberToColor(0));
							}
						}
					}
				}
			}
			
			function numberToColor(number) {
				colors = new Array();
				colors[0] = '#FFFFFF';
				colors[1] = '#666666';
				colors[2] = '#FF0000';
				colors[3] = '#00FF00';
				colors[4] = '#0000FF';
				colors[5] = '#FFCC00';
				colors[6] = '#00FFFF';
				colors[7] = '#FF00FF';
				return colors[number];
			}
			/*
			function getRandom(min, max) {
				if( min > max ) {
					return( -1 );
				}
				if( min == max ) {
					return( min );
				}
				return( min + parseInt( Math.random() * ( max-min+1 ) ) );
			}
			*/
			
			function RandomNumberGenerator(seed) {
				var keySchedule = [];
				var keySchedule_i = 0;
				var keySchedule_j = 0;
				
				function init(seed) {
					for (var i = 0; i < 256; i++)
						keySchedule[i] = i;
					
					var j = 0;
					for (var i = 0; i < 256; i++)
					{
						j = (j + keySchedule[i] + seed.charCodeAt(i % seed.length)) % 256;
						
						var t = keySchedule[i];
						keySchedule[i] = keySchedule[j];
						keySchedule[j] = t;
					}
				}
				init(seed);
				
				function getRandomByte() {
					keySchedule_i = (keySchedule_i + 1) % 256;
					keySchedule_j = (keySchedule_j + keySchedule[keySchedule_i]) % 256;
					
					var t = keySchedule[keySchedule_i];
					keySchedule[keySchedule_i] = keySchedule[keySchedule_j];
					keySchedule[keySchedule_j] = t;
					
					return keySchedule[(keySchedule[keySchedule_i] + keySchedule[keySchedule_j]) % 256];
				}
				
				this.getRandomNumber = function() {
					var number = 0;
					var multiplier = 1;
					for (var i = 0; i < 8; i++) {
						number += getRandomByte() * multiplier;
						multiplier *= 256;
					}
					return number / 18446744073709551616;
				}
			}

			function getRandom(min, max) {
				if( min > max ) {
					return( -1 );
				}
				if( min == max ) {
					return( min );
				}
				return(min + Math.floor(RandomNumber.getRandomNumber() * (max-min+1)));
			}
 
			function levelToInterval(lvl) {
				interval = (800 / (Math.pow(lvl, 2/3)));
				return interval;
			}
			
			function showHighscoreTab() {
				$("#player").css('display', 'none');
				$("#highscore").css('display', 'block');
				$("#highscore").load('ajax_request.php?mod=tetris&file=highscore');
			}
			
			function showPlayerTab() {
				$("#player").css('display', 'block');
				$("#highscore").css('display', 'none');
			}
			
			function addRows(rows) {
				for (y = 0; y < 19 - rows; y++) {
					for (x = 0; x < 10; x++) {
						f[y][x] = f[y+rows][x];
					}
				}
				randX = getRandom(0, 9);
				for (y = 18; y > 18 - rows; y--) {
					for (x = 0; x < 10; x++) {
						if (x != randX) {
							f[y][x] = getRandom(1, 7);
						} 
						else {
							f[y][x] = 0;
						}
					}
				}
				rePaintAll();
			}
			
			function chatSend() {
				$.post("ajax_request.php?mod=tetris&file=chat&nickname={/literal}{$nick}{literal}",
					{ text: $("#chatText").val() },
					function(data) {
						$("#chat").html(data);
					});
				updateChat();
				$("#chatText").val("");
			}
			
			function updateChat() {
				$("#chat").load("ajax_request.php?mod=tetris&file=chat");
			}
			
			function makeWindowBlink(msg) {
				var oldTitle = document.title;
				var timeoutId = setInterval(function() {
					document.title = document.title == msg ? oldTitle : msg;
				}, 1000);
				window.onmousemove = function() {
					clearInterval(timeoutId);
					document.title = oldTitle;
					window.onmousemove = null;
				}
				window.onkeydown = function() {
					clearInterval(timeoutId);
					document.title = oldTitle;
					window.onmousemove = null;
				}
			}
			
			function increaseField(amount) {
				var value = $('#blocksize').val();
				value = parseInt(value) + amount;
				$('#blocksize').val(value);
				$('.tetris_game').width(value);
				$('.tetris_game').height(value);
			}
			
			function decreaseField(amount) {
				var value = $('#blocksize').val();
				value = parseInt(value) - amount;
				$('#blocksize').val(value);
				$('.tetris_game').width(value);
				$('.tetris_game').height(value);
			}
			
			function checkTetrisStr(textbox, keyCode) {
				if (keyCode == 13) { // Enter Pressed
					chatSend();
				}
			}
			
		</script>
		<style type="text/css">
			.count_blocks {
				width:10px; 
				height:10px; 
				border:1px solid #FFFFFF;
			}
			
			.count_blocks_small {
				width:4px; 
				height:4px; 
				border:1px solid #FFFFFF;
			}
			
			.block_0 {
				background-color:#666666;
			}
			.block_1 {
				background-color:#FF0000;
			}
			.block_2 {
				background-color:#00FF00;
			}
			.block_3 {
				background-color:#0000FF;
			}
			.block_4 {
				background-color:#FFCC00;
			}
			.block_5 {
				background-color:#00FFFF;
			}
			.block_6 {
				background-color:#FF00FF;
			}		
			
			.chat_type_0 {
				color: #555555;
			}
			
			.chat_type_1 {
				color: #888888;
			}
			
			.chat_type_10 {
				color: #000000;
			}
			
			.minipreview {
				width:3px; 
				height:3px; 
			}
			
			.tetris_game {
				width:12px; 
				height:12px; 
				border:1px solid #CCC;
			}
		</style>
{/literal}

<input type="hidden" id="blocksize" value="12" />

<div align="center" style="padding-top:15px;">
	<div align="left" class="main">
		
		<div class="headline">Multiplayer Tetris</div>
		
		<table border="0" cellpadding="3" cellspacing="0" width="100%">
			<tr>
				<td valign="top" align="center">
					<table style="border-collapse:collapse; border:1px solid #CCC;" cellpadding="0" cellspacing="0">
						{section name=y loop=$field}
							<tr>
								{section name=x loop=$field[y]}
									<td class="tetris_game" id="{$field[y][x]}"></td>
								{/section}
							</tr>
						{/section}
					</table>
				</td>
				<td style="vertical-align:top;" rowspan="2">
					Next:
					<table style="border-collapse:collapse; border:1px solid #FFF;" cellpadding="0" cellspacing="0">
						{section name=y start=0 loop=3}
							<tr>
								{section name=x start=0 loop=4}
									<td style="width:12px; height:12px; border:1px solid #FFF;" id="next{$smarty.section.x.index}_{$smarty.section.y.index}">
									</td>
								{/section}
							</tr>
						{/section}
					</table>
				</td>
				<td width="50%" align="left" valign="top" rowspan="2">
					<table width="100%" border="0">
						<tr>
							<td colspan="2">
								<div id="master" style="display:none; width:100%;">
									<table width="100%" border="0">
										<tr>
											<td align="center">
												<input type="button" id="startButton" onClick="sendStart();" value="Start Game" />
											</td>
											<td align="center">
												<span id="masteradmin" style="display:none;">
													<input type="button" id="deleteMaster" onClick="deleteMaster();" value="Master l&ouml;schen" />
												</span>
											</td>
										</tr>
									</table>
									<!--<div id="paused" align="right" style="color:#FF0000;">&nbsp;</div>!-->
									<hr size="1" />
								</div>
							</td>
						</tr>
						<tr>
							<td width="150">Level:</td>
							<td><div id="level" align="right">1</div></td>
						</tr>
						<tr>
							<td>Score:</td>
							<td><div id="score" align="right">0</div></td>
						</tr>
						<tr>
							<td>Lines Cleared:</td>
							<td><div id="linesCleared" align="right">0</div></td>
						</tr>
					</table>
					<hr size="1" />
					<table style="border-collapse:collapse;" align="center" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td class="count_blocks_small"></td>
							<td class="count_blocks"></td>
							<td class="count_blocks"></td>
							<td class="count_blocks_small"></td>
							
							<td class="count_blocks_small"></td>
							<td class="count_blocks"></td>
							<td class="count_blocks"></td>
							<td class="count_blocks_small"></td>
							
							<td class="count_blocks_small"></td>
							<td class="count_blocks"></td>
							<td class="count_blocks"></td>
							<td class="count_blocks_small"></td>
							
							<td class="count_blocks_small"></td>
							<td class="count_blocks"></td>
							<td class="count_blocks"></td>
							<td class="count_blocks"></td>
							<td class="count_blocks_small"></td>
							
							<td class="count_blocks_small"></td>
							<td class="count_blocks"></td>
							<td class="count_blocks"></td>
							<td class="count_blocks"></td>
							<td class="count_blocks_small"></td>
							
							<td class="count_blocks_small"></td>
							<td class="count_blocks"></td>
							<td class="count_blocks"></td>
							<td class="count_blocks"></td>
							<td class="count_blocks_small"></td>
							
							<td class="count_blocks_small"></td>
							<td class="block_6 count_blocks"></td>
							<td class="count_blocks_small"></td>
						</tr>
						<tr>
							<td class="count_blocks_small"></td>
							<td class="block_0 count_blocks"></td>
							<td class="block_0 count_blocks"></td>
							<td class="count_blocks_small"></td>
							
							<td class="count_blocks_small"></td>
							<td class="count_blocks"></td>
							<td class="block_1 count_blocks"></td>
							<td class="count_blocks_small"></td>
							
							<td class="count_blocks_small"></td>
							<td class="block_2 count_blocks"></td>
							<td class="count_blocks"></td>
							<td class="count_blocks_small"></td>
							
							<td class="count_blocks_small"></td>
							<td class="count_blocks"></td>
							<td class="block_3 count_blocks"></td>
							<td class="count_blocks"></td>
							<td class="count_blocks_small"></td>
							
							<td class="count_blocks_small"></td>
							<td class="count_blocks"></td>
							<td class="block_4 count_blocks"></td>
							<td class="block_4 count_blocks"></td>
							<td class="count_blocks_small"></td>
							
							<td class="count_blocks_small"></td>
							<td class="block_5 count_blocks"></td>
							<td class="block_5 count_blocks"></td>
							<td class="count_blocks"></td>
							<td class="count_blocks_small"></td>
							
							<td class="count_blocks_small"></td>
							<td class="block_6 count_blocks"></td>
							<td class="count_blocks_small"></td>
						</tr>
						<tr>
							<td class="count_blocks_small"></td>
							<td class="block_0 count_blocks"></td>
							<td class="block_0 count_blocks"></td>
							<td class="count_blocks_small"></td>
							
							<td class="count_blocks_small"></td>
							<td class="count_blocks"></td>
							<td class="block_1 count_blocks"></td>
							<td class="count_blocks_small"></td>
							
							<td class="count_blocks_small"></td>
							<td class="block_2 count_blocks"></td>
							<td class="count_blocks"></td>
							<td class="count_blocks_small"></td>
							
							<td class="count_blocks_small"></td>
							<td class="block_3 count_blocks"></td>
							<td class="block_3 count_blocks"></td>
							<td class="block_3 count_blocks"></td>
							<td class="count_blocks_small"></td>
							
							<td class="count_blocks_small"></td>
							<td class="block_4 count_blocks"></td>
							<td class="block_4 count_blocks"></td>
							<td class="count_blocks"></td>
							<td class="count_blocks_small"></td>
							
							<td class="count_blocks_small"></td>
							<td class="count_blocks"></td>
							<td class="block_5 count_blocks"></td>
							<td class="block_5 count_blocks"></td>
							<td class="count_blocks_small"></td>
							
							<td class="count_blocks_small"></td>
							<td class="block_6 count_blocks"></td>
							<td class="count_blocks_small"></td>
						</tr>
						<tr>
							<td class="count_blocks_small"></td>
							<td class="count_blocks"></td>
							<td class="count_blocks"></td>
							<td class="count_blocks_small"></td>
							
							<td class="count_blocks_small"></td>
							<td class="block_1 count_blocks"></td>
							<td class="block_1 count_blocks"></td>
							<td class="count_blocks_small"></td>
							
							<td class="count_blocks_small"></td>
							<td class="block_2 count_blocks"></td>
							<td class="block_2 count_blocks"></td>
							<td class="count_blocks_small"></td>
							
							<td class="count_blocks_small"></td>
							<td class="count_blocks"></td>
							<td class="count_blocks"></td>
							<td class="count_blocks"></td>
							<td class="count_blocks_small"></td>
							
							<td class="count_blocks_small"></td>
							<td class="count_blocks"></td>
							<td class="count_blocks"></td>
							<td class="count_blocks"></td>
							<td class="count_blocks_small"></td>
							
							<td class="count_blocks_small"></td>
							<td class="count_blocks"></td>
							<td class="count_blocks"></td>
							<td class="count_blocks"></td>
							<td class="count_blocks_small"></td>
							
							<td class="count_blocks_small"></td>
							<td class="block_6 count_blocks"></td>
							<td class="count_blocks_small"></td>
						</tr>
						<tr>
							<td style="text-align:center;" colspan="4" id="block_0">0</td>
							<td style="text-align:center;" colspan="4" id="block_1">0</td>
							<td style="text-align:center;" colspan="4" id="block_2">0</td>
							<td style="text-align:center;" colspan="5" id="block_3">0</td>
							<td style="text-align:center;" colspan="5" id="block_4">0</td>
							<td style="text-align:center;" colspan="5" id="block_5">0</td>
							<td style="text-align:center;" colspan="3" id="block_6">0</td>
						</tr>
					</table>
					<input type="text" id="chatText" style="width:100%;"  onfocus="toggleFocus(true);" onblur="toggleFocus(false);"
						onKeyDown="checkTetrisStr(this, -1)" onKeyUp="checkTetrisStr(this, event.keyCode)" />
					<div id="chat" style="overflow:scroll; height:80px; width:100%; border:1px solid #CCC;">&nbsp;</div>
				</td>
			</tr>
			<tr>
				<td>
					<div style="text-align:right; font-size:1.5em;">
						<a href="javascript:void(0);" onClick="increaseField(2); return false;">+</a>
						<a href="javascript:void(0);" onClick="decreaseField(2); return false;">-</a>
					</div>
				</td>
			</tr>
		</table>		
		
		<div>
			<div>
				<a href="javascript:void(0);" onClick="showPlayerTab();">Player</a> |
				<a href="javascript:void(0);" onClick="showHighscoreTab();">Highscore</a>
			</div>
			
			<div id="player">&nbsp;
				<input type="hidden" name="uniquid" id="uniquid" value="{$uniquid}" />
			</div>
			<div id="highscore" style="display:none;">&nbsp;</div>
		</div>
		
		<div style="color:#ACACAC;">
			<p>
				<a href="javascript:void(0);" onclick="$('#help').toggle();">Help</a>
			</p>
			<div id="help" style="display:none;">
				<p>
					<table>
						<colgroup>
							<col width="100px" />
							<col width="100px" />
							<col width="100px" />
							<col width="100px" />
						</colgroup>
						<tr>
							<th>
								&nbsp;
							</th>
							<th>
								Keys #1
							</th>
							<th>
								Keys #2
							</th>
							<th>
								Keys #3
							</th>
						</tr>
						<tr>
							<th>
								Move left
							</th>
							<td>
								Arrow Left
							</td>
							<td>
								A
							</td>
							<td>
								J
							</td>
						</tr>
						<tr>
							<th>
								Move Right
							</th>
							<td>
								Arrow Right
							</td>
							<td>
								D
							</td>
							<td>
								L
							</td>
						</tr>
						<tr>
							<th>
								Rotate
							</th>
							<td>
								Arrow Up
							</td>
							<td>
								W
							</td>
							<td>
								I
							</td>
						</tr>
						<tr>
							<th>
								Drop Slow
							</th>
							<td>
								Arrow Down
							</td>
							<td>
								Q
							</td>
							<td>
								K
							</td>
						</tr>
						<tr>
							<th>
								Drop Fast
							</th>
							<td>
								Enter
							</td>
							<td>
								S
							</td>
							<td>
								U
							</td>
						</tr>
					</table>
				</p>
				<p>
					When you successfully remove two or more lines, a randomly chosen other player gets these lines minus one additionaly. In further level an attack bonus is given.
				</p>
			</div>
		</div>
		
		<div align="right">
			<object type="application/x-shockwave-flash" data="mod/default/tetris/emff_silk_button.swf" width="16" height="16">
				<param name="movie" value="mod/default/tetris/emff_silk_button.swf" />
				<param name="FlashVars" value="mod/default/tetris/src=tetris.mp3&amp;autostart=no" />
			</object>
		</div>
	</div>
</div>
</div>