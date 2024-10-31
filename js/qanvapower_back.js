
		function ptwidthchange(newval,notextval){
			if(document.getElementById('qanva_pt_width_style') != null){
				var delit = document.getElementById('qanva_pt_width_style');
				delit.remove();
			}
			var notextadd = '';
			if(notextval == 'yes'){
				notextadd = '.elementor-panel .elementor-element .title{font-size:0 !important;height:16px !important}';
				localStorage.setItem("qanva_powertools_text",'yes');
				maketooltipp(1);
			}
			if(notextval == 'no'){
				notextadd = '';
				localStorage.setItem("qanva_powertools_text",'no');
				maketooltipp(0);
			}
			

				var newEl = document.createElement("style"); 
				newEl.setAttribute("id", "qanva_pt_width_style");
					switch(newval){
						case "2" : var textnode = document.createTextNode(".elementor-panel .elementor-responsive-panel{grid-template-columns: repeat(auto-fill, minmax(Min(135px, calc( 50% - 5px)), 1fr))}" + notextadd);
									break;
						case "3" : var textnode = document.createTextNode(".elementor-panel .elementor-responsive-panel{grid-template-columns: repeat(auto-fill, minmax(Min(135px, calc( 30% - 5px)), 1fr)) !important}" + notextadd);
									break;
						case "4" : var textnode = document.createTextNode(".elementor-panel .elementor-responsive-panel{grid-template-columns: repeat(auto-fill, minmax(Min(135px, calc( 20% - 5px)), 1fr)) !important}" + notextadd);
									break;
						case "x" : var textnode = document.createTextNode(".elementor-panel .elementor-responsive-panel{grid-template-columns: repeat(auto-fill, minmax(Min(60px,calc(100% - 60px)),1fr)) !important}" + notextadd);
									break;
					}
						newEl.append(textnode);
							if(document.querySelector('#elementor-panel-inner') != null ){  
								document.getElementById("elementor-panel-inner").appendChild(newEl);
							}
								localStorage.setItem("qanva_powertools_grid",newval);
		}
		
		function maketooltipp(ny){
			if("1" == ny && localStorage.getItem("qanva_powertools_tt") == "yes"){
				if(document.querySelector('#pttt') == null){
					var newdEl = document.createElement("div"); 
					newdEl.setAttribute("id", "pttt");
					newdEl.setAttribute("style", "display:none;padding:5px 7px;background:white;color:red;position:absolute;border:2px solid black;border-radius:3px;");
						if(document.querySelector('#elementor-panel-inner') != null ){  
							document.getElementById("elementor-panel-inner").appendChild(newdEl);
						}
				}
				var el = document.getElementsByClassName("elementor-element-wrapper");
				for(var i = 0; i < el.length; i++){
					el[i].addEventListener("mouseover", function(){var qtt = document.getElementById('pttt');qtt.style.display = 'block'; qtt.textContent = this.querySelector('.title').textContent;}, false);
					el[i].addEventListener("mouseout", function(){var qtt = document.getElementById('pttt');qtt.style.display = 'none'; qtt.textContent = '';}, false);
				}
						
						
			}
		}
		/* get mouse position */
		document.onmousemove = updateTT;
		
		/* kill tooltipp */
		setInterval(function(){if(document.getElementsByClassName("elementor-element-wrapper").length == 0 && document.getElementById('pttt')){document.getElementById('pttt').style.display = 'none'; document.getElementById('pttt').textContent = '';}},200);
			
		function updateTT(e) {
			var tt = document.getElementById('pttt');
			if (tt != null && tt.style.display == 'block') {
				x = (e.pageX ? e.pageX : window.event.x) + tt.offsetParent.scrollLeft - tt.offsetParent.offsetLeft;
				y = (e.pageY ? e.pageY : window.event.y) + tt.offsetParent.scrollTop - tt.offsetParent.offsetTop;
				tt.style.left = (x - 50) + "px";
				tt.style.top 	= (y - 50) + "px";
			}
		}
		
	
		setInterval(function(){
				/* check and set select-box and CSS */
				var selme = 2;
				if('' != localStorage.getItem("qanva_powertools_grid")){
					selme = localStorage.getItem("qanva_powertools_grid");
				}
				if(document.querySelector('#qanva_pt_width')){
						document.getElementById('qanva_pt_width').value = selme;
				}
				/* check no-text setting */
				var notext = 'no';
				if('' != localStorage.getItem("qanva_powertools_text")){
					notext = localStorage.getItem("qanva_powertools_text");
				}
				if('yes' == localStorage.getItem("qanva_powertools_text")){
					maketooltipp(1);
				}
				/* check tooltipp setting */
				if(document.querySelector('[data-setting="qanva_pt_tt"]')){
					document.querySelector('[data-setting="qanva_pt_use_tt"]').checked = true;
				}
				if(document.querySelector('[data-setting="qanva_pt_use"]') && notext == 'yes'){
						document.querySelector('[data-setting="qanva_pt_use"]').checked = true;
				}
					if(document.querySelector('[data-setting="qanva_pt_rem_wp"]') !== null){
						if(localStorage.getItem("qanva_powertools_nowp") == "no"){
							document.querySelector('[data-setting="qanva_pt_rem_wp"]').checked = true;
						}
						else{
							document.querySelector('[data-setting="qanva_pt_rem_wp"]').checked = false;
						}
					}
				/* send info */
				if(document.querySelector('#qanva_pt_width_style') == null){
						ptwidthchange(selme,notext);
				}
		},1000);

		setInterval(function(){
			if(document.querySelector('[data-setting="qanva_pt_use"]')){
				/* check width change */
				if(document.querySelector('[data-setting="qanva_pt_use"]').checked === true){
					notext = 'yes';
					ptwidthchange(localStorage.getItem("qanva_powertools_grid"),notext);
				}
				/* check no-text on/off */
				if(document.querySelector('[data-setting="qanva_pt_use"]').checked === false){
					localStorage.setItem("qanva_powertools_text",'no');
					notext = 'no';
					ptwidthchange(localStorage.getItem("qanva_powertools_grid"),notext);
				}
			}
				/* check WP widget on/off */
				if(document.querySelector('[data-setting="qanva_pt_rem_wp"]') !== null){
					if(document.querySelector('[data-setting="qanva_pt_rem_wp"]').checked === true){
						localStorage.setItem("qanva_powertools_nowp",'no');
					}
					if(document.querySelector('[data-setting="qanva_pt_rem_wp"]').checked === false){
						localStorage.removeItem("qanva_powertools_nowp");
					}
				}
				if(localStorage.getItem("qanva_powertools_nowp") == "no" && document.querySelector('#elementor-panel-category-wordpress') !== null){
						document.querySelector('#elementor-panel-category-wordpress').remove();
				}
		}, 500);

jQuery( document ).ready( function( $ ) {		
	$(document).on('change','#qanvaeebselect',function(){
		var gotourl = $(this).val();
		window.location = 'post.php?post=' + gotourl + '&action=elementor';
	});

	$(document).on('click','#elclone',function(){
		$('#qanvaeeboverlay').show();
	});

	function addbuttonstoelementormenu(){

		$( '.elementor-panel .elementor-panel-menu-item-exit-to-dashboard a[href]' ).eq( 0 ).prop( 'href', './');
			var qanva_select = '<div class="elementor-panel-menu-group-title qebheader">QUICKCHANGER<br><span style="text-transform:none;display:block;margin-top:5px">(Shortcut: ALT/CTRL + &#113;)</span></div>';
			qanva_select += '<div class="elementor-panel-menu-item elementor-panel-menu-item qanvaselect" style="display:none;cursor:auto;background:none;" >';
			qanva_select += '<select name="qanvaeebselect" id="qanvaeebselect"><option>' + qanvapowertoolsvals.seltext + '</option>';
			qanva_select += qanvapowertoolsvals.linkliste ;
			qanva_select += '</select>';
			qanva_select += '</div>';
			
			qanva_select += '<input type="button" id="elclone" value="' + qanvapowertoolsvals.qanva_extrabutton_clone + '">';
			
			if( $( '.elementor-panel .qanvaselect' ).length < 1){
				$( qanva_select ).insertAfter( '.elementor-panel .elementor-panel-menu-item-exit-to-dashboard' );
			}
		
		for( var i = 0; i < qanvapowertoolsvals.qanva_extrabutton_url.length; i++ ){
			if('' != qanvapowertoolsvals.qanva_extrabutton_url[ i ]){
				var qanva_url = qanvapowertoolsvals.qanva_extrabutton_url[ i ];
				var qanva_target = qanvapowertoolsvals.qanva_extrabutton_target[ i ];
				var qanva_text = qanvapowertoolsvals.qanva_extrabutton_text[ i ]; 
				var qanva_class = qanva_text.replace(/#|-|_|\s/g, '' ).toLowerCase();
				var qanva_class_l =  $( '.elementor-panel .qanvabutton-' + qanva_class ).length;
				var eicon = 'eicon-editor-link';
				var linktitel = qanvapowertoolsvals.qanva_extrabutton_self;
				if( qanva_target == '_blank' ){ eicon = 'eicon-editor-external-link'; linktitel = qanvapowertoolsvals.qanva_extrabutton_new; }
				var qanva_html = '<div class="elementor-panel-menu-item elementor-panel-menu-item-exit-to-dashboard qanvabutton-' + qanva_class + '" style="display:none" title="' + linktitel + '"><div class="elementor-panel-menu-item-icon"><i class="' + eicon + '"></i></div><a href="' + qanva_url + '" target="' + qanva_target + '"><div class="elementor-panel-menu-item-title">' + qanva_text + '</div></a></div>';
				if( qanva_class_l < 1 ){
					$( qanva_html ).insertAfter( '.elementor-panel .elementor-panel-menu-item-exit-to-dashboard' );
				}
				$( '.qanvabutton-' + qanva_class ).eq( 1 ).remove();
				$( '.qanvabutton-' + qanva_class ).eq( 0 ).fadeIn();
			}
		}
			$( '.qanvaselect' ).fadeIn();
	}
	var addbuttonstoelementormenucheck = setInterval( addbuttonstoelementormenu, 500 );
	
	/* check and fix permalink */
	function permacheckfunc(){
		if(1 == $('#qanvanewpname').length){
			var proofdash = $('#qanvanewpname').val();
			if('-' == proofdash.substring(0,1)){
				proofdash = proofdash.substring(1,proofdash.length);
			}
			proofdash = proofdash.toLowerCase().replace(/[^a-zA-Z0-9-]+/g, "");
			$('#qanvanewpname').val(proofdash);
		}
	}

	setInterval(function(){
	 $('#qanvanewpname').on('focus',function(){
			clearInterval(permacheck);
  });	
	 $('#qanvanewpname').on('focusout',function(){
			permacheckfunc();
		});
	},500);
	
			$(document).on('click','#qanvasaveperma',function(){
				var parts = window.location.href.split('?');
				var insertstring = $(document).find('#qanvanewpname').val();
				if('' == insertstring){
					return;
				}
					if('-' == insertstring.substring(insertstring.length - 1)){
					 insertstring = insertstring.substring(0,insertstring.length - 1);
						$('#qanvanewpname').val(insertstring);
					}
					var data = {
						'action': 'setnewpermaname',
						'newname': insertstring,
						'postid': parts[1].replace( /\D/g, '')
					};

					jQuery.post(ajaxurl, data, function(response){
						if('' != response){
								$('#qptistsaved').text(response);
								$('#qptistsaved').fadeIn(500,function(){
									$('#qptistsaved').fadeOut(800, function(){
										$('#qptistsaved').text('');
									});
								});
						}
					});
			});
			
			/* check password */
			var thePW = '';
			$(document).on('click mouseup keyup','#qanvauserpw',function(){
				thePW = $(this).val();
				thePW =	thePW.replace(/[^a-zA-Z0-9]+/g, "");
				$('#qanvauserpw').val(thePW);
			});

			/* get Favorites  */			
			$(document).on('click','#qanvagetfavorites',function(){
						if(thePW.length >= 8){
							var datacheck = {
								'action' : 'getqptefavorites',
								'todo' : 'getfavorites',
								'qanvausermail' : $(document).find('#qanvausermail').val(),
								'qanvauserpw' : thePW,
							};
							jQuery.post(ajaxurl, datacheck, function(response){
								if(0 != response){
									location.reload();
								}
							});
						}
						else{
							$('#qanvapwinfo').fadeTo("slow", 1, function(){
								$("#qanvapwinfo").fadeTo(1500,0);
							});
						}
			});
				
			/* save favorites */
			$(document).on('click','#qanvasavefav',function(){
				if(thePW.length >= 8){
					var data = {
						'action' : 'getqptefavorites',
						'todo' : 'setfavorites',
						'qanvausermail' : $(document).find('#qanvausermail').val(),
						'qanvauserpw' : thePW,
					};
					jQuery.post(ajaxurl, data, function(response){
						//alert(response);
						if('' != response){
							$('#qanvasavefav').css({'background':'white','color':'#39b54a','border':'1px solid black'});
							$('#qanvasavefav').text(qanvapowertoolsvals.qanva_done);
							setTimeout(function(){
								$('#qanvasavefav').css({'background':'#0085ba','color':'white','border':'none'});
								$('#qanvasavefav').text(qanvapowertoolsvals.qanva_save_perma);								
							},1000);
						}
					});
				
				}
					else{
						$('#qanvapwinfo').fadeTo("slow", 1, function(){
							$("#qanvapwinfo").fadeTo(1500,0);
						});
					}
			});
			
			/* remove double widgets on start */
			const startdouble = setInterval(removedoublew,1000);
			
			/* remove double widgets switching back */
			$(document).on('click','#elementor-panel-header-add-button',function(){
				removedoublew();
			});		
			
			/* remove after change of favorites	*/
			$(document).on('click','.elementor-context-menu-list',function(){
				setTimeout(favochange,500);
			});
			
			function favochange(){		
				if(document.querySelector('#elementor-panel-categories') !== null){	
					const targetNode = document.getElementById('elementor-panel-categories');
					const config = { childList: true, subtree: true };
					const callback = function(mutationsList, observer){
						for(const mutation of mutationsList) {
							if (mutation.type === 'childList') {
								removedoublew();
							}
						}
					};
					const observer = new MutationObserver(callback);
					observer.observe(targetNode, config);
				}
			}
			
			/* function to remove double widgets */
			function removedoublew(){
				var elic = $(document).find("#elementor-panel-category-favorites .elementor-panel-category-items .elementor-element-wrapper .icon i");
				var eltitle = $(document).find("#elementor-panel-category-favorites .elementor-panel-category-items .elementor-element-title-wrapper div");
				for(var y = 0; y < elic.length; y++ ){
					var ico = elic[y].classList;
					var ictitle = eltitle[y].innerHTML;
					$(document).find("[id^=elementor-panel-category]:not(#elementor-panel-category-favorites) .elementor-element-wrapper").each(function(){
						wtitle = $(this).find(".title");
						if( $(this).find("." + ico) && wtitle.html() == ictitle ){
							$(this).css('display','none');
						}
					});
					
				}
				if(elic.length > 0){
					clearInterval(startdouble);
				}
			}


	 document.getElementById('qanvaeeboverlay').addEventListener('click',function(event){
	 if(event.target.id != 'qanvaeebinfo' && event.target.id != 'qanvaeebcloneselect' && event.target.id != 'qanvaeebselectmodal' && event.target.id != ''){
		document.getElementById('qanvaeeboverlay').style.display = 'none';
	 }
	 });
	 
	 document.addEventListener ("keydown", function (event) {
		  if(event.key == 'q' && (event.altKey == true || event.ctrlKey == true)){
			 document.getElementById('qanvaeeboverlay').style.display = 'block';
			 document.getElementById('qanvaeebselectmodal').focus();
		  }
		  if(event.keyCode == 27){
			 document.getElementById('qanvaeeboverlay').style.display = 'none';
		  }
	 });
		
});