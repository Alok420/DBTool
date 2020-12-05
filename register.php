<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Login form</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  </head>
  <body>

    <div class="container">
      <h2>Login form</h2>
      <form id="myForm" action="controller/update.php" method="get">
        <div class="form-group">
          <label >Liked by:</label>
          <input type="text" class="form-control" id="" placeholder="Enter name" name="liked_by">
        </div>
        <div class="form-group">
          <label >personname:</label>
          <input type="text" class="form-control" id="" placeholder="Enter name" name="personname">
        </div>
        <input type="text" name="id" value="1">
        <div class="form-group">
          <label >liked by:</label>
          <input type="text" class="form-control" id="" placeholder="Enter name" name="liked_by">
        </div>
        <div class="form-group">
          <label >personname:</label>
          <input type="text" class="form-control" id="" placeholder="Enter name" name="personname">
        </div>
        <input type="text" name="id" value="1">
        <button type="button" id="btn" class="btn btn-default" name="submit">Submit</button>
      </form>
    </div>
    <script>
      $(document).ready(function () {
        $("#btn").click(function () {
          var formData = JSON.stringify($("#myForm").serializeArray());
          
          $.ajax({
            type: "POST",
            url: "controller/update.php",
            data: formData,
            success: function (data) {
              alert(data);
            },
            dataType: "json",
            contentType: "application/json"
          });
        });
      });
    </script>
  </body>
</html>
