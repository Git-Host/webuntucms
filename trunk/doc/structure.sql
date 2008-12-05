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

CREATE TABLE bobr_dynamicroute_cs
(
    module_functions_id INTEGER NOT NULL,
    pageid_id INTEGER NOT NULL,
    command VARCHAR,
    PRIMARY KEY (module_functions_id, pageid_id),
    FOREIGN KEY (module_functions_id) REFERENCES bobr_module_functions (id) ON UPDATE CASCADE ON DELETE RESTRICT,
    FOREIGN KEY (pageid_id) REFERENCES bobr_pageid (id) ON UPDATE CASCADE ON DELETE RESTRICT
);
COMMENT ON TABLE bobr_dynamicroute_cs IS 'Seznam dynamickych rout prelozenych do cestiny.';
COMMENT ON COLUMN bobr_dynamicroute_cs.module_functions_id IS 'Odkaz na konkretni funkci.';
COMMENT ON COLUMN bobr_dynamicroute_cs.pageid_id IS 'Odkaz na stranku.';
COMMENT ON COLUMN bobr_dynamicroute_cs.command IS 'Command, prikaz, je to regularni vyraz, ktery se porovnava s tim co prijde jako dotaz';

CREATE TABLE bobr_routestatic_cs
(
    id  SERIAL,
    pageid_id INTEGER NOT NULL,
    command VARCHAR,
    uri VARCHAR NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (pageid_id) REFERENCES bobr_pageid (id) ON UPDATE CASCADE ON DELETE RESTRICT
);
COMMENT ON TABLE bobr_routestatic_cs IS 'Seznam ceskych statickych rout';
COMMENT ON COLUMN bobr_routestatic_cs.id IS 'Unikatni hodnota routy';
COMMENT ON COLUMN bobr_routestatic_cs.pageid_id IS 'Stranka kde se ma vypsat';
COMMENT ON COLUMN bobr_routestatic_cs.command IS 'Prikaz ktery se ma provest';
COMMENT ON COLUMN bobr_routestatic_cs.uri IS 'Alias, ktery zastupuje tuto akci';