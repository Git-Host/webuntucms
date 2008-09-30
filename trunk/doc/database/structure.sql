CREATE TABLE bobr_pageid_node (
	id SERIAL PRIMARY KEY,
	css VARCHAR (512),
	template VARCHAR (512)
);
COMMENT ON COLUMN bobr_pageid_node.id IS 'Primarni klic';
COMMENT ON COLUMN bobr_pageid_node.css IS 'Cesta k CSS souboru';
COMMENT ON COLUMN bobr_pageid_node.template IS 'Cesta k template.';

CREATE TABLE bobr_pageid (
	id SERIAL PRIMARY KEY,
	pageid_node_id INTEGER,
	block_ids TEXT,
	description TEXT
);


COMMENT ON COLUMN bobr_pageid.id IS 'Primarni klic.';
COMMENT ON COLUMN bobr_pageid.pageid_node_id IS 'Odkaz do tabulky pageid_node.';
COMMENT ON COLUMN bobr_pageid.block_ids IS 'ID bloku oddelene carkou.';
COMMENT ON COLUMN bobr_pageid.description IS 'Popis bloku, co je zac atd...';

CREATE TABLE bobr_lang (
	id SERIAL PRIMARY KEY,
	symbol VARCHAR (3),
	country VARCHAR (128)
);
COMMENT ON COLUMN bobr_lang.id IS 'Primarni klic.';
COMMENT ON COLUMN bobr_lang.symbol IS 'Pismeny znak daneho jazyka (Czech rep. => cs).';
COMMENT ON COLUMN bobr_lang.country IS 'Nazev zeme.';


CREATE TABLE bobr_aliases (
	id  SERIAL PRIMARY KEY,
	hash CHAR (32),
	pageid_id INTEGER,
	lang_id INTEGER,
	command TEXT,
	alias TEXT,
	CONSTRAINT bobr_aliases_pageid_id FOREIGN KEY (pageid_id) REFERENCES bobr_pageid (id) ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT bobr_aliases_lang_id FOREIGN KEY (lang_id) REFERENCES bobr_lang (id) ON UPDATE NO ACTION ON DELETE NO ACTION
);
COMMENT ON COLUMN bobr_aliases.id IS 'Primarni klic, na ktery je odkazovano skrz cely navrh databaze.';
COMMENT ON COLUMN bobr_aliases.hash IS 'Zde je ulozen hash ( md5 ) sloupecku alias, slouzi k rychlejsimu vyhledavani url aliasu.';
COMMENT ON COLUMN bobr_aliases.pageid_id IS 'Odkaz na id stranky.';
COMMENT ON COLUMN bobr_aliases.lang_id IS 'Odkaz na jazyk ve kterem je dany alias.';
COMMENT ON COLUMN bobr_aliases.command IS 'Prikaz, ktery vydava stranka modulu.';
COMMENT ON COLUMN bobr_aliases.alias IS 'URI lokalizovany alias.';

CREATE TABLE bobr_description_cs (
	id SERIAL PRIMARY KEY,
	title VARCHAR(256) NOT NULL,
	description TEXT
);
COMMENT ON COLUMN bobr_description_cs.id IS 'ID a primarni klic tabulky';
COMMENT ON COLUMN bobr_description_cs.title IS 'Titulek | nazev prvku ke kteremu se vaze zaznam';
COMMENT ON COLUMN bobr_description_cs.description IS 'Pops prvku na ktery se vaze zaznam';

CREATE TABLE bobr_administrationcategory (
	id SERIAL PRIMARY KEY,
	description_id INTEGER,
	pageid_id INTEGER,
	url VARCHAR(126),
	weight INTEGER NOT NULL DEFAULT (0),
	CONSTRAINT bobr_administrationcategory_pageid_id FOREIGN KEY (pageid_id) REFERENCES bobr_pageid (id)
);
COMMENT ON COLUMN bobr_administrationcategory.id IS 'ID a primarni klic tabulky';
COMMENT ON COLUMN bobr_administrationcategory.description_id IS 'Odkaz na lokalizovany popisek';
COMMENT ON COLUMN bobr_administrationcategory.pageid_id IS 'Odkaz na page_id.';
COMMENT ON COLUMN bobr_administrationcategory.url IS 'URL dane kategorie, pozn. neni v tabulce alias pro minimalizovani vazeb';
COMMENT ON COLUMN bobr_administrationcategory.weight IS 'Vaha polozky';

CREATE TABLE bobr_status (
	id SERIAL PRIMARY KEY,
	description_id INTEGER,
	status_title VARCHAR(32)
);
COMMENT ON COLUMN bobr_status.id IS 'ID a primarni klic tabulky';
COMMENT ON COLUMN bobr_status.description_id IS 'Odkaz na lokalizovany popisek';
COMMENT ON COLUMN bobr_status.status_title IS 'Jmeno statusu';

CREATE TABLE bobr_symptoms (
	id SERIAL PRIMARY KEY,
	interlock_id INTEGER
);
COMMENT ON COLUMN bobr_symptoms.id IS 'ID a primarni klic tabulky';
COMMENT ON COLUMN bobr_symptoms.interlock_id IS 'Odkaz na lokalizovany interlock';

CREATE TABLE bobr_module (
	id SERIAL PRIMARY KEY,
	module VARCHAR (64),
	status INTEGER,
	description_id INTEGER,
	administrationcategory_id INTEGER,
	isdynamic BOOLEAN
);
COMMENT ON COLUMN bobr_module.id IS 'Primarni klic.';
COMMENT ON COLUMN bobr_module.module IS 'Nazev modulu.';
COMMENT ON COLUMN bobr_module.status IS 'Stauts (priznak) modulu, zde je cislo, ktere urcuje status modulu. Cisla jsou schodne vsude a definuji se modulem symptom(staus)';
COMMENT ON COLUMN bobr_module.description_id IS 'Odkaz na lokalizovany popisek';
COMMENT ON COLUMN bobr_module.administrationcategory_id IS 'Cislo kategorie administrace do ktere modul patri';
COMMENT ON COLUMN bobr_module.isdynamic IS 'Booleanovska hodnota zda-li modul je/neni dynamicky.';

CREATE TABLE bobr_module_functions (
	id SERIAL PRIMARY KEY,
	module_id INTEGER,
	hash CHAR (32),
	func TEXT,
	description_id INTEGER,
	administration BOOLEAN,
	author VARCHAR (64),
	funcversion VARCHAR (10),
	bobrversion VARCHAR(10),
	CONSTRAINT bobr_module_funcitons_module_id FOREIGN KEY (module_id) REFERENCES bobr_module (id) 
);
COMMENT ON COLUMN bobr_module_functions.id IS 'Primarni klic.';
COMMENT ON COLUMN bobr_module_functions.module_id IS 'Odkaz na id modulu';
COMMENT ON COLUMN bobr_module_functions.hash IS 'Hash sloupecku func, slouzi pro rychlejsi vyhledavani konkretni funkce modulu. Sklada se z nazvumodulu a nazvu funkce napr. md5(menunew-item)';
COMMENT ON COLUMN bobr_module_functions.func IS 'Funkce modulu ve stringu (menu/edit).';
COMMENT ON COLUMN bobr_module_functions.description_id IS 'Odkaz na lokalizovany popisek';
COMMENT ON COLUMN bobr_module_functions.administration IS 'Pokud je funkce administracni neni jen na vypsani dat nekam a ma se zobrazovat v aministraci je zde TRUE';
COMMENT ON COLUMN bobr_module_functions.author IS 'Jmeno autora modulu';
COMMENT ON COLUMN bobr_module_functions.funcversion IS 'Verze funkce';
COMMENT ON COLUMN bobr_module_functions.bobrversion IS 'Verze BOBRa do ktereho je funkce psana';

CREATE TABLE bobr_dynamicmodule (
	id SERIAL PRIMARY KEY,
	module_functions_id INTEGER,
	aliases_id INTEGER,
	lang_id INTEGER,
	CONSTRAINT bobr_dynamicmodule_module_funcions_id FOREIGN KEY (module_functions_id) REFERENCES bobr_module_functions (id) ON UPDATE NO ACTION ON DELETE CASCADE,
	CONSTRAINT bobr_dynamicmodule_aliases_id FOREIGN KEY (aliases_id) REFERENCES bobr_aliases (id) ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT bobr_dynamicmodule_lang_id FOREIGN KEY (lang_id) REFERENCES bobr_lang (id) ON UPDATE NO ACTION ON DELETE NO ACTION
);
COMMENT ON COLUMN bobr_dynamicmodule.id IS 'Primarni klic';
COMMENT ON COLUMN bobr_dynamicmodule.module_functions_id IS 'Odkaz do tabulky module_functions na id konkretni funkce modulu.';
COMMENT ON COLUMN bobr_dynamicmodule.aliases_id IS 'Odkaz na id aliasu, aliasy slozi pro lokalizovani nazvu dynamickych modulu.';
COMMENT ON COLUMN bobr_dynamicmodule.lang_id IS 'Odkaz na id langu ve kterem je dany alias lokalizovan.';

CREATE TABLE bobr_container (
	id SERIAL PRIMARY KEY,
	title VARCHAR (128) NOT NULL,
	description VARCHAR(512)
);
COMMENT ON COLUMN bobr_container.id IS 'Primarni klic.';
COMMENT ON COLUMN bobr_container.title IS 'Jmeno kontejneru - pise se bez diakritiky a bez mezer.';
COMMENT ON COLUMN bobr_container.description IS 'Popis kontejneru.';


CREATE TABLE bobr_block (
	id SERIAL PRIMARY KEY,
	module_id INTEGER,
	container_id INTEGER,
	command TEXT,
	description_id INTEGER,
	weight INTEGER,
	CONSTRAINT bobr_block_module_id FOREIGN KEY (module_id) REFERENCES bobr_module (id) ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT bobr_block_container_id FOREIGN KEY (container_id) REFERENCES bobr_container (id) ON UPDATE NO ACTION ON DELETE NO ACTION
);
COMMENT ON COLUMN bobr_block.id IS 'Primarni klic';
COMMENT ON COLUMN bobr_block.module_id IS 'Odkaz na id modulu, ktery se stara o vypsani dat.';
COMMENT ON COLUMN bobr_block.container_id IS 'Odkaz na id containeru, kde ma byt blok vypsan.';
COMMENT ON COLUMN bobr_block.command IS 'Prikaz co ma delat modul.';
COMMENT ON COLUMN bobr_block.description_id IS 'Odkaz na lokalizovany popis';
COMMENT ON COLUMN bobr_block.weight IS 'Vaha bloku, cim vetsi cilo tim tezsi blok.';


CREATE TABLE bobr_contenttype_cs (
	id SERIAL PRIMARY KEY,
	module_id INTEGER,
	pageid_id INTEGER,
	title VARCHAR(512),
	description VARCHAR(512),
	status INTEGER,
	CONSTRAINT bobr_contentype_cs_module_id FOREIGN KEY (module_id) REFERENCES bobr_module (id) ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT bobr_contentype_cs_pageid_id FOREIGN KEY (pageid_id) REFERENCES bobr_pageid (id) ON UPDATE NO ACTION ON DELETE NO ACTION
);
COMMENT ON COLUMN bobr_contenttype_cs.id IS 'Primarni klic.';
COMMENT ON COLUMN bobr_contenttype_cs.module_id IS 'Odkaz na id modulu.';
COMMENT ON COLUMN bobr_contenttype_cs.pageid_id IS 'Odkaz na id pageid.';
COMMENT ON COLUMN bobr_contenttype_cs.title IS 'Nazev ContentTypu.';
COMMENT ON COLUMN bobr_contenttype_cs.description IS 'Popis ContentTypu.';
COMMENT ON COLUMN bobr_contenttype_cs.status IS 'Status/symptom daneho ContentTypu.';

CREATE TABLE bobr_interlock_cs (
	id SERIAL PRIMARY KEY,
	contenttype_id INTEGER,
	aliases_id INTEGER,
	pid INTEGER,
	title VARCHAR (256),
	status INTEGER,
	CONSTRAINT bobr_interlock_cz_contenttype_cs FOREIGN KEY (contenttype_id) REFERENCES bobr_contenttype_cs (id) ON UPDATE NO ACTION ON DELETE NO ACTION
);
COMMENT ON COLUMN bobr_interlock_cs.id IS 'Primarni klic.';
COMMENT ON COLUMN bobr_interlock_cs.contenttype_id IS 'Odkaz na ContentType ID, ktery slouzi jako kategorizace interlocku.';
COMMENT ON COLUMN bobr_interlock_cs.aliases_id IS 'ID aliasu, pokud dany interlock slozi i k tomuto ucelu.';
COMMENT ON COLUMN bobr_interlock_cs.pid IS 'ID rodice daneho interlocku.';
COMMENT ON COLUMN bobr_interlock_cs.title IS 'Nazev interlocku.';
COMMENT ON COLUMN bobr_interlock_cs.status IS 'Status/symptom daneho interlocku.';

CREATE TABLE bobr_groups (
	id SERIAL PRIMARY KEY,
	pid INTEGER,
	title VARCHAR (246),
	description VARCHAR (512)
);
COMMENT ON COLUMN bobr_groups.id IS 'Primarni klic.';
COMMENT ON COLUMN bobr_groups.pid IS 'Parent id - kategorizace.';
COMMENT ON COLUMN bobr_groups.title IS 'Nazev Groupy.';
COMMENT ON COLUMN bobr_groups.description IS 'Popis Groupy.';

CREATE TABLE bobr_units (
	id SERIAL PRIMARY KEY,
	groups_id INTEGER,
	pid INTEGER,
	title VARCHAR (246),
	description VARCHAR (512),
	CONSTRAINT bobr_units_groups_id FOREIGN KEY (groups_id) REFERENCES bobr_groups (id) ON UPDATE NO ACTION ON DELETE NO ACTION
);
COMMENT ON COLUMN bobr_units.id IS 'Primarni klic.';
COMMENT ON COLUMN bobr_units.groups_id IS 'Odkaz na hlavni groupu slouzici ke kategorizaci jednotlivych uzlu.';
COMMENT ON COLUMN bobr_units.pid IS 'ID rodice dane unit.';
COMMENT ON COLUMN bobr_units.title IS 'Nazev unity.';
COMMENT ON COLUMN bobr_units.description IS 'Popis unity.';

CREATE TABLE bobr_units_groups (
	id SERIAL PRIMARY KEY,
	units_id INTEGER,
	pid INTEGER,
	title VARCHAR (246),
	description VARCHAR (512),
	CONSTRAINT bobr_units_groups_units_id FOREIGN KEY (units_id) REFERENCES bobr_units (id) ON UPDATE NO ACTION ON DELETE NO ACTION
);
COMMENT ON COLUMN bobr_units_groups.id IS 'Primarni klic.';
COMMENT ON COLUMN bobr_units_groups.units_id IS 'Odkaz na zdrojovou units.';
COMMENT ON COLUMN bobr_units_groups.pid IS ' rodice unitGroupy.';
COMMENT ON COLUMN bobr_units_groups.title IS 'Nazeve UnitGroupy.';
COMMENT ON COLUMN bobr_units_groups.description IS 'Popis UnitGroupy';

CREATE TABLE bobr_users (
	id SERIAL PRIMARY KEY,
	nick VARCHAR (126) NOT NULL UNIQUE,
	pass CHAR(40) NOT NULL,
	email VARCHAR (126) NOT NULL,
	status_id INTEGER NOT NULL DEFAULT(1),
	CONSTRAINT bobr_users_status_id FOREIGN KEY (status_id) REFERENCES bobr_status (id) ON UPDATE NO ACTION ON DELETE NO ACTION
);
COMMENT ON COLUMN bobr_users.id IS 'Primarni klic.';
COMMENT ON COLUMN bobr_users.nick IS 'Nick usera';
COMMENT ON COLUMN bobr_users.pass IS 'Hash hesla usera generovany SHA1.';
COMMENT ON COLUMN bobr_users.email IS 'Email na usera.';
COMMENT ON COLUMN bobr_users.status_id IS 'Odkaz na status (lokalizovany).';

CREATE TABLE bobr_user_groups (
	user_id INTEGER,
	group_id INTEGER,
	CONSTRAINT bobr_user_groups_user_id FOREIGN KEY (user_id) REFERENCES bobr_users (id) ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT bobr_user_groups_group_id FOREIGN KEY (group_id) REFERENCES bobr_groups (id) ON UPDATE NO ACTION ON DELETE NO ACTION
);
COMMENT ON COLUMN bobr_user_groups.user_id IS 'Odkaz na id uzivatele.';
COMMENT ON COLUMN bobr_user_groups.group_id IS 'Odkaz na id groupy.';

CREATE TABLE bobr_interlock_groups_cs (
	group_id INTEGER NOT NULL,
	interlock_id INTEGER NOT NULL,
	follow_childs BOOLEAN DEFAULT(FALSE),
	CONSTRAINT bobr_interlock_groups_cs_group_id FOREIGN KEY (group_id) REFERENCES bobr_groups (id) ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT bobr_interlock_groups_cs_interlock_id FOREIGN KEY (interlock_id) REFERENCES bobr_interlock_cs (id) ON UPDATE NO ACTION ON DELETE NO ACTION
);
COMMENT ON COLUMN bobr_interlock_groups_cs.group_id IS 'Odkaz na id groupy.';
COMMENT ON COLUMN bobr_interlock_groups_cs.interlock_id IS 'Odkaz na id lokalizovaneho interlocku.';
COMMENT ON COLUMN bobr_interlock_groups_cs.follow_childs IS 'Pravo se vztahuje i na potomky.';

CREATE TABLE bobr_group_functions (
	id SERIAL PRIMARY KEY,
	group_id INTEGER,
	module_id INTEGER,
	module_function_id INTEGER,
	privilege BOOLEAN,
	
	CONSTRAINT bobr_group_functions_group_id FOREIGN KEY (group_id) REFERENCES bobr_groups (id) ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT bobr_group_functions_module_id FOREIGN KEY (module_id) REFERENCES bobr_module (id) ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT bobr_group_functions_module_function_id FOREIGN KEY (module_function_id) REFERENCES bobr_module_functions (id) ON UPDATE NO ACTION ON DELETE NO ACTION
);
COMMENT ON COLUMN bobr_group_functions.id IS 'Primarni klic tabylky';
COMMENT ON COLUMN bobr_group_functions.group_id IS 'Odkaz na id groupy.';
COMMENT ON COLUMN bobr_group_functions.module_id IS 'Odkaz na id modulu.';
COMMENT ON COLUMN bobr_group_functions.module_function_id IS 'Odkaz na id funkce modulu.';
COMMENT ON COLUMN bobr_group_functions.privilege IS 'Hodnota prava TRUE | FALSE.';

CREATE TABLE bobr_group_access (
	id SERIAL PRIMARY KEY,
	group_id INTEGER NOT NULL,
	processtype varchar(32) NOT NULL,
	
	CONSTRAINT bobr_group_access_group_id FOREIGN KEY (group_id) REFERENCES bobr_groups (id) ON UPDATE NO ACTION ON DELETE NO ACTION
);
COMMENT ON COLUMN bobr_group_access.id IS 'Id daneho pravidla';
COMMENT ON COLUMN bobr_group_access.group_id IS 'ID groupy na kterou se pravidlo vztahuje';
COMMENT ON COLUMN bobr_group_access.processtype IS 'Nazev processwebu na ktery dana groupa ma pristup udava se string lower a je to nazev bootstrapoveho souboru, ktery spustil cely proces v pripade index.php se zde zada index .';
