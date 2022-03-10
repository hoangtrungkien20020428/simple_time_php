<!-- tham khảo từ Đỗ Quang Huy  -->
<!DOCTYPE html>
<html lang="en">
    <head>
    <title>Date and Time Picker !</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="keywords" content="Date and Time Picker Responsive Widget, Bootstrap Web Templates, Flat Web Templates, Andriod Compatible web template, Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyErricsson, Motorola web design" />
		<script type="application/x-javascript"> addEventListener("load", function() {setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
        <link href='//fonts.googleapis.com/css?family=Ubuntu:400,500,700,300' rel='stylesheet' type='text/css'>
			 <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
            <link href="css/bootstrap-datetimepicker.css" rel="stylesheet">
			<link href="css/style.css" rel="stylesheet" type="text/css"/>
			<script src="js/jquery-2.1.4.min.js"></script>
			<script src="js/bootstrap.min.js"></script>
            <script src="js/moment-with-locales.js"></script>
			<script src="js/bootstrap-datetimepicker.js"></script>
    </head>
    <body>

        <div class="header">
            <h1><a>Date and Time Picker</a></h1>
        </div>
        <?php 
        define('DB_SERVER','localhost');
        define('DB_NAME','dbtime');
        define('DB_USERNAME','root');
        define('DB_PASSWORD','password');
        define('DB_PORT',3306);


        
         $conn = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_NAME,DB_PORT);
         if(!$conn){
            die("some thing went wrong !" . mysqli_connect_error());
         }
         function parseTime(string $date) {
            $check = DateTime::createFromFormat('Y-m-d H:i',$date);
            if(!$check) return DateTime::createFromFormat('Y-m-d H:i:s',$date);
            return $check;
        }
        function saveTime($time,$conn){
            $query = "INSERT INTO date_and_time(datetime) VALUE ('".$time->format('Y-m-d H:i:s')."')";
            mysqli_query($conn,$query);
        }
        function displayTime($id,$conn){
            $query = "SELECT * FROM date_and_time WHERE id = $id";
            $res =  mysqli_query($conn,$query);
            if ($res->num_rows != 1) 
            {
                return NULL;
            }
            else { $r = $res->fetch_assoc();
            return parseTime($r(['date_and_time']));
        }
        }

        $list_time_zone = DateTimeZone::listIdentifiers();
        if (isset($_POST["selectTime"]) && isset($_POST["dt"])) 
        {
			$_POST["dt"][10] = ' ';
			date_default_timezone_set($_POST["localtz"]);
			$datetime = parseTime($_POST["dt"]);
			if (!$datetime) 
            {
				 echo "oh no , check post again";
			} 
            else 
            {
				$dtz = new DateTimeZone('Asia/Tokyo');
				$datetime->setTimezone($dtz);
				saveTime($datetime, $conn);
			}
		}
         else
          {
            echo "some thing wrong !!!!";
        }

$query = "SELECT * FROM date_and_time";
$load_datetime = mysqli_query($conn, $query);
$load_datetime_result = [];
while ($row = $load_datetime->fetch_assoc()) {
array_push($load_datetime_result, ["id" => $row['id'], "datetime" => $row['datetime']]);
}

       
        ?>
        <form  method="get">
            <select name = "display" >
                <?php
                foreach ($list_time_zone as $lt): 
                if($lt == "Asia/Hong_kong"):
               ?>
                    <option value = "<?= $lt?>" selected = "selected"><?= $lt ?></option>
                    <?php else: ?>
                      	<option value = "<?= $lt?>"><?= $lt?></option>
                        <?php endif; 
                    endforeach; ?>
            </select>
            <input  type = "submit" >
        </form>


        <main role="main">
			<article>
                <h2>display time </h2>
				<table border="1">
					<thead>
						<th>ID&nbsp;</th>
						<th>Datetime</th>
					</thead>
					<tbody>
						<?php if ($load_datetime_result) {
							date_default_timezone_set("Asia/Tokyo");
							$display = $_GET['display'] ?? 'Asia/Hongkong';
							$dtz = new DateTimeZone($display);
							foreach($load_datetime_result as $datetime): ?>
						<tr>
							<td><?= $datetime['id'] ?></td>
							<td><?= parseTime($datetime['datetime'])->setTimezone($dtz)->format('Y-m-d H:i:s')?> <i>_<?= $display ?> </i></td>
						</tr>
						<?php endforeach;}?>
					</tbody>
				</table>
			</article>
		</main>
        

        <div>
            <p>input time local</p>
            <form name = "register_form" action="" method="post">
				<div class="form_settings">
						<select name="selectTime">
							<?php foreach ($list_time_zone as $lt):
								if ($lt == "Asia/Ho_Chi_Minh"):
							?>
									<option value = "<?= $lt?>" selected = "selected"><?= $lt ?></option>
								<?php else: ?>
									<option value = "<?= $lt?>"><?= $lt ?></option>
							<?php endif;endforeach; ?>
						</select>
					<p><input type="datetime-local" name = "dt">
                
                </p>
					<p style="padding-top: 15px"><span>&nbsp;</span><input class="submit" type="submit" name="name" /></p>
				</div>
			</form>


        </div>
    </body>
</html>
