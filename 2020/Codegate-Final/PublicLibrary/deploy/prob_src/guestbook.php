<?php
include "pow.php";
session_start();

if(!isset($_SESSION["name"])) {
    header("Location: index.php");
    die();
}

function returnJSON($obj) {
    header("Content-Type: application/json");
    die(json_encode($obj));
}

$search = false;
$err = "";
if(isset($_POST["column"]) && isset($_POST["value"]) && isset($_POST["pow"])) {
	$search = true;
	if(check_pow($_POST["pow"])) {
		@include '_db.php';
		$c = $_POST["column"];
		$v = $_POST["value"];
		$r = searchGuestbook($dbconn, $c, $v);
		if($r == -1) {
			$err = "Don't be evil!";
		}
		else {
			$search = $r;
		}
	}
	else {
		$err = "Wrong PoW";
	}
}

$pow = get_pow();
//Generate PoW

?>
<html>
<head>
    <title>Public Library</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Public Library</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" href="index.php">Home</a>
        </li>
        <li class="nav-item active">
            <a class="nav-link" href="#">Guestbook <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Available Books
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <a class="dropdown-item" href="#cc558259$doc:lorem.txt">Lorem Ipsum</a>
            <a class="dropdown-item" href="#158ea5f5$doc:romeo.txt">Romeo & Juliet</a>
            <!-- We lost Demian... -->
            <!-- <a class="dropdown-item" href="#ad9d243f$doc:demian.txt">Demian</a> -->
            <a class="dropdown-item" href="#4cd997f8$doc:fox.txt">Fox</a>
            <a class="dropdown-item" href="#ed6b84cc$src:helloworld.c">Hello World!</a>
            <a class="dropdown-item" href="#c3b79bc9$src:alert1.js">alert(1)</a>
            </div>
        </li>
        </ul>
    </div>
    </nav>

    <div class="container">
        <br />
        <h3>Guestbook of Public Library</h3>
        <br />
        <strong>We always respect everyone's privacy importantly.</strong>
        <hr />
        <form class="form-inline" method="POST">
            <div class="form-group mb-2">
                <select class="form-control" name="column" id="column">
                    <option value="name">Name</option>
                    <option value="purpose">Purpose</option>
                </select>
            </div>
            <div class="form-group mx-sm-3 mb-2">
                <input type="text" class="form-control" name="value" id="value" placeholder="Keyword">
            </div>
			<div class="form-group mx-sm-3 mb-2">
				<input type="text" class="form-control" name="pow" placeholder="sha1(x)[:5] == <?=$pow?>">
			</div>
            <button type="submit" class="btn btn-primary mb-2" id="search">Search</button>
        </form>
<?php
if($search !== false) {
    if($err) {
        ?>
        <script>alert("<?=$err?>");</script>
        <?php
    }
    else if(count($search) == 0) {
        ?>
        <script>alert("No result");</script>
        <?php
    }
    else {
        ?>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Preview</th>
                </tr>
            </thead>
            <tbody>
        <?php
            for($i=0; $i<count($search); $i++) {
                ?>
                <tr>
                    <th scope="row"><?=($i+1)?></th>
                    <td><?=$search[$i]?></td>
                </tr>
                <?php
            }
        ?>
            </tbody>
        </table>
        <?php
    }
}
?>
    </div>
</body>
</html>
