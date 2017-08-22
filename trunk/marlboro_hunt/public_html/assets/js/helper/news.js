
	$(document).on('click', '.popupNews', function () {
	
		var cid = $(this).attr('prop');
		
		$.post(basedomain+'news/showPopup', {isvalid:true, cid:cid}, function(data){
		
			var html = "";
			
			if (data.status==true){
				
				html +="<div class='newsThumb'>";
                html +="<img src='"+basedomain+"public_assets/news/"+data.rec.image+"' />";
				html +="</div>";
				html +="<div class='newsContent'>";
            	html +="<h3 class='newsTitle'>"+data.rec.title+"</h3>";
                html +="<div class='scroll-pane'>"+data.rec.content+"";
					html +="<div class='commentBox'>";
					html +="<textarea class='newsCommentPopup'></textarea>";
					html +="<input type='submit' value='&nbsp;' class='submitComment' prop='"+data.rec.id+"'/>";
						html +="<div class='comments commentFrame'>";
						
							$.each(data.comment, function(i,e){
							
							
                                html +="<div class='row'>";
                                    html +="<div class='thumb-user'>";
                                        html +="<a href='#'><img src='' width='50px'/></a>";
                                    html +="</div>";
                                    html +="<div class='entry-right'>";
                                        html +="<div class='entry-header'>";
                                            html +="<h3 class='username'><a href='#'>"+e.name+"</a></h3>";
                                        html +="</div><!-- END .entry-header -->";
                                        html +="<div class='entry-body'>";
                                            html +="<p>"+e.comment+"</p>";
                                            html +="<span class='comment-date'>Posted on "+e.date+"</span>";// | <a href='#' class='replyBtn'>REPLY</a>";
                                        html +="</div><!-- END .entry-body -->";
                                    html +="</div><!-- END .entry-right -->";
                                html +="</div><!-- END .row -->";
							})
							// html +="<div class='row comment-child '>";
                                    // html +="<div class='thumb-user'>";
                                        // html +="<a href='#'><img src='' width='50px'/></a>";
                                    // html +="</div>";
                                    // html +="<div class='entry-right'>";
                                        // html +="<div class='entry-header'>";
                                            // html +="<h3 class='username'><a href='#'>Acitjazz</a></h3>";
                                        // html +="</div><!-- END .entry-header -->";
                                        // html +="<div class='entry-body'>";
                                            // html +="<p>Hellow WOrld!</p>";
                                            // html +="<span class='comment-date'>Posted on 18/07/2013 -  18:05PM</span> | <a href='#' class='replyBtn'>REPLY</a>";
                                        // html +="</div><!-- END .entry-body -->";
                                    // html +="</div><!-- END .entry-right -->";
                                // html +="</div><!-- END .row -->";
						html +="</div><!-- END .comments -->";
					html +="</div>";
				html +="</div>";
				html +="</div>";
			
			}else{
				html += "Content not loaded";
			}
			$('.newsDetail').trigger('click');
			$('.newsContentDetail').html(html);
			$('.popupEventSlider').trigger('click');
		}, "JSON");
		
	});
	
	$(document).on('click', '.submitComment', function () {
		
		var cid = $(this).attr('prop');
		var comment = $('.newsCommentPopup').val();
		
		$.post(basedomain+'news/comment', {isvalid:true, cid:cid, comment:comment}, function(data){
			var html = "";
			if (data.status==true){
				
				$.each(data.rec, function(i,e){
							
							
					html +="<div class='row'>";
						html +="<div class='thumb-user'>";
							html +="<a href='#'><img src='' width='50px'/></a>";
						html +="</div>";
						html +="<div class='entry-right'>";
							html +="<div class='entry-header'>";
								html +="<h3 class='username'><a href='#'>"+e.name+"</a></h3>";
							html +="</div><!-- END .entry-header -->";
							html +="<div class='entry-body'>";
								html +="<p>"+e.comment+"</p>";
								html +="<span class='comment-date'>Posted on "+e.date+"</span>";//| <a href='#' class='replyBtn'>REPLY</a>";
							html +="</div><!-- END .entry-body -->";
						html +="</div><!-- END .entry-right -->";
					html +="</div><!-- END .row -->";
				})
				
				$('.commentFrame').html(html);
			}
			
		},"JSON");
	})
	
	$(document).on('click', '.popupEvent', function () {
		var cid = $(this).attr('prop');
		
		$.post(basedomain+'event/showPopup', {isvalid:true, cid:cid}, function(data){
		
			var html = "";
			
			if (data.status==true){
				
				var record = data.rec;
				
				html += "<div id='slider' class='flexslider'>";
				html += "	<ul class='slides'>";
				
				$.each(record.gallery, function(i,e){
				//loop
				html += "		<li>";
				html += "			<a class='eventthumb' href='#'>";
				html += "			<img src='"+basedomain+"public_assets/gallery/"+e.files+"' />";
				html += "			<h3 class='event-title'>'Labor day event'</h3>";
				html += "			</a>";
				html += "		</li>";
				// end loop
				})
				html += "	</ul>";
				html += "	<div class='eventContent'>";
				html += "		<p>Upload your own photo/video from the event</p>";
				// html +="<form method='post' action='"+basedomain+"event/upload' id='uploadevent' enctype='multipart/form-data'>";
				// html +="<input type='file' name='image'>";
				// html +="<input type='hidden' name='idevent' value='"+record.id+"'>";
				// html +="<input type='submit' name='submit' value='submit'>";
				// html +="</form>";
				html += "		<a href='#' class='landbtn landbtn1'>UPLOAD</a>";
				html += "	</div>";
				html += "</div>";
				html += "<div id='carousel' class='flexslider'>";
				html += "	<ul class='slides'>";
				
				$.each(record.gallery, function(index,elem){
				// loop
				html += "		<li>";
				html += "			<a class='smallthumb' href='#'>";
				html += "			<img src='"+basedomain+"public_assets/gallery/"+elem.files+"' />";
				html += "			</a>";
				html += "		</li>";		
				// end loop
				})
				html += "	</ul>";
				html += "</div>";
				
			}else{
				html += "Content not loaded";
			}
			
			$('.eventdetail').trigger('click');
			$('.contentEventDetail').html(html);
			
			
			
		}, "JSON");
		$('.getEvent').trigger('click');
	});
	
	
	
	
