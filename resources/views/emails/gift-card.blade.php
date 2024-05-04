<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="{{ @vite(['resources/css/app.css']) }}">
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    <title>Piedy.com</title>
    <style>
       @import url('https://fonts.googleapis.com/css2?family=Lato&family=Prompt:wght@900&display=swap');
  *{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  body{
    background-color: rgb(18, 17, 18);
  }
    .black-card{
      width: 390px;
      height: 240px;
      background-color: black;
      border-radius: 7px;
      border: 1px solid rgb(214, 180, 0);
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      overflow: hidden;
      box-shadow:
      0 0 5px 5px rgb(27, 27, 27),
      0 0 6px 6px rgb(32, 32, 32);
    }

    .piece-general, .second-piece, .piece-mini{
      position: relative;
      transform: translate(-50%, -50%);
      transform: rotate(45deg);
      overflow: hidden;
    }

    .piece-general{
      left: 16rem;
      top: 4rem;
      width: 5rem;
      height: 5rem;
      background-color: #F3BB23;
      z-index: 6;
    }

    .cuadrado-1, .cuadrado-2, .cuadrado-3{
      width: 1.3em;
      height: 1.3em;
      background-color: rgba(252, 252, 252, 0);
      outline: 0.55em solid rgb(0, 0, 0);
      overflow: hidden;

    }

    .cuadrado-1{
      position: absolute;
      bottom: 0;
    }

    .cuadrado-2{
      position: absolute;
      bottom: 20px;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }

    .cuadrado-3{
      position: absolute;
      right: 0;
    }

    .second-piece{
       width: 10rem;
       height: 10rem;
       top: -1.5rem;
       left: 12rem;
       background: rgb(159,159,159);
      background: linear-gradient(320deg, rgba(159,159,159,0.9303921397660627) 68%, rgba(32,32,32,0.011624632763261533) 88%);
       overflow: hidden;
       opacity: 0.1;
       z-index: 5

    }

    .second-1, .second-2, .second-3{
      width: 2.7em;
      height: 2.7em;
      background-color: rgba(38, 38, 38, 0);
      outline: 1em solid rgb(0, 0, 0);
      overflow: hidden;
    }

    .second-1{
      position: absolute;
      bottom: 0;
    }

    .second-2{
      position: absolute;
      bottom: 20px;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }

    .second-3{
      position: absolute;
      right: 0;
    }

    .piece-mini{
       width: 2.5rem;
       height: 2.5rem;
       top: -13rem;
       left: 2.3rem;
       background: rgb(212,212,212);
      background: linear-gradient(45deg, rgba(212,212,212,1) 20%, rgba(143,143,143,1) 38%, rgba(91,91,91,1) 56%, rgba(32,32,32,0.7259103470489758) 72%, rgba(32,32,32,0.3365546047520571) 87%);
       overflow: hidden;
    }

    .mini-1, .mini-2, .mini-3{
      width: 0.6em;
      height: 0.6em;
      background-color: rgba(254, 254, 254, 0);
      outline: 0.3em solid rgb(0, 0, 0);
    }

    .mini-1{
      position: absolute;
      bottom: 0;
    }

    .mini-2{
      position: absolute;
      bottom: 20px;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }

    .mini-3{
      position: absolute;
      right: 0;
    }

    .chip{
      width: 2.7rem;
      height: 2rem;
      background-color: rgb(210, 210, 210);
      position: relative;
      top: -11.7rem;
      left: 2.19rem;
      border-radius: 5px;
      overflow: hidden;
      display: flex;
      justify-content: space-between;
      flex-wrap: wrap;
    }

    .chip-top1, .chip-top2{
         width: 45%;
         height: 0.7rem;
         border-top: 1px solid black;
         border-bottom: 1px solid black;
    }

    .chip-top1{
       border-top-right-radius: 8px;
       border-bottom-right-radius: 3px;
       border-right: 1px solid black;
    }

    .chip-top2{
       border-top-left-radius: 8px;
       border-bottom-left-radius: 3px;
       border-left: 1px solid black;
    }


    .chip-middle1, .chip-middle2{
         border-bottom: 1px solid black;
         width: 39%;
         height: 0.7rem;
    }

    .chip-middle1{
       border-right: 1px solid black;
    }

    .chip-middle2{
       border-left: 1px solid black;
    }

    .chip-bottom1, .chip-bottom2{
         border-bottom: 1px solid black;
         width: 45%;
         height: 0.7rem;
    }

    .chip-bottom1{
      border-right: 1px solid black;
      border-top-right-radius: 3px;
      border-bottom-right-radius: 8px;
      margin-top: 0.08rem;
    }

    .chip-bottom2{
      border-left: 1px solid black;
      border-top-left-radius: 3px;
      border-bottom-left-radius: 8px;
    }


   .name-card{
     position: absolute;
     bottom: 1rem;
     left: 2rem;
     color: white;
     font-family: 'Lato', sans-serif;
   }

   .visa-credit{
     position: absolute;
     bottom: 0.5rem;
     right: 2rem;
     color: white;
   }

   .visa-credit p:nth-child(2){
     font-size: 8px;
     text-align: right;
   }

   .visa-credit p:nth-child(1){
     font-family: 'Prompt', sans-serif;
   } 
    </style>
</head>
<body>
    <div class="black-card">
        <div class="piece-general">
             <div class="cuadrado-1">
             </div>
                 <div class="cuadrado-2">
                </div>
             <div class="cuadrado-3">
             </div>
       </div>
      <div class="second-piece">
          <div class="second-1">
          </div>
          <div class="second-2">
          </div>
          <div class="second-3">
          </div>
      </div>
      <div class="piece-mini">
          <div class="mini-1">
    
          </div>
          <div class="mini-2">
    
          </div>
          <div class="mini-3">
          </div>
      </div>
      <div class="chip">
          <div class="chip-top1">
          </div>
          <div class="chip-top2">
          </div>
          <div class="chip-middle1">
          </div>
          <div class="chip-middle2">
          </div>
          <div class="chip-bottom1">
          </div>
          <div class="chip-bottom2">
          </div>
      </div>
    
      <div class="name-card">
         <p>SATOSHI NAKAMOTO</p>
      </div>
      <div class="visa-credit">
        <p>VISA</p>
        <p>Debit</p>
      </div>
    
      </div>
</body>
</html>
