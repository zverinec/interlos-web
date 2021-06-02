#!/usr/bin/env swipl
% prolog, splusťte třeba interpretrem swi-prolog: swipl pochuzky-solution.pl
% nebo intraktivně: swipl -t prolog pochuzky-solution.pl

:- initialization(main, main).

% nejprve definujeme dny a jejich pořadí (abychom je pohli porovnávat)
den(po, 0).
den(ut, 1).
den(st, 2).
den(ct, 3).
den(pa, 4).
den(so, 5).
den(ne, 6).

% pondělí až čtvrtek
poct(X, N) :- den(X, N), N =< 3.

% predikáty pro jednoltivá místa, vždy se dvěma argumenty – den a jeho číslo
% omezují možnosti jednotlivých míst, ale ne závislosti mezi nimi

magistrat(ut, 1).
magistrat(ct, 3).

lossman(X, N) :- den(X, N).

glosbus(X, N) :- poct(X, N).

sobata(X, N) :- poct(X, N).

kvetinarstvi(X, N) :- den(X, N), X \== ne.

krev(X, N) :- den(X, N), N >= 2, N =< 4.

zoo(X, N) :- den(X, N).

% samotná podmínka pro jednotlová místa, lze volat z interpretru jako
% solve(Ridicak, Drogerie, Potraviny, Boty, Kvetiny, Krev, Zoo).
solve(Ridicak, Drogerie, Potraviny, Boty, Kvetiny, Krev, Zoo) :-
    % Ridicak
    magistrat(Ridicak, RidicakD),

    % Kvetiny
    kvetinarstvi(Kvetiny, KvetinyD),
    KvetinyD > RidicakD,

    % Boty
    sobata(Boty, BotyD),
    Boty \== Kvetiny,
    BotyD > RidicakD,

    % Zoo
    zoo(Zoo, ZooD),
    Zoo \== Ridicak,
    Zoo \== Kvetiny,
    Zoo \== Boty,
    ZooD > BotyD,

    % Krev
    krev(Krev, KrevD),
    (KrevD + 2 < ZooD; ZooD < KrevD), % zoo před krví, nebo více než 2 dny po
    Krev \== Ridicak,
    (KrevD + 1 < KvetinyD; KvetinyD < KrevD), % nepojede pro květiny den po očkování
    (KrevD + 1 < BotyD; BotyD < KrevD), % nepojede pro boty den po očkování

    % Drogerie
    lossman(Drogerie, _DrogerieD),
    Drogerie \== Ridicak,
    Drogerie \== Kvetiny,
    Drogerie \== Boty,
    Drogerie \== Zoo,
    Drogerie \== Krev,

    % Potraviny
    glosbus(Potraviny, _PotravinyD),
    Potraviny \== Ridicak,
    Potraviny \== Kvetiny,
    Potraviny \== Boty,
    Potraviny \== Zoo,
    Potraviny \== Krev,
    Potraviny \== Drogerie.

% skript je spustitelný
main(_Argv) :-
    solve(Ridicak, Drogerie, Potraviny, Boty, Kvetiny, Krev, Zoo),
    write(ridicak(Ridicak)), nl,
    write(drogerie(Drogerie)), nl,
    write(portraviny(Potraviny)), nl,
    write(boty(Boty)), nl,
    write(kvetiny(Kvetiny)), nl,
    write(krev(Krev)), nl,
    write(zoo(Zoo)), nl, nl.

% vim: ft=prolog
