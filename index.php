<?php
require("inc/includes.inc.php");
?>
<!DOCTYPE html>
<html lang="<?php echo $lang->getLanguageCode(); ?>">
<head>
  <meta charset="UTF-8">
  <title><?php echo $lang->page_title; ?></title>
  <link href="assets/bootstrap/css/bootstrap.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/font-awesome.css">
  <link rel="stylesheet" href="assets/css/jquery.noty.css">
  <link rel="stylesheet" href="assets/css/jquery.noty_theme_twitter.css">
  <link rel="stylesheet" href="assets/css/demo_table.css">
  <link rel="stylesheet" href="assets/css/jquery.contextMenu.css">
  <link rel="stylesheet" href="assets/css/jquery.ddslick.css">
  <link rel="stylesheet" href="assets/css/styles.css">
  <script src="assets/js/jquery-1.8.1.min.js"></script>
  <script src="assets/bootstrap/js/bootstrap.min.js"></script>
  <script src="assets/js/jquery.typewatch.js"></script>
  <script src="assets/js/jquery.noty.js"></script>
  <script src="assets/js/jquery.cookie.js"></script>
  <script src="assets/js/jquery.media.js"></script>
  <script src="assets/js/jquery.idle-timer.js"></script>
  <script src="assets/js/noty_config.js"></script>
  <script src="assets/js/jquery.contextMenu.js"></script>
  <script src="assets/jstree/jquery.jstree.js"></script>
  <script src="assets/datatables/media/js/jquery.dataTables.js"></script>
  <script src="assets/datatables/media/js/DT_bootstrap.js"></script>
  <script src="assets/js/src/article_table.js"></script>
  <script src="assets/js/src/notification_handler.js"></script>
  <script src="assets/js/src/language_handler.js"></script>
  <script src="assets/js/src/category_tree.js"></script>
  <script src="assets/js/src/main.js"></script>
  <script src="assets/js/jquery.timer.js"></script>
  <script src="assets/js/jquery.ddslick.js"></script>
  <script src="assets/js/lang.js.php?lang=<?php echo $lang->getLanguageCode(); ?>"></script>
</head>
<body>
<script>
  document.config = { category_update_time: <?php echo $config['category_update_interval']*1000; ?>,
  category_admin_path: <?php if(isset($config['category_admin_path']) AND file_exists($config['category_admin_path'])) echo "'".$config['category_admin_path']."'"; else echo "false"; ?> };
</script>

  <div class="container-fluid">
    <div class="row">
      <header>
        <div class="col-sm-12">
        <h1><?php echo $lang->page_header; ?><span id="help"><i class="icon-question-sign icon-small"></i></span></h1>
        <div id="header_functions_right">
          <div><a class="btn btn-default" id="refresh"><i class="icon-refresh"></i> <?php echo $lang->refresh_button; ?></a></div>
          <div id="category_master_language_switcher_wrapper">
            <div><?php echo $lang->category_master_language; ?>:</div>
            <div id="category_master_language_switcher" class="language_switcher">
              <form action="#">
                <select>
                <?php
                $languages = LanguageHelper::availableLanguages();
                foreach($languages as $key) {
                  echo "<option value='$key' data-imagesrc='assets/flags/".$lang->getFlagCode($key).".png' ";
                  if($key == $lang->getLanguageCode()) echo ' selected="selected"';
                  echo ">".$lang->getLanguageName($key)."</option>\n";
                }
                ?>
                </select>
              </form>
            </div>
          </div>
          <div id="oxid_language_switcher_wrapper">
          <?php
          $oxid_languages = $lang->getOxidLanguages();
          if(count($oxid_languages) > 0 ) {
          echo "<div>{$lang->oxid_language}:</div>\n";
          echo "<div id=\"oxid_language_switcher\">\n";
          echo "<form action=\"#\">\n<select class='form-control'>\n";
          foreach($oxid_languages as $key) {
            echo "<option value='$key' data-imagesrc=\"assets/flags/".$lang->getFlagCode($key).".png\" ";
            if(isset($_COOKIE['oxid_language']) AND $key == $_COOKIE['oxid_language']) echo " selected=\"selected\"";
            echo ">".$lang->getLanguageName($key)."</option>\n";
          }
          echo "</select></form>\n";
          echo "</div>";
          }
          ?>
          </div>
        </div>
        </div>
      </header>

      <div class="modal fade" id="modal_help">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title"><?php echo $lang->help_modal_legend; ?></h4>
            </div>
            <div class="modal-body">
              <?php echo str_replace('%VERSION%', $version, $lang->help_modal_data); ?>
            </div>
            <div class="modal-footer">
              <a href="#" class="btn btn-default" data-dismiss="modal"><?php echo $lang->modal_close; ?></a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <form id="all-form">
      <div class="row">
        <div class="col-sm-9">
          <div id="width:65%">
            <section id="articles">
              <h2 id="heading_articles"><?php echo $lang->article_header; ?><span id="articles-info"><i class="icon-info-sign"></i></span></h2>
              <div id="sticky_scrolling_selector" style=""><input type="checkbox" id="sticky_scrolling" checked="checked" /> <?php echo $lang->article_enable_sticky_scrolling; ?></div>
              <div class="modal fade" id="modal_articles">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                      <h4 class="modal-title"><?php echo $lang->article_modal_legend; ?></h4>
                    </div>
                    <div class="modal-body">
                      <?php echo $lang->article_modal_data; ?>
                    </div>
                    <div class="modal-footer">
                      <a href="#" class="btn btn-default" data-dismiss="modal"><?php echo $lang->modal_close; ?></a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="clearfix"></div>

              <?php if(file_exists("inc/demo_note.inc.php")) { require("inc/demo_note.inc.php"); } ?>
              <?php if(!isset($config['disable_backup_notice']) OR !$config['disable_backup_notice']) {
                echo "<div class=\"alert alert-warning alert-dismissable\">
                  <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
                    {$lang->backup_notice}
                </div>";
                }
              ?>

              <div id="article_wrapper">
                <div id="no_cats_proto" style="display:hidden"><?php echo $lang->article_show_only_empty_categories; ?>: <input type="checkbox" name="show_only_empty_categories" id="show_only_empty_categories" /></div>
                <div id="hidden_articles_proto" style="display:hidden"><?php echo $lang->article_hide_hidden; ?>: <input type="checkbox" name="hide_inactive_articles" id="hide_inactive_articles" checked="checked" /></div>
                <div id="only_products_from_cat_proto" style="display:hidden"><div class="well well-small"><button type="button" class="close" data-dismiss="alert">×</button><?php echo $lang->article_table_show_only_products_from_cat; ?>:<span></span></div></div>

                <table class="table table-striped table-bordered" id="products">
                  <thead>
                    <tr>
                      <td><input type="text" class="search_init form-control" name="search_title" placeholder="<?php echo $lang->article_table_search; ?>" /></td>
                      <td><input type="text" class="search_init form-control" name="search_manufacturer" placeholder="<?php echo $lang->article_table_search; ?>" style="width:110px" /></td>
                      <td><input type="text" class="search_init form-control" name="search_manufacturer" placeholder="<?php echo $lang->article_table_search; ?>" style="width:110px" /></td>
                      <td><input type="text" class="search_init form-control" name="search_prodartnum" style="width:110px" placeholder="<?php echo $lang->article_table_search; ?>" /></td>
                      <td><input type="text" class="search_init form-control" name="search_artnum" style="width:80px" placeholder="<?php echo $lang->article_table_search; ?>" /></td>
                      <td><input type="text" class="search_init form-control" name="search_ean" style="width:100px" placeholder="<?php echo $lang->article_table_search; ?>" /></td>
                      <td><input type="text" class="search_init form-control" name="search_dist_ean" style="width:100px" placeholder="<?php echo $lang->article_table_search; ?>" /></td>
                      <td><input type="text" class="search_init form-control" name="" style="display: none;" /></td>
                      <td><input type="text" class="search_init form-control" name="" style="display: none;" /></td>
                      <td><input type="text" class="search_init form-control" name="" style="display: none;" /></td>
                      <td><input type="text" class="search_init form-control" name="search_dist_oxinsert" style="width:80px" placeholder="<?php echo $lang->article_table_search; ?>" /></td>
                      <td style="display:none"></td>
                    </tr>
                    <tr>
                      <th style="width:30%"><?php echo $lang->article_table_heading_title; ?></th>
                      <th style="width:13%"><?php echo $lang->article_table_heading_manufacturer; ?></th>
                      <th style="width:13%"><?php echo $lang->article_table_heading_vendor; ?></th>
                      <th style="width:13%"><?php echo $lang->article_table_heading_manufacturer_artnum; ?></th>
                      <th style="width:12%"><?php echo $lang->article_table_heading_artnum; ?></th>
                      <th style="width:12%"><?php echo $lang->article_table_heading_ean; ?></th>
                      <th style="width:12%"><?php echo $lang->article_table_heading_dist_ean; ?></th>
                      <th style="width:6%"><?php echo $lang->article_table_heading_price; ?></th>
                      <th style="width:6%"><?php echo $lang->article_table_heading_tprice; ?></th>
                      <th style="width:6%"><?php echo $lang->article_table_heading_bprice; ?></th>
                      <th style="width:9%"><?php echo $lang->article_table_heading_oxinsert; ?></th>
                      <th style="display:none">Categories</th>
                    </tr>
                  </thead>
                  <tbody>

                  </tbody>
                </table>
              </div>
            </section>
          </div>
        </div>
        <div class="col-sm-3">
          <section id="categories">
            <h2><?php echo $lang->category_header; ?><span id="category-info"><i class="icon-info-sign"></i></span></h2>
            <div class="modal fade" id="modal_categories">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><?php echo $lang->category_modal_legend; ?></h4>
                  </div>
                  <div class="modal-body">
                    <?php echo $lang->category_modal_data; ?>
                  </div>
                  <div class="modal-footer">
                    <a href="#" class="btn btn-default" data-dismiss="modal"><?php echo $lang->modal_close; ?></a>
                  </div>
                </div>
              </div>
            </div>

            <input type="text" id="tree_search" class="form-control" placeholder="<?php echo $lang->category_search; ?>" />
            <div id="tree_close_all"><input type="button" class="btn btn-default btn-small pull-right" value="<?php echo $lang->categories_close_all; ?>" /></div>
            <div id="category_tree">
            </div>
            <div id="search_hidden_cat_warning" class="alert alert-block">
              <a class="close" data-dismiss="alert" href="#">&times;</a>
              <h4 class="alert-heading"><?php echo $lang->categories_hidden_warning_heading; ?></h4>
              <?php echo $lang->categories_hidden_warning_text; ?>
            </div>
          </section>
        </div>
      </div>
    </form>

    <div class="modal fade" id="modal_buy_category_admin">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title"><?php echo $lang->buy_category_admin_modal_legend; ?></h4>
          </div>
          <div class="modal-body">
            <?php echo $lang->buy_category_admin_modal_data; ?>
          </div>
          <div class="modal-footer">
            <a href="#" class="btn btn-default" data-dismiss="modal"><?php echo $lang->modal_close; ?></a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php if(file_exists("inc/tracker.inc.php")) { require("inc/tracker.inc.php"); } ?>
</body>
</html>

