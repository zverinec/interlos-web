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
            <a href="https://interlos.fi.muni.cz/">
            <span class="block header-logo" title="InterLoS - INTERnetová LOgická Soutěž">&nbsp;</span>
            <span class="block header-name" title="InterLoS - INTERnetová LOgická Soutěž">&nbsp;</span>
            </a>
            <div class="header-year">
               <h1>InterLoS 2023</h1>
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
                  <div class="info-time">19:08</div>
                  <div class="info-message">Každá úloha byla alespoň jednou vyřešena. Dobrá práce!</div>
               </div>

               <div class="nok">
                  <div class="info-time">18:13</div>
                  <div class="info-message">Úloha S9 Cink cink byla v odevzdávátku omylem označena jako L9. 3 týmy odevzdaly řešení Cink cink v domnění, že odevzdávají řešení L9 Světlolos. Tato odevzdání jsme zrušili, body jsou přepočítány.</div>
               </div>

               <div class="ok">
                  <div class="info-time">18:00</div>
                  <div class="info-message">3. sada úloh byla úspěšně zveřejněna.</div>
               </div>

               <div class="nok">
                  <div class="info-time">16:46</div>
                  <div class="info-message">P6: příklad vstupu v zadání má více správných řešení, na ostrý vstup tato informace nemá vliv.</div>
               </div>

               <div class="nok">
                  <div class="info-time">16:42</div>
                  <div class="info-message">L5: zaznamenali jsme problém se správným řešením L5 Šachová. V systému bylo zadané špatné korektní řešení. Korektní řešení jsme upravili, body jsou přepočítány.</div>
               </div>

               <div class="nok">
                  <div class="info-time">16:30</div>
                  <div class="info-message">2. sada úloh byla úspěšně zveřejněna.</div>
               </div>

               <div class="ok">
                  <div class="info-time">16:27</div>
                  <div class="info-message">Gratulujeme, všechny šifry 1. sady byly pokořeny.</div>
               </div>

               <div class="ok">
                  <div class="info-time">15:58</div>
                  <div class="info-message">Gratulujeme, všechny logické úlohy 1. sady byly pokořeny.</div>
               </div>

               <div class="ok">
                  <div class="info-time">15:00</div>
                  <div class="info-message">První sada byla úspěšně zveřejněna.</div>
               </div>

               <div class="ok">
                  <div class="info-time">14:30</div>
                  <div class="info-message">Soutěž začíná 16. 12. 2023 v 15:00. Vítáme všechny týmy v Los Metropolos :).</div>
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
