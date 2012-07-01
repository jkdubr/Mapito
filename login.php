<!DOCTYPE html> 
<html> 
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1"> 
        <title>login</title> 
        <link rel="stylesheet"  href="http://code.jquery.com/mobile/1.0b3/jquery.mobile-1.0b3.min.css" />  
        <link rel="stylesheet" href="..{main.css"/>
        <script src="http://code.jquery.com/jquery-1.6.2.min.js"></script>

        <script src="http://code.jquery.com/mobile/1.0b3/jquery.mobile-1.0b3.min.js"></script>
    </head> 
    <body> 

        <div data-role="page">

            <div data-role="header" data-theme="d">
                <h1>Dialog</h1>

            </div>

            <div data-role="content" data-theme="c">
                <form method="POST" action="action/login.php"  data-ajax="false">

                    <div data-role="fieldcontain">
                        <label for="f_mail">Email</label>
                        <input type="email" name="mail" autofocus id="f_mail">
                    </div>

                    <div data-role="fieldcontain">
                        <label for="f_password">Password: </label>
                        <input type="password" name="password" id="f_password">
                    </div>                    

                    <input type="submit" value="Login">
                </form>
            </div>
        </div>


    </body>
</html>
