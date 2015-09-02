<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="apple-touch-icon" href="apple-touch-icon.png">

        <link rel="stylesheet" href="{{asset('clientapp/build/css/bootstrap.min.css')}}">
        <style>
            body {
                padding-top: 50px;
                padding-bottom: 20px;
            }
            .screenPanel {
              width: 100%;
              height: 100%;
              position: absolute;
              top: 34px;
              left: 0px;
              display: none;
            }
            .testWindow {
              display: inline-block;
              margin: 6px;
              position: relative;
            }
            .innerScreen {
              padding-top: 20px;
            }
            #upperInfoBar {
              position: absolute;
              top: 0px;
              left: 0px;
              width: 100%;
              height: 30px;
            }
            #tournamentWindow {
              width: 600px;
              height: 560px;
              position: relative;
            }
            .wrong {
              background-color: red;
            }
            .right {
              background-color: green;
            }          
            .invalid {
              background-color: yellow;
            }    
            .waiting {
              background-color: white;
            }                      
        </style>
        <link rel="stylesheet" href="{{asset('clientapp/build/css/bootstrap-theme.min.css')}}">
        <link rel="stylesheet" href="{{asset('clientapp/build/css/main.css')}}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/chessboard-0.3.0.min.css')}}">
        <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>

        <script src="{{asset('clientapp/build/js/vendor/modernizr-2.8.3-respond-1.4.2.min.js')}}"></script>
        <script src="https://cdn.socket.io/socket.io-1.3.5.js"></script>
        <script src="{{ asset('js/chessboard-0.3.0.js')}}"></script>
    </head>
    <body>
        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->


    <div class="container panel panel-default" id="tournamentWindow" data-tournamentkey="{{$tournamentkey}}" data-username="{{$username}}">
            <div id="titleBar" style="background-color: #444; color: white; position: absolute; width: 600px; height: 60px; font-size: 42px; top: 0px; left: 0px; text-align: center;">Shakkiratkonta - Live App</div>
      <!-- Example row of columns -->
      <div style="position: absolute; width: 600px; height: 30px; top: 70px; left: 0px;">
              <p id="nextRoundMsg" style="font-size: 12px; color: red; text-align: center;"></p>
      </div>

      <div id="client1Window" class="testWindow panel-body" style="width: 580px; height: 450px; padding: 0px; margin:auto; position: absolute; top: 60px; left: 10px;">

      
          
        <div class="infoScreen screenPanel" data-state='resultOfRound'>Result of round</div>
        <div class="infoScreen screenPanel" data-state='idle'>Info</div>
        <div class="playingScreen screenPanel" data-state='playing'>
          <div id="lauta" style="width: 364px; position: absolute; top: 36px; left: 0px;">
            
          </div>
          <div id="resultIndicator" class="alert alert-info" style="width: 366px; height:30px; top:0px; left: 0px; position: relative;">
            <span class="label label-default" id="countDown" style="font-size: 14px; top: 5px; position: absolute; ">- sec</span>
            
          </div>
          <!--<input class="testSolutionInput" id="testSolution1">
          <button  class='submitSolution' id="submitSolution1">Submit</button>
          <p class="problemFen">-</p> -->
          <div class="panel panel-primary" style="position: absolute; width: 200px; height: 396px; top: 0px; left: 372px;">
             <div class="panel-heading">Answers so far</div>
             <div class="panel-body" id="answersSoFar"></div>
            
          </div>
        </div>
        <div class="screenPanel panel panel-default" data-state='standings'>

          <div class="panel-heading">Top Standings</div>
          <div class="panel-body" data-state='standingsUL'></div>
        </div>
        <div class="screenPanel" data-state='waiting'>
          <div id="participationSuccess" style="width: 300px; margin: auto;"></div>
          <div id="timeToStart" style="width: 300px; margin: auto; color: red;"></div>
          
        </div>
        
        <div class="kibitzingScreen screenPanel" data-state='kibitzing'>Kibitz
          
        </div>        
      </div>
      <!--
      <div id="client2Window" class="testWindow" style="background-color: orange; width: 400px; height: 400px;">
        <div class="infoScreen screenPanel" data-state='resultOfRound'>Result of round</div>
        <div class="infoScreen screenPanel" data-state='idle'>Info</div>
        <div class="playingScreen screenPanel" data-state='playing'>Play
          <input class="testSolutionInput" id="testSolution2">
                    <button class='submitSolution' id="submitSolution2">Submit</button>
          <p class="problemFen">-</p>
          <div class="answersSoFar">(Answers so far)</div>
        </div>
        <div class="screenPanel" data-state='standings'>Standings</div>
        <div class="screenPanel" data-state='waiting'>Wait</div>
        
        <div class="kibitzingScreen screenPanel" data-state='kibitzing'>Kibitz
          
        </div>  
          
     
      </div>
      <div id="client3Window" class="testWindow" style="background-color: red; width: 400px; height: 400px;">
        <div class="infoScreen screenPanel" data-state='resultOfRound'>Result of round</div>
        <div class="infoScreen screenPanel" data-state='idle'>Info</div>
        <div class="playingScreen screenPanel" data-state='playing'>Play
          <input class="testSolutionInput" id="testSolution3">
                    <button  class='submitSolution' id="submitSolution3">Submit</button>
          <p class="problemFen">-</p>
          <div class="answersSoFar">(Answers so far)</div>
        </div>
        <div class="screenPanel" data-state='standings'>Standings</div>
        <div class="screenPanel" data-state='waiting'>Wait</div>
        
        <div class="kibitzingScreen screenPanel" data-state='kibitzing'>Kibitz
          
        </div>  
          
    
      </div>
      <div id="client4Window" class="testWindow" style="background-color: yellow; width: 400px; height: 400px;">
        <div class="infoScreen screenPanel" data-state='resultOfRound'>Result of round</div>
        <div class="infoScreen screenPanel" data-state='idle'>Info</div>
        <div class="playingScreen screenPanel" data-state='playing'>Play
          <input class="testSolutionInput" id="testSolution4">
                    <button  class='submitSolution' id="submitSolution4">Submit</button>
          <p class="problemFen">-</p>
          <div class="answersSoFar">(Answers so far)</div>
        </div>
        <div class="screenPanel" data-state='standings'>Standings</div>
        <div class="screenPanel" data-state='waiting'>Wait</div>
        
        <div class="kibitzingScreen screenPanel" data-state='kibitzing'>Kibitz
          
        </div>  
          
    
      </div>
      -->

      


    </div> <!-- /container -->   
      <footer>
       <hr style="width: 50%;">
        <p style="text-align: center;">&copy; Company 2015</p>
      </footer>
        <script src="{{asset('clientapp/build/js/findem.js')}}"></script>


    </body>
</html>