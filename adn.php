<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Motion</title>

    <!-- Bootstrap Core CSS -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

    <!-- Custom CSS -->
    <link href="css/stylish-portfolio.css" rel="stylesheet">

    <style type="text/css">

    body {
    font-family: "Source Sans Pro","Helvetica Neue",Helvetica,Arial,sans-serif;
    }
    html, body {
    width: 100%;
    height: 100%;
    }


    body {
         background-color: #4c555c;
         position: relative;
    }


    .header {
        display: table;
        position: relative;
        width: 100%;
        height: 100%;
        background: url(../img/logo-arete.png) no-repeat center center scroll;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        background-size: cover;
        -o-background-size: cover;
        display: none;
    }

    .text-vertical-center {
        display: table-cell;
        text-align: center;
        vertical-align: middle;
    }

    .text-vertical-center h1 {
        margin: 0;
        padding: 0;
        font-size: 4.5em;
        font-weight: 700;
    }

    .btn-dark {
        border-radius: 0;
        color: #fff;
        background-color: rgba(0,0,0,0.4);
    }

    

    .logo-wrap {
        width: 100%;
        height: 100%;
    }

    .logo {
        width: 300px;
        height: 300px;
        left: 50%;
        top: 50%;
        position: absolute;
        margin-left: -150px;
        margin-top: -150px;
    }

    .bubble {
        position: absolute;
        display: block;
        border-radius: 50%;
        background-color: #4a4f55;
        transform-origin: top center;
    }

    .bubble-small-spin {
        width: 100%;
        height: 100%;
        position: absolute;
        -webkit-animation: updown 4s infinite cubic-bezier(0.75, 0.25, 0.25, 0.75);
        display: block;
    }

    .bubble-small {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        left: 10%;
        top: 80%;
        margin-left: -17px;
        margin-top: -17px;
    }

    .bubble-tiny {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        left: 20%;
        top: 30%;
        margin-left: -10px;
        margin-top: -10px;
    }

    .bubble-main {
        border-radius: 50%;
        background-color: #f7b638;
        transform-origin: center;
        position: absolute;
        left: 0;
        top: 0;
        display: block;
        width: 100%;
        height: 100%;
    }

    .logo-title {
        position: absolute;
        top: 29%;
        right: 12%;
        font-size: 3em;
        font-weight: normal;
    }

    @-webkit-keyframes circle {
        to { transform: rotate(1turn); }
    }


    @keyframes circle {
        to { transform: rotate(1turn); }
    }

    @-webkit-keyframes updown {
      to { transform: rotate(1turn); }
    }


    @keyframes updown {
      to { transform: rotate(1turn); }
    }




    </style>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <!-- Header -->
    <header id="top" class="header">
        <div class="text-vertical-center">
            <h1>&nbsp;</h1>
            <h3>&nbsp;</h3>
            <br>
        </div>
    </header>

    <div class="logo-wrap">
        <div class="logo">
            <div class="bubble-main">
            </div>
            <div class="bubble-small-spin">
                <div class="bubble bubble-small"></div>
            </div>
            <div class="logo-title">ADN</div>

            <div class="bubble bubble-tiny"></div>
        </div>

    </div>

    

    </body>

</html>