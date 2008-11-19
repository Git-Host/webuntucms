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