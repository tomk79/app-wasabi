<!DOCTYPE html>
<html>
    <head>
        <title>LOGIN</title>
    </head>
    <body>
        <h1>LOGIN</h1>
        <form action="" method="post">
            {{ csrf_field() }}
            <input type="text" name="id" placeholder="User ID" />
            <input type="text" name="password" placeholder="Password" />
            <button type="submit">LOGIN</button>
        </form>
    </body>
</html>
