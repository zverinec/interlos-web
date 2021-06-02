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
               <h1>InterLoSík 2021</h1>
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

               <div class="nok">
                  <div class="info-time">19:30</div>
                  <div class="info-message">Hra skončila. Děkujeme všem za účast. Řešení je k dispozici <a href="https://interlos.fi.muni.cz/download/years/2021-interlosik/reseni/reseni.pdf">zde</a>.</div>
               </div>

               <div class="ok">
                  <div class="info-time">19:00</div>
                  <div class="info-message">Statistiky byly na poslední půlhodinu skryty.</div>
               </div>

               <div class="ok">
                  <div class="info-time">17:58</div>
                  <div class="info-message">Všechny úlohy 2. sady byly pokořeny.</div>
               </div>

               <div class="ok">
                  <div class="info-time">17:30</div>
                  <div class="info-message">Sada 2 úspěšně zveřejněna.</div>
               </div>

               <div class="ok">
                  <div class="info-time">17:13</div>
                  <div class="info-message">Gratulujeme týmu <em>Rüebli zum Znüüni</em> k pokoření všech úloh první sady!</div>
               </div>

               <div class="nok">
                  <div class="info-time">17:09</div>
                  <div class="info-message">Upřesnění k L1:
                    <ol>
                    <li><em>Každý den se nakonec podařilo potkat jen čtyřem losíkům</em> znamená <strong>právě</strong> čtyřem losíkům.</li>
                    <li>Každý z losíků vyučuje právě jeden předmět, ale jeden předmět může být vyučován více losíky.</li>
                    <li>S komunikačním kanálem v daný den musí umět <strong>všichni</strong> losíci.</li>
                    <li>Los, který je v daném předmětu doučován, nemůže tento předmět doučovat.</li>
                    </ol>
                  </div>
               </div>

               <div class="ok">
                  <div class="info-time">16:41</div>
                  <div class="info-message">Všechny úlohy 1. sady byly pokořeny.</div>
               </div>

               <div class="ok">
                  <div class="info-time">16:00</div>
                  <div class="info-message">Sada 1 úspěšně zveřejněna.</div>
               </div>

               <div class="ok">
                  <div class="info-time">15:00</div>
                  <div class="info-message">Soutěž začíná 30. 5. 2021 v 16:00</div>
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
