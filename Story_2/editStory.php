<?php
include_once 'db.php';
$id = $_GET['id'];
$story_title_query = mysqli_query($conn, "SELECT * FROM story_title WHERE id=".$id);
$title = mysqli_fetch_assoc($story_title_query);
$story_lines = mysqli_query($conn, "SELECT * FROM story_line WHERE story_id=".$id." ORDER BY p_id");
$i = 0;

 ?>

 <!DOCTYPE html>
 <html>
   <head>
     <meta charset="utf-8">
     <script text="text/javascript" src="js/jquery-1.10.2.js"></script>
     <script type="text/javascript" src="js/jquery-ui.js"></script>
     <script type="text/javascript" src="js/story_script.js"></script>
     <link rel="stylesheet" href="jquery-ui-1.11.4.custom/jquery-ui.css">
     <link rel="stylesheet" href="font-awesome-4.4.0/css/font-awesome.css" type="text/css">
     <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
     <link rel="stylesheet" type="text/css" href="style.css">
     <title>Edit Story</title>
     <script type="text/javascript">
      var x = 1;
     $(document).ready(function(){
       var addButton = $("#addNewStoryLine");
       var wrapper = $("#newLinesDiv");

       var fieldHTML = "<div id='addedField'><table><tr><div class='removeField' style='cursor:pointer;'><i class='fa fa-times-circle fa-lg'></i></div></tr><tr><td>Story Line</td><td><textarea name='addedStoryLine[]'></textarea></td></tr><tr><td><input type='file' name='addedLineImage[]' id='imgUpload'/></td></tr></div></table><hr></hr>";


       $(addButton).click(function(){
         x++;
         $(wrapper).append(fieldHTML);
         $("#noLines").css('display','none');
       });

       $(wrapper).on('click', '.removeField', function(e){
         e.preventDefault();
         $(this).parent('div').remove();
         x--;

       });
     });
     </script>
   </head>
   <body>
     <a href='index.php'><i class='fa fa-home fa-lg'></i></a>
     <form enctype="multipart/form-data" method="post">

     <center><p><label>Story Title&nbsp;</label><input name="title" type="text" style="width: 300px;"class="inputText" value="<?php echo $title['title'];?>"/></p>
       <input name="icon" type="file" id="imgUpload"/>
       <img id="image" style="width:200px; height: 200px;" src="data:image/;base64,<?php echo $title['icon'];?>"/>

       <br><br>
       <label>Story Lines</label>
       <?php
       if(mysqli_num_rows($story_lines) > 0){
         while($line = mysqli_fetch_assoc($story_lines)){

           echo "<table>";
           echo "<p><input name='p_id$i' value='".$line['p_id']."' type='hidden'/></p>";
           echo "<tr><td><textarea style='width:200px;' name='storyLine$i'>".$line['paragraph']."</textarea></td>";
           echo "<td><img id='image' style='width:200px; height: 200px;' src='data:image/;base64,".$line['images']."'/></td></tr>";
           echo "<tr><td><input type='file' name='image$i' id='imgUpload'/></td></tr>";
           echo "</table><hr></hr>";
           $i++;
         }?>
          <input type='button' onclick="location.href='index.php';" value='Cancel'>
         <?php
           echo "<input type='submit' name='submit' value='Update'/>";

       }else {
         echo "<div id='noLines' style='margin-bottom:30px; margin-top: 30px; color: gray;'>No lines found!</div>";
         echo "<div id='newLinesDiv'></div>";
         echo "<br><div style='cursor: pointer;' id='addNewStoryLine'>Add new line</div><br>";
         echo "<input type='submit' name='insertNewLines' value='Update'/>";
         echo "<input type='button' onclick='index.php'/>";
       }

        ?>

      </form>

   </body>
   <script>
   $('.inputText').each(function() {
    var default_value = this.value;
    $(this).focus(function() {
        if(this.value == default_value) {
            this.value = '';
        }
    });
    $(this).blur(function() {
        if(this.value == '') {
            this.value = default_value;
        }
    });

    $("textarea").height( $("textarea")[0].scrollHeight );
  });

  function readURL(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#image').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

    $("#imgUpload").change(function(){
        readURL(this);
    });
   </script>
 </html>
<?php

if(isset($_POST['submit'])){
  $title = mysqli_real_escape_string($conn, $_POST['title']);
  $icon = $_FILES['icon']['tmp_name'];
  if(!empty($_FILES['icon']['name'])){
      if(getimagesize($icon) !== FALSE){
        $icon = base64_encode(file_get_contents($icon));
        $titleUpdate = mysqli_query($conn, "UPDATE story_title SET title='$title', icon='$icon' WHERE id=$id");
        for($j=0; $j<$i; $j++){
          $pid[$j] = mysqli_real_escape_string($conn, $_POST["p_id$j"]);
          $story_line[$j] = mysqli_real_escape_string($conn, $_POST["storyLine$j"]);
          if(!empty($_FILES["image$j"]["name"])){
            $image[$j] = $_FILES["image$j"]["tmp_name"];
            $converted[$j] = base64_encode(file_get_contents($image[$j]));
            $updateLine = mysqli_query($conn, "UPDATE story_line SET paragraph='$story_line[$j]',  images='$converted[$j]' WHERE p_id=$pid[$j]");

          }else{
            $sql = "UPDATE story_line SET paragraph='$story_line[$j]' WHERE p_id=$pid[$j]";
            $updateLine = mysqli_query($conn, $sql);
          }
        }
      }else{
        echo "<script>alert('sorry, the file you uploaded is not an image.');</script>";
      }
      echo "<script>window.location='index.php';</script>";
  }else {
      $titleUpdate = mysqli_query($conn, "UPDATE story_title SET title='$title' WHERE id=$id");
      for($j=0; $j<$i; $j++){
        $pid[$j] = mysqli_real_escape_string($conn, $_POST["p_id$j"]);
        $story_line[$j] = mysqli_real_escape_string($conn, $_POST["storyLine$j"]);
        if(!empty($_FILES["image$j"]["name"])){
          echo "<script>alert('if');</script>";
          $image[$j] = $_FILES["image$j"]["tmp_name"];
          $converted[$j] = base64_encode(file_get_contents($image[$j]));
          $updateLine = mysqli_query($conn, "UPDATE story_line SET paragraph='$story_line[$j]',  images='$converted[$j]' WHERE p_id=$pid[$j]");

        }else{
          $sql = "UPDATE story_line SET paragraph='$story_line[$j]' WHERE p_id=$pid[$j]";
          echo "<script>alert('".$sql."');</script>";
          $updateLine = mysqli_query($conn, $sql);
        }
    }
  }
}
elseif (isset($_POST['insertNewLines'])) {
  $story_line_data = array_combine($_POST['addedStoryLine'], $_FILES['addedLineImage']['tmp_name']);

  foreach ($story_line_data as $paragraph => $lineImage) {
    $paragraph = mysqli_real_escape_string($conn, $paragraph);
    $lineImage = base64_encode(file_get_contents($lineImage));
    $insertLineSql = "INSERT INTO story_line(story_id, paragraph, images) VALUES('$id', '$paragraph', '$lineImage')";
    mysqli_query($conn, $insertLineSql);

  }
  echo "<script>window.location='index.php';</script>";
}
 ?>
