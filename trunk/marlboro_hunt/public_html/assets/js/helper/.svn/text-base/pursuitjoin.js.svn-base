/*trade Floor*/

	
	
	$(document).on('click','.showpopuptradingfloor',function(){
		
		$.post(basedomain+'pursuit/ajax', {popuptradingfloor:true}, function(data){
			var html = "";
			if (data.status == true){
				
				html += generateHtmlTradeFloor(data.rec);
				
			}else{
				html += "Trade not found";
			}
			
			var searchFr = "";
			
			// searchFr += "<form class='searchPlayer'>";
			searchFr += "<input type='text' value='' id='searchTrading' class='searchTrading'/>";
            searchFr += "<input type='submit' value='SEARCH' class='searchDataTrading'/>";
			// searchFr += "</form>";
					
			$(".trading-floor").trigger("click");
			$(".framePagingTrade").attr('total', data.total);
			$(".searchBoxContent").html(searchFr);	
			$(".titlePopupTradealetter").html(locale.tradealatter);	
			$(".contentTradingFloor").html(html);
			$(".titlePopup").html(locale.tradingfloor);
			
			
		},"JSON")
	})
	

	$(document).on('click','.searchDataTrading',function(){
		
		var keywords = $('.searchTrading').val();
		// console.log(keywords);
		$.post(basedomain+'search/tradingfloor', {keywords:keywords}, function(data){
			var html = "";
			// console.log(data);
			if (data.status == true){
				html += generateHtmlTradeFloor(data.rec);
				
			}else{
				
				html += "Trade not found";
			}
			
					
			$(".contentTradingFloor").html(html);
			
		}, "JSON")
	})

	
	function generateHtmlTradeFloor(data)
	{
		var rec = data;
		var html = "";
		html += "<table width='100%' border='0' cellspacing='0' cellpadding='0'>";
		html += "<thead>";
		html += "  <tr>";
		html += "    <th class='usertrade'><h3>Players</h3></th>";
		html += "    <th class='letter-looking'><h3>Looking For</h3></th>";
		html += "    <th class='letter-totrade'><h3>To Trade With</h3></th>";
		html += "    <th>&nbsp;</th>";
		html += "  </tr>";
		html += "</thead>";
		html += "<tbody class='listTradingFloor'>";
			$.each(rec, function(i,e){
				html += "  <tr>";
				html += "    <td class='usertrade'>";
				if (e.image_profile && e.photo_moderation ==1){
					html += "		 <a class='thumb-small fl'><img src='"+basedomain+"public_assets/user/photo/"+e.image_profile+"'/></a>";
				}else{
					html += "		 <a class='thumb-small fl'><img src='"+basedomain+"assets/content/thumb_user.jpg'/></a>";
				}

				html += "        <div class='entry-left fl'>";
				html += "            <h4 class='username'>"+e.name+"</h4>";
				html += "        </div>";
				html += "    </td>";
				html += "    <td class='letter-looking'><img src='"+basedomain+"assets/content/tiles/"+e.targetCodeName.toLowerCase()+".png' /></td>";
				html += "    <td class='letter-totrade'><img src='"+basedomain+"assets/content/tiles/"+e.sourceCodeName.toLowerCase()+".png' /></td>";
				html += "    <td>";
				
				// html += "		<a href='javascript:void(0)' class='btn_black canceltrade ' prop='"+e.id+"'>Cancel</a>";
				html += "		<a href='javascript:void(0)' class='btn_black showpopuptradeLetter' prop='"+e.id+"'>Trade</a>";
				html += "	</td>";
				html += "  </tr>";
			})
		html += "</tbody>";
		html += "</table>";
		
		return html;
	}
	
	/* paging trading floor */
	
	$(document).on('click','.nextpageTradeFloor',function(){
		
		var start = parseInt($(".framePagingTrade").attr("startpage"),10)+6;
		var totaltrade = parseInt($(".framePagingTrade").attr("total"));
		
		if(start < totaltrade){
			$.post(basedomain+'pursuit/ajax', {popuptradingfloor:true, start:start}, function(data){
			var html = "";
			if (data.status == true){
				
				html += generateHtmlTradeFloor(data.rec);
				
			}else{
				html += "Trade not found";
			}
			
			var searchFr = "";
			
			// searchFr += "<form class='searchPlayer'>";
			searchFr += "<input type='text' value='' id='searchTrading' class='searchTrading'/>";
            searchFr += "<input type='submit' value='SEARCH' class='searchDataTrading'/>";
			// searchFr += "</form>";
			
			$(".contentTradingFloor").html(html);
			$(".framePagingTrade").attr("startpage", start);
			},"JSON")
		
		}
		
		
	})
	
	$(document).on('click','.prevpageTradeFloor',function(){
		
		var start = parseInt($(".framePagingTrade").attr("startpage"),10)-6;
		var totaltrade = parseInt($(".framePagingTrade").attr("total"));
		
		if(start>=0){
			$.post(basedomain+'pursuit/ajax', {popuptradingfloor:true, start:start}, function(data){
				var html = "";
				if (data.status == true){
					
					html += generateHtmlTradeFloor(data.rec);
					
				}else{
					html += "Trade not found";
				}
				
				var searchFr = "";
				
				// searchFr += "<form class='searchPlayer'>";
				searchFr += "<input type='text' value='' id='searchTrading' class='searchTrading'/>";
				searchFr += "<input type='submit' value='SEARCH' class='searchDataTrading'/>";
				// searchFr += "</form>";
				
				$(".contentTradingFloor").html(html);
				$(".framePagingTrade").attr("startpage", start);
			},"JSON")
		
		}
		
		
	})
/*tombol trade*/

	
	$(document).on('click','.showpopuptradeLetter',function(){
		var tradeid = $(this).attr('prop');
		$.post(basedomain+'pursuit/ajax', {popuptradeletter:true, idtrade:tradeid}, function(data){
			var html = "";
			var e = data.rec;
			if (data.status == true){
				
				
				html += "<li class='col2'>";
                html += "    <div class='entry-popup'>";
                html += "    	<div class='usertrade'>";
                //html += "            <a class='thumb-small fl'><img src='"+basedomain+"assets/content/thumb_user.jpg' /></a>";
				if (e.mypicture && e.photo_moderation ==1){
					html += "		 <a class='thumb-small fl'><img src='"+basedomain+"public_assets/user/photo/"+e.mypicture+"'/></a>";
				}else{
					html += "		 <a class='thumb-small fl'><img src='"+basedomain+"assets/content/thumb_user.jpg'/></a>";
				}
                
                html += "            <div class='entry-left fl'>";
                html += "                <h4 class='username'>"+e.myname+"</h4>";
                html += "            </div>";
                html += "        </div>";
                html += "        <div class='preview-box'>";
                html += "        	<img src='"+basedomain+"assets/content/tiles/"+e.sourceCodeName.toLowerCase()+".png' />";
                html += "        </div>";
          		html += "		<div class='entry-popup-center'>";
                // html += "        	<p>yeah here you go!</p>";
                html += "        </div>";
          		html += "    </div>";
                html += "</li>";
                html += "<li class='col2'>";
                html += "    <div class='entry-popup'>";
                html += "    	<div class='usertrade'>";
                //html += "            <a class='thumb-small fl'><img src='"+basedomain+"assets/content/thumb_user.jpg' /></a>";
				if (e.image_profile && e.photo_approved==1){
					html += "		 <a class='thumb-small fl'><img src='"+basedomain+"public_assets/user/photo/"+e.image_profile+"'/></a>";
				}else{
					html += "		 <a class='thumb-small fl'><img src='"+basedomain+"assets/content/thumb_user.jpg'/></a>";
				}

                html += "            <div class='entry-left fl'>";
                html += "                <h4 class='username'>"+e.name+"</h4>";
                html += "            </div>";
                html += "        </div>";
                html += "        <div class='preview-box'>";
                html += "        	<img src='"+basedomain+"assets/content/tiles/"+e.targetCodeName.toLowerCase()+".png' />";
                html += "        </div>";
          		html += "		<div class='entry-popup-center'>";
               	html += "		 <a href='javascript:void(0)' class='btn_black3 confirmTrade' prop='"+e.id+"'>Confirm Trade</a>";
                html += "        </div>";
          		html += "    </div>";
                html += "</li>";
				
				$(".trade-confirm").trigger("click");		
				$(".confirmContent").html(html);
				$(".buttonFrame").html("");
				$(".confirmTitle").html(locale.traderequest);
				
			}else{
				html += "<h1>Trade not allowed</h1>";
				
				$(".post-trade-message").trigger("click");		
				$(".postradecontent").html(html);
				
			}
			
			
			
			
		},"JSON")
	})
	
	$(document).on('click','.confirmTrade', function(){
		
		var tradeid = $(this).attr('prop');
		
		$.post(basedomain+'pursuit/ajax',{confirmTrade:true, id:tradeid}, function(data){
			
			var status = data.status;
			var html = "";
			
			if (status == true){
				
				var target = data.getrade;
				var source = data.user;
				
				var letterTarget = target.targetCodeName.toLowerCase();
				
					html += "<ul class='columns-2 clearfix'>";
					html += "<li class='col2'>";
                    html += "<div class='entry-popup'>";
                    html += "	<div class='usertrade'>";
					if (source.image_profile && source.photo_moderation==1){
						html += "		 <a class='thumb-small fl'><img src='"+basedomain+"public_assets/user/photo/"+source.image_profile+"'/></a>";
					}else{
						html += "        <a class='thumb-small fl'><img src='"+basedomain+"assets/content/thumb_user.jpg' /></a>";
					}
                    
                    html += "        <div class='entry-left fl'>";
                    html += "            <h4 class='username'>"+source.name+"</h4>";
                    //html += "            <h4 class='username'>coba</h4>";
                    html += "        </div>";
                    html += "    </div>";
                    html += "    <div class='preview-box'>";
                    html += "    	<img src='"+basedomain+"assets/content/tiles/"+target.sourceCodeName.toLowerCase()+".png' />";
                    //html += "    	<img src='"+basedomain+"assets/content/font/a.png' />";
                    html += "    </div>";
          			html += "	<div class='entry-popup-center'>";
                    html += "    </div>";
          		    html += "</div>";
					html += "</li>";
					html += "<li class='col2'>";
                    html += "<div class='entry-popup'>";
                    html += "	<div class='usertrade'>";
					if (target.image_profile && target.photo_moderation==1){
						html += "		 <a class='thumb-small fl'><img src='"+basedomain+"public_assets/user/photo/"+target.image_profile+"'/></a>";
					}else{
						html += "        <a class='thumb-small fl'><img src='"+basedomain+"assets/content/thumb_user.jpg' /></a>";
					}
					
                    html += "        <div class='entry-left fl'>";
                    html += "            <h4 class='username'>"+target.name+"</h4>";
                    //html += "            <h4 class='username'>ovan</h4>";
                    html += "        </div>";
                    html += "    </div>";
                    html += "    <div class='preview-box'>";
                    html += "    	<img src='"+basedomain+"assets/content/tiles/"+letterTarget+".png' />";
                    //html += "    	<img src='"+basedomain+"assets/content/font/b.png' />";
                    html += "    </div>";
          			html += "	<div class='entry-popup-center'>";
               		html += "	<h3>you have successfully traded letters.</h3>";
                    html += "    </div>";
          		    html += "</div>";
					html += "</li>";
					html += "</ul>";
			}else{
				html += "<h3>Your trade not match</h3>";
			}
			
			$(".trade-confirm").trigger("click");		
			$(".confirmContent").html(html);
			$(".confirmTitle").html("Trade Request");
			
			$(document).on('click', '#fancybox-close , #fancybox-overlay', function(){
				
				location.href = basedomain+"pursuit/join";
				
			})
			
		}, "JSON");
	})
	


/*untuk submit code dari task list*/

	
	
	
	// $(document).on('click', '#fancybox-close , #fancybox-overlay', function(){
		
		// location.href = basedomain+"pursuit/join";
		
	// })
	
	$(document).on('click', '.gettask', function(){
		
		$(".tasksmallcontent").html('');
		$(".tasksmalltitle").html('');
			
			
		var idTask = $(this).attr('prop');
	
		$(".tasksmalltitle").html(locale.pleasewait);
		$.post(basedomain+'pursuit/task',{taskdetail:true,id:idTask},function(data){
			var html = "";
			if(data.status){
				var articletype = data.rec.result[0].articleType;
				var url ="javascript:void(0)";
				
				/* type task for url */
				if(articletype==6) url = basedomain+"games/maybeninja";
				if(articletype==22) url = basedomain+"games/wallbreaker";
				if(articletype==24) url = basedomain+"games/doubtcrasher";
				if(articletype==25) url = basedomain+"games/wordhunt";
				if(articletype==19) url = "javascript:void(0)";
				if(articletype==21) url = "javascript:void(0)";
				if(articletype==18) url = basedomain+"inputcode";
				if(articletype==23) url = basedomain+"thisorthat";

				html += "<h4>"+data.rec.result[0].content+"</h4>";
				if(articletype==21) html += "<p style='font-family:Arial, Helvetica, sans-serif; font-size:11px;'>Please be reminded that as per the <a class='showPopup popuptnc' href='#popup-tnc'>Terms and Conditions</a>, all photos, images or designs submitted will become the property of PM. Please do not upload or submit any photos, images or designs depicting the act of smoking or showing a lit cigarette or minors (persons less than 18 years old). PM reserves to the right to remove or reject any photo, image or design which it, in its sole and absolute discretion, deems offensive, violates the law or is unsuitable in any other manner. The photo you upload will be subject to moderation.</p>";
				html += "    		<div class='row'>";
				if(articletype==19) html += "  <a  class='button btn_red fl closebutton' href='"+url+"' >close</a>";
				else html += "            <a  class='button btn_red fl' href='"+url+"' >"+data.rec.result[0].title+"</a>";
				html += "            </div>";
	
								
				$(".tasksmalltitle").html(data.rec.result[0].title);
			}
			$(".tasksmallcontent").html(html);
		},"JSON");
		
		$(".task-small-master").trigger("click");
		
		
		
	});
	
	$(document).on('click','.closebutton',function(){
	
		$('#fancybox-close').trigger('click');
	});
	
	
	


/*untuk cancel trade dari list sebelah kanan*/

	
	$(document).on('click','.cancelTrade', function(){
		
		var tradeid = $(this).attr('prop');
		
		$.post(basedomain+'pursuit/ajax',{cancelTrade:tradeid}, function(data){
			
			var status = data.status;
			var html = "";
			
			if (status == true){
				html += "<h2>"+locale.tradecancel+"</h2>";
			}else{
				html += "<h2>"+locale.tradenotcancel+"</h2>";
			}
			

			$(".post-trade-message").trigger("click");			
			$(".postradecontent").html(html);
			
			refreshTradewhenDelete();
			
		}, "JSON");
		
		$(document).on('click','#fancybox-close, #fancybox-overlay', function(){
				window.location = basedomain+'pursuit/join';
				//$("#fancybox-close").trigger('click');
		});
		$("#fancybox-outer").addClass('min102');
		
	})
	
	function refreshTradewhenDelete()
	{
		$.post(basedomain+'pursuit/task', {mytrade:true}, function(data){
			var html = "";
			
			if (data.status == true){
				
					$.each(data.rec, function(i,e){
		
					html += "<div class='row'>";
					html += "<ul class='columns-3 clearfix'>";
					html += "<li class='col3'>";
                	html += "<h4>Looking For</h4>";
					html += "<a class='thumb-small fl'><img src='"+basedomain+"assets/content/tiles/"+e.toCode.toLowerCase()+".png' /></a>";
					html += "</li>";
					html += "<li class='col3'>";
                	html += "<h4>To Trade With</h4>";
					html += "<a class='thumb-small fl'><img src='"+basedomain+"assets/content/tiles/"+e.fromCode.toLowerCase()+".png' /></a>";
					html += "</li>";
					html += "<li class='col3'>";
					if (e.n_status == 1){
                	html += "<a class='icon_check' href='javascript:void(0)'>&nbsp;</a>";
					}else{
					html += "<a class='icon_cross cancelTrade' href='javascript:void(0)' prop='"+e.id+"'>&nbsp;</a>";
					}
					html += "</li>";
					html += "</ul>";

					html += "</div>";

					})
					
				
				
				
			}else{
				html += "No trade";
			}
			
			$(".mytradeList").html(html);
			
		},"JSON")
	}
	
	



	
	$(document).on('click','.getradefloor', function(){
		
		var tradeid = $(this).attr('prop');
		
		$.post(basedomain+'pursuit/ajax',{getTrade:tradeid}, function(data){
			
			var status = data.status;
			var html = "";
			
			if (status == true){
				// console.log(true);
				
			}else{
				html += "<h1>"+locale.failed+"</h1>";
				// console.log(false);
			}
			
			$(".pursuit-popup").trigger("click");			
			$(".contentpursuit").html(html);
			$(".titlepursuit").html(locale.traderequest);
			//$(".popupContainer").attr('id', 'popup-trade-open');
			
			
			
			//$(".popupContainer").addclass('popupBlackPaper');
		}, "JSON");
	})
	
	$(document).on('click','.canceltrade',function(){
		
		var tradeid = $(this).attr('prop');
		$.post(basedomain+'pursuit/ajax',{cancelTrade:tradeid}, function(data){
			var statusCancel = data.status;
			html = "";
			if (statusCancel == true){
				html += "<h2>"+locale.successdelete+"</h2>";
			}else{
				html += "<h2>"+locale.failedtodelete+"</h2>";
			}
			$(".trigun").trigger("click");
			$(".msgpopupglobal").html(html);
		},'JSON')
	})
	
	$(document).on('click', '.sendTradereq', function(){
		var targetcode = $(".targetid").attr('prop');
		var sourcecode = $(".sourceid").val();
		var targetid = $(".userTargetid").val();
		var tradeMesg = $(".tradeMesg").val();
		
		
		$.post(basedomain+'pursuit/ajax',{sendTradeReq:true, codefrom:sourcecode, codeto:targetcode, msg:tradeMesg, idtarget:targetid}, function(data){
			
			var statusSend = data.status;
			// console.log(statusSend);
			
		},"JSON")
		
	})
	




/*Untuk search Pursuit player */

	
	
	$(document).on('click','.searchPursuitPlayer',function(){
		var searchKeywords = $('#searchPlayerbox').val();
		
		$.post(basedomain+'search/friends', {keywords:searchKeywords}, function(data){
			var html = "";
			
			if (data.status == true){
				$.each(data.rec, function(i,e){
					//console.log(e.name);
					html += "<div class='boxPlayer'>";
					//html += " <a class='thumb-small'><img src='"+basedomain+"public_assets/user/photo/"+e.image_profile+"'/></a>";
					if (e.image_profile){
						html += "		 <a class='thumb-small'><img src='"+basedomain+"public_assets/user/photo/"+e.image_profile+"'/></a>";
					}else{
						html += "		 <a class='thumb-small'><img src='"+basedomain+"assets/content/thumb_user.jpg'/></a>";
					}

					html += " <p class='username'>"+e.name+"</p>";
					//html += " <a class='icons icon_trade' aria-hidden='true' data-icon='&#xe009;' href='#'>&nbsp;</a>";
					html += " <a class='icons icon_message popup-trade-message' aria-hidden='true' data-icon='&#xe01c;' href='javascript:void(0)' prop='"+e.id+"' desc='"+e.name+"'>&nbsp;</a>";
					html += "</div>";
				})
				
				
			}else{
				html += locale.playernotfound;
			}
			
			$(".listPlayerPursuit").html(html);
			
		},"JSON")
	})
	



/*right pursuit task list*/

	
	
	$(document).on('click','.tasklistpursuit',function(){
		
		$.post(basedomain+'pursuit/task', {tasklist:true}, function(data){
			var html = "";
			
			if (data.status == true){
				
					$.each(data.rec, function(i,e){
					
						
					var hmtltask = "<a class='greyBtn gettask'  href='javascript:void(0)' prop='"+e.id+"'>GO</a>";
					var levels = 'Easy';
					if(e.topcontent==1) levels = 'Medium';
					if(e.topcontent>1) levels = 'Hard';
					
					html += "<div class='row'>";
					//html += "		<a class='thumb-small fl'><img src='"+e.image+"' /></a>";
					html += "		<div class='entry-left fl'>";
					html += "			<h6>";
								
					
					if (e.articleType == 20) hmtltask = "<a class='greyBtn showPopup' href='#popup-invitefriend' prop='"+e.id+"'>GO</a>";			
					//if (e.articleType == 18) hmtltask = "<a class='greyBtn entercodetask'  href='javascript:void(0)' prop='"+e.id+"'>GO</a>";
					if (e.articleType == 21) hmtltask = "<a class='greyBtn uploadphototask'  href='javascript:void(0)' prop='"+e.id+"'>GO</a>";
						
					html +=hmtltask;
				
					html += "			<input type='hidden' value='"+e.id+"' class='taskid'/>";
					html += "			"+e.title+"</h6>";
					html += "		</div>";
					html += "	</div>";
						
					})
			}else{
				html += "No Message";
			}
			
			$(".tasklistpursuitcontent").html(html);
			$(".tasklistpursuitcontent").attr('total', data.total);
			
		},"JSON")
		
	})
	
	$(document).on('click','.pagingtasknext',function(){
		
		var start = parseInt($(".tasklistpursuitcontent").attr("startpage"),10)+5;
		var totalplayer = parseInt($(".tasklistpursuitcontent").attr("total"));
		
		if(start < totalplayer){
			$.post(basedomain+'pursuit/task', {tasklist:true, start:start}, function(data){
				var html = "";
				
				if (data.status == true){
				
					$.each(data.rec, function(i,e){
					
					var hmtltask = "<a class='greyBtn gettask'  href='javascript:void(0)' prop='"+e.id+"'>GO</a>";
					
					var levels = 'Easy';
					if(e.topcontent==1) levels = 'Medium';
					if(e.topcontent>1) levels = 'Hard';
					
					html += "<div class='row'>";
					//html += "		<a class='thumb-small fl'><img src='"+e.image+"' /></a>";
					html += "		<div class='entry-left fl'>";
				html += "			<h6>";
					
					
				if (e.articleType == 20) hmtltask = "<a class='greyBtn showPopup' href='#popup-invitefriend' prop='"+e.id+"'>GO</a>";			
				//if (e.articleType == 18) hmtltask = "<a class='greyBtn entercodetask'  href='javascript:void(0)' prop='"+e.id+"'>GO</a>";
				if (e.articleType == 21) hmtltask = "<a class='greyBtn uploadphototask'  href='javascript:void(0)' prop='"+e.id+"'>GO</a>";
				
				html +=hmtltask;
				
					html += "			<input type='hidden' value='"+e.id+"' class='taskid'/>";
						html += "	"+e.title+"</h6>";
					html += "		</div>";
					html += "	</div>";
						
					})
					$(".tasklistpursuitcontent").attr("startpage", start);
					
			}else{
				html += "No Message";
			}
				
			$(".tasklistpursuitcontent").html(html);
				
			},"JSON")
		
		}
		
		
	})
	
	$(document).on('click','.pagingtaskprev',function(){
		
		var start = parseInt($(".tasklistpursuitcontent").attr("startpage"),10)-5;
		var totalplayer = parseInt($(".tasklistpursuitcontent").attr("total"));
		
		if(start>=0){
			$.post(basedomain+'pursuit/task', {tasklist:true, start:start}, function(data){
				var html = "";
				
				if (data.status == true){
				
					$.each(data.rec, function(i,e){
					
					var hmtltask = "<a class='greyBtn gettask'  href='javascript:void(0)' prop='"+e.id+"'>GO</a>";
					var levels = 'Easy';
					if(e.topcontent==1) levels = 'Medium';
					if(e.topcontent>1) levels = 'Hard';
					
					html += "<div class='row'>";
					//html += "		<a class='thumb-small fl'><img src='"+e.image+"' /></a>";
					html += "		<div class='entry-left fl'>";
					html += "			<h6>";
											
					if (e.articleType == 20) hmtltask = "<a class='greyBtn showPopup' href='#popup-invitefriend' prop='"+e.id+"'>GO</a>";			
					//if (e.articleType == 18) hmtltask = "<a class='greyBtn entercodetask'  href='javascript:void(0)' prop='"+e.id+"'>GO</a>";
					if (e.articleType == 21) hmtltask = "<a class='greyBtn uploadphototask'  href='javascript:void(0)' prop='"+e.id+"'>GO</a>";
					
					html +=hmtltask;
					html += "			<input type='hidden' value='"+e.id+"' class='taskid'/>";
						html += "	"+e.title+"</h6>";
					html += "		</div>";
					html += "	</div>";
						
					})
					$(".tasklistpursuitcontent").attr("startpage", start);
					
			}else{
				html += "No Message";
			}
				
			$(".tasklistpursuitcontent").html(html);
				
			},"JSON")
		
		}
		
		
	})

/*right pursuit message list*/

	
	
	$(document).on('click','.taskpursuitmessage',function(){
		
		$.post(basedomain+'pursuit/task', {taskmessage:true}, function(data){
			var html = "";
			
			if (data.status == true){
				
					$.each(data.rec, function(i,e){
		
					html += "<div class='row'>";
					//html += "		<a class='thumb-small fl'><img src='"+basedomain+"assets/content/thumb_user.jpg'/></a>";
					if (e.image_profile){
						html += "		 <a class='thumb-small fl'><img src='"+basedomain+"public_assets/user/photo/"+e.image_profile+"'/></a>";
					}else{
						html += "		 <a class='thumb-small fl'><img src='"+basedomain+"assets/content/thumb_user.jpg'/></a>";
					}

					html += "		<div class='entry-left fl'>";
					html += "			<h4 class='username'>"+e.name+"</h4>";
					html += "			<h6 class='date'>"+e.datetime+"</h6>";
					html += "			<a class='theMessages' href='#'>"+e.message+"</a>";
					html += "		</div>";
					html += "	</div>";

					})
			}else{
				html += locale.nomessage;
			}
			
			$(".mymessagesList").html(html);
			
		},"JSON")
		
	})
	


/*right pursuit accomplished task list*/

	
	
	$(document).on('click','.taskpursuitaccomplished',function(){
		
		// var pagingStart = $('.prevlistaccomplished').attr('pagingstart');
		// var pagingEnd = $('.nextlistaccomplished').attr('pagingend');
		$.post(basedomain+'pursuit/task', {accomplishedtask:true}, function(data){
			var html = "";
			
			if (data.status == true){
				
					$.each(data.rec, function(i,e){
		
					html += "<div class='row'>";
					//html += "		<a class='thumb-small fl'><img src='"+e.image+"' /></a>";
					html += "		<div class='entry-left fl'>";
					html += "			<h6 class='accomplished-tasks'>"+e.title+"</h6>";
					/*html += "			<h3 class='task-counter'>1/5</h3>";*/
					html += "		</div>";
					html += "	</div>";

					})
			}else{
				html += locale.noaccompalished;
			}
			
			// $('.prevlistaccomplished').attr('pagingstart',0);
			$('.pursuitaccomlishedcontent').attr('total',data.total);
			// $('.nextlistaccomplished').attr('pagingend',4);
			$(".pursuitaccomlishedcontent").html(html);
			
		},"JSON")
		
	})
	
	$(document).on('click','.nextlistaccomplished',function(){
		
		var start = parseInt($(".pursuitaccomlishedcontent").attr("startpage"),10)+5;
		var totalupdate = parseInt($(".pursuitaccomlishedcontent").attr("total"));
		
		if(start < totalupdate){
			$.post(basedomain+'pursuit/task', {accomplishedtask:true, start:start}, function(data){
				var html = "";
				
				if (data.status == true){
					
						$.each(data.rec, function(i,e){
			
						html += "<div class='row'>";
						//html += "		<a class='thumb-small fl'><img src='"+e.image+"' /></a>";
						html += "		<div class='entry-left fl'>";
						html += "			<h6 class='accomplished-tasks'>"+e.title+"</h6>";
						/*html += "			<h3 class='task-counter'>1/5</h3>";*/
						html += "		</div>";
						html += "	</div>";

						})
				}else{
					html += locale.noaccompalished;
				}
				
				$('.pursuitaccomlishedcontent').attr('startpage',start);
				//$('.pursuitaccomlishedcontent').attr('total',data.total);
				$(".pursuitaccomlishedcontent").html(html);
				
			},"JSON")
		
		}
		
	})

	$(document).on('click','.prevlistaccomplished',function(){
		
		var start = parseInt($(".pursuitaccomlishedcontent").attr("startpage"),10)-5;
		var totalupdate = parseInt($(".pursuitaccomlishedcontent").attr("total"));
		
		if(start>=0){
		
			$.post(basedomain+'pursuit/task', {accomplishedtask:true, start:start}, function(data){
				var html = "";
				
				if (data.status == true){
					
						$.each(data.rec, function(i,e){
			
						html += "<div class='row'>";
						//html += "		<a class='thumb-small fl'><img src='"+e.image+"' /></a>";
						html += "		<div class='entry-left fl'>";
						html += "			<h6 class='accomplished-tasks'>"+e.title+"</h6>";
						/*html += "			<h3 class='task-counter'>1/5</h3>";*/
						html += "		</div>";
						html += "	</div>";

						})
				}else{
					html += locale.noaccompalished;
				}
				
				$('.pursuitaccomlishedcontent').attr('startpage',start);
				// $('.prevlistaccomplished').attr('total',data.total);
				$(".pursuitaccomlishedcontent").html(html);
				
			},"JSON")
		
		}
		
	})

/*right pursuit update list*/

	
	
	$(document).on('click','.taskpursuitupdate',function(){
		
		$.post(basedomain+'pursuit/task', {pursuitupdate:true}, function(data){
			var html = "";
			
			if (data.status == true){
				// console.log(data);
					$.each(data.rec, function(i,e){
		
					html += "<div class='row'>";
					//html += "		<a class='thumb-small fl'><img src='"+basedomain+"assets/content/thumb_user.jpg'/></a>";
					if ( e.image_profile){
						if(e.photo_moderation==1) html += "		 <a class='thumb-small fl'><img src='"+basedomain+"public_assets/user/photo/"+e.image_profile+"'/></a>";
						else html += "		 <a class='thumb-small fl'><img src='"+basedomain+"assets/content/thumb_user.jpg'/></a>";
					}else{
						html += "		 <a class='thumb-small fl'><img src='"+basedomain+"assets/content/thumb_user.jpg'/></a>";
					}

					html += "		<div class='entry-left fl'>";
					if(e.action_id==22) html += "		  <p class='entry-update'>"+e.name+" "+locale.pursuitupdateacomppalished+" "+e.action_value+"</p>";
					else html += "		  <p class='entry-update'>"+e.action_value+"</p>";
					html += "		</div>";
					html += "	</div>";

					})
					
					$(".pursuitupdatecontent").attr('total', data.total);
			}else{
				html += locale.notrade;
			}
			
			$(".pursuitupdatecontent").html(html);
			
		},"JSON")
		
	})
	


/*right pursuit update paging*/

	
	
	
	$(document).on('click','.nextlistpursuitupdate',function(){
		
		
		var start = parseInt($(".pursuitupdatecontent").attr("startpage"),10)+5;
		var totalupdate = parseInt($(".pursuitupdatecontent").attr("total"));
		
		
		if(start < totalupdate){
			$.post(basedomain+'pursuit/task', {nextlistpursuitupdate:true, start:start}, function(data){
			var html = "";
			
				if (data.status == true){
				// console.log(data);
					$.each(data.rec, function(i,e){
		
					html += "<div class='row'>";
					//html += "		<a class='thumb-small fl'><img src='"+basedomain+"assets/content/thumb_user.jpg'/></a>";
					if ( e.image_profile){
						if(e.photo_moderation==1) html += "		 <a class='thumb-small fl'><img src='"+basedomain+"public_assets/user/photo/"+e.image_profile+"'/></a>";
						else html += "		 <a class='thumb-small fl'><img src='"+basedomain+"assets/content/thumb_user.jpg'/></a>";
					}else{
						html += "		 <a class='thumb-small fl'><img src='"+basedomain+"assets/content/thumb_user.jpg'/></a>";
					}

					html += "		<div class='entry-left fl'>";
						if(e.action_id==22){ html += "		  <p class='entry-update'>"+e.name+" "+locale.pursuitupdateacomppalished+" "+e.action_value+"</p>";
					}else{ html += "		  <p class='entry-update'>"+e.action_value+"</p>";}
					html += "		</div>";
					html += "	</div>";

					})
					
					$(".pursuitupdatecontent").attr("startpage", start);
					//$(".pursuitupdatecontent").attr('total', data.total);
				}else{
					html += locale.notrade;
				}
				
				$(".pursuitupdatecontent").html(html);
				
			},"JSON")
		
		}
		
		
	})
	
	
	$(document).on('click','.prevlistpursuitupdate',function(){
		
		
		var start = parseInt($(".pursuitupdatecontent").attr("startpage"),10)-5;
		var totalupdate = parseInt($(".pursuitupdatecontent").attr("total"));
		
		
		if(start>=0){
			$.post(basedomain+'pursuit/task', {prevlistpursuitupdate:true,start:start}, function(data){
			var html = "";
			
				if (data.status == true){
				
					$.each(data.rec, function(i,e){
		
					html += "<div class='row'>";
					//html += "		<a class='thumb-small fl'><img src='"+basedomain+"assets/content/thumb_user.jpg'/></a>";
					if ( e.image_profile){
						if(e.photo_moderation==1)  html += "		 <a class='thumb-small fl'><img src='"+basedomain+"public_assets/user/photo/"+e.image_profile+"'/></a>";
						else html += "		 <a class='thumb-small fl'><img src='"+basedomain+"assets/content/thumb_user.jpg'/></a>";
					}else{
						html += "		 <a class='thumb-small fl'><img src='"+basedomain+"assets/content/thumb_user.jpg'/></a>";
					}

					html += "		<div class='entry-left fl'>";
						if(e.action_id==22) html += "		  <p class='entry-update'>"+e.name+" "+locale.pursuitupdateacomppalished+" "+e.action_value+"</p>";
					else html += "		  <p class='entry-update'>"+e.action_value+"</p>";
					html += "		</div>";
					html += "	</div>";

					})
					
					$(".pursuitupdatecontent").attr("startpage", start);
					//$(".pursuitupdatecontent").attr('total', data.total);
				}else{
					html += locale.notrade;
				}
				
				$(".pursuitupdatecontent").html(html);
				
			},"JSON")
		
		}
		
		
	})


/*right trade list*/

	
	
	$(document).on('click','.taskpursuitmytrade',function(){
		
		$.post(basedomain+'pursuit/task', {mytrade:true}, function(data){
			var html = "";
			
			if (data.status == true){
				
				html += generateHtmlMyTrade(data.rec);
				
			}else{
				html += locale.notrade;
			}
			
			$(".mytradeList").attr('total', data.total);
			$(".mytradeList").html(html);
			
		},"JSON")
		
	})
	
	$(document).on('click','.nextpagingmytrade',function(){
		
		var start = parseInt($(".mytradeList").attr("startpage"),10)+3;
		var totalmytrade = parseInt($(".mytradeList").attr("total"));
		
		
		if(start < totalmytrade){
			$.post(basedomain+'pursuit/task', {mytrade:true, start:start}, function(data){
				var html = "";
				
				if (data.status == true){
					
					html += generateHtmlMyTrade(data.rec);
					
				}else{
					html += locale.notrade;
				}
				
				$(".mytradeList").attr("startpage", start);
				$(".mytradeList").html(html);
				
			},"JSON")
		}
		
		
	})
	
	$(document).on('click','.prevpagingmytrade',function(){
		
		
		var start = parseInt($(".mytradeList").attr("startpage"),10)-3;
		var totalmytrade = parseInt($(".mytradeList").attr("total"));
		
		
		if(start>=0){
			$.post(basedomain+'pursuit/task', {mytrade:true, start:start}, function(data){
				var html = "";
				
				if (data.status == true){
					
					html += generateHtmlMyTrade(data.rec);
					
				}else{
					html += locale.notrade;
				}
				
				$(".mytradeList").attr("startpage", start);
				$(".mytradeList").html(html);
				
			},"JSON")
		
		}
		
		
	})
	
	function generateHtmlMyTrade(data)
	{
		var html = "";
		var rec = data;
		
		$.each(rec, function(i,e){
		
			html += "<div class='row'>";
			html += "<ul class='columns-3 clearfix'>";
			html += "<li class='col3'>";
			html += "<div class='myTradeBox'>";
			html += "<h4>Looking For</h4>";
			html += "<a class='thumb-small fl'><img src='"+basedomain+"assets/content/tiles/"+e.toCode.toLowerCase()+".png' /></a>";
			html += "</div>";
			html += "</li>";
			html += "<li class='col3'>";
			html += "<div class='myTradeBox'>";
			html += "<h4>To Trade With</h4>";
			html += "<a class='thumb-small fl'><img src='"+basedomain+"assets/content/tiles/"+e.fromCode.toLowerCase()+".png' /></a>";
			html += "</div>";
			html += "</li>";
			html += "<li class='col3'>";
			html += "<div class='myTradeBox'>";
			if (e.n_status == 1){
			html += "<a class='icon_check' href='#'>&nbsp;</a>";
			}else{
			html += "<a class='icon_cross cancelTrade' href='#' prop='"+e.id+"'>&nbsp;</a>";
			
			}
			html += "</div>";
			html += "</li>";
			html += "</ul>";

			html += "</div>";

		})
		
		return html;
	}

/*right pursuit player list*/

	
	
	$(document).on('click','.pursuitListplayer',function(){
		
		$.post(basedomain+'pursuit/task', {pursuitlistplayer:true}, function(data){
			var html = "";
			
			
			if (data.status == true){
				
					$.each(data.rec, function(i,e){
		
					html += "<div class='boxPlayer'>";
					if (e.image_profile){
						html += "		 <a class='thumb-small'><img src='"+basedomain+"public_assets/user/photo/"+e.image_profile+"'/></a>";
					}else{
						html += "		 <a class='thumb-small'><img src='"+basedomain+"assets/content/thumb_user.jpg'/></a>";
					}
					
					html += "		 <p class='username'>"+e.name+"</p>";
					//html += "		 <a class='icons icon_trade' aria-hidden='true' data-icon='&#xe009;' href='#'>&nbsp;</a>";
					html += "		 <a class='icons icon_message popup-trade-message' aria-hidden='true' data-icon='&#xe01c;' href='javascript:void(0)' prop='"+e.id+"' desc='"+e.name+"'>&nbsp;</a>";
					html += "	</div>";

					})
			}else{
				html += locale.notrade;
			}
			
			$("#fancybox-outer").addClass('min10');
			$(".listPlayerPursuit").html(html);
			$(".framelistplayer").attr('total', data.total);
			
		},"JSON")
		
	})
	


/*right pursuit player list paging*/

	
	
	
	$(document).on('click','.prevlistpursuitplayer',function(){
		
		var start = parseInt($(".framelistplayer").attr("startpage"),10)-12;
		var totalplayer = parseInt($(".framelistplayer").attr("total"));
		
		//console.log(start);
		//console.log(totalplayer);
		if(start>=0){
			$.post(basedomain+'pursuit/task', {prevlistplayer:true, start:start}, function(data){
				var html = "";
				
				if (data.status == true){
					
						$.each(data.rec, function(i,e){
			
						html += "<div class='boxPlayer'>";
						//html += "		 <a class='thumb-small'><img src='"+basedomain+"assets/content/thumb_user.jpg'/></a>";
						if (e.image_profile){
							html += "		 <a class='thumb-small'><img src='"+basedomain+"public_assets/user/photo/"+e.image_profile+"'/></a>";
						}else{
							html += "		 <a class='thumb-small'><img src='"+basedomain+"assets/content/thumb_user.jpg'/></a>";
						}

						html += "		 <p class='username'>"+e.name+"</p>";
						//html += "		 <a class='icons icon_trade' aria-hidden='true' data-icon='&#xe009;' href='#'>&nbsp;</a>";
						html += "		 <a class='icons icon_message popup-trade-message' aria-hidden='true' data-icon='&#xe01c;' href='javascript:void(0)' prop='"+e.id+"' desc='"+e.name+"'>&nbsp;</a>";
						html += "	</div>";

						})
						
					$(".framelistplayer").attr("startpage", start);
				}else{
					html += locale.notrade;
				}
				$("#fancybox-outer").addClass('min10');
				
				$(".listPlayerPursuit").html(html);
				
			},"JSON")
		
		}
		
		
	})
	

/*right pursuit player list paging*/

	
	
	
	$(document).on('click','.nextlistpursuitplayer',function(){
		
		var start = parseInt($(".framelistplayer").attr("startpage"),10)+12;
		var totalplayer = parseInt($(".framelistplayer").attr("total"));
		
		if(start < totalplayer){
			$.post(basedomain+'pursuit/task', {nextlistplayer:true, start:start}, function(data){
				var html = "";
				
				if (data.status == true){
					
						$.each(data.rec, function(i,e){
			
						html += "<div class='boxPlayer'>";
						//html += "		 <a class='thumb-small'><img src='"+basedomain+"assets/content/thumb_user.jpg'/></a>";
						if (e.image_profile){
							html += "		 <a class='thumb-small'><img src='"+basedomain+"public_assets/user/photo/"+e.image_profile+"'/></a>";
						}else{
							html += "		 <a class='thumb-small'><img src='"+basedomain+"assets/content/thumb_user.jpg'/></a>";
						}

						html += "		 <p class='username'>"+e.name+"</p>";
						//html += "		 <a class='icons icon_trade' aria-hidden='true' data-icon='&#xe009;' href='#'>&nbsp;</a>";
						html += "		 <a class='icons icon_message popup-trade-message' aria-hidden='true' data-icon='&#xe01c;' href='javascript:void(0)' prop='"+e.id+"' desc='"+e.name+"'>&nbsp;</a>";
						html += "	</div>";

						})
						
					$(".framelistplayer").attr("startpage", start);
				}else{
					html += locale.notrade;
				}
				$("#fancybox-outer").addClass('min10');
				
				$(".listPlayerPursuit").html(html);
				
			},"JSON")
		
		}
		
		
	})
	


/*right pursuit popup task list*/

	
	
	$(document).on('click','.entercodetask',function(){
		$(".tasksmallcontent").html('');
		$(".tasksmalltitle").html('');
			
			var idTask = $(this).attr('prop');
		$(".tasksmalltitle").html(locale.pleasewait);
		$.post(basedomain+'pursuit/task',{taskdetail:true,id:idTask, cid:idTask},function(data){
			var html = "";
			if(data.status){
			
				html += "<h4>"+data.rec.result[0].content+"</h4>";
				html += "    		<form class='small_form'>";
				html += "        	<div class='row'><input type='text' placeholder='ENTER CODE' value='' class='fl taskCode maskcode' /></div>";
				html += "        	<div class='row'><input type='text' placeholder='ENTER CAPTCHA' value='' class='fl taskCode captcha' />";
				html += "        	<img src='"+basedomain+"assets/media/captcha.php' style='width:80px;height:30px' /></div>";
				html += "        	<input type='hidden' value='"+idTask+"' class='fl tasklistCode'/>";
				html += "            <div class='row'><input type='button' value='SUBMIT' class='button btn_grey fl submitaskcode' />";
				html += "            </div>";
				html += "            </form>";
								
				$(".tasksmalltitle").html(data.rec.result[0].title);
			}
			$(".tasksmallcontent").html(html);
		},"JSON");
		
		$(".task-small-master").trigger("click");
		
		
	})
	
	
	$(document).on('click','.uploadphototask',function(){
		$(".tasksmallcontent").html('');
		$(".tasksmalltitle").html('');
			
			var idTask = $(this).attr('prop');
		$(".tasksmalltitle").html(locale.pleasewait);
		
		$.post(basedomain+'pursuit/task',{taskdetail:true,id:idTask},function(data){
			var html = "";
			if(data.status){
			
				html += "<h4>"+data.rec.result[0].content+"</h4>";
				html+="		<form action='"+basedomain+"pursuit/saveuploadphoto' method='POST' enctype='multipart/form-data' id='formuploadphototask' >";
				html+="         	<div class='row'> ";
				
				html+="         	<input type='file' name='image' id='imagephotoprofile' />";
				html+="    		    <p class='infoText'>Maximum File Size of 250KB</p>";
				html+="            </div>";
				html+="             <div class='row'>";
				html+="           	<div id='photo-preview' class='previewer-syncro'>";
				html+="                </div>";
				html+="             </div>";
				html+="         	<p>"+locale.popuptaskdesc+"</p>";
				html+="            <div class='row rowSubmit' >";
				html+="                <input type='hidden' value='"+idTask+"' name='taskid' >";
				html+="                <input type='hidden' value='"+idTask+"' name='cid' >";
				html+="                <input type='submit' class='button btnRed submitImage' value='SUBMIT' id='savephotoprofile' >";
				html+="            </div>";
				html+="</form></div>";
		
			
								
				$(".tasksmalltitle").html(data.rec.result[0].title);
			}
			
			$(".tasksmallcontent").html(html);

					
			var uploadphotooption = {
				dataType:"json",
				beforeSubmit: function(data) { 
							$('.previewer-syncro').html("<img src='"+basedomain+"assets/images/loader.gif' class='loader loader-profile' />");
				},
				success : function(data) {									
						if(data.result) $('.previewer-syncro').html("<img src='"+basedomain+"public_assets/thisorthat/"+data.filename.filename+"' />");
						else $('.previewer-syncro').html("<span style='color:black' >image not found</span>");
						
						
				}
			};					


			$("#formuploadphototask").ajaxForm(uploadphotooption);	
			
			
			
		},"JSON");
		
		
		$(".task-small-master").trigger("click");
		
		
	})
		
	
	
	$(document).on('change','#imagephotoprofile',function(){
		$("#formuploadphototask").attr('action',basedomain+"pursuit/saveuploadphoto");
		$("#savephotoprofile").trigger("click");					
		$("#formuploadphototask").attr('action',basedomain+"pursuit/uploadtaskphotoevent");
		$(".submitImage").attr('id',"savephotoprofilewhensubmit");
		
	});
	
	$(document).on('click','#savephotoprofilewhensubmit',function(){
		var uploadphotooption = {
				dataType:"json",
				beforeSubmit: function(data) { 
							$('.previewer-syncro').html("<img src='"+basedomain+"assets/images/loader.gif' class='loader loader-profile' />");
				},
				success : function(data) {									
						if(data.result){
							$(".contentmyaccount").html(locale.successuploadfotothisorthat);
							$(".my-account-profile").trigger("click");
						}else{
							$(".contentmyaccount").html(locale.faileduploadfotothisorthat);
							$(".my-account-profile").trigger("click");
						}
						
				}
			};		
		$("#formuploadphototask").ajaxForm(uploadphotooption);	
		
		$(document).on('click','#fancybox-close, #fancybox-overlay, .cancelchangeletter', function(){
				window.location = basedomain+'pursuit/join';
				//$("#fancybox-close").trigger('click');
		});
		$("#fancybox-outer").addClass('min102');
				
	});
	
	$(document).on('click','#closepopupjs',function(){
		
		window.location = basedomain+"pursuit/join";
		
	})
	

/*trade a letter*/

	
	
	$(document).on('click','.titlePopupTradealetter',function(){
		
		postTradingFloor();
		
	})
	
	function postTradingFloor()
	{
		$.post(basedomain+'pursuit/trade', {tradealetter:true}, function(data){
			var html = "";
			
			if (data.status == true){
				
					$('.lookLeter').val(0);
					$('.tradeLeter').val(0);
		
					html += "<li class='col2'>";
					html += "		<div class='entry-popup'>";
					html += "			<h3 class='black_title'>What I'm Looking For</h3>";
					html += "			<input type='hidden' class='lookLeter' value=''>";
					html += "			<div class='preview-box prevCode'>";
					html += "			</div>";
					html += "			<div class='letter-box'>";
					
					$.each(data.rec.needcode, function(i,e){
					// console.log(e.toLowerCase());
					// html += "			<div class='tradereqletterBox letter_"+e.toLowerCase()+"'>";
						html += "<span class='letter_"+e.toLowerCase()+"'>";
						html += "				<img src='"+basedomain+"assets/content/tiles/"+e.toLowerCase()+".png' prop='"+i+"' class='code'/>";
						html += "</span>";
					
					
					// html += "<div class='letterBox'><img src='"+basedomain+"assets/content/tiles/"+e.toLowerCase()+".png' class='changeletter' prop='"+i+"'/>";
					// html += "						<span class='total-tiles'>{$v.total}</span>";
					// html += "						</div>";
								
					})
					html += "			</div>";
					html += "		</div>";
					html += "	</li>";
					html += "	<li class='col2'>";
					html += "		<div class='entry-popup'>";
					html += "			<h3 class='black_title'>I'm Trading The Letter</h3>";
					html += "			<input type='hidden' class='tradeLeter' value=''>";
					html += "			<div class='preview-box prevMyCode'>";
					html += "			</div>";
					html += "			<div class='letter-box'>";
					
					$.each(data.rec.needcode, function(i,e){
					html += "			<div class='tradereqletterBox letter_"+e.toLowerCase()+"'>";
					if (data.rec.totalletter[i] >0){
						
							// html += "<span class='letter_"+e.toLowerCase()+"'>";
							html += "				<img src='"+basedomain+"assets/content/tiles/"+e.toLowerCase()+".png' prop='"+i+"' class='mycode' val='"+data.rec.totalletter[i]+"'/><span class='total-tiles'>"+data.rec.totalletter[i]+"</span>";
							// html += "</span>";
						
							
					}else{
					
						
							// html += "<span class='letter_"+e.toLowerCase()+"'>";
							html += "				<img src='"+basedomain+"assets/content/tiles/"+e.toLowerCase()+".png' prop='"+i+"' class='' val='"+data.rec.totalletter[i]+"'/><span class='total-tiles'>"+data.rec.totalletter[i]+"</span>";
							// html += "</span>";
						
					}
					
					//html += "						";
					html += "			</div>";
					})
					html += "			</div>";
					html += "		</div>";
					html += "	</li>";

					
			}else{
				html += locale.notrade;
			}
			
			$(".trade-confirm").trigger("click");		
			$(".confirmContent").html(html);
			
			$(".buttonFrame").html("<a href='javascript:void(0)' class='btn_black2 postTrade posttradetitlebutton'>Post On Trading Floor</a>");
			$(".confirmTitle").html(locale.traderequest);
			
			
			// $(document).on('click','#fancybox-close, #fancybox-overlay, .cancelchangeletter', function(){
					// window.location = basedomain+'pursuit/join';
					
			// });
			// $("#fancybox-outer").addClass('min102');
			
			
		},"JSON")
	}
	
	$(document).on('click', '.mycode', function(){
		var value = parseInt($(this).attr('val'));
		var html ="";
		if (value==0){
			html += locale.tradefailed;
			$(".post-trade-message").trigger("click");		
			$(".postradecontent").html(html);
		}
	})


	$(document).on('click', '.postTrade', function(){
		var lookCode = parseInt($('.lookLeter').val());
		var tradeCode = parseInt($('.tradeLeter').val());
		var html = "";
		if(lookCode>0 && tradeCode>0){
			$.post(basedomain+"pursuit/ajax", {postTrade:true, look:lookCode, trade:tradeCode},  function(data){
				var statusTrade = data.status;
				
				if (statusTrade == true){
					
					
					html += "<div class='entry-popup-center'>";
					html += "<h3>you have successfully posted on the trading floor.<br />would you like to post another one?</h3>";
					html += "</div>";
					html += "<div class='center-button'>";
					html += "<a href='javascript:void(0)' class='btn_black2 titlePopupTradealetter'>Trade A Letter</a>";
					//html += "<a href='javascript:void(0)' class='btn_black2 showpopuptradingfloor'>Back To Trading Floor</a>";
					html += "</div>";
					
					
				}else{
					html += locale.failed;
				}
				
				$(".post-trade-message").trigger("click");		
				$(".postradecontent").html(html);	
				
				
			}, "JSON")
		}else{
			html += locale.tradefailed;
			$(".post-trade-message").trigger("click");		
			$(".postradecontent").html(html);	
		}
		$(document).on('click','#fancybox-close, #fancybox-overlay', function(){
						window.location = basedomain+'pursuit/join';
						//$("#fancybox-close").trigger('click');
				});
				$("#fancybox-outer").addClass('min102');
	})
	
	$(document).on('click', '.code', function(){
		var getcodeID = $(this).attr('prop');
		var getcodeSrc = $(this).attr('src');
		var htmlcode = "";
		
		htmlcode += "<img src='"+getcodeSrc+"'/>";
		$('.prevCode').html(htmlcode);
		$('.lookLeter').val(getcodeID);
	})
	
	$(document).on('click', '.mycode', function(){
		var getmycodeID = $(this).attr('prop');
		var getmycodeSrc = $(this).attr('src');
		var htmlmycode = "";
		
		htmlmycode += "<img src='"+getmycodeSrc+"'/>";
		$('.prevMyCode').html(htmlmycode);
		$('.tradeLeter').val(getmycodeID);
	})
	
	$(document).on('dblclick','.changeletter',function(){
		var thisobject = $(this);
		var confirmationhtml = "";
		
		confirmationhtml += "<div><div class='row'> <h1>Are you sure ?<br/> This action can't be undone</h1></div> ";
		confirmationhtml += "<div class='row' ><a href='javascript:void(0)' class='button btn_red btn_red3  okchangeletter ' ><span>Yes</span></a> ";
		confirmationhtml += "<img src='"+basedomain+"assets/images/maybe.png' style=' position: relative; top: 7px; ' /> ";
		confirmationhtml += "<a href='javascript:void(0)' class='button btn_red btn_red3 cancelchangeletter' ><span>No</span></a></div></div> ";
		
	
			$(".contentmyaccount").html(confirmationhtml);
			$(".my-account-profile").trigger("click");		
	
		
			$(document).on('click','.okchangeletter', function(){
				html = "please wait";
				$(".contentmyaccount").html(html);
				$(".my-account-profile").trigger("click");		
				
				var letterID = thisobject.attr('prop');
				var letter = thisobject.attr('prop');
				//alert(letterID);
				$.post(basedomain+'pursuit/setletter', {setletter:true, id:letterID}, function(data){
					var html = "";
					var stat = "<div class='row' >"+locale.failed+"</div>";
					
					if (data.status == true){
						
							$('#'+letter).addClass('red');
							//$('.letterBox').html(html);
							stat = "<div class='row' >Success</div>";
							
					}
					
					if(data.phrase){
						setTimeout(function(){
							$("."+data.phrase).trigger("click");
						},1000);				
					}else{					
						$(".contentmyaccount").html(stat);
						$(".my-account-profile").trigger("click");	
					}
					
							
				},"JSON")
		
			});
			
			$(document).on('click','#fancybox-close, #fancybox-overlay, .cancelchangeletter', function(){
					window.location = basedomain+'pursuit/join';
					//$("#fancybox-close").trigger('click');
			});
			$("#fancybox-outer").addClass('min102');
		
	})
	
	
	
	$(document).on('click','.redeemdont',function(){
		var code = $(this).attr('prop');
		var phrase = $(this).attr('phrase');
		var merchanName = $(this).attr('phrase');
		
		
		// alert(code);
		// alert(phrase);
			/*
			var confirmationhtml = "";
			
			confirmationhtml += "<div><div class='row'> <h1>Are you sure ?<br/> This action can't be undone</h1></div> ";
			confirmationhtml += "<div class='row' ><a href='javascript:void(0)' class='button btn_red btn_red3  okchangeletter ' ><span>Yes</span></a> ";
			confirmationhtml += "<img src='"+basedomain+"assets/images/maybe.png' style=' position: relative; top: 7px; ' /> ";
			confirmationhtml += "<a href='javascript:void(0)' class='button btn_red btn_red3 cancelchangeletter' ><span>No</span></a></div><input type='hidden' class='' value=''/></div>";
			
		
				$(".contentmyaccount").html(confirmationhtml);
				$(".my-account-profile").trigger("click");		
				
				$(document).on('click','.okchangeletter', function(){
					html = "please wait";
					$(".contentmyaccount").html(html);
					$(".my-account-profile").trigger("click");	
					
					alert(code);
			*/		
					var html = "";
					$.post(basedomain+'pursuit/ajaxRedeem', {redeemConfirm:true, id:code}, function(data){
						
						if (data.status == true){
							
							html += "<div class='row centering'>";
							html += "	<table>";
							html += "    	<tr>";
							html += "        	<td valign='top' align='center'>";
							html += "                <div class='frame'><div class='boxPrizeBig'>";
							html += "                    <a href='#' class='thumb-prizeBig'><img src='"+basedomain+"public_assets/merchandiseStock/"+data.rec.image+"' /></a>";
							html += "                </div></div>";
							html += "            </td>";
							html += "        </tr>";
							html += "    </table>";
							html += "</div>";
							html += "<div class='row centering rowbottom'>";
							html += "<h6 class='titlemerchan'>";
							html += "For "+data.rec.detail+".<br/> Are You sure you want to do this?";
							html += "</h6>";
							html += "	<a class='btn_black3 confirmRedeem' href='javascript:void(0)'>CONFIRM REDEMPTION</a>";
							html += "</div>";
							
						}else{
							html += "<h1>Status False</h1>";
						}
						
						$(".redeem-popup").trigger("click");			
						$(".contentredeem").html(html);
						$(".titleredeemcomplete").html("YOU ARE ABOUT TO TRADE IN");
						$(".titleredeemphrase").html(phrase);
						$(".titleredeem").html('Redeem');
						
						
						$(document).on('click','.confirmRedeem', function(){
							/*
							var load = "";
							load = "please wait";
							$(".contentmyaccount").html(load);
							$(".my-account-profile").trigger("click");	
							*/
							
							
							$.post(basedomain+'pursuit/ajaxRedeem', {redeemDont:true, id:code}, function(data){
								var htmlSucess = "";
								
								if (data.status == true){
									
									htmlSucess += "<div class='row centering'>";
									htmlSucess += "	<table>";
									htmlSucess += "    	<tr>";
									htmlSucess += "        	<td valign='top' align='center'>";
									htmlSucess += "                <div class='frame'><div class='boxPrizeBig'>";
									htmlSucess += "                    <a href='#' class='thumb-prizeBig'><img src='"+basedomain+"public_assets/merchandiseStock/"+data.rec.image+"' /></a>";
									htmlSucess += "                </div></div>";
									htmlSucess += "            </td>";
									htmlSucess += "        </tr>";
									htmlSucess += "    </table>";
									htmlSucess += "</div>";
									htmlSucess += "<div class='row centering rowbottom'>";
									htmlSucess += "<h6 class='titlemerchan'>Your "+data.rec.detail+" is on its way.<br /> "+locale.secondredeemdtext+"  </h6>" ;
									htmlSucess += "	<a class='btn_black3 successRedeem' href='javascript:void(0)'>OK</a>";
									htmlSucess += "</div>";
									
								}else{
									htmlSucess += locale.failed;
								}
								
								$(".redeem-popup").trigger("click");			
								$(".contentredeem").html(htmlSucess);
								$(".titleredeem").html('Redeem');
								$(".titleredeemcomplete").html("");
								
								
							},"JSON")
							
						})
						
					},"JSON")
					
				//})
				/*
				$(document).on('click','#fancybox-close, #fancybox-overlay, .cancelchangeletter', function(){
						window.location = basedomain+'pursuit/join';
						//$("#fancybox-close").trigger('click');
				});
				$("#fancybox-outer").addClass('min102');
				*/
	})
	
	$(document).on('click','.redeemdontbe',function(){
		var code = $(this).attr('prop');
		var phrase = $(this).attr('phrase');
		var merchanName = $(this).attr('phrase');
		
		var html = "";
		$.post(basedomain+'pursuit/ajaxRedeem', {redeemConfirm:true, id:code}, function(data){
			
			if (data.status == true){
				
				html += "<div class='row centering'>";
				html += "	<table>";
				html += "    	<tr>";
				html += "        	<td valign='top' align='center'>";
				html += "                <div class='frame'><div class='boxPrizeBig'>";
				html += "                    <a href='#' class='thumb-prizeBig'><img src='"+basedomain+"public_assets/merchandiseStock/"+data.rec.image+"' /></a>";
				html += "                </div></div>";
				html += "            </td>";
				html += "        </tr>";
				html += "    </table>";
				html += "</div>";
				html += "<div class='row centering rowbottom'>";
				html += "<h6 class='titlemerchan'>";
				html += "For  "+data.rec.detail+".<br/> Are You sure you want to do this?";
				html += "</h6>";
				html += "	<a class='btn_black3 confirmRedeem' href='javascript:void(0)'>CONFIRM REDEMPTION</a>";
				html += "</div>";
				
			}else{
				html += "<h1>Status False</h1>";
			}
			
			$(".redeem-popup").trigger("click");			
			$(".contentredeem").html(html);
			$(".titleredeemcomplete").html("YOU ARE ABOUT TO TRADE IN");
			$(".titleredeemphrase").html(phrase);
			$(".titleredeem").html('Redeem');
			
			
			$(document).on('click','.confirmRedeem', function(){
				
				$.post(basedomain+'pursuit/ajaxRedeem', {redeemDontBe:true, id:code}, function(data){
					var htmlSucess = "";
					
					if (data.status == true){
						
						htmlSucess += "<div class='row centering'>";
						htmlSucess += "	<table>";
						htmlSucess += "    	<tr>";
						htmlSucess += "        	<td valign='top' align='center'>";
						htmlSucess += "                <div class='frame'><div class='boxPrizeBig'>";
						htmlSucess += "                    <a href='#' class='thumb-prizeBig'><img src='"+basedomain+"public_assets/merchandiseStock/"+data.rec.image+"' /></a>";
						htmlSucess += "                </div></div>";
						htmlSucess += "            </td>";
						htmlSucess += "        </tr>";
						htmlSucess += "    </table>";
						htmlSucess += "</div>";
						htmlSucess += "<div class='row centering rowbottom'>";
						htmlSucess += "<h6 class='titlemerchan'>Your "+data.rec.detail+" is on its way.<br /> "+locale.secondredeemdtext+"  </h6>" ;
						htmlSucess += "	<a class='btn_black3 successRedeem' href='javascript:void(0)'>OK</a>";
						htmlSucess += "</div>";
						
					}else{
						htmlSucess += locale.failed;
					}
					
					$(".redeem-popup").trigger("click");			
					$(".contentredeem").html(htmlSucess);
					$(".titleredeem").html('Redeem');
					$(".titleredeemcomplete").html("");
					
				},"JSON")
				
			})
			
		},"JSON")
		
	})
	
	$(document).on('click','.redeemdontbea',function(){
		var code = $(this).attr('prop');
		var phrase = $(this).attr('phrase');
		var merchanName = $(this).attr('phrase');
		
		var html = "";
		$.post(basedomain+'pursuit/ajaxRedeem', {redeemConfirm:true, id:code}, function(data){
			
			if (data.status == true){
				
				html += "<div class='row centering'>";
				html += "	<table>";
				html += "    	<tr>";
				html += "        	<td valign='top' align='center'>";
				html += "                <div class='frame'><div class='boxPrizeBig'>";
				html += "                    <a href='#' class='thumb-prizeBig'><img src='"+basedomain+"public_assets/merchandiseStock/"+data.rec.image+"' /></a>";
				html += "                </div></div>";
				html += "            </td>";
				html += "        </tr>";
				html += "    </table>";
				html += "</div>";
				html += "<div class='row centering rowbottom'>";
				html += "<h6 class='titlemerchan'>";
				html += "For  "+data.rec.detail+".<br/> Are You sure you want to do this?";
				html += "</h6>";
				html += "	<a class='btn_black3 confirmRedeem' href='javascript:void(0)'>CONFIRM REDEMPTION</a>";
				html += "</div>";
				
			}else{
				html += "<h1>Status False</h1>";
			}
			
			$(".redeem-popup").trigger("click");			
			$(".contentredeem").html(html);
			$(".titleredeemcomplete").html("YOU ARE ABOUT TO TRADE IN");
			$(".titleredeemphrase").html(phrase);
			$(".titleredeem").html('Redeem');
			
			
			$(document).on('click','.confirmRedeem', function(){
				
				$.post(basedomain+'pursuit/ajaxRedeem', {redeemDontBeA:true, id:code}, function(data){
					var htmlSucess = "";
					
					if (data.status == true){
						
						htmlSucess += "<div class='row centering'>";
						htmlSucess += "	<table>";
						htmlSucess += "    	<tr>";
						htmlSucess += "        	<td valign='top' align='center'>";
						htmlSucess += "                <div class='frame'><div class='boxPrizeBig'>";
						htmlSucess += "                    <a href='#' class='thumb-prizeBig'><img src='"+basedomain+"public_assets/merchandiseStock/"+data.rec.image+"' /></a>";
						htmlSucess += "                </div></div>";
						htmlSucess += "            </td>";
						htmlSucess += "        </tr>";
						htmlSucess += "    </table>";
						htmlSucess += "</div>";
						htmlSucess += "<div class='row centering rowbottom'>";
						htmlSucess += "<h6 class='titlemerchan'>Your "+data.rec.detail+" is on its way.<br /> "+locale.secondredeemdtext+"  </h6>" ;
						htmlSucess += "	<a class='btn_black3 successRedeem' href='javascript:void(0)'>OK</a>";
						htmlSucess += "</div>";
						
					}else{
						htmlSucess += locale.failed;
					}
					
					$(".redeem-popup").trigger("click");			
					$(".contentredeem").html(htmlSucess);
					$(".titleredeem").html('Redeem');
					$(".titleredeemcomplete").html("");
					
				},"JSON")
				
			})
			
		},"JSON")
		
	})
	
	
	$(document).on('click','.redeemdontbeamaybe',function(){
		var code = $(this).attr('prop');
		var phrase = $(this).attr('phrase');
		var merchanName = $(this).attr('phrase');
		
		var html = "";
		$.post(basedomain+'pursuit/ajaxRedeem', {redeemConfirm:true, id:code}, function(data){
			
			if (data.status == true){
				
				html += "<div class='row centering'>";
				html += "	<table>";
				html += "    	<tr>";
				html += "        	<td valign='top' align='center'>";
				html += "                <div class='frame'><div class='boxPrizeBig'>";
				html += "                    <a href='#' class='thumb-prizeBig'><img src='"+basedomain+"public_assets/merchandiseStock/"+data.rec.image+"' width='100%' height='190px'/></a>";
				html += "                </div></div>";
				html += "            </td>";
				html += "        </tr>";
				html += "    </table>";
				html += "</div>";
				html += "<div class='row centering rowbottom'>";
				html += "<h6 class='titlemerchan'>";
				html += locale.dontbemaybeconfirm+".<br/> Are You sure you want to do this?";
				html += "</h6>";
				html += "	<a class='btn_black3 confirmRedeem' href='javascript:void(0)'>CONFIRM REDEMPTION</a>";
				html += "</div>";
				
			}else{
				html += "<h1>Status False</h1>";
			}
			
			$(".redeem-popup").trigger("click");			
			$(".contentredeem").html(html);
			$(".titleredeemcomplete").html("YOU ARE ABOUT TO TRADE IN");
			$(".titleredeemphrase").html(phrase);
			$(".titleredeem").html('Redeem');
			
			
			$(document).on('click','.confirmRedeem', function(){
				
				$.post(basedomain+'pursuit/ajaxRedeem', {redeemDontBeAMayBe:true, id:code}, function(data){
					var htmlSucess = "";
					
					if (data.status == true){
						
						htmlSucess += "<div class='row centering'>";
						htmlSucess += "	<table>";
						htmlSucess += "    	<tr>";
						htmlSucess += "        	<td valign='top' align='center'>";
						htmlSucess += "                <div class='frame'><div class='boxPrizeBig'>";
						htmlSucess += "                    <a href='#' class='thumb-prizeBig'><img src='"+basedomain+"public_assets/merchandiseStock/"+data.rec.image+"' /></a>";
						htmlSucess += "                </div></div>";
						htmlSucess += "            </td>";
						htmlSucess += "        </tr>";
						htmlSucess += "    </table>";
						htmlSucess += "</div>";
						htmlSucess += "<div class='row centering rowbottom'>";
						htmlSucess += "<h6 class='titlemerchan'>"+locale.dontbemaybeconfirmsuccess+"  </h6>" ;
						htmlSucess += "	<a class='btn_black3 successRedeem' href='javascript:void(0)'>OK</a>";
						htmlSucess += "</div>";
						
					}else{
						htmlSucess += locale.failed;
					}
					
					$(".redeem-popup").trigger("click");			
					$(".contentredeem").html(htmlSucess);
					$(".titleredeem").html('Redeem');
					$(".titleredeemcomplete").html("");
					
				},"JSON")
				
			})
			
		},"JSON")
		
	})
	
	
	$(document).on('click','.redeemprize',function(){
		$.post(basedomain+'pursuit/ajaxRedeem', {redeemprize:true}, function(data){	
			// console.log(data.phrase);
			if (data.status ==true){
				if(data.phrase){
					
					$("."+data.phrase).trigger("click");	
				}
			}else{
				if(data.message) var message = data.message;
				else var message = "You don't have any words to redeem";
				
				var stat = "<div class='row' >"+message+"</div>";
				$(".contentmyaccount").html(stat);
				$(".my-account-profile").trigger("click");	
			}
			
			
			
		},"JSON")
	})
	
	function inArray(needle, haystack) {
		var length = haystack.length;
		for(var i = 0; i < length; i++) {
			if(haystack[i] == needle) return true;
		}
		return false;
	}

	function viewredeemhtml(data,validate,has,filename,titlePhrase,id,redeemprizetype,redeembutton,continuebutton,titlehidden, completephrase){
		//redeemPrizeDont
		// console.log(titlePhrase);
		var html = "";
				html += "<div class='row centering'>";
				html += "            	<table>";
				html += "                	<tr>";
				html += "                    	<td valign='top'>";
				html += "                            <div class='frame'><div class='boxPrizeBig'>";
				html += "                                <a href='#' class='thumb-prizeBig' >";
				if (completephrase ==1)html += "<img src='"+basedomain+"public_assets/merchandiseStock/"+filename+"'  style='height:100%;width:100%;top:0' />";
				else html += "<img src='"+basedomain+"public_assets/merchandiseStock/"+filename+"'/>";
				html+= "</a>";
				html += "                     		    <h6>"+data.detail+"</h6>";
				html += "                            </div></div>";
				html += "                        </td>";
				html += "                    </tr>";
				html += "                </table>";
				html += "            </div>";
				html += "            <div class='row centering'>";
				if (completephrase == 0){
					html += "				<h6 class='m15'>CHOOSE AND REDEEM ANY OF PRIZE BELOW:</h6>";
				}
				
				html += "            </div>";
				html += "            <div class='row centering'>";
				html += "            	<table>";
				html += "                	<tr>";
				
				var allowed = false;
				var allowedMerchan = new Array();
				
				if (completephrase == 0){
				
					$.each(data, function(i,e){
						
						if (e.has==false){
							allowed = true;
							allowedMerchan[i] = e.image;
						}
						
						if (filename == e.image){
							var active = " active ";
						}else{
							var active = "";
						}
									
						
						html += "                    	<td valign='top'>";
						html += "                            <div class='boxPrize'>";
						if(e.has==true) html += "<a href='javascript:void(0)' class='noneactive thumb-prize  '  prop='"+e.id+"' filename='"+e.image+"' ><img src='"+basedomain+"public_assets/merchandiseStock/"+e.image+"' /></a>";
						else html += "                                <a href='javascript:void(0)' class='"+active+" thumb-prize "+redeemprizetype+"'  prop='"+e.id+"' filename='"+e.image+"' ><img src='"+basedomain+"public_assets/merchandiseStock/"+e.image+"' /></a>";
						html += "                                <h6>"+e.detail+"</h6>";
						html += "<div><h6><span class='thisstock_"+e.id+"' >"+e.stock+"</span>&nbsp;&nbsp;left </h6></div>";
						html += "                            </div>";
						html += "                        </td>";
					})
				}
				html += "                    </tr>";
				html += "                </table>";
				html += "            </div>";
				html += "            <div class='row centering'>";
				
				if (completephrase ==1){
					html +="<p>"+locale.completephrasedontbeamaybe+"</p>";
					html += "				<a class='btn_black3 "+continuebutton+"' href='javascript:void(0)' prop='"+id+"' phrase="+titlehidden+">Continue</a>";
					
					html += "            </div>";
				}else{
					if(inArray(filename, allowedMerchan)){
						if (validate == 1){
							html += "					<a class='btn_black3 "+redeembutton+"' href='#popup-redeem-claim' prop='"+id+"' phrase="+titlehidden+">REDEEM</a>";
						}
					}
					//console.log(has);
					html += "				<a class='btn_black3 "+continuebutton+"' href='javascript:void(0)'>Keep Playing</a>";
					html += "            </div>";
				}
				return html;
	
	}
	
	$(document).on('click','.redeemPrizeDont',function(){
		
		var id = $(this).attr('prop');
		var filename = $(this).attr('filename');
		
		$.post(basedomain+'pursuit/ajaxRedeem', {redeemViewDont:true, id:id}, function(data){
			
			if (data.status == true){
			
				var title = "";
				var titleComplete = "";
				var titlePhrase = "";
				var titlehidden = "";
				
				if (data.validate == 1){
					title += "Redeem"; 
					titleComplete += "You ve completed the phrase!"; 
					titlePhrase += "DON'T";
					titlehidden += "DON'T";
				}else{
					title += ""; 
					titleComplete += ""; 
					titlePhrase += ""; 
					titlehidden += ""; 
				}
				
				var html = viewredeemhtml(data.dontbe,data.validate,data.has,filename,titlePhrase,id,"redeemPrizeDont","redeemdont","continuetodontbe", titlehidden,0);
				
				$(".redeem-popup").trigger("click");			
				$(".contentredeem").html(html);
				$(".titleredeem").html(title);
				$(".titleredeemcomplete").html(titleComplete);
				$(".titleredeemphrase").html(titlePhrase);
			}else{
				var confirmationhtml = locale.alreadyredeem;
				$(".contentmyaccount").html(confirmationhtml);
				$(".my-account-profile").trigger("click");		
			}
		}, "JSON")
	})
	
	$(document).on('click','.redeemPrizeDontBe',function(){
		
		var id = $(this).attr('prop');
		var filename = $(this).attr('filename');
		
		$.post(basedomain+'pursuit/ajaxRedeem', {redeemViewDontBe:true}, function(data){
			
			if (data.status == true){
				var title = "";
				var titleComplete = "";
				var titlePhrase = "";
				var titlehidden = "";
				
				if (data.validate == 1){
					title += "Redeem"; 
					titleComplete += "You ve completed the phrase!"; 
					titlePhrase += "DON'T BE";
					titlehidden += '"DON\'T BE"';
				}else{
					title += ""; 
					titleComplete += ""; 
					titlePhrase += ""; 
					titlehidden += ""; 
				}
				
				var html = viewredeemhtml(data.dontbea,data.validate,data.has,filename,titlePhrase,id,"redeemPrizeDontBe","redeemdontbe","continuetodontbe", titlehidden,0);
								
				$(".redeem-popup").trigger("click");			
				$(".contentredeem").html(html);
				$(".titleredeem").html(title);
				$(".titleredeemcomplete").html(titleComplete);
				$(".titleredeemphrase").html(titlePhrase);
			}else{
				var confirmationhtml = locale.alreadyredeem;
				$(".contentmyaccount").html(confirmationhtml);
				$(".my-account-profile").trigger("click");		
			}
		}, "JSON")
	})
	
	$(document).on('click','.redeemPrizeDontBeA',function(){
		
		var id = $(this).attr('prop');
		var filename = $(this).attr('filename');
		
		$.post(basedomain+'pursuit/ajaxRedeem', {redeemViewDontBeA:true}, function(data){
			
			if (data.status == true){
				
				var title = "";
				var titleComplete = "";
				var titlePhrase = "";
				var titlehidden = "";
				
				if (data.validate == 1){
					title += "Redeem"; 
					titleComplete += "You ve completed the phrase!"; 
					titlePhrase += "DON'T BE A";
					titlehidden += '"DON\'T BE A"';
				}else{
					title += ""; 
					titleComplete += ""; 
					titlePhrase += ""; 
				}
				
				var html = viewredeemhtml(data.dontbeamaybe,data.validate,data.has,filename,titlePhrase,id,"redeemPrizeDontBeA","redeemdontbea","continuetodontbe", titlehidden,0);
								
				$(".redeem-popup").trigger("click");			
				$(".contentredeem").html(html);
				$(".titleredeem").html(title);
				$(".titleredeemcomplete").html(titleComplete);
				$(".titleredeemphrase").html(titlePhrase);
			}else{
				var confirmationhtml = locale.alreadyredeem;
				$(".contentmyaccount").html(confirmationhtml);
				$(".my-account-profile").trigger("click");		
			}
		}, "JSON")
	})
	
	$(document).on('click','.redeemPrizeDontBeAMaybe',function(){
		
		var id = $(this).attr('prop');
		var filename = $(this).attr('filename');
		
		$.post(basedomain+'pursuit/ajaxRedeem', {redeemViewDontBeAMaybe:true}, function(data){
			
			if (data.status == true){
				
				var title = "";
				var titleComplete = "";
				var titlePhrase = "";
				var titlehidden = "";
				
				if (data.validate == 1){
					title += "Redeem"; 
					titleComplete += "You ve completed the phrase!"; 
					titlePhrase += "DON'T BE A MAYBE";
					titlehidden += '"DON\'T BE A MAYBE"';
				}else{
					title += ""; 
					titleComplete += ""; 
					titlePhrase += ""; 
				}
				// data,validate,has,filename,titlePhrase,id,redeemprizetype,redeembutton,continuebutton,titlehidden
				var html = viewredeemhtml(data.complete,data.validate,data.has,filename,titlePhrase,id,"redeemPrizeDontBeAMaybe","","redeemdontbeamaybe", titlehidden,1);
								
				$(".redeem-popup").trigger("click");			
				$(".contentredeem").html(html);
				$(".titleredeem").html(title);
				$(".titleredeemcomplete").html(titleComplete);
				$(".titleredeemphrase").html(titlePhrase);
			}else{
				var confirmationhtml = locale.alreadyredeem;
				$(".contentmyaccount").html(confirmationhtml);
				$(".my-account-profile").trigger("click");		
			}
		}, "JSON")
	})
	
	$(document).on('click','.continuetodontbe', function(){
	
		window.location.href=basedomain+'pursuit/join';
	})
	$(document).on('click','.successRedeem', function(){
	
		window.location.href=basedomain+'pursuit/join';
	})
	
	$(document).on('click','.showPopupTnc',function(){
		
		$('.tnc').trigger('click');
		
	})
	
	function redeemConfirm()
	{
		$(document).on('dblclick','.changeletter',function(){
			var thisobject = $(this);
			var confirmationhtml = "";
			
			confirmationhtml += "<div><div class='row'> <h1>Are you sure ?<br/> This action can't be undone</h1></div> ";
			confirmationhtml += "<div class='row' ><a href='javascript:void(0)' class='button btn_red btn_red3  okchangeletter ' ><span>Yes</span></a> ";
			confirmationhtml += "<img src='"+basedomain+"assets/images/maybe.png' style=' position: relative; top: 7px; ' /> ";
			confirmationhtml += "<a href='javascript:void(0)' class='button btn_red btn_red3 cancelchangeletter' ><span>No</span></a></div></div> ";
			
		
				$(".contentmyaccount").html(confirmationhtml);
				$(".my-account-profile").trigger("click");		
		
			
				$(document).on('click','.okchangeletter', function(){
					html = "please wait";
					$(".contentmyaccount").html(html);
					$(".my-account-profile").trigger("click");		
					
					var letterID = thisobject.attr('prop');
					var letter = thisobject.attr('prop');
					//alert(letterID);
					$.post(basedomain+'pursuit/setletter', {setletter:true, id:letterID}, function(data){
						var html = "";
						var stat = "<div class='row' >"+locale.failed+"</div>";
						
						if (data.status == true){
							
								$('#'+letter).addClass('red');
								//$('.letterBox').html(html);
								stat = "<div class='row' >Success</div>";
								
						}
						
						
						if(data.phrase){
							$("."+data.phrase).trigger("click");	
						}
						
						$(".contentmyaccount").html(stat);
						$(".my-account-profile").trigger("click");	
						
					},"JSON")
			
				});
				
				$(document).on('click','#fancybox-close, #fancybox-overlay, .cancelchangeletter', function(){
						window.location = basedomain+'pursuit/join';
						//$("#fancybox-close").trigger('click');
				});
				$("#fancybox-outer").addClass('min102');
			
		})
	}
	
$(document).ready(function(){ merchsync = setTimeout(getmerchstock, 1000); });
		function getmerchstock(){
				$.post(basedomain+'pursuit/ajaxPrizeRedeem',function(data){
					if(data){
						$.each(data,function(e,i){
							$(".thisstock_"+e).html(i);
							if(i<=0)$('.merch_'+e).addClass('noneactive');
							if(i>0)$('.merch_'+e).removeClass('noneactive');
						})	
							
						merchsync = setTimeout(getmerchstock, 10000);
					}
			},"JSON");	
		}