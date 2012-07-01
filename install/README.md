<?php

/*
 * zde budou SQL prikazy pro vytvoreni v modulu
 * po nainstalovani 
 *  se vytvori danne tabulky
 *  pokud jde o podmodul
 *      zkopiruji skripty z module/lib/php do hlavni slozky /lib
 *          -main.lib.php (nekopiruju) - if submodul then require_once hlahni main.lib.php else autoinclude php lib
 *      -knihovny by meli byt pojmenovany LG<nazev modulu><nazev tridy>
 * 
 * 
 * dneska moc nepařím na iPadu a jak jsem produktivní :) Už jsem to vymyslel, ještě to dodělat. Vím že jsme se moc nestihli bavit o vrtulníku, ale měli bychom mít nejdřív uzavřenou jednu kapitolu, než se vrhneme na další.

  na netu jsem našel knížku o vývoje v GITu (https://github.s3.amazonaws.com/media/progit.en.pdf), už jsem si jí nahrál do kindla,


  Vývoj Ligea
  bude probihat rozdelen na moduly, vývoj se presune na github
  ???nejak je nutno distribuovat mezi moduly skripty z /lib/php/


  Instalace Ligea
  (*) Uživatel si stáhne z GITHUBU ligeo manager - na webu budeme mít návod, jak to rozbehnout na openshiftu - tzn uzivatel akorát zkopíruje příkazy do terminálu
  při prvním zobrazení php stránky (index.php) se detekuje nenainstalovaný systém (není settings/main.php)
  (*) zobrazí se form, po vyplnění bude vygenerován soubor settings.php
  nainstaluje se MySQL
  (*) uživatel si vybere, které chce nainstalovat moduly (nekde na netu je nutne mit seznam modulu - zatim staci mit napevno v ligeo)
  z githubu se stahne ligeo-viewer do slozky /install/temp/
  rozbali se do slozky /module
  stahnou se pripadne dalsi moduly
  nainstaluji se moduly (zkopiruje se do nich nastaveni z ligeo)

  (*) interakce uživatele

  -dále stojí za přečtení toto https://openshift.redhat.com/community/blogs/how-to-create-an-openshift-github-quick-start-project

 */
?>
