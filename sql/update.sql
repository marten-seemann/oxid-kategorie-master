-- this view is required to exist. without it, no articles will be shown in the article list
DROP VIEW view_oxarticles_manufacturers;
CREATE VIEW view_oxarticles_manufacturers AS SELECT oxarticles.*, oxmanufacturers.OXTITLE AS manufacturer_OXTITLE, oxmanufacturers.OXTITLE_1 AS manufacturer_OXTITLE_1, oxmanufacturers.OXTITLE_2 AS manufacturer_OXTITLE_2, oxvendor.OXTITLE AS vendor_OXTITLE, oxvendor.OXTITLE_1 AS vendor_OXTITLE_1, oxvendor.OXTITLE_2 AS vendor_OXTITLE_2 FROM oxarticles LEFT JOIN oxmanufacturers ON oxarticles.OXMANUFACTURERID = oxmanufacturers.OXID LEFT JOIN oxvendor ON oxarticles.OXVENDORID = oxvendor.OXID WHERE OXPARENTID = "";