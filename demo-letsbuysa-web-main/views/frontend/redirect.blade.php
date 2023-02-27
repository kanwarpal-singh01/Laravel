<html>
  <head>
    <title>Show Result Demo</title>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"
    />

    <link
      rel="shortcut icon"
      href="https://goSellJSLib.b-cdn.net/v1.6.0/imgs/tap-favicon.ico"
    />
    <link
      href="https://goSellJSLib.b-cdn.net/v1.6.0/css/gosell.css"
      rel="stylesheet"
    />
  </head>
  <body>

    <script
      type="text/javascript"
      src="https://goSellJSLib.b-cdn.net/v1.6.0/js/gosell.js"
    ></script>

    <div id="root"></div>
    <script>
       goSell.showResult({
           callback: response => {
           console.log("callback", response);
         }
      });
    </script>
  </body>
</html>
