<?php
include_once 'db.php';

$id = $_POST['storyId'];
$functionName = $_POST['functionName'];

if($functionName == 'deleteStory'){
  $sql = "DELETE FROM story_title WHERE id=".$id;
  if(mysqli_query($conn, $sql)){
    echo json_encode("Deleted Story");
    mysqli_query($conn, "DELETE FROM story_line WHERE story_id=".$id);
  }else {
    echo json_encode("error");
  }


}
 ?>
