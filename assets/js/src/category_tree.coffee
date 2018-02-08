# handle both the language selectors.
#
"use strict"

class window.CategoryTree

  # Constructor
  #
  # make a tree using the jQuery jstree plugin
  #
  # does not call the *initialize* function!
  #
  # @param [jQuery] dom_elem the DOM element where the tree should be created
  # @see http://www.jstree.com
  constructor: (@dom_elem) ->
    @selected = [] # contains all selected (= highlighted elements in the tree)
    @notifications = document.notification_handler
    @loading = @notifications.loading

  # initialize the category tree
  #
  # does the whole configuration necessary for the jstree plugin
  #
  # calls *addListeners* at the end
  initialize: ->
    @article_table = document.article_table
    # initialize the jstree
    # must be placed at the bottom
    starttime = new Date().getTime()
    @loading.category_tree = true
    @notifications.checkDisplayLoading()
    @dom_elem.jstree
      types:
        valid_children: [ "root" ]
        types:
          root:
            icon:
              image: "jstree/_docs/_drive.png"
      ui:
        select_multiple_modifier: "on"
        disable_selecting_children: false
        selected_parent_close: false
        selected_parent_open: true
      search: show_only_matches: true
      core: animation: 100
      themes: theme: 'default'
      # disable moving tree elements completely
      crrm:
        move:
          check_move: (m) -> false
      # use the cookies plugin to save which nodes where opened the last time, but not which were selected
      cookies:
        save_selected: false
      json_data:
        ajax:
          url: "ajax/categories.php"
          cache: false
          progressive_render: false
          data: (n) ->
            id: if n.attr then n.attr("id") else 0
          complete: =>
            @loading.category_tree = false
            @notifications.checkDisplayLoading()
      dnd:
        open_timeout: 500
        drag_finish: (data) ->
          $("body").css('cursor', 'auto')
          $(data.r).children("a").trigger "contextmenu"
      plugins: [ "json_data", "ui", "themes", "search", "dnd", "crrm", "cookies" ]

    endtime = new Date().getTime()
    # console.log "Time to build the tree: "+(endtime-starttime)+" ms"
    @addListeners()

  # add listeners
  #
  #  adds lots of listeners to handle click, selection, deselecting etc. of nodes
  #
  # calls *addContextMenu* at the end
  addListeners: ->
    # close all button: on click close *all* nodes in the tree
    $('#tree_close_all input[type="button"]').bind 'click', =>
      @dom_elem.jstree("close_all", -1)
      false

    # only handle right click on tree nodes
    @dom_elem.filter("li a").bind 'mousedown', (event) =>
      return if event.button != 2

    # modify the default "deselect_all" behaviour of the jstree
    # we need to do this because the main_category is sometimes added as a CSS class when selecting nodes, thus it must also be deselected
    @dom_elem.bind 'deselect_all.jstree', (event,data) =>
      @dom_elem.find(".main_category, .category_for_all").removeClass "main_category category_for_all"

    # show long category name - if category name was shortened - on hover
    @dom_elem.bind 'hover_node.jstree', (event, data) =>
      node = data.args[0]
      name_long = $(node).parent("li").data "name_long"
      return false if !!name_long && name_long.length == 0
      $(node).tooltip title: name_long, placement: 'left'
      $(node).tooltip 'show'

    # disable selecting nodes (by clicking)
    # only selection should be possible by the script, so we disable it here
    @dom_elem.bind 'select_node.jstree', (event, data) =>
      # data.args.length == 3 only when the node was clicked
      # if the node was selected by the script, we have data.args.length == 1
      # why? nobody knows :-)
      if data.args.length == 3 then @dom_elem.jstree('deselect_node', $(data.args[0]))

    # disable deselecting nodes (by clicking)
    # only deselection should be possible by the script, so we disable it here
    @dom_elem.bind 'deselect_node.jstree', (event, data) =>
      # check if the clicked node is already selected
      return false unless $(data.args[0]).is 'li' # avoid some strange, annoying error message
      contained = false
      for elem in @selected
        if $(elem).attr('id') == data.args[0].attr('id') then contained = true
      # if the clicked node was already selected, reselect it
      # this is necessary because the jstree first deselects it, when clicked, and then fires this event
      if contained then @dom_elem.jstree('select_node', $(data.args[0]))

    # fixes a faulty behaviour occuring in all browsers: if the jstree fits on one page on the screen, sticky scrolling does not work and leads to a "bouncing" datatable
    # fix: disable the sticky scrolling selector if the tree is so small that it fits one page on the screen
    # filter article list according to category given as a GET parameter
    @dom_elem.bind 'after_open.jstree after_close.jstree loaded.jstree', (event,data) =>
      # height of the jstree + (absolute) y-position of the jstree - (absolute) y-position of the topmost element when sticky scrolling is enabled
      tree_bottom_y = @dom_elem.height() + @dom_elem.offset().top - $("#article_wrapper").position().top
      if tree_bottom_y < $(window).height()
        $('#sticky_scrolling_selector').hide()
        document.sticky_scrolling = false
      else
        $('#sticky_scrolling_selector').show()
        # ugly workaround to trigger the body of the click-handler :-)
        # this then determines how to set the variable sticky_scrolling
        $('#sticky_scrolling').triggerHandler 'click'
        $('#sticky_scrolling').triggerHandler 'click'

      # filter the article list to show only products from a certain category if given as a GET parameter
      if $.urlParam('only_cat').length > 0 then @article_table.enableCategoryFiltering $.urlParam('only_cat')

    # show the subtree of a found element
    # default behaviour of jstree is to hide *all* non-matched elements
    # @param [jQuery] elem the tree node whose children (and children of children and so on) should be shown
    showSubtree = (elem) =>
      # correct the appearance of the jstree by adding the jstree-last CSS class to the last elements of each subtree
      # needed when manually showing / hiding nodes
      correctNode = (elem) ->
        last = elem.children("li").eq(-1)
        last.addClass("jstree-last")
        children = elem.children("li")
        correctNode($(child).children("ul:first")) for child in children

      elem.siblings("ul:first").find("li").show()
      correctNode elem.siblings("ul:first")

    # search the jstree
    $('#tree_search').typeWatch
      callback: (data, elem) =>
        @loading.category_tree = true
        starttime = new Date().getTime()
        @dom_elem.jstree("search", data)
        endtime = new Date().getTime()
        # console.log "Time to search the tree: "+(endtime-starttime)+" ms"
        # treeelem.children("ul").children("li").eq(-1).addClass("jstree-last")
        showSubtree $(".jstree-search")
        @highlightCategories
        @loading.category_tree = false
      wait: 600,
      highlight: true
      captureLength: 0

    @addContextMenu()

  # add the context menu appearing when dropping elements on the tree or when right clicking on a tree node
  #
  # using the jQuery ContextMenu plugin
  # @see http://medialize.github.com/jQuery-contextMenu/
  addContextMenu: ->
    contextmenu_items =
      "main_category":
        name: lang.contextmenu_set_main_category
        icon: "heart"
      "redefine":
        name: lang.contextmenu_redefine
        icon: "legal"
      "add":
        name: lang.contextmenu_add
        icon: "plus"
      "delete":
        name: lang.contextmenu_remove
        icon: "trash"
      "sep1": "---------"
      "contained_products":
        name: lang.contextmenu_only_products_from_this_cat
        icon: "filter"
      "open_category_admin":
        name: lang.contextmenu_open_category_admin
        icon: "external-link"
      "sep2": "---------"
      "quit":
        name: lang.contextmenu_quit
        icon: "remove"

    # drag context menu (is shown when dropped)
    $.contextMenu
      selector: "#{@dom_elem.selector} li a"
      build: (trigger, event) =>
        # catch some strange behaviour when rightclicking when contextMenu is already enabled
        return false if !event.originalEvent && event.pageX
        # make a copy of contextmenu_items. thus, we do not change contextmenu_items itself
        contextmenu_items_tmp = {}
        $.extend(contextmenu_items_tmp,contextmenu_items)
        # disable the function to show only products from this category if we are showing only products without a category
        contextmenu_items_tmp.contained_products.disabled = !!$("#show_only_empty_categories").is(':checked')
        unless event.which? # are we dealing with a drag&drop event?
          contextmenu_items_tmp.contained_products.disabled = true # disable the function to show only products from this category
          contextmenu_items_tmp.open_category_admin.disabled = true
        # enable / disable options according to the selected products
        if @article_table.getSelectedRows().length is 0 # if no products are selected, we cannot do anything with the categories
          contextmenu_items_tmp.add.disabled = true
          contextmenu_items_tmp.delete.disabled = true
          contextmenu_items_tmp.redefine.disabled = true
          contextmenu_items_tmp.main_category.disabled = true
        else
          # if @selected.length is 1 then contextmenu_items_tmp.redefine.disabled = true # if only one category is highlighted, you cannot set this one as the only category (since it is exacty this already) DOES NOT WORK
          contextmenu_items_tmp.add.disabled = if trigger.hasClass('category_for_all') then true else false
          contextmenu_items_tmp.main_category.disabled = if trigger.hasClass('category_for_all main_category') then true else false
          if event.button != 2 then delete contextmenu_items_tmp.delete       # show the delete option only on right click
          else
            # disable the delete option if
            # 1. the selected category is not highlighted
            # 2. the selected category is a main category
            contextmenu_items_tmp.delete.disabled = if (@dom_elem.jstree('is_selected',trigger) && !trigger.hasClass('main_category')) then false else true
        # finised enabling / disabling
        items: contextmenu_items_tmp, callback: (key, options) =>
          newcat = options.$trigger.parent("li").attr('id').substr(5)
          if key is "quit" then return true
          else if key is "contained_products" then @article_table.enableCategoryFiltering newcat
          else if key is "open_category_admin"
            if document.config.category_admin_path then $.open_in_new_tab "#{document.config.category_admin_path}?cat=#{newcat}"
            else $('#modal_buy_category_admin').modal 'toggle' #show the dialog explaining how to buy the Category Admin
          else
            ids = ( $(elem).attr('id') for elem in @article_table.getSelectedRows() )
            $.ajax
              type: "POST"
              url: "ajax/assign.php"
              dataType: "json"
              cache: false
              data:
                mode: key
                ids: ids.join(",")
                new_cat: newcat
              error: (data) =>
                @notifications.showError lang.error_categories_updated
              success: (data, data2, data3) =>
                if data == "false" || data == "" then @notifications.showError lang.error_categories_updated
                else
                  @article_table.updateCategories data
                  @notifications.showSuccess lang.sucess_categories_updated


  # open tree nodes according to the selected rows in the table
  #
  # @param [Boolean] open_nodes open subnodes if parent node is selected. **Caution**: open_nodes = false needs modified *jstree.js*
  # @todo write a method *selectNode* or sth like this to clean up this function
  highlightCategories:  ( open_nodes = true ) ->
    settings = @dom_elem.jstree "get_settings"
    if settings.ui.selected_parent_open != open_nodes
      settings.ui.selected_parent_open = if open_nodes then true else false
      # Caution: needs modified jstree!!!
      @dom_elem.jstree("set_settings", settings)

    invisible_cats = false
    categories = @article_table.getCategoriesOfSelected()
    @dom_elem.jstree "deselect_all"
    # highlight all categories that are assigned
    num_rows_selected = @article_table.getSelectedRows().length
    for cat in categories
      if cat.length == 0 then continue
      node = $("#node_" + $.escapeId(cat))
      @dom_elem.jstree("select_node", node)
      if node.is(':hidden') then invisible_cats = true
      if $.countOccurences(categories,cat) == num_rows_selected then node.children("a").addClass 'category_for_all'
    categories_main = @article_table.getMainCategoriesOfSelected()
    # now add the highlighting for the main categories
    for cat in categories_main
      if cat.length == 0 then continue
      node = $("#node_" + $.escapeId(cat))
      node.children("a").addClass 'main_category'
      if $.countOccurences(categories_main,cat) == num_rows_selected then node.children("a").addClass 'category_for_all'
    if invisible_cats then $("#search_hidden_cat_warning").show() else $("#search_hidden_cat_warning").hide()
    @selected = @dom_elem.jstree 'get_selected'


  # get the category names of all parent categories of a category
  #
  # the category itself will be included in the name listing
  # the order is as shown in the category tree, thus the first element returned is the name of topmost category
  #
  # @param [String] cat_id the OXID of the category
  # @return [Array<String>] the names of all parent categories starting with the topmost category
  getParentsNames: (cat_id) ->
    cat_id = $.escapeId cat_id
    names = ($.trim $(node).children("a").text() for node in $("#node_#{cat_id}").add($("#node_#{cat_id}").parents().filter("li")))

  # deselect all selected tree elements
  deselectAll: ->
    @dom_elem.jstree "deselect_all"
