-- this view is required to exist. without it, no articles will be shown in the article list
CREATE VIEW view_oxarticles_manufacturers AS SELECT oxarticles.*, oxmanufacturers.OXTITLE AS manufacturer_OXTITLE, oxmanufacturers.OXTITLE_1 AS manufacturer_OXTITLE_1, oxmanufacturers.OXTITLE_2 AS manufacturer_OXTITLE_2, oxvendor.OXTITLE AS vendor_OXTITLE, oxvendor.OXTITLE_1 AS vendor_OXTITLE_1, oxvendor.OXTITLE_2 AS vendor_OXTITLE_2 FROM oxarticles LEFT JOIN oxmanufacturers ON oxarticles.OXMANUFACTURERID = oxmanufacturers.OXID LEFT JOIN oxvendor ON oxarticles.OXVENDORID = oxvendor.OXID WHERE OXPARENTID = "";

-- set indexes in the oxarticles table
-- this is optional, but will increase the performance significantly, depending on the number of articles in the databse
ALTER TABLE `oxarticles` ADD INDEX `OXEAN` ( `OXEAN` );
ALTER TABLE `oxarticles` ADD INDEX `OXTITLE` ( `OXTITLE` );
ALTER TABLE `oxarticles` ADD INDEX `OXMPN` ( `OXMPN` );
