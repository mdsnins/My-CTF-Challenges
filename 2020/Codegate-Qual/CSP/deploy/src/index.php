<!DOCTYPE html>
<html>
  <head>
    <title>Advanced Echo Service</title>
  </head>
  <body>
    <!-- TODO: implement form to support multiple APIs in same time -->
    <form action="view.php" method="GET">
      <p>API Name (Required): </p>
      <input name="name" type="text" required /> 
      <p>API Param#1 (Optional) : </p>
      <input name="p1" type="text" />
      <p>API Param#2 (Optional) : </p>
      <input name="p2" type="text" />
      <button type="submit">Submit</button>
    </form>
    <br />
    <p>
      If you find a bug, please <a href="/report.php">report</a>!
    </p>
  </body>
</html>
