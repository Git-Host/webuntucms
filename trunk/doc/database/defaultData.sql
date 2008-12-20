INSERT INTO bobr_routedynamic_cs (module_functions_id, webinstance_id, pageid_id,command) VALUES
(5, 2, 3, 'udelej/novej-clanek'),
(7, 1, 1, 'ukaz/clanek/cislo/([0-9]*)');

INSERT INTO bobr_routestatic_cs (webinstance_id, pageid_id, command, uri) VALUES
(1, 1, 'login/show', 'prihlaseni'),
(2, 4, 'menu/show', 'ukaz-menu');