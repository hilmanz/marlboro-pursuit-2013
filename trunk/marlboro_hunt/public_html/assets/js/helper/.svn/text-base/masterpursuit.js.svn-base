
			
	$(document).ready(function(){
		if(isLogin) {
			// $('.listmessageinbox').hide();
			// $('.contentMessageInbox').hide();
			
			/* close it */	return false;
			$.post(basedomain+'pursuit/task', {taskmessage:true}, function(data){
				var html = "";
				var paging = "";
				var total = 0;
				
				if (data.status == true){
					
						$.each(data.rec, function(i,e){
						
							var datetimes = e.datetime.split(' ');
							var dates = datetimes[0].split('-');
						
							html += "<div class='row'>";
							html += "     <span class='date'>"+e.name+" : "+dates[2]+" / "+dates[1]+" / "+dates[0]+"</span>";
							html += "     <p>"+e.message+" ";
							if(e.type==1) html+= "<a href='javascript:void(0)' class='popup-trade-message reply-message' prop='"+e.fromid+"' desc='"+e.name+"'> reply </a>";
							html+="</p>";
							html += "  </div>";			
							
						})
				}else{
					return false;
					// html += locale.nomessage;
				}
				
				if(total>=5){
					
					paging += " <span class='page-num'>11</span> <span class='page'>Of</span>";	 
					paging += "<span class='page-num last-margin'> "+data.total+" </span> ";	
                    paging += "<a class='prev-Page' href='javascript:void(0)'><</a>";	
                    paging += "<a class='next-Page' href='javascript:void(0)'>></a>";
					// html+="<div class='row paging'>";
					// html+="<a class='prev' href='#'>PREV</a>";
					// html+="<a class='next' href='#'>NEXT</a>";
					// html+="</div>";
				}
				
				$(".startpageinbox").attr('total',data.total);
				$(".listmessageinbox").html(html);
				$(".pagingInboxMessage").html(paging);
			
				
			},"JSON")
			
			
		}
	})
	
	
	// $(".open-inbox").click(function(event){
	
	
	$(document).on('click', '.selectAllMessage', function (event) {
		event.preventDefault();
		
			var ele = $('.inboxtable').find('input');
			if(ele.is(':checked')){
				ele.prop('checked', false);
				$('.inboxtable').removeClass('admin_checked');
			}else{
				ele.prop('checked', true);
				$('.inboxtable').addClass('admin_checked');
			}
		
	});
	
	$(document).on('click', '.deletetAllMessage', function() {
		//console.log('foo');
		var val = [];
		var type = [];
		var fromadmin = [];
		$(':checkbox:checked').each(function(i){
		  val[i] = $(this).val();
		  type[i] = $(this).attr('prop');
		  fromadmin[i] = $(this).attr('fromadmin')
		});
		//console.log(fromadmin);
		$.post(basedomain+'home/ajax', {deletemessage:true, type:type, id:val, admin: fromadmin}, function(data){
			
			if (data.status == true){
				// console.log('sukses');
				// getinboxcount();
				$(".listmessageinbox").html("");
				getmessaging();
			}else{
				console.log('gagal');
			}
			
		},"JSON")
		
	})
	
	
	  
	$(document).on('click', '.backinbox', function (event) {
		event.preventDefault();
		// $('.listmessageinbox').show();
		// $('.contentMessageInbox').hide();
		// $('.listinbox').show();
		
		
		
	});
	
	/*
	$(document).on('click', '.messagepreview', function (event) {
		event.preventDefault();
		
		var id = $(this).attr('idmesg');
		var type = $(this).attr('replay');
		
		$.post(basedomain+'pursuit/readmessage',{id:id, type:type},function(data){
			// console.log(data);
			getinboxcount();
		});
		
		$('.contentMessageInbox').show();
		$('.listinbox').hide();
	});
	*/
	
	function readMessage(data)
	{
		// [data.rec.id], [data.rec.image_profile], [subject], [content], [data.rec.name], [replay], [datetimes]
		
		var html = "";
		
		html += "<div id='read-message' class='inbox-message-detail'>";
		html += "<ul class='columns-isi-inbox clearfix'>";
		if (data[4]){
			html += "<li class='col2-left'>";
			html += "	<div class='row'>";
			html += "	<div class='user-thumb'>";
			html += "		<img src='"+basedomain+"public_assets/user/photo/"+data[1]+"' width='50' height='50'/>";
			html += "	</div>";
			html += "	</div>";
			html += "</li>";
		}
		html += "<li class='col2-right'>";
		html += "	<div class='row messageInfo'>";
		html += "			<h1>"+data[2]+"</h1>";
		if (data[4]){
		html += "			<h4>Sender : "+data[4]+"</h4>";
		}else{
		var datetimes = data[6].split(' ');
		var dates = datetimes[0].split('-');
	 
		// html += "			<h4>Sender : MARLBORO</h4>";
		html += "			<h4> "+dates[2]+" / "+dates[1]+" / "+dates[0]+" </h4>";
		}
		
		html += "		<div class='messageText'>";
		if (data[5]==0){
			
			var showSubject = "";
			var parseNotif = data[3].split('|');
			var subjectNotif = parseNotif[0]+'<br>'+parseNotif[1];
			if (subjectNotif) substr = subjectNotif;
			else substr = data[3];
			html += "		<p>"+substr+"</p>";
		}else{
		html += "		<p>"+data[3]+"</p>";
		}
		
		html += "		</div>";
		html += "	</div>";
		// html += "	<div class='row messageInfo'>";
		// html += "			<h4>YOU</h4>";
		// html += "		<div class='messageText'>";
		// html += "		<p>Hello Miguel! I don’t have a phone number.. Sorry</p>";
		// html += "		</div>";
		// html += "	</div>";
		html += "</li>";
		html += "</ul>";
		if (data[5]==1){
		html += "<div class='row message-reply'>";
		html += "<label>Reply</label>";
		// html += "<input id='reply' class='reply-message replyContent' type='text' name='reply' value=''>";
		html += "<input class='replyContent1' type='text' value=''>";
		html += "<input class='subject' type='hidden' name='subject' value='"+data[2]+"'>";
		html += "</div>";
		html += "<div class='row'>";
		html += "<span style='position: relative; text-align: right;'  class='messagestatusreply' ></span>";
		html += "<a class='fr deleteInbox replayinbox' href='javascript:void(0)' prop='"+data[0]+"'>SEND</a>";
		html += "</div>";
		}
		html += "</div>";
		
		return html;
	}
	
	$(document).on('click', '.replayinbox', function () {
		
		
		var id = $(this).attr('prop');
		var subject = $('.subject').val();
		var messg = $('.replyContent1').val();
		//console.log(subject);
		//console.log(messg);
		if(messg=='') return false;
		$(".messagestatusreply").html(" <img src='"+basedomain+"assets/images/loader.gif' /> ");
		
		$.post(basedomain+'pursuit/replayMessage',{reply:true, id:id,subject:subject,messg:messg},function(data){
			var html = "";
			var getStatus = data.status;
			if (getStatus == true){
				html += locale.messagesent;
			}else{
				html += locale.messagefail;
			}
			
			 					
			$('.messagestatusreply').html(html);
			$('.replyContent1').val("");
		},"JSON");
	})
	
	$(document).on('click', '.inboxbutton', function () {
		// $('.composebutton').show();
		$(".startpageinbox").attr('page','0');
			$(".startpageinbox").attr('lastpage','0');
		$.post(basedomain+'pursuit/ajax',{setinboxstatus:true, param:'1'},function(data){
			// console.log(data);
			
		},"JSON");
		/* 
		$('.sentbutton').removeClass('active-inbox');
		$('.trashbutton').removeClass('active-inbox');
		$('.notificationbutton').removeClass('active-inbox');
		$('.inboxbutton').removeClass('active-inbox');
		 */
		$('.allbuttonnotifbox').removeClass('active-inbox');
		$(this).addClass('active-inbox');
		inboxcounting();
		// $("#inboxcounting").trigger("click");
		// $('.inboxbutton').addClass('active-inbox');
		
		
	});
	
	$(document).on('click', '.sentbutton', function () {
		// $('.composebutton').show();
		
			/* 
		$('.sentbutton').removeClass('active-inbox');
		$('.trashbutton').removeClass('active-inbox');
		$('.notificationbutton').removeClass('active-inbox');
		$('.inboxbutton').removeClass('active-inbox');
		 */
		$('.allbuttonnotifbox').removeClass('active-inbox');
			$(this).addClass('active-inbox');
			$(".startpageinbox").attr('page','0');
			$(".startpageinbox").attr('lastpage','0');
		$.post(basedomain+'pursuit/ajax',{setinboxstatus:true, param:'3'},function(data){
			//console.log(data);
			
		},"JSON");
		
		inboxcounting();
		// $("#inboxcounting").trigger("click");
		
		
	});
	
	$(document).on('click', '.notificationbutton', function () {
		// $('.composebutton').show();
		/* 
		$('.sentbutton').removeClass('active-inbox');
		$('.trashbutton').removeClass('active-inbox');
		$('.notificationbutton').removeClass('active-inbox');
		$('.inboxbutton').removeClass('active-inbox');
		 */
		$('.allbuttonnotifbox').removeClass('active-inbox');
			$(this).addClass('active-inbox');
			$(".startpageinbox").attr('page','0');
			$(".startpageinbox").attr('lastpage','0');
		$.post(basedomain+'pursuit/ajax',{setinboxstatus:true, param:'2'},function(data){
			//console.log(data);
		},"JSON");
		
		
		// $('.notificationbutton').addClass('active-inbox');
		
		inboxcounting();
		// $("#inboxcounting").trigger("click");
		
		
		
		
	});
	
	$(document).on('click', '.trashbutton', function () {
		// $('.composebutton').show();
		/* 
		$('.sentbutton').removeClass('active-inbox');
		$('.trashbutton').removeClass('active-inbox');
		$('.notificationbutton').removeClass('active-inbox');
		$('.inboxbutton').removeClass('active-inbox');
		 */
		$('.allbuttonnotifbox').removeClass('active-inbox');
			$(this).addClass('active-inbox');
		$(".startpageinbox").attr('page','0');
			$(".startpageinbox").attr('lastpage','0');
		$.post(basedomain+'pursuit/ajax',{setinboxstatus:true, param:'4'},function(data){
			//console.log(data);
		},"JSON");
		
		
		// $('.trashbutton').addClass('active-inbox');
		inboxcounting();
		// $("#inboxcounting").trigger("click");
		
		
		
	});
	
	$(document).on('click', '.composebutton', function () {
		$(".startpageinbox").attr('page','0');
			$(".startpageinbox").attr('lastpage','0');
		$.post(basedomain+'pursuit/ajax',{setinboxstatus:true, param:'5'},function(data){
			// console.log(data);
		},"JSON");
		
		// $("#inboxcounting").trigger("click");
		$('.composebutton').hide();
	/* 
		$('.sentbutton').removeClass('active-inbox');
		$('.trashbutton').removeClass('active-inbox');
		$('.notificationbutton').removeClass('active-inbox');
		$('.inboxbutton').removeClass('active-inbox');
		 */
		$('.allbuttonnotifbox').removeClass('active-inbox');
		
	});
	
	$(document).on('click', '#selectallinbox', function () {
	
		$('.selectedId').prop('checked', this.checked);
	});

	$(document).on('change', '.selectedId', function () {
	
		var check = ($('.selectedId').filter(":checked").length == $('.selectedId').length);
		$('#selectall').prop("checked", check);
	});
	

	function generateInbox(data)
	{
		var rec = data;
		var html = "";
		// console.log(rec);
		html += "<table class='inboxtable' id='alternatecolor'>";
//		html += "	<thead>";
	//	html += "		<tr>";
		//html += "			<th colspan='2'>Subject</th>";
		//html += "			<th>Sender</th>";
		//html += "		</tr>";
		//html += "	</thead>";
		html += "	<tbody>";
		if(rec){
		
			html += "		<tr>";
			html += "			<td>";
	
			/* if (rec[0].type==1) //tipe notif */
			/* if(rec[0].n_status!=2){*/
			html += "<span class='alltext'>ALL</span> <input type='checkbox' name='' value='' id='selectallinbox'> ";
			/* 	}*/
			html += " </td>";
			html += "			<td colspan='2'>";
			/* if(rec[0].n_status!=2){*/
				html+="<div class='inaction' >";
			/* }else{*/
				html+="<div class='inaction' style='top: 0px; left: -16px;'>";
			/* }*/
			html+="<div class='page-arrow fr'><a href='javascript:void(0)' class='prev-message-page prevInbox '>&nbsp;</a><a href='javascript:void(0)' class='next-message-page nextInbox'>&nbsp;</a></div>";
			/*if(rec[0].n_status!=2){*/
			html+="<a class='fr deleteInbox deletetAllMessage' href='javascript:void(0)'>Delete</a></div>";
			/*}*/
			html += "		</td></tr>";
		
			$.each(rec, function(i,e){
				var readmess = "black";
				if(e.n_status==0) readmess = "red";
				
				
				var substr = e.message;			
				html += "		<tr class='open-inbox'>";
				/*if (e.type==1){ //tipe notif */
				/*if(e.n_status!=2){*/
					html += "			<td class='checklist'>";
					html += "				<form action=''>";
					html += "					<input type='checkbox' name='' value='"+e.id+"' prop='"+e.type+"' fromadmin='"+e.fromwho+"' class='selectedId'> ";
					html += "				</form>";
					html += "			</td>";
				/*}*/
			/*} //tipe notif */
				
				// if (e.type==1){
				var showSubject = "";
				var parseNotif = substr.split('|');
				var subjectNotif = parseNotif[0];
				if (subjectNotif) substr = subjectNotif;
				else substr = e.message;
				
				// }
				html += "			<td><span class='messagepreview' fromadmin='"+e.fromwho+"' prop='"+e.fromid+"' datetimes='"+e.datetime+"' content='"+e.message+"' from='"+e.name+"' replay='"+e.type+"' idmesg='"+e.id+"' subject='"+e.subject+"' style='color:"+readmess+"' >"+substr+"</span></td>";
				
				if (e.type==0){
				html += "			<td>MARLBORO</td>";
				}else{
				html += "			<td width='80'>"+e.name+"</td>";
				}
				
				html += "		</tr>";
				
			})
		}else html += "<tr><td>"+locale.nomessage+"</td></tr>	";
		html += "	</tbody>";
		html += "</table>";
		
		return html;
	}
	
	$(document).on("click","#inboxcounting",function(){
	// $('.composebutton').show();
		 inboxcounting();
	});
	
	
	
	
	$(document).on('click','.nextInbox',function(){
		
		var start = parseInt($(".startpageinbox").attr('page'),10)+5;
		var totalInbox = parseInt($(".startpageinbox").attr("total"));
		
		var lastpage = $('.startpageinbox').attr('lastpage');
		if(lastpage==1) $('.nextInbox').addClass('lastpage');
		
		if(start < totalInbox){
			$.post(basedomain+'pursuit/task', {taskmessage:true, start:start}, function(data){
					var html = "";
					var paging = "";
					// var total = 0;
					
					if (data.status == true){
						
						html += generateInbox(data.rec);
					
					}else{
						return false;
						// html += locale.nomessage;
					}
					
					var lastpage = parseInt(start)+5;
					
					if (lastpage>=totalInbox){
						var last = 'lastpage';
						$('.startpageinbox').attr('lastpage','1');
					}else{
						var last = '';
						$('.startpageinbox').attr('lastpage','0');
					}
					
					
					if(data.total>=5){
						
						var currentRecord = parseInt(start)+5;
						if (currentRecord>data.total) currentRecord = data.total;
						paging += " <span class='page-num'>"+currentRecord+"</span> <span class='page'>Of</span>";	 
						paging += "<span class='page-num last-margin'> "+data.total+" </span> ";	
						paging += "<a class='prev-Page prevInbox' href='javascript:void(0)'><</a>";	
						paging += "<a class='next-Page nextInbox "+last+"' href='javascript:void(0)'>></a>";
						// html+="<div class='row paging'>";
						// html+="<a class='prevInbox' href='javascript:void(0)'>PREV</a>";
						// html+="<a class='nextInbox "+last+"' href='javascript:void(0)'>NEXT</a>";
						// html+="</div>";
					}
				
				$(".startpageinbox").attr('page',start);
				// $(".startpageinbox").attr('total',totalInbox);
				$(".listmessageinbox").html(html);
				$(".pagingInboxMessage").html(paging);
			},"JSON")
			
		}else{
			$('.startpageinbox').attr('lastpage','1');
		}
		
		
			
	})
	
	$(document).on('click','.prevInbox',function(){
		
		var start = parseInt($(".startpageinbox").attr('page'),10)-5;
		var totalInbox = parseInt($(".startpageinbox").attr("total"));
		// var nextStart = parseInt(start+5);
		
		
		if(start>=0){
			$.post(basedomain+'pursuit/task', {taskmessage:true, start:start}, function(data){
					var html = "";
					var paging = "";
					// var total = 0;
					
					if (data.status == true){
						
						html += generateInbox(data.rec);
					}else{
						return false;
						// html += locale.nomessage;
					}
					
					var lastpage = parseInt(start)-5;
					
					if (lastpage<=0){
						var last = 'lastpage';
						// $('.startpageinbox').attr('lastpage','1');
					}else{
						var last = '';
						$('.startpageinbox').attr('lastpage','0');
					}
					
					if(data.total>=5){
						
						var startmsgPage = parseInt($(".startpageinbox").attr('page'));
					
						var currentRecord = parseInt(startmsgPage);
					
						paging += " <span class='page-num'>"+currentRecord+"</span> <span class='page'>Of</span>";	 
						paging += "<span class='page-num last-margin'> "+data.total+" </span> ";	
						paging += "<a class='prev-Page prevInbox "+last+"' href='javascript:void(0)'><</a>";	
						paging += "<a class='next-Page nextInbox' href='javascript:void(0)'>></a>";
						// html+="<div class='row paging'>";
						// html+="<a class='prevInbox "+last+"' href='javascript:void(0)'>PREV</a>";
						// html+="<a class=' nextInbox' href='javascript:void(0)'>NEXT</a>";
						// html+="</div>";
					}
				
				$(".startpageinbox").attr('page',start);
				// $(".startpageinbox").attr('total',totalInbox);
				$(".listmessageinbox").html(html);
				$(".pagingInboxMessage").html(paging);
			},"JSON")
		
			$('.startpageinbox').attr('lastpage','0');
			$('.nextInbox').removeClass('lastpage');
		}else{
			// $('.startpageinbox').attr('lastpage','1');
			$('.prevInbox').addClass('lastpage');
		}
		
		
	})
	
	$(document).on('click', '.messagepreview', function() {
		var detail = "";
		var getid = $(this).attr('prop');
		var from = $(this).attr('from');
		var content = $(this).attr('content');
		var datetimes = $(this).attr('datetimes');
		var replay = $(this).attr('replay');
		var subject = $(this).attr('subject');
		var fromadmin = $(this).attr('fromadmin');
		// var detail = generateHtmlInbox(getid, from,subject,content, replay);
		
		var id = $(this).attr('idmesg');
		var type = $(this).attr('replay');
		
		$.post(basedomain+'pursuit/readmessage',{id:id, type:type,from:getid,fromadmin:fromadmin},function(data){
			
			if (data.status==true){
				//console.log(data);
				
				var myMessage=[data.rec.id,data.rec.image_profile,subject,content, data.rec.name, replay,datetimes];
				// console.log(myMessage);
				var detail = readMessage(myMessage);
			}
			
			$(".listmessageinbox").html(detail);
			
			getinboxcount();
		}, "JSON");
		
		$('.contentMessageInbox').show();
		$('.listinbox').hide();
		
		
		
		// $(".contentMessageInbox").hide();
		
		// $(".contentMessageInbox").show();
		
		
	});
	
	$(document).on('click', '.composebutton', function() {
		var htmlCompose = "";
		var getid = $(this).attr('prop');
		var from = $(this).attr('from');
		var content = $(this).attr('content');
		var subject = $(this).attr('subject');
		var replay = $(this).attr('replay');
		
		var htmlCompose = comoposeMessage();
		$(".listmessageinbox").html("");
		$(".listmessageinbox").html(htmlCompose);

				
		// $('.framelistinbox').html('');
		// $('.titleinbox').html('');
		// $('.back-Action').html('');
	});
	
	
	 
	
	$(document).on('keyup','#recepienttxt',function(){
				var keywords = $(this).val();
				
				$(".taggingusersoninbox").css('display','block');
				$(".taggingusersoninbox").html(locale.pleasewait);
				$.post(basedomain+'content/searchnameofalluser',{q:keywords},function(data){
							var html="";
								if(data){
									$.each(data,function(i,e){
										
										html+="<div class='tagthisuseroninbox'  emailid='"+e.email+"'  fid='"+e.id+"' nameuser='"+e.name+" "+e.last_name+"' >"+e.name+" "+e.last_name+"</div>";
									
									});
									
								}
								$(".taggingusersoninbox").html(html);					
				},"JSON");
				 
	});

	$(document).on('click','.tagthisuseroninbox',function(){
						var nameofuser = $(this).attr('nameuser');
						var fid = $(this).attr('fid');
						var emailid = $(this).attr('emailid');
						
						/* userid tagging*/
					 
						$('#recepienttxt').val(nameofuser);
						$('#recepientid').val(fid);
						$(".taggingusersoninbox").html("");
						$(".taggingusersoninbox").css('display','none');
					 
	});	
	
	
	function comoposeMessage()
	{
		var html = "";
		
		html += "<div id='compose-message' class='inbox-message-compose'>";
        html += "                        	<h3>COMPOSE NEW MESSAGE</h3>";
        // html += "                        	<div class='row'>";
        // html += "                                <label>NICKNAME</label>";
        // html += "                                <input id='nickname' class='full-width required nickname' type='text' name='nickname'>";
        // html += "                            </div>";
        html += "                            <div class='row'>";
        html += "                                <label>Recipient</label>";
        html += "                                <input id='recepienttxt' class='full-width required ' type='text' name='recepienttxt'>";
		html += "                                <input id='recepientid' type='hidden' name='recepientid' class='recipient' >";
		html += " 							 <div class='taggingusersoninbox' style='display:none'></div>";
        html += "                            </div>";
        html += "                            <div class='row'>";
        html += "                                <label>Subject</label>";
        html += "                                <input id='subjecttxt' class='full-width required subject' type='text' name='subjecttxt'>";
        html += "                            </div>";
        html += "                            <div class='row'>";
        html += "                                <label>Message</label>";
        html += "                               <textarea id='messagetxt' class='message-compose messagebody' name='messagetxt'></textarea>";
	 
        html += "                            </div>";
        html += "                            <div class='row'>";
        html += "                            	<a class='fr landbtn landbtn2 sendMessageFromNotification' href='javascript:void(0)'>Send</a>";
        html += "                            </div>";
        html += "                        </div>";
		
		return html;
	}
		
	function generateHtmlInbox(id,from, subject, content, replay){
		
		var html = "";
		// var rec = data;
		var replay = parseInt(replay);
		
		html += "<div class='info-sender'>";
		html += "			<div class='row'>";
		html += "				<label>From :</label>";
		if (replay==0){
		html += "				<span>MARLBORO</span>";
		}else{
		html += "				<span>"+from+"</span>";
		}
		
		html += "			</div>";
		html += "			<div class='row'>";
		html += "				<label>Subject :</label>";
		if (replay==0){
		html += "				<span>[NOTIFICATION]</span>";
		}else{
		html += "				<span>[MESSAGE]</span>";
		}
		html += "			</div>";
		html += "		</div>";
		html += "		<div class='text-message'>";
		html += content;
		html += "		</div>";
		html += "		<div class='actionInbox'>";
		html += "				<div class='back-Action fl'>";
		html += "					<a class='backinbox' href='javascript:void(0)'>Back </a>";
		html += "				</div>";
		
		if (replay==1){
		html += "				 <div class='reply fr'>";
		html += "					<a class='replyMessage bg_red popup-trade-message reply-message' href='javascript:void(0)' prop='"+id+"' desc='"+from+"'>reply</a>";
		html += "				 </div>";
		}
		
		html += "		  </div>";
		
		return html;
	}
	
	$(document).on('click','.popup-trade-message',function(){
		
		var userID = $(this).attr('prop');
		var userName = $(this).attr('desc');
		var html = "";
		// $('.listmessageinbox').toggle();
		
		// html += "<div class='entry-popup popup-create-message'>";
        // html += "    	<h4 class='titleMessages'>Enter your message to "+userName+" below</h4>";
		// html += "        	<div class='row'>";
        // html += "			<textarea class='trademessage'></textarea>";
        // html += "			<input type='hidden' value='"+userID+"' class='tradeidUser'/>";
		// html += "            <input type='submit' value='SUBMIT' class='button btn_grey fl submittrademessage' />";
        // html += "            </div>";
        // html += "    </div>";
		
		html += "<div class='info-sender'>";
		html += "			<div class='row'>";
		html += "				<label>From :</label>";
		html += "				<span>"+userName+"</span>";
		html += "			</div>";
		html += "			<div class='row'>";
		html += "				<label>Subject :</label>";
		html += "				<span>[MESSAGE]</span>";
		html += "			</div>";
		html += "		</div>";
		html += "		<div class='row'>";
		html += "			<textarea class='trademessage'></textarea>";
		html += "		</div>";
		html += "		<div class='actionInbox'>";
		html += "			 <div class='reply fr'>";
		html += "			<input type='hidden' value='"+userID+"' class='tradeidUser'/>";
		html += "				<a class='replyMessage bg_red submittrademessage' href='javascript:void(0)'>reply</a>";
		html += "			 </div>";
		html += "	  </div>";
		// html +="tes";
		$(".replay-message").trigger("click");			
		$(".replaymessagecontent").html(html);
		$(".titlepursuit").html("Message");
	})
	
	$(document).on('click','.submittrademessage',function(){
		var idUser = $('.tradeidUser').val();
		var getMesg = $('.trademessage').val();
		
		$.post(basedomain+'pursuit/ajax', {tradeMesg:true, id:idUser, mesg:getMesg}, function(data){
			var html = "";
			var getStatus = data.status;
			if (getStatus == true){
				html += "<h1>"+locale.messagesent+"</h1>";
			}else{
				html += "<h1>"+locale.messagefail+"</h1>";
			}
			
			$(".pursuit-popup").trigger("click");			
			$(".contentpursuit").html(html);
			$(".titlepursuit").html(locale.messagetitle);
		},"JSON")
	})
	
	
	$(document).on('click','.sendMessage',function(){
		
		var nick = $('.nickname').val();
		var receipt = $('.recipient').val();
		var mess = $('.messagebody').val();
		var subject = $('.subject').val();
		
		// console.log(nick);
		// console.log(receipt);
		// console.log(mess);
		// console.log(subject);
		// return false;
		$.post(basedomain+'pursuit/composemessage', {send:true, nick:nick, receipt:receipt,mess:mess,subject:subject}, function(data){
			var html = "";
			var getStatus = data.status;
			if (getStatus == true){
				html += "<h1>"+locale.messagesent+"</h1>";
			}else{
				html += "<h1>"+locale.messagefail+"</h1>";
			}
			
			$(".wakeboarding-thanks").trigger("click");										
			$('.content').html(html);
			
			// $(".pursuit-popup").trigger("click");			
			// $(".contentpursuit").html(html);
			// $(".titlepursuit").html(locale.messagetitle);
			
		},"JSON")
	})
	
	
	$(document).on('click','.sendMessageFromNotification',function(){
		
		var nick = $('.nickname').val();
		var receipt = $('.recipient').val();
		var mess = $('.messagebody').val();
		var subject = $('.subject').val();
		
		// console.log(nick);
		// console.log(receipt);
		// console.log(mess);
		// console.log(subject);
		// return false;
		$.post(basedomain+'pursuit/composemessage', {send:true, nick:nick, receipt:receipt,mess:mess,subject:subject}, function(data){
			var html = "";
			var getStatus = data.status;
			if (getStatus == true){
				html += "<h1>"+locale.messagesent+"</h1>";
			}else{
				html += "<h1>"+locale.messagefail+"</h1>";
			}
			
			$(".wakeboarding-thanks").trigger("click");										
			$('.content').html(html);
			
			// $(".pursuit-popup").trigger("click");			
			// $(".contentpursuit").html(html);
			// $(".titlepursuit").html(locale.messagetitle);
			
		},"JSON")
	})
	
	function hiddenpackage(){
		$(document).on('click', '.hiddenPackage', function(){
			
			var getToken = $(this).attr('token');
			$.post(basedomain+"games/hiddencode",{hiddenCode:true, param:getToken}, function(data){
				
				var html = "";
				if (data.status == true){
					//html += "<div class='popup'>";
					//html += "<div class='popupContainer popup-hidden-package' id='popup-hidden-package' style='width:400px' >";
					html += "<div class='popupContent centerText'>";
					html += "<h2>Congratulations!</h2>";
					html += "<h3>"+locale.hiddenpacktextheader+"</h3>";
					// html += "<a class='thumb-small fl'><img src='"+basedomain+"assets/content/tiles/"+data.rec.toLowerCase()+".png' /></a></h3>";
					// html += "<h3>"+data.rec+" &nbsp"+locale.hiddenpacktextfooter+"</h3>";
					// html += "<h3>you've found a hidden marlboro pack.<br />you got yourself a code for <a href='"+basedomain+"pursuit'>the pursuit.</a> <br />copy the code below and use it to<br /> redeem a letter.</h3>";
					// html += "<input type='text' value='"+data.rec+"' class='theCodes' />";
					html += "</div>";
					//html += "</div>";
					//html += "</div>";
				}else{
					html += "<h1>"+locale.failed+"</h1>";
				}
				
				
				
				
				$(".hidden-package").trigger("click");			
				$(".contenthiddenpack").html(html);
				
				
				$(document).on('click','#fancybox-close, #fancybox-overlay', function(){
					location.reload();
					
				});
				$("#fancybox-outer").addClass('min102');
				
				
			},"JSON")
			
		});
	}
	
	function mybirthday(){
		
				$.post(basedomain+"home/task",{getGiftBirthday:true}, function(data){
						

					 
						if (data.status == true){
						
							var html ="<div class='entry-popup'>";
							html += "<div class='birthdayThumb'>";
							html += "<img src='"+basedomain+"public_assets/merchandiseStock/"+data.rec.image+"' width='100%' />";
							html += "</div>";
							html += "<div class='birthdayContent'>";
							html += "<h1>Happy Birthday</h1>";
							html += "<h2>Here's something to light those birthday candles with</h2>";
							html += "<p>Your gift will be delivered to your registered address</p>";
							html += "<div class='but-birthday'>";
							html += "<a class='landbtn btn_birthday mybirthdayconfirms' href='javascript:void(0)'>Confirm Mailing Address</a>";
							html += "</div>";
							html += "</div> ";
						  
							$(".contentmybirthday").html(html);
							$(".my-birthday").trigger("click");
							$("#fancybox-close").addClass("fancybox-close-birthday");
						}
					},"JSON");
						
			 
	}
	
		$(document).on("click",".mybirthdayconfirms",function(){
		
				$.post(basedomain+"home/task",{getGiftBirthday:true}, function(data){
											 
						if (data.status == true){
						
								var html  = "<div class='entry-popup'>";
								html += "<div class='birthday-confirm'>";
								html += "<h1>Edit mailing address</h1>";
								html += "<ul class='columns-2 clearfix'>";
								html += "<li class='col2'>";
								html += "<div class='entryForm'>";
								html += "<div class='row'>";
								html += "<label class='fl'>House # and Sreet</label>";
								html += "<input type='text' value='' class='birthdayaddress' />";
								html += "</div>";
								html += "<div class='row'>";
								html += "<label class='fl'>Province</label>";
				 						html += "<div class='styledProvince selectProvince'>";
											html += "<select id='province' class='required valid' name='birthprovince'>";
											html += "<option value='0'> - </option>";	
											$.each(data.provinceList, function(k,v){
												html += "<option value='"+v.id+"'>"+v.province+"</option>";	
											});
		                                    		                                        
		                                    html += "</select>";
										html += "</div>";
								// html += "<input type='text' value='' class='birthprovince' />";							 
								html += "</div>";
								html += "</div> ";
								html += "</li>";
								html += "<li class='col2'>";
								html += "<div class='entryForm'>";
								html += "<div class='row'>";
								html += "<label class='fl'>Barangay</label>";
								html += "<input type='text' value='' class='birthbarangay' />";
								html += "</div>";
								html += "<div class='row'>";
								html += "<label class='fl'>City</label>";
								html += "<div id='selectCity' class='styledProvince selectCity'>";
							 	html += "</div>";
								//html += "<input type='text' value='' class='birthcity' />";
								html += "<input type='hidden' value='"+data.rec.id+"' class='hiddenidbirth' />";
							 
								html += "</div>";
								html += "</div> ";
								html += "</li>";
								html += "</ul>";
								html += "<div class='but-birthday-confirm'>";
								html += "<a class='landbtn landbtn2 getgiftbirtday' href='javascript:void(0)'>Confirm</a>";
								html += "</div>";
								html += "</div> ";
					  
							
							
							$(".contentmybirthday").html(html);
							$(".my-birthday").trigger("click");
							$("#fancybox-close").addClass("fancybox-close-birthday");
							
							$('.contentmybirthday').on('change','#province',function(){
								var value = $(this).val();
								var htm="";
								$.post(basedomain+"account/getProvinsibyAjax",{id:value}, function(response){
									
										htm += "<select class='required valid' name='birthcity'>";
										htm += "<option value='0'> - </option>";	
										$.each(response, function(kk,vv){
											htm += "<option value='"+vv.city+"'>"+vv.city+"</option>";	
										});
	                                    		                                        
	                                    htm += "</select>";
									
									$('.contentmybirthday #selectCity').html(htm);
								},'JSON');

							});
						}
					},"JSON");
						
						
		$(document).on('click','.getgiftbirtday',function(){
			
			var name = $('.birthname').val();
			var address = $('.birthdayaddress').val();
			var city = $('.birthcity').val();
			var barangay = $('.birthbarangay').val();
			var province = $('.birthprovince').val();
			var codezip = $('.birthzip').val();
			
			//var phone = $('.birthdayphone').val();
			//var email = $('.birthdayemail').val();
			
			
			var id = $('.hiddenidbirth').val();
			 
			if(address=='') return false;
			if(city=='')return false;
			if(barangay=='')return false;
			if(province=='')return false;
			if(id=='')return false;
			
			address = address+', '+barangay+', '+city+', '+province+', '+codezip;
			$.post(basedomain+"home/task",{birthdayGift:true, address:address, id:id,name:name}, function(data){
				
				var html = "";
				if (data.status == true){
					html += "<h1>Successfully updated your information</h1>";
				}else{
					html += "<h1>Failed</h1>";
				}
				
				$(".wakeboarding-thanks").trigger("click");										
				$('.content').html(html);
			},"JSON");
		})
	});
	
	
	function login20th(){
	
						html = "<div class='popupContent ' style='text-align:left;' >";
						html += "<h2>Congratulations!</h2>";
						html += "<h2>This is your 20th login. You get a new letter!</h2>";
						html += "<div class='row' style='text-align:center' ><img src='"+basedomain+"assets/content/tiles/"+letter+".png' /></div>";
					
						html += "</div>";
						
				
					
						$(".contentmyaccount").html(html);
						$(".my-account-profile").trigger("click");
						$("#fancybox-outer").addClass('min10');
					
	
	}
	
	
	function getmessaging(){
		
		var startmsg = parseInt($(".startpageinbox").attr('page'),10);
		var totalInbox = parseInt($(".startpageinbox").attr("total"));
		// var nextStart = parseInt(start+5);
		// if(start < totalInbox){
		$.post(basedomain+'pursuit/task', {getinboxstatus:true}, function(data){
				
				// if (data.status==true){
					// $('.'+data.classstyle).addClass('active-inbox');
					
				// }
				
			},"JSON")
		
		$.post(basedomain+'pursuit/task', {taskmessage:true,start:startmsg}, function(data){
				var html = "";
				var paging = "";
				var total = 0;
				
				if (data.status == true){
					html += generateInbox(data.rec);
				}else{
					return false;
					// html += locale.nomessage;
				}
				
				if(data.total>=5){
					
					var lastpage = $('.startpageinbox').attr('lastpage');
					//if(lastpage==1) $('.nextInbox').addClass('lastpage');
					if (lastpage==1){
						var last = 'lastpage';
					}else{
						var last = '';
					}
					
					if (startmsg ==0){
						startmsg = 5;
						var pre = 'lastpage';
					}else{
						var pre = '';
					}
					// html+="<div class='row paging'>";
					// html+="<a class='prevInbox "+pre+"' href='javascript:void(0)'>PREV</a>";
					// html+="<a class='nextInbox "+last+"' href='javascript:void(0)'>NEXT</a>";
					// html+="</div>";
					
					var startmsgPage = parseInt($(".startpageinbox").attr('page'));
					
					var currentRecord = parseInt(startmsgPage)+5;
					
					
					
					paging += " <span class='page-num'>"+currentRecord+"</span> <span class='page'>Of</span>";	 
					paging += "<span class='page-num last-margin'> "+data.total+ "</span> ";	
					paging += "<a class='prev-Page prevInbox "+pre+"' href='javascript:void(0)'><</a>";	
					paging += "<a class='next-Page nextInbox "+last+"' href='javascript:void(0)'>></a>";
					
				}
				
				
				$(".listmessageinbox").html(html);
				$(".pagingInboxMessage").html(paging);
			
				
				
			},"JSON")
			
		
			
			
		// }
	}
	
	function getFlashVersion(){
	  // ie
		try {
				try {
				  // avoid fp6 minor version lookup issues
				  // see: http://blog.deconcept.com/2006/01/11/getvariable-setvariable-crash-internet-explorer-flash-6/
					var axo = new ActiveXObject('ShockwaveFlash.ShockwaveFlash.6');
					try { axo.AllowScriptAccess = 'always'; }
					catch(e) { return '6,0,0'; }
				} catch(e) {}
				return new ActiveXObject('ShockwaveFlash.ShockwaveFlash').GetVariable('$version').replace(/\D+/g, ',').match(/^,?(.+),?$/)[1];
				// other browsers
			} catch(e) {
				try {
					if(navigator.mimeTypes["application/x-shockwave-flash"].enabledPlugin){
						return (navigator.plugins["Shockwave Flash 2.0"] || navigator.plugins["Shockwave Flash"]).description.replace(/\D+/g, ",").match(/^,?(.+),?$/)[1];
				}
				} catch(e) {}
			}
		return '0,0,0';
	}
	
	$(document).on('click','.getfeed',function(){
		
		$.post(basedomain+'home/newsfeed', {feed:true}, function(data){
			var html = "";
			if (data.status==true){
				// html += "<ul>";
				
				$.each(data.rec, function(i,e){
					html += "	<li><a href='javascript:void(0)'>"+e.desc+"</a></li>";
				})
				
                // html += "</ul>";
			}else{
				html += "<li><a href='javascript:void(0)'>data not available</a></li>";
			}	
		 
			$('.newsfeedlist ul').html(html);
			$('.newsfeedlist').jScrollPane(
				{
					showArrows: true,
					arrowScrollOnHover: true,
					autoReinitialise: true
				}
			);
		},"JSON")
	})
  
  function inboxcounting(){
		$('.listinbox').show();
		
		var compose = $("#inboxcounting").attr('compose');
		var name = $("#inboxcounting").attr('prop');
		var emailid = $("#inboxcounting").attr('hiddenid');
		$(".listmessageinbox").html(" <img style='padding-top:150px' src='"+basedomain+"assets/images/loader.gif' /> ");
		$.post(basedomain+'pursuit/ajax',{getinboxstatus:true, compose:compose},function(data){
			// console.log(data);
				
			if (compose==1){
				setTimeout(function(){
					$('.composebutton').trigger('click');
					$('#recepienttxt').val(name);
					$('.recipient').val(emailid);
				},1000);
				
			}else{
				//$('.'+data.classstyle).addClass('active-inbox');
			}
			
			
			
		},"JSON");
		
		
		var inboxNotif = parseInt($("#inboxcounting").attr('inbox'));
		var notification = parseInt($("#inboxcounting").attr('notif'));
		
			$.post(basedomain+'pursuit/task', {taskmessage:true}, function(data){
				var html = "";
				var paging = "";
				var total = 0;
			
				if (data.status == true){
					html += generateInbox(data.rec);
				}else{
					$(".listmessageinbox").html(generateInbox());
					return false;
					// html += locale.nomessage;
				}
				
				if(data.total>=5){
					var start = parseInt($(".startpageinbox").attr('page'),10)-5;
					if (start<=0){
						var last = 'lastpage';
						
					}else{
						var last = '';
					}
					
					paging += " <span class='page-num'>5</span> <span class='page'>Of</span>";	 
					paging += "<span class='page-num last-margin'> "+data.total+" </span> ";	
                    paging += "<a class='prev-Page prevInbox "+last+"' href='javascript:void(0)'><</a>";	
                    paging += "<a class='next-Page nextInbox' href='javascript:void(0)'>></a>";
				}
				
				// $(".notifinbox").html("("+inboxNotif+")");
				$(".startpageinbox").attr('total',data.total);		
				$(".notifinbox").html("("+inboxNotif+")");
				$(".notification").html("("+notification+")");
				$(".listmessageinbox").html(html);
				$(".pagingInboxMessage").html(paging);
				
			},"JSON")
			
			var lastpage = $('.startpageinbox').attr('lastpage');
			if(lastpage==1) $('.nextInbox').addClass('lastpage');
			
			//$('.listmessageinbox').toggle();
		 
			$(document).on('click','#fancybox-close, #fancybox-overlay', function(){
				//location.reload();
				
			});
			$("#fancybox-outer").addClass('min102');
		
  }
  
  // nonactive right click
	var message="Sorry, Nothing Here !";

	function clickIE4(){
		if (event.button==2){
			// alert(message);
			return false;
		}
	}

	function clickNS4(e){
		if (document.layers||document.getElementById&&!document.all){
			if (e.which==2||e.which==3){
				// alert(message);
				return false;
			}
		}
	}

	if (document.layers){
		document.captureEvents(Event.MOUSEDOWN);
		document.onmousedown=clickNS4;
	}else if (document.all&&!document.getElementById){
		document.onmousedown=clickIE4;
	}

	document.oncontextmenu=new Function("return false")	
	
	/* disable save password browser */
	$(function() { 
		$('#theUsername').attr("autocomplete", "off");
		$('#thePassword').attr("autocomplete", "off");
		setTimeout('$("#theUsername").val("");', 50); 
		setTimeout('$("#thePassword").val("");', 50); 
	});