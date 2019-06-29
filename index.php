<!doctype html>
<head>
    <title>Login</title>
    <link rel="icon" type="image/png" href="CSS/imges/PageLogo.PNG" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://s3.amazonaws.com/api_play/src/js/jquery-2.1.1.min.js"></script> 
    <script src="https://s3.amazonaws.com/api_play/src/js/vkbeautify.0.99.00.beta.js"></script>
    <link rel="stylesheet" href="css/www.w3schools.com_w3css_4_w3.css">

    <style>   
        /*CSS*/
        body {font-family: Arial, Helvetica, sans-serif;}
        form {border: 3px solid #f1f1f1;}

        input[type=text],input[type=password], select {
            font-size: 25px;
            width: 50%;
            padding: 12px 20px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type=submit] {
            width: 50%;
            background-color: #4CAF50;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type=submit]:hover {
            background-color: #45a049;
        }

        .imgcontainer {
            text-align: center;
            margin: 24px 0 12px 0;
        }


        .container {
            padding: 16px;
        }

        /* Change styles for span and cancel button on extra small screens */
        @media screen and (max-width: 300px) {
            span.psw {
                display: block;
                float: none;
            }
        }

    </style>

    <script>
        $(function () {
            $("#generate-button").click(function () {
                var instanceurl = $("#instanceurl").val();
                var client_id = $("#client_id").val();
                var client_secret = $("#client_secret").val();
                var redirect_uri = $("#redirect_uri").val();
                var username = $("#username").val();
                var password = $("#password").val();
                if (username === "" || password === "")
                    alert("Username or Password can not be empty");
                else
                {
                    var token_input = $("#token");
                    var result_div = $("#result");
                    document.getElementById("iurl").value = document.getElementById("instanceurl").value;
                    generate_token(instanceurl, client_id, client_secret, redirect_uri, username, password, token_input, result_div);
                }
            });
        });
    </script>

    <script>
        function generate_token(instanceurl, client_id, client_secret, redirect_uri, username, password, token_input, result_div) {
            token_input.val("");
            result_div.html("");
            try
            {
                var xmlDoc;
                var xhr = new XMLHttpRequest();
                xhr.open("POST", instanceurl + "/oauth/token", true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function (e)
                {
                    if (xhr.readyState === 4)
                    {
                        var a = JSON.parse(e.target.responseText);
                        token_input.val(a["access_token"]);
                        if (token_input.val() !== "")
                        {
                            alert("Welcome, Login Successful.");
                            document.getElementById("generate-report").click();
                        }
                        result_div.html(show_response(e.target.responseText));
                        xmlDoc = this.responseText;
                        txt = "";
                    }
                };
                xhr.send("client_id=" + client_id + "&client_secret=" + client_secret + "&grant_type=password&username=" + username + "&password=" + password + "&redirect_uri=" + redirect_uri);
            } catch (err)
            {
                alert(err.message);
            }
        }
        ;

        function show_response(str) {
            str = vkbeautify.xml(str, 4);
            return str.replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/\n/g, "<br />");
        }
        ;

        function validateForm() {
            var x = document.forms["frm"]["token"].value;
            if (x === "") {
                alert("Generate an access token first");
                return false;
            }
        }
        ;
    </script>
</head>
<body>
    <div class="imgcontainer">
        <img src="css/imges/Alsanawbar-Logo.jpg" style="width: 15rem" alt="Alsanawbar School">
        <img src="css/imges/InDepth-Logo.png" style="width: 19rem" alt="InDepth">
        <br>

        <!--API Connecting with Alsanawbar--> 
        <input  id="instanceurl" type="hidden" name="instanceurl" value="https://alsanawbar.school"/>
        <input  id="client_id" type="hidden" value="807ee0dddf6b79166323a61f2d8e8473865f8fb7455052e2d9a47c05200b6822"/>
        <input  id="client_secret" type="hidden" value="86e7e63d9f030b770e7152c632ddda32daeb8cef5c5c7eb8a44bf0736231a8af"/>
        <input  id="redirect_uri" type="hidden" value="http://indepthreports.online/"/>

        <input  id="username" type="text" placeholder="Your Admin account username" autofocus/>
        <input  id="password" type="password" placeholder="Password"/>

        <input  type= "submit" id="generate-button" value ="Login" class="w3-xlarge">

        <form name="frm" onsubmit="return validateForm()" action="statistics.php" method="POST" style="display: none">
            <input id="token" type="hidden" name="token">
            <input id="iurl" type="hidden" name="iurl">
            <input type= "submit" id="generate-report" value ="Generate Reports">
        </form>
    </div>

    <script>
        var input = document.getElementById("password");
        input.addEventListener("keyup", function (event) {
            if (event.keyCode === 13)
                document.getElementById("generate-button").click();
        });
    </script>
</body>
</html>

