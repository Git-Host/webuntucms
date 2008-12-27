CREATE TABLE bobr_block
(
    id SERIAL PRIMARY KEY,
    command VARCHAR,
    description VARCHAR(256)
);
COMMENT ON TABLE bobr_block IS 'Blocky slouzi jako prikazovac pro moduly. Blocky se zasazuji do containeru.';
COMMENT ON COLUMN bobr_block.id IS 'Unikatni cislo blocku.';
COMMENT ON COLUMN bobr_block.command IS 'Prikaz pro modul ktery se provede.';
COMMENT ON COLUMN bobr_block.description IS 'Popis blocku. Popis slouzi k orientaci v administraci k cemu dany block slouzi.';

CREATE TABLE bobr_container
(
    id SERIAL PRIMARY KEY,
    "name" VARCHAR(60) UNIQUE,
    description VARCHAR(256)
);
COMMENT ON TABLE bobr_container IS 'Seznam containeru do kterych se vkladaji blocky.';
COMMENT ON COLUMN bobr_container.id IS 'Unikatni cislo containeru.';
COMMENT ON COLUMN bobr_container."name" IS 'Unikatni ident containeru.';
COMMENT ON COLUMN bobr_container.description IS 'Popis kontejneru, slouzi pro administraci.';

CREATE TABLE bobr_pageid_node
(
    id SERIAL PRIMARY KEY,
    css VARCHAR,
    template VARCHAR
);
COMMENT ON TABLE bobr_pageid_node IS 'Zde jsou ulozene cesty ke kaskadam a templatam.';
COMMENT ON COLUMN bobr_pageid_node.id IS 'Unikatni cislo pageid_node.';
COMMENT ON COLUMN bobr_pageid_node.css IS 'Cesta k soubrou s kaskadama.';
COMMENT ON COLUMN bobr_pageid_node.template IS 'Cesta k hlavni sablone.';

CREATE TABLE bobr_pageid
(
    id SERIAL PRIMARY KEY,
    pageid_node_id INTEGER,
    description VARCHAR,
    FOREIGN KEY (pageid_node_id) REFERENCES bobr_pageid_node (id) ON UPDATE CASCADE ON DELETE RESTRICT
);
COMMENT ON TABLE bobr_pageid IS 'Tabulka slouzi k unikatni identifikaci stranky.';
COMMENT ON COLUMN bobr_pageid.id IS 'Unikatni cislo pageId.';
COMMENT ON COLUMN bobr_pageid.pageid_node_id IS 'Odkaz na kaskady a sablonu.';
COMMENT ON COLUMN bobr_pageid.description IS 'Popis stranky, slouzi pro administraci.';

CREATE TABLE bobr_pageid_container_block
(
    pageid_id INTEGER,
    container_id INTEGER,
    block_id INTEGER,
    weight INTEGER,
    PRIMARY KEY(pageid_id, container_id, block_id),
    FOREIGN KEY (pageid_id) REFERENCES bobr_pageid (id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (container_id) REFERENCES bobr_container (id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (block_id) REFERENCES bobr_block (id) ON UPDATE CASCADE ON DELETE CASCADE
);
COMMENT ON TABLE bobr_pageid_container_block IS 'Vazebni tabulka urcujici vahu a pozici blocku v page.';
COMMENT ON COLUMN bobr_pageid_container_block.pageid_id IS 'Odkaz na pageid.';
COMMENT ON COLUMN bobr_pageid_container_block.container_id IS 'Odkaz na kontejner.';
COMMENT ON COLUMN bobr_pageid_container_block.block_id IS 'Odkaz na block.';
COMMENT ON COLUMN bobr_pageid_container_block.weight IS 'Vaha blocku v danem kontejneru a page.';

CREATE TABLE bobr_group_webinstance
(
	group_id INTEGER NOT NULL,
	webinstance_id INTEGER NOT NULL,
	PRIMARY KEY (group_id, webinstance_id),
	FOREIGN KEY (group_id) REFERENCES bobr_groups (id) ON UPDATE CASCADE ON DELETE RESTRICT,
	FOREIGN KEY (webinstance_id) REFERENCES bobr_webinstance (id) ON UPDATE CASCADE ON DELETE RESTRICT
);
COMMENT ON COLUMN bobr_group_webinstance.group_id IS 'ID groupy na kterou se pravidlo vztahuje';
COMMENT ON COLUMN bobr_group_webinstance.webinstance_id IS 'ID webinstance na kterou skupina ma pravo.';

CREATE TABLE bobr_routedynamic_cs
(
    module_functions_id INTEGER NOT NULL,
    webinstance_id INTEGER NOT NULL,
    pageid_id INTEGER NOT NULL,
    command VARCHAR,
    PRIMARY KEY (module_functions_id, webinstance_id),
    FOREIGN KEY (module_functions_id) REFERENCES bobr_module_functions (id) ON UPDATE CASCADE ON DELETE RESTRICT,
    FOREIGN KEY (webinstance_id) REFERENCES bobr_webinstance (id) ON UPDATE CASCADE ON DELETE RESTRICT,
    FOREIGN KEY (pageid_id) REFERENCES bobr_pageid (id) ON UPDATE CASCADE ON DELETE RESTRICT
);
COMMENT ON TABLE bobr_routedynamic_cs IS 'Seznam dynamickych rout prelozenych do cestiny.';
COMMENT ON COLUMN bobr_routedynamic_cs.module_functions_id IS 'Odkaz na konkretni funkci.';
COMMENT ON COLUMN bobr_routedynamic_cs.webinstance_id IS 'Odkaz na id webinstance pro kterou je urcen odkaz';
COMMENT ON COLUMN bobr_routedynamic_cs.pageid_id IS 'Odkaz na stranku.';
COMMENT ON COLUMN bobr_routedynamic_cs.command IS 'Command, prikaz, je to regularni vyraz, ktery se porovnava s tim co prijde jako dotaz';

CREATE TABLE bobr_routestatic_cs
(
    id  SERIAL,
    webinstance_id INTEGER NOT NULL,
    pageid_id INTEGER NOT NULL,
    command VARCHAR,
    uri VARCHAR NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (webinstance_id) REFERENCES bobr_webinstance (id) ON UPDATE CASCADE ON DELETE RESTRICT,
    FOREIGN KEY (pageid_id) REFERENCES bobr_pageid (id) ON UPDATE CASCADE ON DELETE RESTRICT    
);
COMMENT ON TABLE bobr_routestatic_cs IS 'Seznam ceskych statickych rout';
COMMENT ON COLUMN bobr_routestatic_cs.id IS 'Unikatni hodnota routy';
COMMENT ON COLUMN bobr_routestatic_cs.webinstance_id IS 'Odkaz na id webinstance pro kterou je urcen odkaz';
COMMENT ON COLUMN bobr_routestatic_cs.pageid_id IS 'Stranka kde se ma vypsat';
COMMENT ON COLUMN bobr_routestatic_cs.command IS 'Prikaz ktery se ma provest';
COMMENT ON COLUMN bobr_routestatic_cs.uri IS 'Alias, ktery zastupuje tuto akci';
