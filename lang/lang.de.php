<?php
$lang = array(
  'page_title' => "OXID Kategorie-Master",
  'page_header' => "OXID Kategorie-Master",
  'refresh_button' => "neu laden",
  'oxid_language' => "OXID-Sprache",
  'category_master_language' => "Sprache",
  'article_header' => "Artikel",
  'category_header' => "Kategorien",
  'article_enable_sticky_scrolling' => "mitscrollende Artikelliste",
  'article_show_only_empty_categories' => "nur Artikel ohne zugeordnete Kategorien anzeigen",
  'article_hide_hidden' => "inaktive Artikel ausblenden",
  'article_table_search' => "suchen",
  'article_table_heading_title' => "Titel",
  'article_table_heading_vendor' => "Lieferant",
  'article_table_heading_manufacturer' => "Hersteller",
  'article_table_heading_manufacturer_artnum' => "Hersteller-Art.-Nr.",
  'article_table_heading_artnum' => "Artikelnummer",
  'article_table_heading_ean' => "EAN",
  'article_table_heading_dist_ean' => "Hersteller EAN",
  'article_table_heading_price' => "Preis",
  'article_table_heading_tprice' => "UVP",
  'article_table_heading_bprice' => "Einkaufspreis",
  'article_table_heading_oxinsert' => "angelegt am",
  'article_table_show_only_products_from_cat' => "alle Artikel aus der Kategorie",
  'category_search' => 'Suchen',
  'category_modal_legend' => 'Kategorien',
  'categories_hidden_warning_heading' => 'Achtung',
  'categories_close_all' => 'alle schließen',
  'modal_close' => 'Schließen',
  'categories_hidden_warning_text' => 'Die ausgewählten Artikel befinden sich in Kategorien, welche momentan geschlossen oder aufgrund der Suche ausgeblendet sind.',
  'category_modal_data' => 'Dieser Baum zeigt alle Kategorien Ihres OXID Shops an.<br>Wenn Sie auf der linken Seite einen Artikel auswählen, werden im Baum alle Kategorien markiert, denen dieser Artikel zugeordnet ist.<br><br>Die Farben haben folgende Bedeutung:
    <ul>
      <li><span class="category_hidden">Kategorie</span>: diese Kategorie ist versteckt</li>
      <li><span class="category_inactive">Kategorie</span>: diese Kategorie ist inaktiv</li>
      <li><span class="category_hidden category_inactive">Kategorie</span>: diese Kategorie ist inaktiv und versteckt</li>
      <li><span class="main_category category_for_all">Kategorie</span>: die Kategorie ist die <strong>Hauptkategorie</strong> für <strong>alle</strong> ausgewählten Artikel</li>
    <li><span class="main_category">Kategorie</span>: diese Kategorien sind jeweils <strong>Hauptkategorien</strong> für die ausgewählten Artikel, aber die Artikel haben <strong>verschiedene</strong> Hauptkategorien</li>
    <li><span class="category category_for_all">Kategorie</span>: <strong>alle</strong> Artikel sind dieser Kategorie zugeordnet</li>
    <li><span class="category">Kategorie</span>: <strong>einige</strong>, aber nicht alle der ausgewählten Artikel sind dieser Kategorie zugeordnet</li></ul>
    <p>Durch Rechtsklick auf eine Kategorie können Sie in der Artikelliste alle Artikel, die dieser oder einer Unterkategorie zugeordnet sind, anzeigen lassen.',
  'article_modal_legend' => 'Artikel',
  'article_modal_data' => 'Diese Tabelle enthält alle Artikel in Ihrem OXID Shop.<br><br>
  Sie können durch Klicken auf den Spaltentitel bestehende Spalten ausblenden oder andere einblenden. Es ist möglich, diese Auflistung nach den einzelnen Spalten zu <strong>filtern</strong>. Auch mehrere Einträge sind möglich.<br>Außerdem kann die Anzeige auf die Artikel beschränkt werden, welche noch keiner einzigen Kategorie zugeordnet sind.<br>
  Auch können Sie inaktive Artikel entweder mit anzeigen lassen oder ausblenden.<br>
  Das alles lässt sich von Ihnen auch beliebig mischen.
  <br>
  <p>Die Tabelle kann <strong>sortiert</strong> werden, indem man auf den Spaltentitel klickt.</p><br>
  <p><strong>Wählen Sie einen Bereich</strong> von Artikel aus, indem Sie mit gedrückter Shift-Taste auf die Artikel klicken. Einer Auswahl können Artikel hinzugefügt oder entfernt werden, wenn man sie mit gedrückter Alt- oder Strg-Taste anklickt.</p>
<p>Auch können Sie über die Spalte <strong>mitscrollende Artikelliste</strong> entscheiden, ob Sie sich mehr Artikel anzeigen lassen können und diesen dann mit der Kategorieleiste mitscrollen lassen oder ob Sie die Auswahl auf eine kleinere Anzahl von Artikeln beschränken und, bei vielen Kategorien, nur die Kategorieleiste zur Kategorieauswahl scrollen lassen.',
  'help_modal_legend' => 'Generelle Hilfe',
  'help_modal_data' => '
    <p>Filtern Sie einfach Ihre Artikel, lassen Sie sich diese dann anzeigen und ordnen Sie diese dann entweder per Drag and Drop (Maustaste gedrückt halten und diese dann auf die entsprechende Kategorie rechts ziehen) oder per Rechtsklick den entsprechenden Kategorien zu.<br>
    Weitere Unterstützung z.B. zum Filtern der Artikel finden Sie unter den kleinen Info-Buttons unter Kategorien oder Artikel.</p>
    <p>Version: %VERSION%</p>',
  'buy_category_admin_modal_legend' => 'Kategorie-Admin nicht gefunden.',
  'buy_category_admin_modal_data' => '<p>Ihre Installation des Kategorie-Admins konnte nicht gefunden werden.</p>
    <p>Mit dem Kategorie-Admin können Sie die Kategorien ihres OXID-Shops verwalten. Sie können neue Kategorien anlegen, bestehende per Drag & Drop umsortieren oder löschen. Außerdem können Sie die Kategoriedetails bearbeiten, zum Beispiel den Titel, die Beschreibung, die Kategoriebilder und Sie können Kategorien aktivieren / deaktivieren und ein- und ausblenden.</p>
    <p>Sie können den Kategorie-Admin unter <a href="http://www.oxid-responsive.com" target="_blank">www.oxid-responsive.com</a> erwerben.</p><br>
    <p>Sollten Sie den Kategorie-Admin bereits installiert haben, überprüfen Sie bitte, dass Sie den richten Pfad in der Konfigurationsdatei (<em>config.php</em>) eingetragen haben.</p>',
    'backup_notice' => '<strong>Achtung:</strong> Bitte fertigen Sie regelmäßig Backups Ihrer Datenbank an.',
    'no_auth_user_error' => '<strong>Achtung!</strong><br>
      Bitte legen Sie aus Sicherheitsgründen einen Passwortschutz mit <em>.htaccess</em> und <em>.htpasswd</em> für das Verzeichnis <em>%DIR%</em> an.',
  );
