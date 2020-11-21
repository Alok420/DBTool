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
  <form action="controller/insert.php" method="post">
    <div class="form-group">
      <label >Name:</label>
      <input type="text" class="form-control" id="" placeholder="Enter name" name="name">
    </div>
    <div class="form-group">
      <label >Name:</label>
      <input type="text" class="form-control" id="" placeholder="Enter name" name="email">
    </div>
    <div class="form-group">
      <label for="pwd">Password:</label>
      <input type="password" class="form-control" id="pwd" placeholder="Enter password" name="password">
    </div>
    <button type="submit" class="btn btn-default" name="submit">Submit</button>
  </form>
</div>

</body>
</html>
