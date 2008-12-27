INSERT INTO bobr_block (command, description) VALUES
('login', 'Posloucha prikazy z getu.'),
('login/show', 'Ukaze prihlasovaci formular.'),
('login/logout', 'Odhlasuje uzivatele.'),
('content', 'Posloucha prikazy z getu.'),
('content/show/1', 'Ukaze prvni content.'),
('content/new', 'Ukaze formular na vytvnoreni noveho contentu.');

INSERT INTO bobr_container ("name", description) VALUES
('header', 'Header container'),
('left', 'Left  container'),
('center', 'Center  container'),
('right', 'Right container'),
('footer', 'Footer  container');

INSERT INTO bobr_pageid_node (css, template) VALUES
('themes/earthling/css/default.css', 'themes/earthling/template/default.phtml'),
('themes/admin/css/default.css', 'themes/admin/template/default.phtml');

INSERT INTO bobr_pageid (pageid_node_id, description) VALUES
(1, 'Uvodni stranka'),
(1, 'Neco dalsiho'),
(1, 'Login'),
(2, 'Stranka v administraci'),
(2, 'Dalsi stranka v administraci');

INSERT INTO bobr_pageid_container_block (pageid_id, container_id, block_id, weight) VALUES
(1,3,4,1),
(1,4,2,1),
(2,3,2,1),
(2,4,4,1),
(3,3,1,1),
(4,3,6,1),
(4,2,2,1),
(5,2,6,1),
(5,3,2,1);


INSERT INTO bobr_routedynamic_cs (module_functions_id, webinstance_id, pageid_id,command) VALUES
(5, 2, 3, 'udelej/novej-clanek'),
(7, 1, 1, 'ukaz/clanek/cislo/([0-9]*)');

INSERT INTO bobr_routestatic_cs (webinstance_id, pageid_id, command, uri) VALUES
(1, 1, 'login/show', 'prihlaseni'),
(2, 4, 'menu/show', 'ukaz-menu');