<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="apple-touch-icon" href="apple-touch-icon.png">

        <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css')}}">
        <style>
            body {
                padding-top: 50px;
                padding-bottom: 20px;
            }
            html.js .nojs_lauta {
              display: none;
            }

        </style>
        <link rel="stylesheet" href="{{ asset('css/bootstrap-theme.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/main.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/chessboard-0.3.0.min.css')}}">
        <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>

        <script src="{{ asset('js/vendor/modernizr-2.8.3-respond-1.4.2.min.js')}}"></script>
        <script src="{{ asset('js/chessboard-0.3.0.js')}}"></script>
    </head>
    <body>



      <div class="container">

      <div class="panel panel-default">

        <div class="panel-body">
          <ul class="nav nav-pills">
            <li role="presentation" class="active"><a href="{{route('etusivu')}}">Etusivu</a></li>
            <li role="presentation" class="active"><a href="{{route('createcollectionpage')}}">Uusi kokoelma</a></li>
            <li role="presentation" class="active"><a href="{{route('askcollectionkey')}}">Muokkaa kokoelmaa</a></li>
            <li role="presentation" class="active"><a href="{{route('createtournamentpage')}}">Uusi turnaus</a></li>
            <li role="presentation" class="active"><a href="{{route('tournamentsearch')}}">Etsi turnaus</a></li>

          </ul>
          <hr>
          <?php 
            if ($errors->any()) {
              $msg = $errors->all()[0];
              echo '<div class="alert alert-danger">' . $msg . '</div>';
            }
            if (Session::has('errorMsg')) {
              echo '<div class="alert alert-danger">' . Session::get("errorMsg") . '</div>';
            } else if (Session::has('successMsg')) {
              echo '<div class="alert alert-success">' . Session::get("successMsg") . '</div>';
            }
            ?>
          @yield('content')
        </div>
      </div>

      


      
      <hr>

      <footer>
        <p>&copy; Nollaversio IT 2015</p>
      </footer>
    </div> <!-- /container -->        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.2.min.js"><\/script>')</script>

        <script>
        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
        </script>
        <script src="js/vendor/bootstrap.min.js"></script>

        <script src="js/main.js"></script>
        

        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
        <script>
            (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
            function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
            e=o.createElement(i);r=o.getElementsByTagName(i)[0];
            e.src='//www.google-analytics.com/analytics.js';
            r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
            ga('create','UA-XXXXX-X','auto');ga('send','pageview');
        </script>
    </body>
</html>
