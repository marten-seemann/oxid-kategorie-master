<?php
$lang = array(
  'page_title' => "OXID Category Master",
  'page_header' => "OXID Category Master",
  'refresh_button' => "refresh",
  'oxid_language' => "OXID language",
  'category_master_language' => "language",
  'article_header' => "Articles",
  'category_header' => "Categories",
  'article_enable_sticky_scrolling' => "enable sticky scrolling",
  'article_show_only_empty_categories' => "show only articles with empty categories",
  'article_hide_hidden' => "hide inactive articles",
  'article_table_search' => "suchen",
  'article_table_heading_title' => "Title",
  'article_table_heading_manufacturer' => "Manufacturer",
  'article_table_heading_vendor' => "Vendor",
  'article_table_heading_manufacturer_artnum' => "Manufacturer Art. Num.",
  'article_table_heading_artnum' => "Article Number",
  'article_table_heading_ean' => "EAN",
  'article_table_heading_dist_ean' => "Man. EAN",
  'article_table_heading_price' => "Price",
  'article_table_heading_tprice' => "RRP",
  'article_table_heading_bprice' => "purchase price",
  'article_table_heading_oxinsert' => "created on",
  'article_table_show_only_products_from_cat' => "all articles from the category",
  'category_search' => 'Search',
  'category_modal_legend' => 'Category Tree',
  'categories_hidden_warning_heading' => 'Warning',
  'categories_hidden_warning_text' => 'Product selection matches closed or hidden categories.',
  'categories_close_all' => 'Close all',
  'modal_close' => 'Close',
  'category_modal_data' => 'This tree shows the categories of your OXID shop.<br>Select an article in the table on the left, and the tree will highlight the categories this article is assigned to.<br><br>The colors mean:
    <ul>
    <li><span class="category_hidden">category</span>: this category is hidden</li>
    <li><span class="category_inactive">category</span>: this category is inactive</li>
    <li><span class="category_hidden category_inactive">category</span>: this category is hidden and inactive</li>
    <li><span class="main_category category_for_all">category</span>: the category is the <strong>main category</strong> for <strong>all</strong> selected articles</li>
    <li><span class="main_category">category</span>: those categories are <strong>main categories</strong> for the selected articles, but the selected articles have <strong>different</strong> main categories</li>
    <li><span class="category category_for_all">category</span>: <strong>all</strong> articles are assigned to this category</li>
    <li><span class="category">category</span>: <strong>some</strong> of the selected articles are assigned to this category</li></ul>
    <p>By right clicking on a category you can choose to show all products that are assigned to this category or any of its subcategories.</p>',
  'article_modal_legend' => 'Article List',
  'article_modal_data' => '<p>This list contains all the articles available in your OXID shop.</p>
    <p>By clicking on the column title you can hide columns or show additional ones. You can <strong>filter</strong> the list by searching through the individual columns.<br>You can also choose to show products that do not yet have any category assigned.</p>
    <br><p><strong>Order</strong> the table by clicking in the table header.</p>
    <p><strong>Select a range</strong> by Shift-clicking on an article. <strong>Add</strong> or <strong>remove</strong> articles by clicking while holding down the Alt key.</p>
    <p>By enabling / disabling<strong> sticky scrolling</strong> you have the possibility to choose if the article list follows your scrolling or not. This might be useful if you plan to assign many products at once.</p>',
    'help_modal_legend' => 'General Help',
    'help_modal_data' => '
      <p><p>At first, you can <strong>filter</strong> the article list to show the articles which you want to assign. Then <strong>assign</strong> them to the categories either via Drag and Drop (click on them and hold the mouse button pressed, then drag them over onto the category in the tree) or via right click on the desired category.<br>
      You will find further information using the small info buttons in the article and the category sections.</p>
      <p>Version: %VERSION%</p>',
  'buy_category_admin_modal_legend' => 'Category Admin not found.',
  'buy_category_admin_modal_data' => '<p>Your installation of the Category Admin could not be found.</p>
    <p>The Category Admin can be used to create, delete and move categories for your OXID shop. Furthermore, you can edit the category details, e.g. the title, the description or you can activate / deactivate and hide / show a category.</p>
    <p>The Category Admin is available at <a href="https://shop.oxid-responsive.com/" target="_blank">shop.oxid-responsive.com/</a>.</p><br>
    <p>Should you already have installed the Category Admin, please check if you have specified the correct path in the configuration file (<em>config.php</em>).</p>',
  'backup_notice' => '<strong>Notice:</strong> Please backup your database on a regular basis.',
  'no_auth_user_error' => '<strong>Error!</strong><br>
    For security reasons, please enable password protection with <em>.htaccess</em> and <em>.htpasswd</em> for the directory <em>%DIR%</em>.',
);
