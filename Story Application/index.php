<?php
include_once 'db.php';

$per_page = 5;
$pages_query = mysqli_query($conn, "SELECT count(id) FROM story_title");
$pages = ceil(mysqli_fetch_array($pages_query)[0] / $per_page);


if(!isset($_GET['page'])){
  echo "<script>window.location = 'index.php?page=1';</script>";
}else {
  $page = $_GET['page'];
}
$start = (($page - 1) * $per_page);

$sql = "SELECT title, id FROM story_title ORDER BY title ASC LIMIT $start, $per_page ";
$stories = mysqli_query($conn, $sql);


 ?>

 <!DOCTYPE html>
 <html>
   <head>
     <meta charset="utf-8">
     <script text="text/javascript" src="js/jquery-1.10.2.js"></script>
     <script type="text/javascript" src="js/jquery-ui.js"></script>
     <link rel="stylesheet" href="js/jquery-ui.css">
     <script type="text/javascript" src="story_script.js"></script>
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
     <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
     <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
     <link rel="stylesheet" href="font-awesome-4.4.0/css/font-awesome.css" type="text/css">
     <link rel="stylesheet" type="text/css" href="style.css">
     <title>Story Application - Web System</title>


   </head>
   <body>
     <div style="display:none;" id="successDelete" class="alert alert-success  alert-dismissible" role="alert">Successfully Deleted Story!</div>
     <div style="display:none;" id="errorDelete" class="alert alert-danger" role="alert">Sorry! Something went wrong while deleting that story!</div>
     <center>

     <h3><span style="color:black;">Pepper's </span><span style="color:black;">Bedtime Stories</span></h3>
     <ol class="breadcrumb">
       <li class="active"><a href="index.php" style="margin-bottom: 10px;"><i class="fa fa-home fa-lg"></i>ホーム</a></li>
     </ol>

     <div id="createStoryBtn" style="cursor:pointer; font-family: myfont; margin-bottom:30px; font-size:32px; display: inline-block;" class="cmn-t-underline"><i class="fa fa-plus fa-lg"></i>&nbsp;ストーリー</div>

            <table class="table">
              <tr>
                <th>タイトル</th>
              </tr>
              <?php
                while($row = mysqli_fetch_assoc($stories)){
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

            <?php
                echo "<ul class='pagination'>";
                for($num = 1; $num <= $pages; $num++){
                  echo '<li><a href="?page='.$num.'">'.$num.' </a></li>';
                }
                echo "</ul>";
             ?>
    </center>


   </body>
 </html>
