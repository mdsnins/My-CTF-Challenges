<?php
require_once 'auth.php';
session_start();

$status = "";
if(isset($_POST["url"]) && isset($_POST["pow"])) {
    if (!check_pow($_POST["pow"])) {
        $status = "wrongpow";
    }
    elseif (substr($_POST["url"], 0, 4) !== "http") {
        $status ="wrongurl";
    }
    else {
        require_once '__db_conn_.php';
        $stmt = $conn->prepare("INSERT INTO que (id, link, ip) VALUES (:u, :l, :i)");
        $stmt->bindValue(":u", md5("ehlrprlsthhhhhhhhhhhrma".$_POST["url"].$_SERVER["REMOTE_ADDR"].time()), SQLITE3_TEXT);
        $stmt->bindValue(":l", $_POST["url"], SQLITE3_TEXT);
        $stmt->bindValue(":i", $_SERVER["REMOTE_ADDR"], SQLITE3_TEXT);
        $stmt->execute();
    }
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Advanced Echo Service - Report</title>
  </head>
  <body>
    <form method="POST">
      <p>Report your bug</p>
      <br />
      <p>URL to report: </p>
      <input type="text" name="url" required />
      <p>Solve this challenge: <code>substr(sha1($ans). 0. 5) === <?=get_pow();?></code></p>
      <input type="text" name="pow" required />
      <button type="submit">Submit</button>
      <br />
      <strong>
        <?php
        if ($status === "wrongpow")
            echo "Wrong Answer!";
        elseif ($status === "wrongurl")
            echo "Invalid URL!";
        ?>
      </strong>
    </form>
  </body>
</html>
