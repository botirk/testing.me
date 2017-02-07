<span style="margin-right:1ex">Выберете тест или загрузите свой</span> 
<input type="file" name="json" style="display:inline; color:transparent;" accept=".json">
<script>
	var input = document.getElementsByTagName('input')[0];
	input.onchange = function(){ if (input.files.length == 1){
		var data = new FormData();	
		data.append("json",input.files[0]);
		
		var request = new XMLHttpRequest();
		request.onreadystatechange = function(){ if(request.readyState == 4){
			var json = JSON.parse(request.responseText);
			if (json.error)
				alert(json.error);
			else if (json.success) {
				var coreName = json.success.slice(0,-5);
				document.getElementsByClassName('list-group')[0].innerHTML += "<a href=./test.php?test=" + coreName + " class=\"list-group-item\">" + coreName + "</a>"
			}
		}};
		request.open("POST","./upload.php");
		request.send(data);
	}};
</script>
