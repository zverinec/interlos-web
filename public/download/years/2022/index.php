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
               <h1>InterLoS 2022</h1>
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
                  <div class="info-time">19:42</div>
                  <div class="info-message">3. sada úloh byla pokořena. 1. a 2. sada pořád zůstávají nepokořeny.</div>
               </div>

               <div class="ok">
                  <div class="info-time">19:30</div>
                  <div class="info-message">Zbývá 30 minut do konce hry, statistiky byly skryty.</div>
               </div>

               <div class="nok">
                  <div class="info-time">18:31</div>
                  <div class="info-message">Pokud vám simulátor kuličkového počítače (P4) neakceptoval řešení 3. podúlohy, zkuste to teď.</div>
               </div>

               <div class="ok">
                  <div class="info-time">18:00</div>
                  <div class="info-message">3. sada byla úspěšně zveřejněna.</div>
               </div>

               <div class="ok">
                  <div class="info-time">17:48</div>
                  <div class="info-message">Řešení úlohy L4 204L8 zadávejte do <a href="https://interlos-204l8.iamroot.eu/" target="_blank">https://interlos-204l8.iamroot.eu/</a>.</div>
               </div>

               <div class="nok">
                  <div class="info-time">16:40</div>
                  <div class="info-message">Odevzdání úlohy P2 byla přepočítána.</div>
               </div>

               <div class="ok">
                  <div class="info-time">16:30</div>
                  <div class="info-message">2. sada úloh byla zveřejněna.</div>
               </div>

               <div class="nok">
                  <div class="info-time">16:29</div>
                  <div class="info-message">Evidujeme problém s úlohou P2 Losdělování buchty. Systém neuznával správná řešení. Během pár minut budou tato řešení uznána.</div>
               </div>

               <div class="ok">
                  <div class="info-time">15:07</div>
                  <div class="info-message">První vyřešenou úlohou sady je P1 Lámání hesla. Gratulujeme!</div>
               </div>

               <div class="nok">
                  <div class="info-time">15:00</div>
                  <div class="info-message">První sada úloh byla úspěšně zveřejněna.</div>
               </div>

               <div class="ok">
                  <div class="info-time">14:00</div>
                  <div class="info-message">Soutěž začíná 10. 12. 2022 v 15:00. Orgové mají všechno pod kontrolou :).</div>
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
