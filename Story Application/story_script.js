$(function(){


  $("#createStoryBtn").click(function(){
    window.location = "newStory.php";
  });

  $(".editStory").click(function (){
    var id = this.id.split('-');
    var url = 'editStory.php?id='+id[1];
    window.location = url;
  });

  $(".deleteStory").click(function(){
    var id = this.id.split('-');
    if(confirm("Are you sure you want to delete this story?") == true){
      $.ajax({
        type: "POST",
        url: "storyApp.php",
        data: "storyId="+id[1]+"&functionName=deleteStory",
        dataType: "json",
        success: function(data){
          location.reload();
          document.getElementById("successDelete").style.display = "inherit";
        },
        error: function(data){
          document.getElementById("errorDelete").style.display = "inherit";
        }
      });
    }
  });


});
