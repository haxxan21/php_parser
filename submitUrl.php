<!DOCTYPE html>
<html>
<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <style>
            .container{
                margin-top: 1cm
            }
        </style>
    </head>
    <body>
        <div class="container">
        <form method="POST">
        <div class="row">    
            <div class="col-sm-6">
                <div class="form-group">    
                     <input type="text" class="form-control" name="url" placeholder="Enter URL to file">
                 </div>
            </div>
            <div class="col-sm-6">
                <button type="submit" class="btn btn-success" name="submit">Submit</button>
            </div>
        </div>
        </form>
        </div>
    </body>
</html>
<?php
    if(isset($_POST['submit'])){
        include_once 'EmailParser.php';
        $email = new EmailParser();
        $email->index($_POST['url']);
    }
?>