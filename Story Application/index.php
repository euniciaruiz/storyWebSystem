<?php
include_once 'db.php';


$sql1 = "SELECT title, id FROM story_title ORDER BY title ASC";
$query1 = mysqli_query($conn, $sql1);


 ?>

 <!DOCTYPE html>
 <html>
   <head>
     <meta charset="utf-8">
     <script text="text/javascript" src="js/jquery-1.10.2.js"></script>
     <script type="text/javascript" src="js/jquery-ui.js"></script>
     <link rel="stylesheet" href="js/jquery-ui.css">
     <script type="text/javascript" src="story_script.js"></script>
     <link rel="stylesheet" href="font-awesome-4.4.0/css/font-awesome.css" type="text/css">
     <link rel="stylesheet" type="text/css" href="style.css">
     <title>Story Application - Web System</title>
   </head>
   <body><center>
     <!-- 009FE3 -->
     <h3><span style="color:black;">Pepper's </span><span style="color:black;">Bedtime Stories</span></h3>
     <a href="index.php" style="margin-bottom: 10px;"><i class="fa fa-home fa-lg"></i>ホーム</a><br><br>
     <div id="createStoryBtn" style="cursor:pointer; font-family: myfont; margin-bottom:30px; font-size:32px; display: inline-block;" class="cmn-t-underline"><i class="fa fa-plus fa-lg"></i>&nbsp;ストーリー</div>

            <table>
              <tr>
                <th>タイトル</th>
              </tr>
              <?php
                while($row = mysqli_fetch_assoc($query1)){
               ?>
              <tr>
                <td><?php echo $row['title'];?></td>
                <td><div id='editStory-<?php echo $row['id'];?>' class='editStory'><i class="fa fa-pencil"></i></div></td>
                <td><div id='deleteStory-<?php echo $row['id'];?>' class='deleteStory'><i class="fa fa-trash-o"></i></div></td>
              </tr>
              <?php
              }
               ?>
            </table>
    </center>
   </body>
 </html>
