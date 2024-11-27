<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
			   <h1>ročník 2015</h1>
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
				  <div class="info-time">20:00</div>
				  <div class="info-message">Letošní InterLoS skončil, řešení jsou k dispozici. Vítězům gratulujeme. Věříme, že jste si hru všichni užili.</div>
			   </div>
			   <div class="nok">
				  <div class="info-time">19:41</div>
				  <div class="info-message">Úloha P8 je potřeba uspořádat od nejnižší priority po nevyšší a vybrat poslední.</div>
			   </div>
			   <div class="nok">
				  <div class="info-time">18:57</div>
				  <div class="info-message">Úloha P9 na poslednim řádku nemá přibýt 7 a výsledek má být 10.</div>
			   </div>
			   <div class="ok">
				  <div class="info-time">18:45</div>
				  <div class="info-message">Každá úloha ze sady 3 byla vyluštěna!</div>
			   </div>
			   <div class="nok">
				  <div class="info-time">18:35</div>
				  <div class="info-message">Úloha P9 má být pro čísla 1 až 10 000 000! Omlouváme se za chybu.</div>
			   </div>
			   <div class="nok">
				  <div class="info-time">18:15</div>
				  <div class="info-message">Úloha L5 má více možných řešení! Omlouváme se, budeme uznávat všechna správná řešení. Jestli jste si jisti správností svého řešení a neobdržíte body, napište nám mail na interlos@fi.muni.cz a dodatečně Vám je přičteme.</div>
			   </div>
			   <div class="ok">
				  <div class="info-time">18:00</div>
				  <div class="info-message">Třetí sada zveřejněna.</div>
			   </div>
			   <div class="nok">
				  <div class="info-time">17:58</div>
				  <div class="info-message">V úloze P6 použijte každé slovo maximálně jednou! Omlouváme se za nejasnost.</div>
			   </div>
			   <div class="ok">
				  <div class="info-time">17:30</div>
				  <div class="info-message">Každá úloha ze sady 2 byla vyluštěna!</div>
			   </div>
			   <div class="ok">
				  <div class="info-time">16:30</div>
				  <div class="info-message">Druhá sada zveřejněna.</div>
			   </div>
			   <div class="ok">
				  <div class="info-time">15:30</div>
				  <div class="info-message">Sada 1 úspěšně nasazena, drobné problémy postihly vývěsku, nyní už by mělo být vše v pořádku.</div>
			   </div>
			   <div class="nok">
				  <div class="info-time">15:00</div>
				  <div class="info-message">Súťaž začína 9.12.2018 o 15:00</div>
			   </div>
			   <div class="hr">&nbsp;</div>
			   <p>Veškeré problémy s úlohami a odevzdávacím systémem hlaste z e-mailové adresy uvedené v profilu týmu na adresu <a href="mailto:interlos@fi.muni.cz"><span class="bold">interlos@fi.muni.cz</span></a>.
				  V předmětu e-mailu musí být uvedeno ID týmu . Svoje ID také najdete <a href="http://interlos.fi.muni.cz/">na stránce soutěže</a> v záhlaví stránky vedle jména.
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
