<?php

session_start();
@include '_db.php';

if(isset($_SESSION["name"])) {
    include 'main.php';
    die();
}

$n = isset($_POST["name"]) ? $_POST["name"]  : "";
$a = isset($_POST["age"]) ? $_POST["age"]  : "";
$p = isset($_POST["purpose"]) ? $_POST["purpose"]  : "";

if($n && $a && $p) {
    if(!(is_string($n) && strlen($n) < 10)) {
        die("No hack");
    }
    if(!(is_string($a) && intval($a) > 12))
        die("No hack");
    if(!(is_string($p) && strlen($p) < 50))
        die("No hack");
    $_SESSION["name"] = $n;
    $_SESSION["age"] = $a;
    $_SESSION["purpose"] = $p;

    if(!writeGuestbook($dbconn, $n, $a, $p))
        die("hi");
    include 'main.php';
    die();
}
?>
<html>
<head>
    <title>Public Library</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container">
        <br />
        <h3>Welcome to Public Library!</h3>
        <h4>To enter library, please write down your information</h4>
        <br />
        <form method="POST" id="enterform">
            <div class="form-group">
                <label>Name</label>
                <input type="text" class="form-control" name="name" id="name" maxlength="10"/>
            </div>
            <div class="form-group">
                <label>Age(Only 12+)</label>
                <input type="text" class="form-control" name="age" id="age"/>
            </div>
            <div class="form-group">
                <label>Purpose of usage (&lt;50 letters)</label>
                <textarea class="form-control" name="purpose" id="purpose" maxlength="50"></textarea>
            </div>
            <button type="button" class="btn btn-primary" id="enter">Enter</button>
        </form>
    </div>
    <script>
        $('#enter').click(function() {
            let n = $('#name').val();
            let a = parseInt($('#age').val());
            let p = $('purpose').text();
            if(n.length > 20 || p.length > 50)
                return false;
            if(isNaN(a) || a < 12)
                return false;
            $('#enterform').submit();
        })
    </script>
</body>
</html>
