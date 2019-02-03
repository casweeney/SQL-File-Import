<?php
	$message = "";
	if(isset($_POST['import'])){
		if($_FILES['database']['name'] != ""){
			$array = explode(".", $_FILES['database']['name']);
			$extension = end($array);
			if($extension == "sql"){
				$connect = mysqli_connect("localhost", "root", "", "testing_import");
				$output = "";
				$count = 0;
				$file_data = file($_FILES['database']['tmp_name']);
				foreach($file_data as $row){
					$start_character = substr(trim($row), 0, 2);
					if($start_character != "--" || $start_character != "/*" || $start_character != "//" || $row != ""){
						$output = $output . $row;
						$end_character = substr(trim($row), -1, 1);
						if($end_character == ";"){
							if(!mysqli_query($connect, $output)){
								$count++;
							}
							$output = "";
						}
					}
				}
				if($count > 0){
					$message = "<label style='color: #c00;'><strong>There is an error in database import</strong></label>";
				}else{
					$message = "<label style='color: #0c0;'><strong>Database Successfully Imported</strong></label>";
				}
			}else{
				$message = "<label style='color: #c00;'><strong>Invalid file</strong></label>";
			}
		}else{
			$message = "<label style='color: #c00;'><strong>Please select sql file</strong></label>";
		}
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Import SQL using PHP</title>
	</head>
	<body>
		<br><br>
		<div style="width: 50%; margin-right: auto; margin-left: auto;">
			<h2 style="text-align: ;">How to import SQL file to MySQL Database using PHP</h2>
			<?php echo $message; ?>
			<form method="post" enctype="multipart/form-data">
				<p>
					<label>Select SQL file</label>
					<input type="file" name="database">
				</p>
				<input type="submit" name="import" value="Import">
			</form>
		</div>
	</body>
</html>