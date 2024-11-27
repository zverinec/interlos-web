module Main where

-- tento program je ve (funkcionálním programovacím jazyce Haskell --
-- http://www.haskell.org/haskellwiki/Haskell),
-- pokud jej chcete spustit, potřebujete buď interpret/kompilátor Haskellu
-- (nejlépe GHC), potom můžete kompilovat takto:
-- ghc P6s.hs -o reseni
-- nebo nějaký online interpret 
-- (například http://www.compileonline.com/compile_haskell_online.php).

import Data.List ( permutations, minimumBy )
-- asociativní mapa
import Data.Map ( Map, (!), empty, insert, fromList )

-- neprve si zadefinujeme jaké máme prádlo a akce které s ním můžeme dělat
data Pradlo = Kosile | Spodni | Rucniky | Uterky | Saty deriving ( Show, Eq, Ord )
data Akce = Prat | Zdimat | Susit | Zehlit deriving ( Show, Eq )

-- typ do kterého můžeme uložit buď číslo pokud máme danou akci provádět,
-- nebo hodnotu Preskocit která indikuje, že je akce přeskočena
data Trvani = Trva Int | Preskocit deriving ( Eq )

-- seznam všeho prádla k vyprání
vsechnoPradlo :: [ Pradlo ]
vsechnoPradlo = [ Kosile, Spodni, Rucniky, Uterky, Saty ]

-- zde si zadefinujeme jak dlouho trvají jednotivé akce pro různé prádlo
delkaAkce :: Akce -> Pradlo -> Trvani
delkaAkce Prat Kosile = Trva 40
delkaAkce Prat Spodni = Trva 40
delkaAkce Prat Rucniky = Trva 80
delkaAkce Prat Uterky = Trva 80
delkaAkce Prat Saty = Trva 75

delkaAkce Zdimat Kosile = Preskocit
delkaAkce Zdimat Spodni = Trva 30
delkaAkce Zdimat Rucniky = Trva 60
delkaAkce Zdimat Uterky = Trva 90
delkaAkce Zdimat Saty = Preskocit

delkaAkce Susit Kosile = Trva 110
delkaAkce Susit Spodni = Trva 30
delkaAkce Susit Uterky = Trva 30
delkaAkce Susit Rucniky = Trva 50
delkaAkce Susit Saty = Preskocit

delkaAkce Zehlit Kosile = Trva 60
delkaAkce Zehlit Spodni = Preskocit
delkaAkce Zehlit Rucniky = Preskocit
delkaAkce Zehlit Uterky = Trva 50
delkaAkce Zehlit Saty = Preskocit

-- senamy prádel podle toho co se nimi dělá, filtrujeme podle toho, že
-- akce není Preskocit
-- tomuto zápisu se říká intenzionální zápis seznamu, je to podobné,
-- jako když zapisujeme množiny
-- zápis znamená asi toto: do proměnné prádlo se postupně přiřazují
-- hodnoty z seznamu vsechnoPradlo, pro každou z nich se ptáme,
-- zda platí že delka příslušné akce není Preskocit, pokud toto platí,
-- prádlo se ocitne v seznamu
prane, zdimane, susene, zehlene :: [ Pradlo ]
prane =   [ pradlo | pradlo <- vsechnoPradlo, delkaAkce Prat   pradlo /= Preskocit ]
zdimane = [ pradlo | pradlo <- vsechnoPradlo, delkaAkce Zdimat pradlo /= Preskocit ]
susene =  [ pradlo | pradlo <- vsechnoPradlo, delkaAkce Susit  pradlo /= Preskocit ]
zehlene = [ pradlo | pradlo <- vsechnoPradlo, delkaAkce Zehlit pradlo /= Preskocit ]

-- tímto typem budeme reprezentovat rozvrh
data Rozvrh = Rozvrh
	{ akce :: [ (Akce, [ Pradlo ] ) ]
	, cas :: Int
	} deriving ( Show, Eq )

-- rozvrh a je lepší rozvrh než rozvrh b pokud je kratší, porovnání na časech
lepsiRozvrh :: Rozvrh -> Rozvrh -> Ordering
lepsiRozvrh a b = compare (cas a) (cas b)

-- fukce najde nejlepší rozvrh pro daný seznam prádla
solve :: [ Pradlo ] -> Rozvrh
solve pradlo = minimumBy lepsiRozvrh -- spočítáme minimum podle kritéria lepsiRozvrh
		-- vytváříme seznam všech možných rozvrhů
		[ Rozvrh { akce = seznam, cas = casPrani }
		| prat <- permutations prane -- postupně do pr přiřazuj permutace prádla k praní
		, zdimat <- permutations zdimane -- pro kazdé pořadí prádla k praní zkus
									 -- všechny permutace ždímání
		, susit <- permutations susene -- stejně tak pro sušení
		, zehlit <- permutations zehlene -- a žehlení
		  -- vytvoříme seznam dvojic, kde každá obsahuje akci
		  -- a pořadí prádla v němž se má akce na prádle provádět
		, let seznam = [ (Prat, prat), (Zdimat, zdimat), (Susit, susit), (Zehlit, zehlit) ]
		  -- spocitame jak dlouho trva prani
		, let casPrani = vyper seznam
		]
  where
	-- při výpočtu budeme postupovat postupně po akcích, u každého prádla
	-- si budeme pamatovat, kdy zkončila předchozí akce na tomto prádle
	--
	-- tato funkce jen inicializuje proměnné pro funkci vyper'
	-- a vrátí její výsledek (čas skončení poslední akce na posledním prádle)
	vyper :: [ (Akce, [ Pradlo ] ) ] -> Int
	vyper akce = vyper' akce nulovaMapa 0

	vyper' :: [ (Akce, [Pradlo] ) ] -> Map Pradlo Int -> Int -> Int
	-- pokud máme prázdný seznam akcí, je čas konce poslední prováděné akce
	-- i časem konce všech akcí
	vyper' [] koncePredchozi konecPosledni = konecPosledni
	-- pokud máme nějakou akci a prádlo na začátku, nejprve to zpracujeme
	-- a pak půjdeme na další
	-- v proměnné koncePredchozi máme uložené konce předchozí akce pro
	-- jednotlivá prádla
	vyper' ((akce, pradlo) : dalsi) koncePredchozi konecPosledni =
			-- zjistíme kdy tuhle akci dokončí jednotlivé prádlo, na začátku
			-- je stroj připravený (může pracovat od času 0).
		let noveKonce = konceAkce akce pradlo koncePredchozi 0
			-- konec této akce odpovídá konci jejího provádění na posledním prádle
			konecTeto = noveKonce ! (last pradlo)
			-- vypereme další
		in  vyper' dalsi noveKonce (max konecPosledni konecTeto)

	konceAkce :: Akce -> [ Pradlo ] -> Map Pradlo Int -> Int -> Map Pradlo Int
	-- pokud už v této akci memáme další prádlo k obsloužení, vrátíme
	-- mapu konců akce
	konceAkce _	[]		 koncePredchozi volnoOd = koncePredchozi
	-- pokud máme prádlo p a pak nějaké další (pradlo), zjistíme
	-- konec provádění akce pro p a pak pro ostatní
	konceAkce akce (p:pradlo) koncePredchozi volnoOd =
			-- začít s p můžu nejdříve v čase kdy je volný "stroj" na akci
			-- po zpracování poředchozího prádla a kdy je hotova předchozí
			-- akce s tímto prádlem
		let muzuZacit = max volnoOd (koncePredchozi ! p)
			Trva cas  = delkaAkce akce p -- získáme čas trvání akce
			dokonceno = muzuZacit + cas
		in  -- aktualizujeme konce poslední akce pro toto prádlo a zpracujeme další
			konceAkce akce pradlo (aktualizujKonce koncePredchozi p dokonceno) dokonceno

	-- konce akce aktualizujeme tak, že "přeplácname" hodnotu v mapě
	aktualizujKonce :: Map Pradlo Int -> Pradlo -> Int -> Map Pradlo Int
	aktualizujKonce stareKonce aktualizovanePradlo novyKonec
		= insert aktualizovanePradlo novyKonec stareKonce

	-- mapa která pro každé prádlo říká, že konce předchozí akce nastal v čase 0
	nulovaMapa = fromList (zip vsechnoPradlo [0,0..])

-- vypočítáme a vypíšeme řešení
main = print (solve vsechnoPradlo)
