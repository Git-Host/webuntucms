-----------------------------------------------------
-- ! TOTO SE MUSI NAIMPORTOVAT DO DATABAZE JAKO PRVNI
-- ! OSTATNI ZAZNAMY NA TUTO TABULKU MAJI VAZBY
-----------------------------------------------------
-- Zakladni statusy
INSERT INTO bobr_status
(description_id, status_title)
VALUES 
(1, 'disabled'),
(2, 'enabled'),
(3, 'concept'),
(4, 'hidden'),
(5, 'private'),
(6, 'trash'),
(7, 'delete'),
(8, 'annihilate');
-------------------------------------------------------
-- TYTO DATA JSOU NUTNE PRO SPUSTENI BOBRA
-------------------------------------------------------


-- Lang - seznan podporovanych langu
INSERT INTO bobr_lang
( symbol, country )
VALUES
( 'cs', 'Czech rep.');

-- Default uzivatele s passwordem - pass 
INSERT INTO bobr_users 
(nick,pass,email) 
VALUES 
('admin', '9d4e1e23bd5b727046a9e3b4b7db57bd8d6ee684', 'info@bobrpico.cz'),
('user', '9d4e1e23bd5b727046a9e3b4b7db57bd8d6ee684', 'info@bobrpico.cz');

-- Popisky k zakladnimu rozmisteni webu
INSERT INTO bobr_description_cs
(title, description)
VALUES  
-- Statusy
('Nepovolený', 'Výchozí položka pro nově vzniklé elementy.'),
('Povolený', 'Opak výchozí položky Nepovolený, tyto elementy se zobrazují.'),
('Koncept', 'Touto položkou se označují koncepty.'),
('Skrytý', 'Schovaná položka.'),
('Privátní', 'Položka by se měla zobrazit pro uživatel, který patří do stejné unity.'),
('Koš', 'Položka je označena za smazanou, spadne do koše.'),
('Smazat', 'Položka je označena za smazanou, pujde do adminova kose.'),
('Vyhladit', 'Definitivně provede.'),
-- Kategorie administrace
('Obsah', 'Clanky, Obrazky, Soubory ...'),
('Uzivatele', 'Sprava uzivatelu.'),
('Nastaveni', 'Nastaveni webu.'),
('Ostatni', 'Ostatni nastaveni.'),
('Statistiky', 'Statistiky webu.'),
('Komunikace', 'Komunikace s uzivately, email.'),
-- Popisky k modulum
('Priznaky', 'Priznaky objektu webu (modulu, clanku, uzivatelu atd...).'),
('Login', 'Nastavovani loginu.'),
('Obsah', 'Clanky atd...'),
('Menu klasik', 'Zobrazuje kategorizovane menu.'),
-- Popisky k funkcim ( zacina id 19 )
('Pridat', 'Pridat priznak.'),
('Editovat', 'Editovat priznak.'),
('Ukazat', 'Ukazat priznaky.'),
('Ukazat', 'Ukazat prihlasovaci formular.'),
('Pridat', 'Pridat obsah.'),
('Editovat', 'Editovat obsah.'),
('Ukazat', 'Ukazat obsah.'),
('Pridat menu', 'Pridat menu.'),
('Pridat polozku', 'Pridat polozku do menu.'),
('Editovat', 'Editovat.'),
('Ukazat', 'Ukazat.');

-- Administrace - Kategorie administrace
INSERT INTO bobr_administrationcategory
( description_id, pageid_id, url, weight )
VALUES
( 9, 2, 'content-settings', 1 ),
( 10, 2, 'users', 2 ),
( 11, 2, 'settings', 3 ),
( 12, 2, 'other', 4 ),
( 13, 2, 'statistics', 5 ),
( 14, 2, 'comunication', 6);

-- Module - par demo modulu do databaze
INSERT INTO bobr_module 
( module, status, description_id, administrationcategory_id, isdynamic )
VALUES
( 'symptom', 1, 15, 3, false ),
( 'login', 1, 16, 3, true ),
( 'content', 1, 17, 1, false ),
( 'menu', 1, 18, 3, false );

-- Modules function - funkce k modulum
INSERT INTO bobr_module_functions
( module_id, hash, func, description_id, administration )
VALUES
( 1, md5( 'symptom/new-symptom' ), 'new-symptom', 19, true ),
( 1, md5( 'symptom/edit' ), 'edit', 20, true ),
( 1, md5( 'symptom/show' ), 'show', 21, true ),
( 2, md5( 'login/show' ), 'show', 22, true ),
( 3, md5( 'content/new-content' ), 'new-content', 23, true ),
( 3, md5( 'content/edit' ), 'edit', 24, true ),
( 3, md5( 'content/show' ), 'show', 25, true ),
( 4, md5( 'menu/new-menu' ), 'new-menu', 26, true ),
( 4, md5( 'menu/new-item' ), 'new-item', 27, true ),
( 4, md5( 'menu/edit' ), 'edit', 28, true ),
( 4, md5( 'menu/show' ), 'show', 29, true );

-- Groups - uzivatelske skupiny
INSERT INTO bobr_groups
( title, description )
VALUES
( 'BOBRAdmin', 'BOBR PICO a PICO BOBR' ),
( 'Anonymous', 'Devky bez bobra' ),
( 'Register', 'Uzivatele, ktery ze mit BOBRa je dobra vec..... BOBR PICO :)');

-- UserGroups - vazba mezi uzivateli a Groupama
INSERT INTO bobr_user_groups
( user_id, group_id )
VALUES
( 1, 1),
( 2, 2);

-- GroupFunction - Funkce do kterych dana groupa muze
INSERT INTO bobr_group_functions
( group_id, module_id, module_function_id )
VALUES
-- BOBRAdmin
( 1, 1, 1),
( 1, 1, 2),
( 1, 1, 3),
( 1, 2, 3),
( 1, 3, 4),
( 1, 3, 5),
( 1, 3, 6),
( 1, 4, 7),
( 1, 4, 8),
( 1, 4, 9),
( 1, 4, 10),
-- Anonymous
( 2, 1, 1),
( 2, 2, 4),
( 2, 3, 6),
( 2, 4, 10);

-- GroupAccess - Pristupy kam vsude groupa muze vstoupit
INSERT INTO bobr_group_access
( group_id, processtype )
VALUES
( 1, 'index'),
( 1, 'admin'),
( 2, 'index');

---------------------------------------------------------
-- 	TOTO BYLI DATA BEZ KTERYCH SE NEPODARI SPUSTIT BOBRA
-- 	TYTO DATA SE DAJI BRAT ZA DEFAULT AZ NA NEJAKE DESCRIPTION, KTERE JSOU VULGARNI
---------------------------------------------------------

--------------------------------------------------------
-- ZDE JSOU DEVEL DEMO DATA
--------------------------------------------------------

-- PageIdNode - cesty pro pageID
INSERT INTO bobr_pageid_node
( css, template )
VALUES
( 'share/theme/default/css/default.css', 'share/theme/default/template/default.tpl');

-- PageID - konkretni pageID
INSERT INTO bobr_pageid
( pageid_node_id, block_ids, description )
VALUES
( 1, '1', 'Zobrazeni loginu.'),
( 1, '1,2,3', 'Neco dalsiho.');

-- Container - seznam kontejneru, ktere muzeme pouzivat
INSERT INTO bobr_container
( title, description )
VALUES
( 'header', 'Header container.'),
( 'left', 'Left container.'),
( 'center', 'Center container.'),
( 'right', 'Right container.'),
( 'footer', 'footer container.');


-----------------------------------------------------
-- Zde jsou nejake data pro devel praci s BOBRem   --
-----------------------------------------------------

-- Popisky k blokum zaciname na ID 30
INSERT INTO bobr_description_cs
(title, description)
VALUES
('Login', 'Zadejte svoje prihlasovaci udaje a stisknete tlacitko odeslat ci co tam je.');
('Obsah', 'Tady je nejak trapna show metoda na vypsani obsahu.');

-- Block - konkretni bloky
INSERT INTO bobr_block
( module_id, container_id, command, description_id, weight )
VALUES
( 2, 1, 'login/show', 30, 1 ),
( 3, 1, 'content/show', 31, 2);

-- Aliases - nejake aliasy pro zacatek
INSERT INTO bobr_aliases
( hash, pageid_id, lang_id, command, alias )
VALUES
( md5('prihlaseni'), 1, 1, 'login/show', 'prihlaseni'),
( md5('stranka'), 2, 1, 'addresser/show', 'stranka');

-- dynamicModule - nejake data pro zacatek
INSERT INTO bobr_dynamicmodule
( module_functions_id, aliases_id, lang_id )
VALUES
( 4, 1, 1);

------------------------------------------------------------------------------------------------
-- RBAS 28-09-2008
------------------------------------------------------------------------------------------------

-- Popisky zacneme na 32
INSERT INTO bobr_description_cs
( title, description )
VALUES
( 'PageID', 'Vytvareni stranek.'),
( 'Nova', 'Vytvoreni nove stranky.'),
( 'Nova cesta', 'Vytvoreni nove cesty ke strance ( CSS, Template ).'),
( 'Editace', 'Editovani stranky.'),
( 'Editace cesty', 'Editace cesty pro pageId ( CSS, Template ).'),
( 'Content', 'Posloucha prikazi z GETu.'),
( 'Nova PageID', 'Vytvoreni nove pageID.'),
( 'Nova PageID cesta', 'Vytvoreni nove pageID cesty.'),
( 'Editace PageID', 'Editace pageID.'),
( 'Editace PageID cesty', 'Editace pageID.');

-- module - id  5
INSERT INTO bobr_module
( module, status, description_id, administrationcategory_id, isdynamic)
VALUES
('pageid', 1, 32, 3, false );

-- Modules function - funkce k modulum -- id 12
INSERT INTO bobr_module_functions
( module_id, hash, func, description_id, administration )
VALUES
( 5, md5( 'pageid/new-pageid' ), 'new-pageid', 33, true ),
( 5, md5( 'pageid/new-node' ), 'new-node', 34, true ),
( 5, md5( 'pageid/edit' ), 'edit', 35, true ),
( 5, md5( 'pageid/edit/node' ), 'edit/node', 36, true );

-- Priradime prava i grupe
INSERT INTO bobr_group_functions
( group_id, module_id, module_function_id )
VALUES
( 1, 5, 12),
( 1, 5, 13),
( 1, 5, 14),
( 1, 5, 15);

-- pageidnode
INSERT INTO bobr_pageid_node
( css, template )
VALUES
( 'share/theme/admin/css/default.css', 'share/theme/admin/template/default.tpl');

-- pageid
INSERT INTO bobr_pageid
( pageid_node_id, block_ids, description )
VALUES
( 2, '1,2,4,5,6,7,8', 'Stranka v administraci.');

INSERT INTO bobr_block
( module_id, container_id, command, description_id, weight )
VALUES
( 3, 3, '', 38, 1),
( 5, 3, 'page/new-pageid', 39, 1),
( 5, 3, 'page/new/node', 40, 1),
( 5, 3, 'page/edit', 41, 1),
( 5, 3, 'page/edit/node', 42, 1);