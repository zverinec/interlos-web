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
			<div class="header-year"><h1>ročník 2015</h1></div>
			<div class="cleaner-both">&nbsp;</div>
		</div>
		</div>
	<?php } ?>
	
	<div class="main">
	<div class="contents" <?php if (isset($_GET['headless'])) { echo 'style="padding: 0px; width: 100%;max-width: inherit;"';  }   ?>>
		<div class="main-block">
			<h1 class="lefted">Aktuální informace</h1>
						<div class="time-to-refresh">Obnovení za: <span id="remaining-time"></span> s</div>

			<div class="cleaner-both">&nbsp;</div>

						<div class="neutral">
								<div class="info-time">19:30</div>
								<div class="info-message">After párty ve Dřevákovi ve 20:30 (<a href="http://www.udrevaka.cz/">http://www.udrevaka.cz/</a>)!</div>
						</div>

			<div class="neutral">
								<div class="info-time">19:30</div>
								<div class="info-message">Poslední půlhodina hry!</div>
						</div>

			<div class="nok">
								<div class="info-time">18:20</div>
								<div class="info-message">Kvůli chybě ve vstupu úlohy P9 Špízy jsme uznávali špatné heslo. To je nyní opraveno.</div>
						</div>
				
						<div class="nok">
								<div class="info-time">18:20</div>
								<div class="info-message">V úloze P9 Špízy jsme našli chybu. Stáhněte si nový vstup a zadání ze standardního umístění.</div>
						</div>

			<div class="ok">
								<div class="info-time">18:00</div>
								<div class="info-message">Třetí sada zveřejněna. Přejeme příjemný finiš..</div>
						</div>

			<div class="neutral">
								<div class="info-time">17:40</div>
								<div class="info-message">Všechny úlohy druhé sady jsou řešitelné a to nás opravdu těší... Zbývá 20 minut do další sady.</div>
						</div>

			<div class="nok">
								<div class="info-time">17:30</div>
								<div class="info-message">V úloze P6 Podmořská procházka ve výsledném hesle nesčítejte zaplavené oblasti po řádcích.</div>
						</div>

			<div class="neutral">
								<div class="info-time">17:05</div>
								<div class="info-message"><a target="_new" href="https://www.facebook.com/interlos/photos/a.388323821270221.1073741828.369734636462473/640197516082849/?type=3&theater">Fotka týmu ze štábu</a>.</div>
						</div>

						<div class="ok">
								<div class="info-time">16:30</div>
								<div class="info-message">Druhá sada zveřejněna.</div>
						</div>

						<div class="neutral">
								<div class="info-time">15:57</div>
								<div class="info-message">Z radosti týmu, že všechny šifry jsou luštitelné, začal organizátorský tým přepadávat po vzoru <a href="https://www.youtube.com/watch?v=QqreRufrkxM">španělské inkvizice</a> týmy ve stejné budově.</div>
						</div>

						<div class="neutral">
								<div class="info-time">15:37</div>
								<div class="info-message">Ve štábu panuje veselá atmosféra z dosavadního průběhu :-)</div>
						</div>
			
			<div class="ok">
								<div class="info-time">15:00</div>
								<div class="info-message">Štastný nový InterLoS!</div>
						</div>

			<div class="ok">
								<div class="info-time">14:45</div>
								<div class="info-message">Všechno je připraveno ke hře! Přejeme hodně úspěchů!</div>
						</div>

		<div class="hr">&nbsp;</div>
		<p>Veškeré problémy s úlohami a odevzdávacím systémem hlaste z e-mailové adresy uvedené v profilu týmu na adresu <a href="mailto:interlos@fi.muni.cz"><span class="bold">interlos@fi.muni.cz</span></a>.
		V předmětu e-mailu musí být uvedeno ID týmu <?php if (isset($_GET['subject'])) echo '(' . htmlspecialchars($_GET['subject']) . ')'; ?>. Svoje ID také najdete <a href="http://interlos.fi.muni.cz/">na stránce soutěže</a> v záhlaví stránky vedle jména.</p>
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

