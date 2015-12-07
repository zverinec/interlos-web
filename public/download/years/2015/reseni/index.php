<?php
date_default_timezone_set('Europe/Prague');
if(new DateTime() >= new DateTime("2015-12-06 20:01:00")) {
	@rename(".htaccess",'.htaccess-old');
} else {
	die('Reseni bude zpristupneno az ve 20:00.');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="content-language" content="cz" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=2.0">
  <meta name="description" content="InterLoS - Internetova Logicka Soutez" />
  <link href="../design/stylesheet.css" media="screen" rel="stylesheet" type="text/css" />
  <link rel="icon" href="../design/favicon.ico" type="image/x-icon" />
  <title>Řešení &ndash; INTERnetová LOgická SOutěž</title>
</head>
<div class="root">
    <div class="header">
	<div class="header-line">&nbsp;</div>
	<div class="contents">
		<a href="http://interlos.fi.muni.cz/">
			<span class="block header-logo" title="InterLoS - INTERnetová LOgická Soutěž">&nbsp;</span>
			<span class="block header-name" title="InterLoS - INTERnetová LOgická Soutěž">&nbsp;</span>
		</a>
		<div class="header-year"><h1>ročník 2015</h1></div>
		<div class="cleaner-both">&nbsp;</div>
	</div>
    </div>
    
    <div class="main">
	<div class="contents">
		<div class="main-block">
		<h1>Řešení</h1>
		<p>Všechna (textová řešení) v jednom PDF souboru: <a href="reseni.pdf">reseni.pdf</a></p>
		<h2>Přiložené soubory</h2>
		<ul>
			<li><a href="P1s-converter.py">P1s-converter.py</a> (P1 Formule na sněhu)</li>
      			<li><a href="P1s-evaluator.py">P1s-evaluator.py</a> (P1 Formule na sněhu)</li>
      			<li><a href="P2s-reseni.cpp">P2s-reseni.cpp</a> (P2 Double Cola)</li>
      			<li><a href="P3s-reseni.cpp">P3s-reseni.cpp</a> (P3 Šibenice)</li>
      			<li><a href="P5s-reseni.py">P5s-reseni.py</a> (P5 Procházka zapastěnou šachovnicí)</li>
      			<li><a href="P6s-reseni.cpp">P6s-reseni.cpp</a> (P6 Podmořská procházka)</li>
			<li><a href="P7s-faze1-reseni.cpp">P7s-faze1-reseni.cpp</a> (P7 ICAO rozhlas)</li>
			<li><a href="P7s-faze2-reseni.cpp">P7s-faze2-reseni.cpp</a> (P7 ICAO rozhlas)</li>
			<li><a href="P7s-faze3-reseni.rb">P7s-faze3-reseni.rb</a> (P7 ICAO rozhlas)</li>
      			<li><a href="P8s-reseni.cpp">P8s-reseni.cpp</a> (P8 Tramtárie)</li>
			<li><a href="P8s-reseni.cpp">P9s-reseni.cpp</a> (P9 Špízy)</li>

		</ul>
		<div class="hr">&nbsp;</div>
		<p>Veškeré problémy s úlohami a odevzdávacím systémem hlaste z e-mailové adresy uvedené v profilu týmu na adresu <a href="mailto:interlos@fi.muni.cz"><span class="bold">interlos@fi.muni.cz</span></a>. V předmětu e-mailu musí být uvedeno ID týmu, který najdete <a href="http://interlos.fi.muni.cz/">na stránce soutěže</a> v záhlaví stránky vedle jména.</p>
		</div>
	</div>
    </div>
    
    <div  class="footer">
	<div class="footer-line">&nbsp;</div>
    </div>
 
</div>
</body>
</html>
