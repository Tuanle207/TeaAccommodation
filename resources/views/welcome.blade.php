<!DOCTYPE html>
<html>
    <head>
        <title>Admin</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link rel="stylesheet" type="text/css" href="./resources/css/style.css"/>
        <link rel="stylesheet" type="text/css" href="./resources/css/index_page.css"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script>
            $(document).ready(function() {
                let list = document.getElementById('listOrder');
                fetch("http://localhost:8000/api/login", {
                    method: "POST",
                    headers: {
                        "Accept": "application/json",
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({email: 'letgo237@gmail.com', password: 'test1234'}),
                    credentials: "include",
                })
                .then(response => response.json())
                .then(json => {
                    alert(json.message);
                })
                .catch(error => alert(error.toString()));
            });
        </script>
    </head>
    <body>
        
    </body>
</html>