<!DOCTYPE html>
<html>
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <meta http-equiv="content-language" content="cz" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=2.0">
      <meta name="description" content="InterLoS - Internetova Logicka Soutez" />
      <link href="design/stylesheet.css" media="screen" rel="stylesheet" type="text/css" />
      <link rel="icon" href="design/favicon.ico" type="image/x-icon" />
      <title>INTERnetová LOgická SOutěž &ndash; Aktuální informace</title>
      <script>
         window.onload = (function () {
            var countdown = 5*60;
            function secondPassed() {
                countdown -= 1;
                if(countdown == 0) {
                    location.reload();
                }
                document.getElementById("remaining-time").innerHTML = countdown;
            };
            document.getElementById("remaining-time").innerHTML = countdown;
            window.setInterval(secondPassed, 1000);
         });
      </script>
   </head>
   <div class="root">
      <?php if (!isset($_GET['headless']) || !$_GET['headless']) { ?>
      <div class="header">
         <div class="header-line">&nbsp;</div>
         <div class="contents">
            <a href="http://interlos.fi.muni.cz/">
            <span class="block header-logo" title="InterLoS - INTERnetová LOgická Soutěž">&nbsp;</span>
            <span class="block header-name" title="InterLoS - INTERnetová LOgická Soutěž">&nbsp;</span>
            </a>
            <div class="header-year">
               <h1>InterLoS 2021</h1>
            </div>
            <div class="cleaner-both">&nbsp;</div>
         </div>
      </div>
      <?php } ?>
      <div class="main">
         <div class="contents" >
            <div class="main-block">
               <h1 class="lefted">Aktuální informace</h1>
               <div class="time-to-refresh">Obnovení za: <span id="remaining-time"></span> s</div>
               <div class="cleaner-both">&nbsp;</div>

               <div class="ok">
                  <div class="info-time">20:02</div>
                  <div class="info-message">Soutěž skončila, děkujeme za účast :)</div>
               </div>
               <div class="ok">
                  <div class="info-time">18:56</div>
                  <div class="info-message">Všechny úlohy třetí sady padly, gratulujeme!</div>
               </div>
               <div class="nok">
                  <div class="info-time">18:35</div>
                  <div class="info-message">Upřesnění k P6: je třeba vybrat podmnožinu, a to největší takovou, aby v ní každý alespoň 20 lidí znal a alespoň 20 lidí neznal.</div>
               </div>
               <div class="ok">
                  <div class="info-time">18:00</div>
                  <div class="info-message">Třetí sada byla zveřejněna.</div>
               </div>
               <div class="nok">
                  <div class="info-time">17:38</div>
                  <div class="info-message">Všechny úlohy druhé sady padly, gratulujeme!</div>
               </div>
               <div class="ok">
                  <div class="info-time">16:30</div>
                  <div class="info-message">Druhá sada zveřejněna.</div>
               </div>
               <div class="nok">
                  <div class="info-time">15:54</div>
                  <div class="info-message">Všechny úlohy první sady byly pokořeny, gratulujeme!</div>
               </div>
               <div class="ok">
                  <div class="info-time">15:15</div>
                  <div class="info-message">První sada úspěšně zveřejněna, některé úlohy jsou již úspěšně vyřešeny.</div>
               </div>
               <div class="ok">
                  <div class="info-time">15:00</div>
                  <div class="info-message">Soutěž začíná 4. 12. 2021 v 15:00. Orgové mají všechno pod kontrolou :).</div>
               </div>

               <div class="hr">&nbsp;</div>
               <p>Veškeré problémy s úlohami a odevzdávacím systémem hlaste z e-mailové adresy uvedené v profilu týmu na adresu <a href="mailto:interlos@fi.muni.cz"><span class="bold">interlos@fi.muni.cz</span></a>.
                  V předmětu e-mailu musí být uvedeno ID týmu. Svoje ID také najdete <a href="https://interlos.fi.muni.cz/">na stránce soutěže</a> v záhlaví stránky vedle jména.
               </p>
            </div>
         </div>
      </div>
      <?php if (!isset($_GET['headless']) || !$_GET['headless']) { ?>
      <div  class="footer">
         <div class="footer-line">&nbsp;</div>
      </div>
      <?php } ?>
   </div>
   </body>
</html>
