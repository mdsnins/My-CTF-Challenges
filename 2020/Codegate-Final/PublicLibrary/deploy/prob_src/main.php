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
        <li class="nav-item active">
            <a class="nav-link" href="#">Home<span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="guestbook.php">Guestbook</a>
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
        <h3>Welcome to Public Library!</h3>
        <br />
        <p>
            Due to COVID-19, online library is prepared!<br />
            However, there is a minor copyright issue, you only can read 5 sample documents :P<br />
            If you are accepted to other things, please fill the form at the bottom.
        </p>
        <hr />
        <div class="form-group">
            <textarea class="form-control" id="body" rows="20" placeholder="Book will be shown here" disabled>
            </textarea>
        </div>
        <hr />
        <form>
            <div class="form-group">
                <label>Book code</label>
                <input type="text" class="form-control" id="book" name="book"/>
            </div>
            <div class="form-group">
                <label>Auth code</label>
                <input type="text" class="form-control" id="auth" name="auth"/>
            </div>
            <button type="button" id="read">Read</button>
        </form>
        
    </div>
    <script>
        function loadBook(book, auth) {
            $.get(`./read.php?book=${encodeURIComponent(book)}&auth=${encodeURIComponent(auth)}`)
            .done(function(r) {
                if(r.code ==  'error') {
                    alert(r.msg);
                    return false;
                }
                $("#body").text(r.body);
            });
        }
        
        $('#read').click(function() {
            let b = $('#book').val();
            let a = $('#auth').val();
            loadBook(b, a);
        })

        $(window).on('hashchange', function() {
            let h = location.hash.substring(1).split('$');
            loadBook(h[1], h[0]);
        })
    </script>
</body>
</html>