var savebutton = true;
	function readURLplay(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				$('.imagePreview').attr('style', 'width:100px;height:100px;');
				$('#previewphoto').attr('src', e.target.result);
				$('#previewphoto').attr('width', '100px');
				$('#previewphoto').attr('height', '100px');
			}
			 reader.readAsDataURL(input.files[0]);
		}
	}
	
	function readURLChallenge(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
			
			// console.log(e.target.result);
			var a = e.target.result.split(";");
			
			// console.log(a);
			var b = a[0].split(":");
			// console.log(b);
			
			var c = b[1].split("/");
			// console.log(c[0]);
			
				$('.imagePreviewPopup').attr('style', 'width:100px;height:100px;margin:0 auto;');
				if (c[0]=="image"){
					$('#previewphotoPopup').attr('src', e.target.result);
				}
				if (c[0]=="video"){
					$('#previewphotoPopup').attr('src',  basedomain+'assets/images/Video-Clip-icon.png');
				}
				$('#previewphotoPopup').attr('width', '100px');
				$('#previewphotoPopup').attr('height', '100px');
			}
			 reader.readAsDataURL(input.files[0]);
		}
	}
	
	$("#imgPost").change(function(){
		readURLplay(this);
		
		$('#fancybox-close, #fancybox-overlay').trigger('click');
	});
	
	$("#vidPost").change(function(){
		$('.imagePreview').attr('style', 'width:100px;height:100px;');
		$('#previewphoto').attr('src', basedomain+'assets/images/Video-Clip-icon.png');
		$('#previewphoto').attr('width', '100px');
		$('#previewphoto').attr('height', '100px');
	});
	
	
	$(document).on('click', '.completeChallenge', function(){
		
		var postid = $(this).attr('prop');
		
		$.post(basedomain+'movefwd/ajaxChallenge', {challenge:true, postid:postid}, function(data){
			
			var html ="";
			if (data.status==true){
			
				if (data.rec.pledge){
					html += "You pledged "+data.rec.point+" points";
				}else{
					html +="<div class='row title-wakeboarding'>";
					html +="<h1>"+data.rec.title+"</h1>";
					// html +="<h5>10 Slot Left</h5>";
					html +="<p>Accomplish all 3 tasks for a chance to win this offer</p>";
					html +="</div>";
					html +="<div class='row'>";
					
					// html +="<div id='wake1' class='paper-button task-done'></div>";
					// html +="<div id='wake1' class='paper-button'><p>quetion2</p></div>";
					// html +="<div id='wake1' class='paper-button task-lock'></div>";
					
					$.each(data.rec.task, function(i,e){
						//if (e.donetask){
							html +="<div id='wake1' class='paper-button task-done'><p>"+e.question+"</p></div>";
						//}
						
					})
					
					
					
					html +="</div>";
				}
					
					
					$('.wakeboarding').trigger('click');
				$('.detailChallenge').html(html);
			}
			
		}, "JSON");
		
				
				
	})
	
	$(document).on('click', '.commentstatus', function(){
		
		var id = $(this).attr('prop');
		// console.log(id);
		$('.commentbox_'+id).css('display','block');
	})
	
	$(document).on('click', '.replycomment', function(){
		// alert('ada');
		var id = $(this).attr('prop');
		$('.replycommentbox_'+id).css('display','block');
	})
	
	$(document).on('click', '.postComment', function(){
			
		var postid = $(this).attr('prop');
		var contentid = $('.hiddencontentid').val();
		var content = $('.mystatuscomment_'+postid).val();
		
		$.post(basedomain+'movefwd/commentAjax', {comment:true, postid:postid, content:content,contentid:contentid}, function(data){
			var htmlThanks = "";
			if (data.status==true){
				/*
				$.each(data.rec, function(i,e){
					
					
					html += "<div class='row comment-child '>";
					html += "<div class='thumb-user'>";
					html += "                <a href='#'><img src='"+basedomain+"public_assets/user/photo/"+e.image_profile+"' width='50px' /></a>";
					html += "            </div>";
					html += "            <div class='entry-right'>";
					html += "                <div class='entry-header'>";
					html += "                    <h3 class='username'><a href='#'>"+e.name+"</a></h3>";
					html += "                </div>";
					html += "                <div class='entry-body'>";
					html += "                    <p>"+e.content+"</p>";
					html += "                    <span class='comment-date'>Posted on "+e.newDate+" - "+e.newTime+"</span>";
					html += "<a class='child-reply replycomment' href='javascript:void(0)' prop='"+e.id+"'> Reply</a>";
					html += "                </div>";
					html += "            </div>";
					
					// subcomment
					html += "<div class='framereplycommentbox_"+e.id+"'>";
					if (e.reply){
					
					$.each(e.reply, function(a,value){
					
					if (value.content!=""){
						
						html += "						<div class='row comment-child '>";
						html += "							<div class='thumb-user'>";
						html += "								<a href='#'><img src='"+basedomain+"public_assets/user/photo/"+value.image_profile+"' width='50px' /></a>";
						html += "							</div>";
						html += "							<div class='entry-right'>";
						html += "								<div class='entry-header'>";
						html += "									<h3 class='username'><a href='#'>"+value.name+"</a></h3>";
						html += "								</div>";
						html += "								<div class='entry-body'>";
						html += "									<p>"+value.content+"</p>";
						html += "									<span class='comment-date'>Posted on "+value.newDate+" - "+value.newTime+"</span>";
						html += "								</div>";
						html += "							</div>";
						html += "						</div>";
					}
					
					})
								
					}
					
					html += "				</div>";
					// end sub comment
					
					// hidden comment
					html += "<div class='row reply-comment replycommentbox_"+e.id+"' style='display:none'>";
					html += "								<div class='thumb-user-small'>";
					html += "									<a href='#'><img src='"+basedomain+"public_assets/user/photo/"+e.image_profile+"' width='50px' /></a>";
					html += "								</div>";
					html += "								<div class='entry-right'>";
					html += "									<form class='replycomment'>";
					html += "										<textarea class='mystatuscomment_"+e.id+"' placeholder='Type your comment here'></textarea>";
					html += "										<input type='button' value='&nbsp;' class='post replyCommentbutton postComment' prop='"+e.id+"'/>";
					html += "										<input type='hidden' value='"+e.content_id+"' class='hiddencontentcommentid'/>";
					html += "									</form>";
					html += "								</div>";
					html += "							</div>";
					html += "							</div>";
					html += "							</div>";
					html += "							</div>";
					// end hidden comment
					
					html += "</div>";
					
				})
				
				
				$('.commentFrame_'+data.cid).html(html);
				$('.mystatuscomment_'+data.cid).val('');
				*/
				
				htmlThanks = locale.postcomment;
				
			}else{
				if(data.msg){
					htmlThanks = data.msg;
				}else{
					htmlThanks = locale.failed;
				}
				
			}
			
			
			$(".wakeboarding-thanks").trigger("click");
			$(".content").html(htmlThanks);
			
			$(document).on('click', '#fancybox-close, #fancybox-overlay', function(){
				location.reload();
			})
		}, "JSON")
		
	})
	
	
	$(document).on('click', '.replyCommentbutton', function(){
			
		var postid = $(this).attr('prop');
		var contentid = $('.hiddencontentcommentid').val();
		var content = $('.mystatuscommentreply_'+postid).val();
		
		$.post(basedomain+'movefwd/commentAjax', {comment:true, postid:postid, content:content,contentid:contentid}, function(datareply){
			// $('.replycommentbox_'+datareply.cid).css('display','none');
			var htmlreply = "";
			if (datareply.status==true){
				
				/*
				$.each(datareply.rec, function(i,e){
					
					htmlreply += "<div class='row comment-child '>";
					htmlreply += "<div class='thumb-user'>";
					htmlreply += "                <a href='#'><img src='"+basedomain+"public_assets/user/photo/"+e.image_profile+"' width='50px' /></a>";
					htmlreply += "            </div>";
					htmlreply += "            <div class='entry-right'>";
					htmlreply += "                <div class='entry-header'>";
					htmlreply += "                    <h3 class='username'><a href='#'>"+e.name+"</a></h3>";
					htmlreply += "                </div>";
					htmlreply += "                <div class='entry-body'>";
					htmlreply += "                    <p>"+e.content+"</p>";
					htmlreply += "                    <span class='comment-date'>Posted on "+e.newDate+" - "+e.newTime+"</span>";
					htmlreply += "                </div>";
					htmlreply += "            </div>";
					// htmlreply += "<div class='row reply-comment replycommentbox_"+e.id+"' style='display:none'>";
					// htmlreply += "								<div class='thumb-user-small'>";
					// htmlreply += "									<a href='#'><img src='"+basedomain+"public_assets/user/photo/"+e.image_profile+"' width='50px' /></a>";
					// htmlreply += "								</div>";
					// htmlreply += "								<div class='entry-right'>";
					// htmlreply += "									<form class='replycomment'>";
					// htmlreply += "										<textarea class='mystatuscommentreply_"+e.id+"' placeholder='Type your comment here'></textarea>";
					// htmlreply += "										<input type='button' value='&nbsp;' class='post replyCommentbutton' prop='"+e.id+"'/>";
					// htmlreply += "										<input type='hidden' value='"+e.content_id+"' class='hiddencontentcommentid'/>";
					// htmlreply += "									</form>";
					// htmlreply += "								</div>";
					// htmlreply += "							</div>";
					// htmlreply += "							</div>";
					// htmlreply += "							</div>";
					// htmlreply += "							</div>";
					htmlreply += "</div>";
					
					// $('replycommentbox_'+e.id).css('display','none');
				})
				
				
				$('.framereplycommentbox_'+postid).html(htmlreply);
				
				// $('.mystatuscommentreply_'+data.cid).val('');
				*/
				
				htmlThanks = locale.postcomment;
				
			}else{
				if (data.msg){
					htmlThanks = data.msg;
				}else{
					htmlThanks = locale.failed;
				}
				
			}
			
			$(".wakeboarding-thanks").trigger("click");
			$(".content").html(htmlThanks);
			
			$(document).on('click', '#fancybox-close, #fancybox-overlay', function(){
				location.reload();
			})
			
		}, "JSON")
		
	})
	var postStatus = {
				dataType : "JSON",
				beforeSubmit: function(data) { 
								
								$(".wakeboarding-thanks").trigger("click");		
								$(".content").html(locale.pleasewait);
					},
				success : function(data) {	
								// var email_token =$('.tokensubmition').val();
								// var html = "success to activate tracking code";
								if(data.status==true){
									
									
									setTimeout(function(){
									
										$(".wakeboarding-thanks").trigger("click");
										$(".content").html(locale.postchallenge);
									},1000);
									
									
									// location.reload();
								}
								
								$(document).on('click', '#fancybox-close, #fancybox-overlay', function(){
					
									location.reload();
									
								})
				}
			};					
				
	$("#createstatus").ajaxForm(postStatus);
	
	
	$(document).on('click', '.challengePopup', function(){
		
		var postid = $(this).attr('prop');
		
		$.post(basedomain+'movefwd/ajaxChallenge', {challenge:true, postid:postid}, function(data){
			
			var html ="";
			if (data.status==true){
				
				if (data.has){
					html +="<div class='row title-wakeboarding'>";
					html +="<h1>"+data.rec.title+"</h1>";
					// html +="<h5>10 Slot Left</h5>";
					html +="<p>Accomplish all 3 tasks for a chance to win this offer</p>";
					html +="</div>";
					html +="<div class='row'>";
					
					// html +="<div id='wake1' class='paper-button task-done'></div>";
					// html +="<div id='wake1' class='paper-button'><p>quetion2</p></div>";
					// html +="<div id='wake1' class='paper-button task-lock'></div>";
					
					var pending = 0;
					$.each(data.rec.task, function(i,e){
						if (e.donetask){
							html +="<div id='wake1' class='paper-button task-done'><p>"+e.question+"</p></div>";
						}else{
							if (e.opentask){
								if (e.is_pending==true){
									
									pending = 1;
									html +="<div id='wake1' class='paper-button'><p>Pending verification</p></div>";
									
								}else{
									pending = 0;
									html +="<div id='wake1' class='paper-button challengeUploadList'><p>"+e.question+"</p></div>";
								}
								
							}else{
								html +="<div id='wake1' class='paper-button task-lock'></div>";
							}
						}
						
						
						
					})
					
					
					
					html +="</div>";
					//console.log(pending);
					if(pending==0){
						html +="<div class='row'>";
						html +="<a class='landbtn landbtn2 challengeUpload' href='javascript:void(0)' prop='"+data.rec.id+"' taskid='"+data.rec.taskid+"'>Continue</a>";
						html +="<a class='landbtn landbtn2 close' href='javascript:void(0)'>Do it Later</a>";
						html +="</div>";
					}
					$('.wakeboarding').trigger('click');
				$('.detailChallenge').html(html);
				}else{
					html +="<div class='row title-wakeboarding'>";
					html +="<h1>"+data.rec.title+"</h1>";
					// html +="<h5>"+data.c_left+" Slot Left</h5>";
					// html +="<p>How do you wan to pay for this? Choose below.</p>";
					html +="<p>Accomplish all 3 tasks for a chance to win this offer</p>";
					html +="</div>";
					html +="<div class='row button-choose'>";
					
					html +="<a class='landbtn landbtn2 challengeChoose' href='javascript:void(0)' prop='"+data.rec.id+"' taskid='"+data.rec.taskid+"'>Play</a><span class='red'>or</span>";
					
					html +="<a class='landbtn landbtn2 doWithPoint' href='javascript:void(0)' prop='"+data.rec.id+"'>Pledge</a>";
					html +="</div>";
					html +="<div class='row ruleWakeboard'>";
					html +="<ul class='columns-2'>";
					html +="	<li class='col2'>";
					html +="    <p>Accomplish the task below to unlock the next task:</p>";
					html +="    <ol>";
					$.each(data.rec.task, function(i,e){
						if (e.opentask==true){
						html +=" <li>"+e.question+"</li>";
						}
					})
				   
					html +="    </ol>";

					html +="    <p>Read full mechanics <a class='openpopupfullmekanincredeem' href='javascript:void(0)'>here</a></p>";

					html +="    </li>";
						
					html +="    <li class='col2'>";
					html +="    <p>Pledge "+data.rec.point+" points right now</p>";
					html +="    </li>";
					html +="</ul>";
					html +="</div>";
					
					$('.wakeboarding').trigger('click');
				$('.detailChallenge').html(html);
				}
				
				
				
			}else{
			
				if (data.msg){
					html += data.msg;
				}else{
					html += locale.failed;
				}
				
				$('.wakeboarding').trigger('click');
				$('.detailChallenge').html(html);
			}
			
			
		},"JSON")
		
	})	
	
	
	$(document).on('click', '.doWithPoint', function(){
		
		var postid = $(this).attr('prop');
		
		var htmlThanks = "";
		
		htmlThanks += 'Please wait';
		$(".wakeboarding-thanks").trigger("click");										
		$('.content').html(htmlThanks);
			
		$.post(basedomain+'movefwd/setByPoint', {point:true, postid:postid}, function(data){
			
			
			var html = "";
			if (data.status==true){
				html += data.msg;
			}else{
				html += data.msg;
			}
			$(".wakeboarding-thanks").trigger("click");										
			$('.content').html(html);
			
			$(document).on('click', '#fancybox-close, #fancybox-overlay', function(){
					
				location.reload();
				
			})
			
		},"JSON");
		
	})
	
	
	
	$(document).on('click', '.challengeChoose', function(){
		
		var postid = $(this).attr('prop');
		
		$.post(basedomain+'movefwd/ajaxChallenge', {choose:true, postid:postid}, function(data){
			
			var html ="";
			if (data.status==true){
				
				html +="<div class='row title-wakeboarding'>";
            	html +="<h1>"+data.rec.title+"</h1>";
                // html +="<h5>10 Slot Left</h5>";
                html +="<p>Accomplish all 3 tasks for a chance to win this offer</p>";
				html +="</div>";
				html +="<div class='row'>";
				
				// html +="<div id='wake1' class='paper-button task-done'></div>";
				// html +="<div id='wake1' class='paper-button'><p>quetion2</p></div>";
				// html +="<div id='wake1' class='paper-button task-lock'></div>";
            	
				$.each(data.rec.task, function(i,e){
					if (e.donetask){
						html +="<div id='wake1' class='paper-button task-done'>"+e.question+"</div>";
					}else{
						if (e.opentask){
							html +="<div id='wake1' class='paper-button challengeUploadList'><p>"+e.question+"</p></div>";
						}else{
							html +="<div id='wake1' class='paper-button task-lock'></div>";
						}
					}
					
					
					
				})
				
				
				
				html +="</div>";
				html +="<div class='row'>";
           		html +="<a class='landbtn landbtn2 challengeUpload' href='javascript:void(0)' prop='"+data.rec.id+"' taskid='"+data.rec.taskid+"'>Continue</a>";
                html +="<a class='landbtn landbtn2 close' href='javascript:void(0)'>Do it Later</a>";
				html +="</div>";
				
				$('.wakeboarding').trigger('click');
				$('.detailChallenge').html(html);
				
				$(document).on('click', '.close', function(){
					
					location.reload();
					
				})
			}
		},"JSON")
		
	})	
	
	/*
	$(document).on('click', '#fileupload,.browseUploadFile', function(){
		$('#upload-file1').trigger('click');
	})
	*/
	$(document).on('click', '.challengeUploadList', function(){ 
			$(".challengeUpload").trigger("click");
	});
	
	$(document).on('click', '.challengeUpload', function(){
		
		var postid = $(this).attr('prop');
		
		$.post(basedomain+'movefwd/ajaxChallenge', {preupload:true, postid:postid}, function(data){
			
			var html ="";
			if (data.status==true){
				
				var embedLink = 0;
				html +="<div class='row title-wakeboarding'>";
            	html +="<h1>"+data.rec.title+" </h1>";
				$.each(data.rec.task, function(i,e){
					if (e.opentask){
						html +="<h1>"+e.question+"</h1>";
						if (e.other_type==1)embedLink = 1;
					}
				})
                
				html +="</div>";
				html +="<form method='post' action='"+basedomain+"movefwd/uploadDataChallenge' enctype='multipart/form-data' id='uploadchallenge'>";
				html +="<div class='row'>";
				
            	html +="<input id='upload-file1' type='file' name='dataChallenge'/>";
            	
				if (embedLink==1){
					html +="<div id='popup-rulesUpload'>";
					html +="<span>You can upload a video in four easy steps:</span>";
					html +="<ol><li>Log on to Youtube and click 'Upload'.</li>";
					html +="<li>Before uploading the video, be sure that privacy is set to 'unlisted'. <br/>(Remember: If your video is not unlisted, we cannot approve it)</li>";
					html +="<li>Make sure your video’s name is the same as the MoveFWD offer, and start uploading.</ul>";
					html +="<li>Paste your video’s URL to the MoveFWD section of Marlboro.ph</li></ol>";
					html +="</div>";
					html +="<span style='top:-5px;position:relative' ><input style='width:430px' type='text' placeholder='Youtube Link' class='embedLink' name='embedLink'></span>";
					//html +="<span id='spanfileupload'><a id='fileupload' ></a></span>"; // ini upload video ya ,, engine nya blom ada yang buat video filter //
				}else{
					
					html +="<span id='spanfileupload'><label id='fileupload' for='upload-file1'></label></span>";
				}
				html +="<input type='hidden' name='token' value='1'/>";
            	html +="<input type='hidden' name='bucketid' value='"+data.rec.id+"'/>";
            	html +="<input type='hidden' name='taskid' value='"+data.rec.taskid+"'/>";
            	//html +="<span id='spanfileupload'><a id='fileupload'></a></span>";

				//html +="<p  style='font-size:9px; text-align:left; font-family:Arial,Helvetica,sans-serif;padding:5px 75px 0 75px;'>*Picture maximum file size is 2MB (format file : jpeg, png, gif)</p>";
				//html +="<p  style='font-size:9px; text-align:left; font-family:Arial,Helvetica,sans-serif;padding:5px 75px 0 75px;'>*Video maximum file size is 20MB (format file : mp4, mpeg, quicktime)</p>";

				
				if (embedLink==0){
					html +="<p  style='font-size:9px; text-align:left; font-family:Arial,Helvetica,sans-serif;padding:5px 75px 0 75px;'>*Picture maximum file size is 2MB (format file : jpeg, png, gif)</p>";
					// html +="<p  style='font-size:9px; text-align:left; font-family:Arial,Helvetica,sans-serif;padding:5px 75px 0 75px;'>*Video maximum file size is 20MB (format file : mp4, mpeg, quicktime)</p>";
					
				}
				
				html +="</div>";
				html +="<div style='display:none' class='imagePreviewPopup'>";
				html +="<img src='' id='previewphotoPopup'/>";
				html +="</div>";
				html +="<div class='row'>";
				if (embedLink==0){
					html +="<label for='upload-file1' style='font-size:20px;'class='landbtn landbtn1 browseUploadFile' href='#'>Browse</label>";
            	}
				html +="<input type='submit' name='save' value='Upload' id='uploadbuttonbucketlist' />";
				// html +="<a class='landbtn landbtn2 uploadData' href='javascript:void(0)' prop='"+data.rec.id+"'>Upload</a>";
				html +="</div>";
				html +="</form>";
				
				
				html +="<div class='termConditionUpload'>";
				
				if (embedLink==1){
					html +="<p>Please be reminded that as per the <a href='#popup-tnc' class='showPopup popuptnc'>Terms and Conditions</a>, all photos, videos, images or designs submitted will become the property of PM. Please do not upload or submit any photos, videos, images or designs depicting the act of smoking or showing a lit cigarette or minors (persons less than 18 years old). PM reserves to the right to remove or reject any photo, video, image or design which it, in its sole and absolute discretion, deems offensive, violates the law or is unsuitable in any other manner. The photo you upload will be subject to moderation.</p>";
				}else{
					html +="<p>Please be reminded that as per the <a href='#popup-tnc' class='showPopup popuptnc'>Terms and Conditions</a>, all photos, images or designs submitted will become the property of PM. Please do not upload or submit any photos, images or designs depicting the act of smoking or showing a lit cigarette or minors (persons less than 18 years old). PM reserves to the right to remove or reject any photo, image or design which it, in its sole and absolute discretion, deems offensive, violates the law or is unsuitable in any other manner. The photo you upload will be subject to moderation.</p>";
				}
				
				html +="</div>";
				
									
				
				
				
				
				$('.detailChallenge').html(html);
				
				var updateoptions = {
						dataType:  'json', 	
						beforeSubmit: function(data) { 
							if(savebutton==true){
										if ($(".embedLink").val()!=''){
											// return false; 
											//console.log('ada link');
										}else{
											if($("#upload-file1").val()=='') return false;
										}
									 			 								
										$('#uploadbuttonbucketlist').hide();
										$('#spanfileupload').html("<img style='width:467px;height:30px' src='"+basedomain+"assets/images/loader.gif' />");
							}else{
				
							}
						},
						success : function(data) {		
								var notif = locale.faileduploadphoto;
								if(savebutton==true){
										if(data){
											if(data.status==true){
												var htmlThanks = "";
												htmlThanks +="<div class='row title-wakeboarding'>";
												htmlThanks +="<h1>Thank you for your entry.</h1>";
												htmlThanks +="<p>We will process and verify your submission.<br />and send you confirmation within 24 hours.</p>";
												htmlThanks +="</div>";
												htmlThanks +="<div class='row'>";
												htmlThanks +="<a class='landbtn landbtn2 close' href='javascript:void(0)' prop='"+data.cid+"'>Continue</a>";
												htmlThanks +="</div>";
												
												
												$(".wakeboarding-thanks").trigger("click");										
												$('.content').html(htmlThanks);
												
												$(document).on('click', '.close', function(){
							
													location.reload();
													
												})
											}else{
												var htmlThanks = data.msg;
												$(".wakeboarding-thanks").trigger("click");										
												$('.content').html(htmlThanks);
												
												$(document).on('click', '.close', function(){
							
													location.reload();
													
												})
												
											}									
										}
								
								}else{
								
									savebutton= true;
									$('.imagePreviewPopup').show();
									$('.imagePreviewPopup').html('<img src="'+data.filename+'" width="170" id="previewphotoPopup" />');								
									$('#uploadchallenge').attr('action',basedomain+'movefwd/uploadDataChallenge');
									
								}
									
						}
					};	
					
				$("#uploadchallenge").ajaxForm(updateoptions);
				
				$('.wakeboarding').trigger('click');
			}else{
				
				var failed = "already submit";
				$('.content').html(failed);
				$(".wakeboarding-thanks").trigger("click");
			}
		},"JSON")
		
	})

	$(document).on('click', '.moveforwardUpload', function(){
		
		var html ="";
			
				
				html +="<div class='row title-wakeboarding'>";
            	html +="<h1> Attach Photo</h1>";
				
                
				html +="</div>";
				html +="<form method='post' action='"+basedomain+"movefwd/uploadDataChallenge' enctype='multipart/form-data' id='uploadpost'>";
				html +="<div class='row'>";
				
            	
            	html +="<span id='spanfileupload'><a id='fileupload' class='attachfotoPost'></a></span>";
				
				html +="</div>";
				html +="<div style='display:none' class='imagePreviewPopup'>";
				html +="<img src='' id='previewphotoPopup'/>";
				html +="</div>";
				html +="<div class='row'>";
				html +="<a style='font-size:20px;'class='landbtn landbtn1 attachfotoPost' href='javascript:void(0)'>Browse</a>";
            	// html +="<input type='submit' name='submit' value='Upload' id='uploadbuttonbucketlist' />";
				// html +="<a class='landbtn landbtn2 uploadData' href='javascript:void(0)' prop='"+data.rec.id+"'>Upload</a>";
				html +="</div>";
				html +="</form>";
				html +="<div class='termConditionUpload'>";
				html +="<p>Please be reminded that as per the <a href='#popup-tnc' class='showPopup popuptnc'>Terms and Conditions</a>, all photos, images or designs submitted will become the property of PM. Please do not upload or submit any photos, images or designs depicting the act of smoking or showing a lit cigarette or minors (persons less than 18 years old). PM reserves to the right to remove or reject any photo, image or design which it, in its sole and absolute discretion, deems offensive, violates the law or is unsuitable in any other manner. The photo you upload will be subject to moderation.</p>";
				html +="</div>";
				
									
				
				
				
				
				$('.detailChallenge').html(html);
					
				$('.wakeboarding').trigger('click');
			
		
		
	})
	
	$(document).on('change', '#upload-file1', function(){
	// $("#upload-file-task").change(function(){
		//readURLChallenge(this);
		savebutton= false;
		$('#uploadchallenge').attr('action',basedomain+'about/sendtotempimages');
		
		$('#uploadchallenge').submit();
	});
	
	$(document).on('change', '.embedLink', function(){
		var uri = $(this).val();
		$.post(basedomain+'movefwd/getEmbed', {uri:uri}, function(data){
			
			if (data.status==true){
				readEmbedVideo(data.res);
			}
			
		},"JSON");
	});
	
	
	function readEmbedVideo(video_thumbnail)
	{
		
		$('.imagePreviewPopup').attr('style', 'width:100px;height:100px;margin:0 auto;');
		if (video_thumbnail){
			$('#previewphotoPopup').attr('src', 'https://img.youtube.com/vi/'+video_thumbnail+'/0.jpg');
		}
		$('#previewphotoPopup').attr('width', '100px');
		$('#previewphotoPopup').attr('height', '100px');
		$('#previewphotoPopup').attr('class', 'greyscale');
		
	}
	/*
	$(document).on('click', '.uploadThanks', function(){
		
		// var postid = $(this).attr('prop');
		
	
			html +="<div class='row title-wakeboarding'>";
			html +="<h1>Thank you for your entry.</h1>";
			html +="<p>Please give us time to process and verify your submission.<br />We'll send you an e-mail within 24 hours.</p>";
			html +="</div>";
			html +="<div class='row'>";
			html +="<a class='landbtn landbtn2 fancybox-close' href='javascript:void(0)'>Continue</a>";
			html +="</div>";
				
			$('.wakeboarding').trigger('click');
			$('.detailChallenge').html(html);
				
	
		
	})*/
	
	$(document).on('click', '.sendMessage', function(){
	
		var name = $(this).attr('prop');
		var userid = $(this).attr('hiddenid');
		
		$('.replay-message').trigger('click');
		$('.messgTo').val(name);
		$('.hiddenid').val(userid);
		
	})
	
	var sendMessage = {
				dataType : "JSON",
				beforeSubmit: function(data) { 
								
								$(".wakeboarding-thanks").trigger("click");		
								$(".content").html(locale.pleasewait);
					},
				success : function(data) {	
								// var email_token =$('.tokensubmition').val();
								// var html = "success to activate tracking code";
								if(data.status==true){
									
									
									setTimeout(function(){
									
										$(".wakeboarding-thanks").trigger("click");
										$(".content").html('message sent');
									},1000);
									
									
									// location.reload();
								}
								
				}
			};					
				
	$("#sendMessageToUser").ajaxForm(sendMessage);
	
	
	/*right pursuit player list paging*/

	
	
	
	$(document).on('click','.prevlistpursuitplayer',function(){
		// alert('ada');
		var start = parseInt($(".listParticipant").attr("startpage"),10)-2;
		var totalplayer = parseInt($(".listParticipant").attr("total"));
		var contentid = parseInt($(".listParticipant").attr("prop"));
		//console.log(start);
		//console.log(totalplayer);
		if(start>=0){
			$.post(basedomain+'movefwd/participant', {prevlistplayer:true, start:start,contentid:contentid}, function(data){
				var html = "";
				
				if (data.status == true){
					
						$.each(data.rec, function(i,e){
			
						html += "<a style='margin-right:5px;' href='#popup-inbox-new' class='showPopup composemessagefromthumbnail' recepienttxt='"+e.name+"' recepientid='"+e.id+"'  ><img src='"+basedomain+"public_assets/user/photo/"+e.image_profile+"' width='50px' height='50px'  /></a>";

						})
						
					$(".listParticipant").attr("startpage", start);
				}else{
					html += locale.notrade;
				}
				
				
				$(".participantBoxList").html(html);
				
			},"JSON")
		
		}
		
		
	})
	

/*right pursuit player list paging*/

	
	
	
	$(document).on('click','.nextlistpursuitplayer',function(){
		// alert('ada');
		var start = parseInt($(".listParticipant").attr("startpage"),10)+2;
		var totalplayer = parseInt($(".listParticipant").attr("total"));
		var contentid = parseInt($(".listParticipant").attr("prop"));
		
		if(start < totalplayer){
			$.post(basedomain+'movefwd/participant', {nextlistplayer:true, start:start,contentid:contentid}, function(data){
				var html = "";
				
				if (data.status == true){
					
					
						$.each(data.rec, function(i,e){
			
						html += "<a style='margin-right:5px;' href='#popup-inbox-new' class='showPopup composemessagefromthumbnail' recepienttxt='"+e.name+"' recepientid='"+e.id+"'  ><img src='"+basedomain+"public_assets/user/photo/"+e.image_profile+"' width='50px' height='50px'  /></a>";

						})
						
					$(".listParticipant").attr("startpage", start);
				}else{
					html += locale.notrade;
				}
				
				
				$(".participantBoxList").html(html);
				
			},"JSON")
		
		}
		
		
	})
	