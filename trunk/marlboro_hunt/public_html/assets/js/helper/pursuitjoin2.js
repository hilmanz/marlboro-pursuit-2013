/*trade Floor*/

	
	
	$(document).on('click','.showpopuptradingfloor',function(){
		
		$.post(basedomain+'pursuit/ajax', {popuptradingfloor:true}, function(data){
			var html = "";
			if (data.status == true){
				
					//console.log(e.name);
				
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
					$.each(data.rec, function(i,e){
						html += "  <tr>";
						html += "    <td class='usertrade'>";
						//html += "        <a class='thumb-small fl'><img src='"+basedomain+"public_assets/user/photo/"+e.image_profile+"' /></a>";
						if (e.image_profile){
							html += "		 <a class='thumb-small fl'><img src='"+basedomain+"public_assets/user/photo/"+e.image_profile+"'/></a>";
						}else{
							html += "		 <a class='thumb-small fl'><img src='"+basedomain+"assets/content/thumb_user.jpg'/></a>";
						}

						html += "        <div class='entry-left fl'>";
						html += "            <h4 class='username'>"+e.name+"</h4>";
						html += "        </div>";
						html += "    </td>";
						html += "    <td class='letter-looking'><img src='"+basedomain+"assets/content/font/"+e.targetCodeName.toLowerCase()+".png' /></td>";
						html += "    <td class='letter-totrade'><img src='"+basedomain+"assets/content/font/"+e.sourceCodeName.toLowerCase()+".png' /></td>";
						html += "    <td>";
						//html += "		<a href='javascript:void(0)' class='btn_black canceltrade ' prop='"+e.id+"'>Cancel</a>";
						html += "		<a href='javascript:void(0)' class='btn_black showpopuptradeLetter' prop='"+e.id+"'>Trade</a>";
						html += "	</td>";
						html += "  </tr>";
					})
				html += "</tbody>";
				html += "</table>";
				
				
				
			}else{
				html += "Player not found";
			}
			
			var searchFr = "";
			
			searchFr += "<input type='text' value='' id='searchTrading'/>";
            searchFr += "<input type='submit' value='SEARCH' class='searchTradingFloor'/>";
					
			$(".pursuit-popup").trigger("click");		
			$(".pursuitsearchbox").html(searchFr);	
			$(".titlePopupTradealetter").html(locale.tradealatter);	
			$(".contentpursuit").html(html);
			$(".titlepursuit").html(locale.tradingfloor);
			
		},"JSON")
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
				if (e.image_profile){
					html += "		 <a class='thumb-small fl'><img src='"+basedomain+"public_assets/user/photo/"+e.image_profile+"'/></a>";
				}else{
					html += "		 <a class='thumb-small fl'><img src='"+basedomain+"assets/content/thumb_user.jpg'/></a>";
				}
                
                html += "            <div class='entry-left fl'>";
                html += "                <h4 class='username'>"+e.myname+"</h4>";
                html += "            </div>";
                html += "        </div>";
                html += "        <div class='preview-box'>";
                html += "        	<img src='"+basedomain+"assets/content/font/"+e.sourceCodeName.toLowerCase()+".png' />";
                html += "        </div>";
          		html += "		<div class='entry-popup-center'>";
                html += "        	<p>yeah here you go!</p>";
                html += "        </div>";
          		html += "    </div>";
                html += "</li>";
                html += "<li class='col2'>";
                html += "    <div class='entry-popup'>";
                html += "    	<div class='usertrade'>";
                //html += "            <a class='thumb-small fl'><img src='"+basedomain+"assets/content/thumb_user.jpg' /></a>";
				if (e.image_profile){
					html += "		 <a class='thumb-small fl'><img src='"+basedomain+"public_assets/user/photo/"+e.image_profile+"'/></a>";
				}else{
					html += "		 <a class='thumb-small fl'><img src='"+basedomain+"assets/content/thumb_user.jpg'/></a>";
				}

                html += "            <div class='entry-left fl'>";
                html += "                <h4 class='username'>"+e.name+"</h4>";
                html += "            </div>";
                html += "        </div>";
                html += "        <div class='preview-box'>";
                html += "        	<img src='"+basedomain+"assets/content/font/"+e.targetCodeName.toLowerCase()+".png' />";
                html += "        </div>";
          		html += "		<div class='entry-popup-center'>";
               	html += "		 <a href='javascript:void(0)' class='btn_black3 confirmTrade' prop='"+e.id+"'>Confirm Trade</a>";
                html += "        </div>";
          		html += "    </div>";
                html += "</li>";
				
				$(".trade-confirm").trigger("click");		
				$(".confirmContent").html(html);
				$(".confirmTitle").html(locale.traderequest);
				
			}else{
				html += "<h1>Trade not allowed</h1>";
				
				$(".post-trade-message").trigger("click");		
				$(".postradecontent").html(html);
				
			}
			
			
			
			
		},"JSON")
	})
	


/*untuk submit code dari task list*/

	
	
	
	$(document).on('click', '#fancybox-close , #fancybox-overlay', function(){
		
		//location.href = basedomain+"pursuit/join";
		
	})
	
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
					html += "<a class='thumb-small fl'><img src='"+basedomain+"assets/content/font/"+e.toCode.toLowerCase()+".png' /></a>";
					html += "</li>";
					html += "<li class='col3'>";
                	html += "<h4>To Trade With</h4>";
					html += "<a class='thumb-small fl'><img src='"+basedomain+"assets/content/font/"+e.fromCode.toLowerCase()+".png' /></a>";
					html += "</li>";
					html += "<li class='col3'>";
					if (e.n_status == 1){
                	html += "<a class='icon_check' href='#'>&nbsp;</a>";
					}else{
					html += "<a class='icon_cross cancelTrade' href='#' prop='"+e.id+"'>&nbsp;</a>";
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
				console.log(true);
				
			}else{
				html += "<h1>"+locale.failed+"</h1>";
				console.log(false);
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
			console.log(statusSend);
			
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
					html += "		<a class='thumb-small fl'><img src='"+e.image+"' /></a>";
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
						html += "		<a class='thumb-small fl'><img src='"+e.image+"' /></a>";
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
						html += "		<a class='thumb-small fl'><img src='"+e.image+"' /></a>";
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
				
					$.each(data.rec, function(i,e){
		
					html += "<div class='row'>";
					//html += "		<a class='thumb-small fl'><img src='"+basedomain+"assets/content/thumb_user.jpg'/></a>";
					if (e.image_profile){
						html += "		 <a class='thumb-small fl'><img src='"+basedomain+"public_assets/user/photo/"+e.image_profile+"'/></a>";
					}else{
						html += "		 <a class='thumb-small fl'><img src='"+basedomain+"assets/content/thumb_user.jpg'/></a>";
					}

					html += "		<div class='entry-left fl'>";
					html += "		  <p class='entry-update'>"+e.action_value+"</p>";
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
				
					$.each(data.rec, function(i,e){
		
					html += "<div class='row'>";
					//html += "		<a class='thumb-small fl'><img src='"+basedomain+"assets/content/thumb_user.jpg'/></a>";
					if (e.image_profile){
						html += "		 <a class='thumb-small fl'><img src='"+basedomain+"public_assets/user/photo/"+e.image_profile+"'/></a>";
					}else{
						html += "		 <a class='thumb-small fl'><img src='"+basedomain+"assets/content/thumb_user.jpg'/></a>";
					}

					html += "		<div class='entry-left fl'>";
					html += "		  <p class='entry-update'>"+e.action_value+"</p>";
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
					if (e.image_profile){
						html += "		 <a class='thumb-small fl'><img src='"+basedomain+"public_assets/user/photo/"+e.image_profile+"'/></a>";
					}else{
						html += "		 <a class='thumb-small fl'><img src='"+basedomain+"assets/content/thumb_user.jpg'/></a>";
					}

					html += "		<div class='entry-left fl'>";
					html += "		  <p class='entry-update'>"+e.action_value+"</p>";
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
				
					$.each(data.rec, function(i,e){
		
					html += "<div class='row'>";
					html += "<ul class='columns-3 clearfix'>";
					html += "<li class='col3'>";
                	html += "<h4>Looking For</h4>";
					html += "<a class='thumb-small fl'><img src='"+basedomain+"assets/content/font/"+e.toCode.toLowerCase()+".png' /></a>";
					html += "</li>";
					html += "<li class='col3'>";
                	html += "<h4>To Trade With</h4>";
					html += "<a class='thumb-small fl'><img src='"+basedomain+"assets/content/font/"+e.fromCode.toLowerCase()+".png' /></a>";
					html += "</li>";
					html += "<li class='col3'>";
					if (e.n_status == 1){
                	html += "<a class='icon_check' href='#'>&nbsp;</a>";
					}else{
					html += "<a class='icon_cross cancelTrade' href='#' prop='"+e.id+"'>&nbsp;</a>";
					}
					html += "</li>";
					html += "</ul>";

					html += "</div>";

					})
					
				
				
				
			}else{
				html += locale.notrade;
			}
			
			$(".mytradeList").html(html);
			
		},"JSON")
		
	})
	


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
		$.post(basedomain+'pursuit/task',{taskdetail:true,id:idTask},function(data){
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
				html+="         	<p>Please be reminded that as per the Terms and Conditions, all photos, images or designs submitted will become the property of PM. No photos of minors allowed. The photo you upload will be subject to moderation.</p>";
				html+="         	<input type='file' name='image' id='imagephotoprofile' />";
				html+="    		    <p class='infoText'>Maximum File Size of 250KB</p>";
				html+="            </div>";
				html+="             <div class='row'>";
				html+="           	<div id='photo-preview' class='previewer-syncro'>";
				html+="                </div>";
				html+="             </div>";
				html+="            <div class='row rowSubmit' >";
				html+="                <input type='hidden' value='"+idTask+"' name='taskid' >";
				html+="                <input type='submit' class='button btnRed' value='SAVE' id='savephotoprofile' >";
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
				
					
		
					html += "<li class='col2'>";
					html += "		<div class='entry-popup'>";
					html += "			<h3 class='black_title'>What I'm Looking For</h3>";
					html += "			<input type='hidden' class='lookLeter' value=''>";
					html += "			<div class='preview-box prevCode'>";
					html += "			</div>";
					html += "			<div class='letter-box'>";
					
					$.each(data.rec.needcode, function(i,e){
					
					html += "				<img src='"+basedomain+"assets/content/font/"+e.toLowerCase()+".png' prop='"+data.rec.codeID[i]+"' class='code'/>";
								
					})
					html += "			</div>";
					html += "		</div>";
					html += "	</li>";
					html += "	<li class='col2'>";
					html += "		<div class='entry-popup'>";
					html += "			<h3 class='black_title'>Im Trading The Letter</h3>";
					html += "			<input type='hidden' class='tradeLeter' value=''>";
					html += "			<div class='preview-box prevMyCode'>";
					html += "			</div>";
					html += "			<div class='letter-box'>";
					$.each(data.rec.mycode, function(i,e){
					
					html += "				<img src='"+basedomain+"assets/content/font/"+e.toLowerCase()+".png' prop='"+data.rec.mycodeID[i]+"' class='mycode'/>";
								
					})
					html += "			</div>";
					html += "		</div>";
					html += "	</li>";

					
			}else{
				html += locale.notrade;
			}
			
			$(".trade-confirm").trigger("click");		
			$(".confirmContent").html(html);
			$(".posttradetitlebutton").html("Post On Trading Floor");
			$(".confirmTitle").html(locale.traderequest);
			
		},"JSON")
	}
	



	
	
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
					var stat = "<div class='row' >Failed</div>";
					
					if (data.status == true){
						
							$('#'+letter).addClass('red');
							//$('.letterBox').html(html);
							stat = "<div class='row' >Success</div>";
							
					}
					
					$(".contentmyaccount").html(stat);
					$(".my-account-profile").trigger("click");	
					
					if(data.phrase){
						$("."+data.phrase).trigger("click");	
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
		
		
		$.post(basedomain+'pursuit/ajaxRedeem', {redeem:true, redeemDont:true, id:code}, function(data){
			var html = "";
			
			if (data.status == true){
				
				html += "<div class='row centering'>";
				html += "	<table>";
				html += "    	<tr>";
				html += "        	<td valign='top' align='center'>";
				html += "                <div class='frame'><div class='boxPrizeBig'>";
				html += "                    <a href='#' class='thumb-prizeBig'><img src='"+basedomain+"public_assets/merchandiseStock/"+data.rec.image+"' /></a>";
				html += "           			 <h6>"+data.rec.detail+".<br /><br />";
				html += "                        Please wait for our e-mail informing you";
				html += "                        of its delivery date and claiming requirements.</h6>";
				html += "                </div></div>";
				html += "            </td>";
				html += "        </tr>";
				html += "    </table>";
				html += "</div>";
				html += "<div class='row centering'>";
				html += "	<a class='btn_black3 successRedeem' href='#'>OK</a>";
				html += "</div>";
				
			}else{
				html += "<h1>Status False</h1>";
			}
			//console.log('ada');
			$(".redeem-popup").trigger("click");			
			$(".contentredeem").html(html);
			$(".titleredeem").html('Congratulations!');
			//$(".titleredeemcomplete").html('You ve completed the word!');
			//$(".titleredeemphrase").html('DONT');
			
		},"JSON")
	})
	
	$(document).on('click','.redeemprize',function(){
		$.post(basedomain+'pursuit/ajaxRedeem', {redeemprize:true}, function(data){	
			
			if (data.status ==true){
				if(data.phrase){
					
					$("."+data.phrase).trigger("click");	
				}
			}else{
				var stat = "<div class='row' >You not have phrase to redeem</div>";
				$(".contentmyaccount").html(stat);
				$(".my-account-profile").trigger("click");	
			}
			
			
			
		},"JSON")
	})
	
	
	$(document).on('click','.redeemPrizeDont',function(){
		
		var id = $(this).attr('prop');
		var filename = $(this).attr('filename');
		
		$.post(basedomain+'pursuit/ajaxRedeem', {chooseredeem:true, redeemViewDont:true, id:id}, function(data){
			
			if (data.status == true){
			
				var title = "";
				var titleComplete = "";
				var titlePhrase = "";
				
				if (data.validate == 1){
					title += "Congratulations!"; 
					titleComplete += "You ve completed the word!"; 
					titlePhrase += "DONT"; 
				}else{
					title += ""; 
					titleComplete += ""; 
					titlePhrase += ""; 
				}
				
				var html = "";
				html += "<div class='row centering'>";
				html += "            	<table>";
				html += "                	<tr>";
				html += "                    	<td valign='top'>";
				html += "                            <div class='frame'><div class='boxPrizeBig'>";
				html += "                                <a href='#' class='thumb-prizeBig' ><img src='"+basedomain+"public_assets/merchandiseStock/"+filename+"'/></a>";
				html += "                     		    <h6>"+data.dont.detail+"</h6>";
				html += "                            </div></div>";
				html += "                        </td>";
				html += "                    </tr>";
				html += "                </table>";
				html += "            </div>";
				html += "            <div class='row centering'>";
				html += "				<h6 class='m15'>CHOOSE AND REDEEM ANY OF PRIZE BELOW:</h6>";
				html += "            </div>";
				html += "            <div class='row centering'>";
				html += "            	<table>";
				html += "                	<tr>";
				
				$.each(data.dontbe, function(i,e){
					
					html += "                    	<td valign='top'>";
					html += "                            <div class='boxPrize'>";
					html += "                                <a href='javascript:void(0)' class='thumb-prize showPopup1'><img src='"+basedomain+"public_assets/merchandiseStock/"+e.image+"' /></a>";
					html += "                                <h6>"+data.dontbe[0].detail+"</h6>";
					html += "                            </div>";
					html += "                        </td>";
				})
				
				html += "                    </tr>";
				html += "                </table>";
				html += "            </div>";
				html += "            <div class='row centering'>";
				if (data.validate == 1){
				html += "					<a class='btn_black3 redeemdont' href='#popup-redeem-claim' prop='"+id+"'>REDEEM</a>";
				}
				html += "				<a class='btn_black3 continuetodontbe' href='javascript:void(0)'>CONTINUE</a>";
				html += "            </div>";
				
				$(".redeem-popup").trigger("click");			
				$(".contentredeem").html(html);
				$(".titleredeem").html(title);
				$(".titleredeemcomplete").html(titleComplete);
				$(".titleredeemphrase").html(titlePhrase);
			}
		}, "JSON")
	})
	
	$(document).on('click','.redeemPrizeDontBe',function(){
		
		var id = $(this).attr('prop');
		var filename = $(this).attr('filename');
		
		$.post(basedomain+'pursuit/ajaxRedeem', {chooseredeem:true, redeemViewDontBe:true}, function(data){
			
			if (data.status == true){
				var title = "";
				var titleComplete = "";
				var titlePhrase = "";
				
				if (data.validate == 1){
					title += "Congratulations!"; 
					titleComplete += "You ve completed the word!"; 
					titlePhrase += "DONT BE"; 
				}else{
					title += ""; 
					titleComplete += ""; 
					titlePhrase += ""; 
				}
				
				var html = "";
				html += "<div class='row centering'>";
				html += "            	<table>";
				html += "                	<tr>";
				html += "                    	<td valign='top' align='center'>";
				html += "                            <div class='boxPrize'>";
				html += "                                <a href='#' class='thumb-prize' ><img src='"+basedomain+"public_assets/merchandiseStock/"+filename+"'/></a>";
				html += "                     		    <h6>"+data.dontbe[0].detail+"</h6>";
				html += "                            </div>";
				html += "                        </td>";
				html += "                    </tr>";
				html += "                </table>";
				html += "            </div>";
				html += "            <div class='row centering'>";
				html += "            	<h6>Do you want to redeem the prize that corresponds to this word, or do you want to continue with The Hunt?</h6>";
				if (data.validate == 1){
				html += "					<a class='btn_black3 redeemdont' href='#popup-redeem-claim' prop='"+id+"'>REDEEM</a><br /><br />";
				}
				
				html += "				<h6>If YOU CONTINUE AND COMPLETE THE NEXT WORD, YOU CAN CHOOSE TO REDEEM THESE PRIZES:</h6>";
				html += "            </div>";
				html += "            <div class='row centering'>";
				html += "            	<table>";
				html += "                	<tr>";
				
				$.each(data.dontbea, function(i,e){
					
					html += "                    	<td valign='top' align='center'>";
					html += "                            <div class='boxPrize'>";
					html += "                                <a href='javascript:void(0)' class='thumb-prize showPopup1'><img src='"+basedomain+"public_assets/merchandiseStock/"+e.image+"' /></a>";
					html += "                                <h6>"+data.dontbea[0].detail+"</h6>";
					html += "                            </div>";
					html += "                        </td>";
				})
				
				html += "                    </tr>";
				html += "                </table>";
				html += "            </div>";
				html += "            <div class='row centering'>";
				html += "				<a class='btn_black3 continuetodontbe' href='#'>CONTINUE</a>";
				html += "            </div>";
				
				$(".redeem-popup").trigger("click");			
				$(".contentredeem").html(html);
				$(".titleredeem").html(title);
				$(".titleredeemcomplete").html(titleComplete);
				$(".titleredeemphrase").html(titlePhrase);
			}
		}, "JSON")
	})
	
	$(document).on('click','.redeemPrizeDontBeA',function(){
		
		var id = $(this).attr('prop');
		var filename = $(this).attr('filename');
		
		$.post(basedomain+'pursuit/ajaxRedeem', {chooseredeem:true, redeemViewDontBeA:true}, function(data){
			
			if (data.status == true){
				
				var title = "";
				var titleComplete = "";
				var titlePhrase = "";
				
				if (data.validate == 1){
					title += "Congratulations!"; 
					titleComplete += "You ve completed the word!"; 
					titlePhrase += "DONT BE A"; 
				}else{
					title += ""; 
					titleComplete += ""; 
					titlePhrase += ""; 
				}
				
				var html = "";
				html += "<div class='row centering'>";
				html += "            	<table>";
				html += "                	<tr>";
				html += "                    	<td valign='top' align='center'>";
				html += "                            <div class='boxPrize'>";
				html += "                                <a href='#' class='thumb-prize' ><img src='"+basedomain+"public_assets/merchandiseStock/"+filename+"'/></a>";
				html += "                     		    <h6>"+data.dontbea[0].detail+"</h6>";
				html += "                            </div>";
				html += "                        </td>";
				html += "                    </tr>";
				html += "                </table>";
				html += "            </div>";
				html += "            <div class='row centering'>";
				html += "            	<h6>Do you want to redeem the prize that corresponds to this word, or do you want to continue with The Hunt?</h6>";
				if (data.validate == 1){
				html += "					<a class='btn_black3 redeemdont' href='#popup-redeem-claim' prop='"+id+"'>REDEEM</a><br /><br />";
				}
				
				html += "				<h6>If YOU CONTINUE AND COMPLETE THE NEXT WORD, YOU CAN CHOOSE TO REDEEM THESE PRIZES:</h6>";
				html += "            </div>";
				html += "            <div class='row centering'>";
				html += "            	<table>";
				html += "                	<tr>";
				
				$.each(data.dontbeamaybe, function(i,e){
					
					html += "                    	<td valign='top' align='center'>";
					html += "                            <div class='boxPrize'>";
					html += "                                <a href='javascript:void(0)' class='thumb-prize showPopup1'><img src='"+basedomain+"public_assets/merchandiseStock/"+e.image+"' /></a>";
					html += "                                <h6>"+data.dontbeamaybe[0].detail+"</h6>";
					html += "                            </div>";
					html += "                        </td>";
				})
				
				html += "                    </tr>";
				html += "                </table>";
				html += "            </div>";
				html += "            <div class='row centering'>";
				html += "				<a class='btn_black3 continuetodontbe' href='#'>CONTINUE</a>";
				html += "            </div>";
				
				$(".redeem-popup").trigger("click");			
				$(".contentredeem").html(html);
				$(".titleredeem").html(title);
				$(".titleredeemcomplete").html(titleComplete);
				$(".titleredeemphrase").html(titlePhrase);
			}
		}, "JSON")
	})
	
	$(document).on('click','.continuetodontbe', function(){
	
		window.location.href=basedomain+'pursuit/prize';
	})
	$(document).on('click','.successRedeem', function(){
	
		window.location.href=basedomain+'pursuit/prize';
	})
	