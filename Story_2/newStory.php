<?php
include_once 'db.php';
if(isset($_POST['submit'])){

  $iconInfo = getimagesize($_FILES['storyIcon']['tmp_name']);
  if($iconInfo !== FALSE){
    $story_title = mysqli_real_escape_string($conn,$_POST['storyTitle']);
    $story_icon = base64_encode(file_get_contents($_FILES['storyIcon']['tmp_name']));
    $queryForId = mysqli_query($conn, "SELECT MAX(id) FROM story_title");
    if(is_null($queryForId)){
      $story_id = 1;
    }else {
      $story_id = mysqli_fetch_row($queryForId)[0] + 1;
    }
    $sql = "INSERT INTO story_title(id,title, icon) VALUES('$story_id','$story_title', '$story_icon')";
    mysqli_query($conn, $sql);

    $story_line_data = array_combine($_POST['storyLine'], $_FILES['storyLineImage']['tmp_name']);

    foreach ($story_line_data as $paragraph => $lineImage) {
      if(!empty($lineImage)){
        if(getimagesize($lineImage) !== FALSE){
          $paragraph = mysqli_real_escape_string($conn, $paragraph);
          $lineImage = base64_encode(file_get_contents($lineImage));
          $insertLineSql = "INSERT INTO story_line(story_id, paragraph, images) VALUES('$story_id', '$paragraph', '$lineImage')";
          mysqli_query($conn, $insertLineSql);
        }else {
          $paragraph = mysqli_real_escape_string($conn, $paragraph);
          echo "<script>alert('The file you uploaded is not an image!')</script>";
          $insertLineSql = "INSERT INTO story_line(story_id, paragraph, images) VALUES('$id', '$paragraph', '')";
          mysqli_query($conn, $insertLineSql);
        }
      }else {
        $paragraph = mysqli_real_escape_string($conn, $paragraph);
        $insertLineSql = "INSERT INTO story_line(story_id, paragraph, images) VALUES('$story_id', '$paragraph', '')";
        mysqli_query($conn, $insertLineSql);
      }
    }
    echo "<script>window.location='index.php';</script>";
  }else{
    echo "<script>alert('Story Icon should be an image!');</script>";
  }
}
 ?>

 <!DOCTYPE html>
 <html>
   <head>
     <meta charset="utf-8">
     <link rel="stylesheet" href="font-awesome-4.4.0/css/font-awesome.css" type="text/css">
     <script text="text/javascript" src="js/jquery-1.10.2.js"></script>
     <script type="text/javascript" src="js/jquery-ui.js"></script>
     <link rel="stylesheet" href="js/jquery-ui.css">
     <link rel="stylesheet" type="text/css" href="style.css">
     <title>Create Story</title>
     <script type="text/javascript">
      var x = 1;
     $(document).ready(function(){
       var addButton = $("#addLine");
       var wrapper = $("#newStoryLine");

       var fieldHTML = "<div id='addedField'><div class='removeField' style='cursor:pointer;'><i class='fa fa-times-circle fa-lg'></i></div><p><label>Story Line<br><input type='text' class='inputText' name='storyLine[]' value=''/></label></p><p><label>Line Image<br><input type='file' name='storyLineImage[]'/></label></p></div>";


       $(addButton).click(function(){

         x++;
         $(wrapper).append(fieldHTML);
       });

       $(wrapper).on('click', '.removeField', function(e){
         e.preventDefault();
         $(this).parent('div').remove();
         x--;
       });
     });
     </script>
   </head>
   <body style="width: 70%;">
     <center><h3><span style="color:#009FE3;">pepper</span><span style="color:black;">Stories</span></h3>
     <a href="index.php"><i class='fa fa-home fa-lg'></i>&nbsp;Home</a></center>

     <form method="post" enctype="multipart/form-data">
       <p><label>Story Title<span class="required">*</span></label><br>
         <input type="text" name="storyTitle" class="inputText" required="true"/></p>

       <p><label>Story Icon<span class="required">*</span></label><br>
         <input type="file" name="storyIcon" required="true"/></p>

         <div id="addLine" style="float: right; cursor:pointer;"><i class="fa fa-plus-circle fa-lg"></i>&nbsp; Add Lines</div>

         <div id="newStoryLine">
           <legend><label class='header' for='line'>Story Lines</label></legend>
           <div id="first_line">
             <p><legend>Story Line<span class="required">*</span></legend><br>
               <input type="text" name="storyLine[]" value="" required="true"/></p>
             <p><legend>Line Image<span class="required">*</span></legend><br>
               <input type="file" name="storyLineImage[]" required="true"/></p>
           </div>
         </div>

         <input type="submit" name="submit" value="Save Story"/>
     </form>
   </body>
 </html>
