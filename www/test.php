<!doctype html>
<html>
<head>
	<title>testing.me</title>
	<link rel="stylesheet" href="./js/bootstrap.min.css">
	<link rel="stylesheet" href="./js/bootstrap-theme.min.css">
	<script src="./js/jquery-3.1.1.min.js"></script>
	<script src="./js/bootstrap.min.js"></script>
</head>	
<body>
	<nav class="navbar navbar-default">
  		<div class="container-fluid">
	  		<div class="navbar-header">
			  <a class="navbar-brand" href="../">testing.me</a>
			</div>
		</div>
	</nav>
	<blockquote>
		<?php
			if (!isset($_GET['test']) || !file_exists('../tests_json/'.$_GET['test'].'.json'))
				echo "<p>Тест не найден</p>";
			else {
				$json_file = file_get_contents('../tests_json/'.$_GET['test'].'.json');
				echo "<script> var json_file = $json_file; </script>"
				?>
				<script>
					json_file.cursor = 0;
					json_file.current = json_file[0];
					json_file.previous = json_file[0];
					$("blockquote").append("<div class='panel panel-default'></div>");
					$(".panel").append("<div class='panel-heading'><span>0/0 </span><span> Вопрос</span></div>");
					$(".panel").append("<div class='panel-body'>Интерактив</div>");
					$(".panel").append("<button type='button' id='prev'>\<</button>");
					$("#prev").click(function(_){ save(); json_file.current=json_file[--json_file.cursor]; fill(); });
					$(".panel").append("<button type='button' id='next'>\></button>");
					$("#next").click(function(_){ save(); json_file.current=json_file[++json_file.cursor]; fill(); });
					$(".panel").append("<button type='button' id='finish'>завершить</button>");
					$("#finish").click(function(){ 
						$(this).prop("disabled",true);
						save();
						//count correct 
						var isCorrect = function(t){
							var isCorrect = false;
							if (t.type_variations == undefined) isCorrect = (t.answer == t.save);
							else if (t.type_variations == "checkbox") {
								if (typeof(t.answer) == "number") isCorrect = (t.save && t.save[t.variations[t.answer-1]])
								else {
									var localCorrect = 0;	
									t.answer.forEach(function(a){ if (t.save && t.save[t.variations[a-1]]) localCorrect++; });
									isCorrect = (localCorrect == t.answer.length);
								}
							} else if (t.type_variations == "radio") isCorrect = (t.save && t.save[t.variations[t.answer-1]])
							return isCorrect;
						}
						var correct = 0;
						json_file.forEach(function(t){ console.log(t,isCorrect(t)); if (isCorrect(t)) correct++; });
						// alert amount of correct and %
						alert(correct + "/" + json_file.length + " тестов успешно выполнено: " + (correct/json_file.length * 100).toString().substr(0,5) + "%");
						// alter aftermath
						if (correct < json_file.length) 
							// bad boy, show bad results only
							json_file = json_file.filter(function(v){ return !isCorrect(v); });
						// show aftermath
						json_file.finished = true;
						json_file.cursor = 0;
						json_file.current = json_file[0];
						json_file.previous = json_file[0];
						fill();
					});
					
					function save(){
						//save existing
						if (json_file.current.type_variations == undefined) 
							json_file.current.save = $("input").val();
						else if (json_file.current.type_variations == "checkbox") {
							json_file.current.save = {};
							$("input").each(function(i,e){  json_file.current.save[e.value] = e.checked; });
						} else if (json_file.current.type_variations == "radio") {
							json_file.current.save = {};
							$("input").each(function(i,e){ json_file.current.save[e.value] = e.checked; });
						}
					}
					
					function fill(){
						//create new
						$(".panel-heading :first-child").text(json_file.cursor + 1 + "/" + json_file.length + " ");
						$(".panel-heading :last-child").text(json_file.current.question);
						if (json_file.current.type_variations == undefined) {
							$(".panel-body").empty();
							if (!json_file.finished)
								$(".panel-body").html("<input type='text' value='" + (json_file.current.save || "") + "'>");
							else {
								var correct = (json_file.current.save == json_file.current.answer)
								if (correct)
									$(".panel-body").html("<input type='text' value='" + json_file.current.save + "' class='bg-success' disabled>");
								else 
									$(".panel-body").html("<input type='text' value='" + json_file.current.save + "' class='bg-danger' disabled> <span class='text-success'>" + json_file.current.answer + "</span");
							}
						} else if (json_file.current.type_variations == "checkbox") {
							$(".panel-body").empty();
							json_file.current.variations.forEach(function(e){
								$(".panel-body").append("<label><input type='checkbox' value='" + e + "'>" + e + "</label><br>");
								if (json_file.current.save && json_file.current.save[e]) $("input").last().prop("checked",true);
								if (json_file.finished) {
									$("input").last().prop("disabled",true);
									if (typeof(json_file.current.answer) == "number") 
										$("label").last().addClass(e == json_file.current.variations[json_file.current.answer-1] ? "bg-success" : "bg-danger");
									else {
										var correct = false;
										json_file.current.answer.forEach(function(answer){
											correct = correct || (e == json_file.current.variations[answer-1]);
										});
										$("label").last().addClass(correct ? "bg-success" : "bg-danger");
									}	
								}
							});
						} else if (json_file.current.type_variations == "radio") {
							$(".panel-body").empty();
							json_file.current.variations.forEach(function(e){
								$(".panel-body").append("<label><input type='radio' name='cb' value='" + e + "'>" + e + "</label><br>");
								if (json_file.current.save && json_file.current.save[e]) $("input").last().prop("checked",true);
								if (json_file.finished) {
									$("input").last().prop("disabled",true);
									$("label").last().addClass(e == json_file.current.variations[json_file.current.answer-1] ? "bg-success" : "bg-danger");
								}
							});
						}
						$("#prev").prop("disabled",json_file.cursor == 0);
						$("#next").prop("disabled",json_file.cursor + 1 == json_file.length);
					}
					fill();
				</script>
				<?php
			}
		?>
	</blockquote>
</body>
</html>
