<?php
include_once 'db.php';
if(isset($_POST['submit'])){
  $story_title = mysqli_real_escape_string($conn,$_POST['storyTitle']);
  $lang = mysqli_real_escape_string($conn, $_POST['language']);
  $checkTitle = mysqli_query($conn, "SELECT id FROM story_title WHERE title='$story_title'");
  if(mysqli_num_rows($checkTitle) > 0){
    echo '<script>alert("Story already exists in the database!");</script';
  }else{
    $iconInfo = getimagesize($_FILES['storyIcon']['tmp_name']);
    if($iconInfo !== FALSE){

      $story_icon = base64_encode(file_get_contents($_FILES['storyIcon']['tmp_name']));
      $queryForId = mysqli_query($conn, "SELECT MAX(id) FROM story_title");
      if(is_null($queryForId)){
        $story_id = 1;
      }else {
        $story_id = mysqli_fetch_row($queryForId)[0] + 1;
      }
      $sql = "INSERT INTO story_title(id,title, icon, language) VALUES('$story_id','$story_title', '$story_icon', '$lang')";
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
  }

 ?>

 <!DOCTYPE html>
 <html>
   <head>
     <meta charset="utf-8">
     <link rel="stylesheet" href="font-awesome-4.4.0/css/font-awesome.css" type="text/css">
     <script text="text/javascript" src="js/jquery-1.10.2.js"></script>
     <script type="text/javascript" src="js/jquery-ui.js"></script>
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
     <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
     <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
     <link rel="stylesheet" href="js/jquery-ui.css">
     <link rel="stylesheet" type="text/css" href="style.css">
     <title>作ります ストーリー</title>
     <script type="text/javascript">
      var x = 1;
     $(document).ready(function(){
       var addButton = $("#addLine");
       var wrapper = $("#newStoryLine");

       var fieldHTML = "<div id='addedField'><hr></hr><div class='removeField' style='cursor:pointer;'><i class='fa fa-times-circle fa-lg'></i></div><p><label>ストーリー ライン<br><textarea name='storyLine[]' required='true'></textarea></label></p><p><label>ライン イメージ<br><input type='file' name='storyLineImage[]'/></label></p></div>";


       $(addButton).click(function(){

         x++;
         $(wrapper).append(fieldHTML);
       });

       $(wrapper).on('click', '.removeField', function(e){
         e.preventDefault();
         $(this).parent('div').remove();
         x--;
       });

       $('.dropdown-menu li').click(function(){
         var lang = $(this).html();
        //  alert(lang);
         document.getElementById("language").value = lang;
         $("#label").text(lang);
        //  $("#setlangbtn").innerHTML = <span class="caret"></span>;


       });
     });
     </script>
   </head>
   <body style="width: 70%;">
     <a href="index.php"><i class='fa fa-home fa-lg'></i>&nbsp;ホーム</a> > Add ストーリー</center>

     <br><br>
     <div class="dropdown">
      <button title="click me to set language" class="btn btn-primary dropdown-toggle" type="button" id="setlangbtn" data-toggle="dropdown"><span id="label">Set Language</span>
      <span class="caret"></span></button>
      <ul class="dropdown-menu">
        <span class="disabled dropdown-header">Language Options</span>
        <li>English</li>
        <li>日本語</li>
      </ul>
    </div>
    <br>

     <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data" style="">
       <input type="hidden" id="language" name="language"/>
       <p><label>ストーリー タイトル (Story Title)<span class="required">*</span></label><br>
         <input type="text" name="storyTitle" class="inputText" required="true"/></p>

       <p><label>ストーリー アイコン (Story Icon)<span class="required">*</span></label><br>
         <input type="file" name="storyIcon" required="true"/></p>


         <div id="newStoryLine">
           <legend><label class='header' for='line'>ストーリー ライン (Story Lines)</label></legend>

           <div id="first_line">
             <legend>ストーリー ライン (Story Line)<span class="required">*</span></legend><br>
               <textarea name="storyLine[]" required="true"/></textarea>
             <legend>ライン イメージ (Line Image)<span class="required">*</span></legend><br>
               <input type="file" name="storyLineImage[]" required="true"/>
           </div>
         </div>
         <div id="addLine" style="float: left; cursor:pointer;" title="Add Story Line"><i class="fa fa-plus-circle fa-lg"></i>&nbsp; ストーリー ライン</div>
         <br><br>

         <input type="submit" name="submit" value="Save Story"/>
     </form>
   </body>
 </html>
