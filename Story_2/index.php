<?php
include_once 'db.php';
$sql = "SELECT * FROM story_title";
$query = $conn->query($sql);
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
     <h3><span style="color:#009FE3;">pepper</span><span style="color:black;">Stories</span></h3>
     <div id="createStoryBtn" style="cursor:pointer; font-family: myfont; margin-bottom:30px; font-size:32px; display: inline-block;" class="cmn-t-underline"><i class="fa fa-plus fa-lg"></i>&nbsp;Story</div>
     <table border="0" >
       <tr>
         <th>Story Title</th>
       </tr>
     <?php
     if($query->num_rows > 0)
      while($story = $query->fetch_assoc()){
        echo "<tr>";
        echo "<td style='padding-right: 50px;'>".$story['title']."</td>";
        echo "<td style='padding-right: 20px;'><div class='editStory' id='editStory-".$story['id']."' style='cursor: pointer;'><i class='fa fa-pencil fa-lg'></i></div></td>";
        echo "<td style='padding-right: 20px;'><div class='deleteStory' id='deleteStory-".$story['id']."' style='cursor: pointer;'><i class='fa fa-trash-o fa-lg'></i></div></td>";
        echo "</tr>";
      }
      else {
        echo "No stories found.";
      }
      ?>
    </table></center>
   </body>
 </html>
