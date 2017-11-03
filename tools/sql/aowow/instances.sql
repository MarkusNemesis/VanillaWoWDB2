/* Types:
 4 - Arena
 3 - Battleground
 2 - Raid
 1 - Instance */

-- Map 'Ahn'Qiraj Temple'[531]  Area 'Ahn'Qiraj'[3428] -- a buggy map image
UPDATE aowow_zones SET /*x_min=-9775, y_min=802, x_max=-7528, y_max=2319,*/ type = 2 WHERE mapID=531 AND areatableID=3428;

-- Map 'Black Temple'[564]  Area 'Black Temple'[3959] -- a map image is the problem
UPDATE aowow_zones SET /*x_min=110, y_min=1, x_max=1000, y_max=1290,*/ type = 2 WHERE mapID=564 AND areatableID=3959;

-- Map 'Blackfathom Deeps'[48]  Area 'Blackfathom Deeps'[719]
UPDATE aowow_zones SET x_min=-903, y_min=-615, x_max=-124, y_max=554, type = 1 WHERE mapID=48 AND areatableID=719;

-- Map 'Blackrock Depths'[230]  Area 'Blackrock Depths'[1584] -- The map is rotated a bit
UPDATE aowow_zones SET /*x_min=254, y_min=-1333, x_max=1496, y_max=775,*/ type = 1 WHERE mapID=230 AND areatableID=1584;

-- Map 'Blackrock Spire'[229]  Area 'Blackrock Spire'[1583] -- It's impossible to scale it anyway
UPDATE aowow_zones SET /*x_min=-250, y_min=-650, x_max=300, y_max=-50,*/ type = 1 WHERE mapID=229 AND areatableID=1583;

-- Blackwing Lair
UPDATE aowow_zones SET type = 2 WHERE mapID=469 AND areatableID=2677;

-- Map 'Dire Maul'[429]  Area 'Dire Maul'[2557]
UPDATE aowow_zones SET x_min=-280, y_min=-900, x_max=980, y_max=990, type = 1 WHERE mapID=429 AND areatableID=2557;

-- Map 'Gnomeregan'[90]  Area 'Gnomeregan'[721] => 'Gnomeregan'[133]
UPDATE aowow_zones SET areatableID=133, x_min=-940, y_min=-240, x_max=-212, y_max=854, type = 1 WHERE mapID=90 AND areatableID=721;

-- Map 'Maraudon'[349]  Area 'Maraudon'[2100]
UPDATE aowow_zones SET x_min=-224, y_min=-1292, x_max=1235, y_max=871, type = 1 WHERE mapID=349 AND areatableID=2100;

-- Map 'Molten Core'[409]  Area 'Molten Core'[2717]
UPDATE aowow_zones SET x_min=456, y_min=-1435, x_max=1274, y_max=-210, type = 2 WHERE mapID=409 AND areatableID=2717;

-- Onyxia's Lair
UPDATE aowow_zones SET type = 2 WHERE mapID=249 AND areatableID=2159;

-- Map 'Ragefire Chasm'[389]  Area 'Ragefire Chasm'[2437]
UPDATE aowow_zones SET x_min=-455, y_min=-285, x_max=55, y_max=460, type = 1 WHERE mapID=389 AND areatableID=2437;

-- Map 'Razorfen Downs'[129]  Area 'Razorfen Downs'[722]
UPDATE aowow_zones SET x_min=2271, y_min=592, x_max=2643, y_max=1156, type = 1 WHERE mapID=129 AND areatableID=722;

-- Map 'Razorfen Kraul'[47]  Area 'Razorfen Kraul'[491] -- (map image is the problem)
UPDATE aowow_zones SET /*x_min=1850, y_min=1361, x_max=2350, y_max=2050,*/ type = 1 WHERE mapID=47 AND areatableID=491;

-- Map 'Ruins of Ahn'Qiraj'[509]  Area 'Ruins of Ahn'Qiraj'[3429]
UPDATE aowow_zones SET x_min=-9891, y_min=594, x_max=-8376, y_max=2883, type = 2 WHERE mapID=509 AND areatableID=3429;

-- Map 'Scholomance'[289]  Area 'Scholomance'[2057]
UPDATE aowow_zones SET x_min=-100, y_min=-400, x_max=345, y_max=266, type = 1 WHERE mapID=289 AND areatableID=2057;

-- Map 'Stratholme'[329]  Area 'Stratholme'[2017]
UPDATE aowow_zones SET x_min=3362, y_min=-3978, x_max=4194, y_max=-2746, type = 1 WHERE mapID=329 AND areatableID=2017;

-- Map 'Sunken Temple'[109]  Area 'The Temple of Atal'Hakkar'[1417]
-- (second floor is mapped on the first one)
UPDATE aowow_zones SET x_min=-765, y_min=-170, x_max=-202, y_max=639, type = 1 WHERE mapID=109 AND areatableID=1417;

-- Map 'Stormwind Stockade'[34]  Area 'The Stockade'[717]
UPDATE aowow_zones SET x_min=-35, y_min=-195, x_max=223, y_max=195, type = 1 WHERE mapID=34 AND areatableID=717;

-- Map 'Coilfang: The Underbog'[546]  Area 'The Underbog'[3716]
UPDATE aowow_zones SET x_min=-160, y_min=-669, x_max=429, y_max=256, type = 1 WHERE mapID=546 AND areatableID=3716;

-- Map 'Uldaman'[70]  Area 'Uldaman'[1337]
UPDATE aowow_zones SET x_min=-385, y_min=-179, x_max=190, y_max=670, type = 1 WHERE mapID=70 AND areatableID=1337;

-- Map 'Zul'Farrak'[209]  Area 'Zul'Farrak'[1176] => 'Zul'Farrak'[978]
UPDATE aowow_zones SET areatableID=978, x_min=1200, y_min=370, x_max=2080, y_max=1700, type = 1 WHERE mapID=209 AND areatableID=1176;

-- Map 'Zul'Gurub'[309]  Area 'Zul'Gurub'[1977] => 'Zul'Gurub'[19]
UPDATE aowow_zones SET areatableID=19, x_min=-12490, y_min=-2440, x_max=-11380, y_max=-780, type = 2 WHERE mapID=309 AND areatableID=1977;

-- Map 'Scarlet Monastery'[189]  Area 'Scarlet Monastery'[796]
-- (map image is the reason of these complex queries)
UPDATE aowow_zones SET x_min=1 WHERE mapID=189 AND areatableID=796;
UPDATE aowow_zones SET x_min=0 WHERE mapID=189 AND areatableID=796 LIMIT 1;
DELETE FROM aowow_zones WHERE mapID=189 AND areatableID=796 AND x_min=1;
INSERT INTO aowow_zones SELECT * FROM aowow_zones WHERE mapID=189 AND areatableID=796;
INSERT INTO aowow_zones SELECT * FROM aowow_zones WHERE mapID=189 AND areatableID=796;
UPDATE aowow_zones SET x_min=-68, y_min=-752, x_max=318, y_max=-175 WHERE mapID=189 AND areatableID=796 AND x_min=0 LIMIT 1;
UPDATE aowow_zones SET x_min=1580, y_min=-510, x_max=2055, y_max=200 WHERE mapID=189 AND areatableID=796 AND x_min=0 LIMIT 1;
UPDATE aowow_zones SET x_min=472, y_min=935, x_max=1277, y_max=2223 WHERE mapID=189 AND areatableID=796 AND x_min=0 LIMIT 1;
UPDATE aowow_zones SET x_min=1620, y_min=660, x_max=2295, y_max=1575 WHERE mapID=189 AND areatableID=796 AND x_min=0 LIMIT 1;

-- Map 'Wailing Caverns'[43]  Area 'Wailing Caverns'[718]
UPDATE aowow_zones SET x_min=-413, y_min=-380, x_max=211, y_max=551 WHERE mapID=43 AND areatableID=718;
