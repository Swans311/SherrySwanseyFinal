<!DOCTYPE HTML> 
<html> 
	<head> 
		<title> 
			How to remove an HTML element 
			using JavaScript ? 
		</title> 
		
		<style> 
			#GFG_DIV { 
				background: green; 
				height: 100px; 
				width: 200px; 
				margin: 0 auto; 
				color: white; 
			} 
		</style> 
	</head> 
	
	<body style = "text-align:center;"> 
		<form method=get">
		<h1 style = "color:green;" > 
			GeeksForGeeks 
		</h1> 
		
        <div id="new">
        </div>

		<p id = "GFG_UP" style = 
			"font-size: 19px; font-weight: bold;"> 
		</p> 
		
		<div id = "GFG_DIV"> 
			This is Div box. 
		</div> 
		<br> 
		
		<button onClick = "GFG_Fun()"> 
			remove here 
		</button> 
        <button onClick = "GFG_Add()">
            add here
        </button
		
		<p id = "GFG_DOWN" style = "color: green; font-size: 24px; font-weight: bold;"> 
		</p> 
		
		<!-- Script to remove HTML element -->
		<script> 
			var up = document.getElementById('GFG_UP'); 
			var down = document.getElementById('GFG_DOWN'); 
			var div = document.getElementById('GFG_DIV'); 
			up.innerHTML = "Click on button to remove the element."; 
			
			function GFG_Fun() { 
				document.getElementById('new').remove(); 
				down.innerHTML = "Element is removed."; 
			} 
            function GFG_Add(){
                document.getElementById('new').innerHTML += "Hello David";

            }
		</script> 
	</body> 
</html>					 
