
window.parent.document.getElementById('qanvaeeboverlay').addEventListener('click',function(event){
if(event.target.id != 'qanvaeebinfo' && event.target.id != 'qanvaeebcloneselect' && event.target.id != 'qanvaeebselectmodal' && event.target.id != ''){
   top.document.getElementById('qanvaeeboverlay').style.display = 'none';
}
});

document.addEventListener ('keydown', function (event) {
 if(event.key == 'q' && (event.altKey == true || event.ctrlKey == true)){
   top.document.getElementById('qanvaeeboverlay').style.display = 'block';
		top.document.getElementById('qanvaeebselectmodal').focus();
  }
 if(event.keyCode == 27){
		top.document.getElementById('qanvaeeboverlay').style.display = 'none';
	}
});