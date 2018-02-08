"use strict"

# handle the article table.
#
class window.ArticleTable
  # Constructor
  #
  # build the article table using the jQuery DataTables plugin
  #
  # does not call the *initialize* function!
  #
  # @param [jQuery] dom_elem the DOM element where the table should be created
  # @see http://datatables.net
  constructor: (@dom_elem) ->
    # @treeelem = $("#category_tree") # TODO: remove later by OOP style
    @notifications = document.notification_handler
    @loading = @notifications.loading
    @category_column_index = document.category_column_index
    @last_selected = false
    @classstring_selected = "row_selected jstree-draggable"


  # initialize the article table
  #
  # the whole configuration for the DataTable is done here, including setting all callbacks
  #
  # calls *addListeners* and *initAutoReload* at the end
  initialize: ->
    @tree = document.category_tree
    @oTable = @dom_elem.dataTable
      iDisplayLength: 15
      # needed for twitter bootstrap
      # "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
      # "sDom": "<'row-fluid'<'span12'l>r>t<'row-fluid'<'span6'i><'span6'p>>",
      "sDom": "<'row'<'col-sm-9'<'#no_cats'><'#hidden_articles'>><'col-sm-3'l>r><'#only_products_from_cat'>t<'row'<'col-sm-5'i><'col-sm-7'p>>",
      # sDom: "<'row-fluid'r>t<'row-fluid'<'span6'i><'span6'p>>",
      sPaginationType: "bootstrap"
      sWrapper: "dataTables_wrapper form-inline" # needed for datatables to work with bootstrap.
      oLanguage:
        "sProcessing":   lang.datatables_sProcessing
        "sLengthMenu":   lang.datatables_sLengthMenu
        "sZeroRecords":  lang.datatables_sZeroRecords
        "sInfo":         lang.datatables_sInfo
        "sInfoEmpty":    lang.datatables_sInfoEmpty
        "sInfoFiltered": lang.datatables_sInfoFiltered
        "sInfoPostFix":  lang.datatables_sPostFix
        "sSearch":       lang.datatables_sSearch
        "sInfoThousands": lang.datatables_sInfoThousands
        "sUrl":          "",
        "oPaginate":
          "sFirst":    lang.datatables_sFirst
          "sPrevious": lang.datatables_sPrevious
          "sNext":     lang.datatables_sNext
          "sLast":     lang.datatables_sLast
      # server side data processing
      bProcessing: false # dont show the "Processing" indicator
      bServerSide: true
      bAutoWidth: false
      sProcessing: ""
      aoColumns: [ null, null, null, null, null, null, null, { sClass: 'price', bSearchable: false }, { sClass: 'price', bSearchable: false }, { sClass: 'price', bSearchable: false }, null, null ]
      aaSorting: [[2,"asc"]] # sort by article number
      aLengthMenu: [[10, 15, 20, 25, 50, 100 ], [10, 15, 20, 25, 50, 100 ]]
      sAjaxSource: 'ajax/products.php'
      fnServerParams: (aoData) =>
        # add additional params to the AJAX query
        aoData.push
          name: "only_empty_categories",
          value: if $("#show_only_empty_categories").is(":checked") then "true" else "false"
        aoData.push
          name: "hide_inactive_articles",
          value: if $("#hide_inactive_articles").is(":checked") then "true" else "false"
        aoData.push
          name: "show_only_products_in_cat",
          value: if @dom_elem.data("show_only_products_in_cat")? then @dom_elem.data("show_only_products_in_cat") else ""
      fnServerData: (sSource, aoData, fnCallback) =>
        $.ajax({
          dataType: 'json'
          url: sSource
          data: aoData
          cache: false
          error: (data) =>
            @loading.article_table = false
            @notifications.checkDisplayLoading()
            @notifications.showError lang.error_product_list_load
          success: (json) =>
            @loading.article_table = false
            @notifications.checkDisplayLoading()
            fnCallback(json)
          beforeSend: () =>
            @loading.article_table = true
            @notifications.checkDisplayLoading()
          })
      fnDrawCallback: (oSettings) =>
        # check for page changes
        if oSettings._iDisplayStart != iDisplayStart
          iDisplayStart = oSettings._iDisplayStart
          @tree.deselectAll()
        # end check for page changes
        row_selector = @dom_elem.find('tbody tr')
        row_selector.unbind()
        # hide the category column. this column is only needed to be read by this script, not by the user
        @hideColumn @category_column_index

        # hide user hidden columns (important when reloading the table)
        counter = 0
        for col in @columns
          continue if counter is @category_column_index
          unless col.visible then @hideColumn counter
          counter = counter+1

        row_selector.bind 'click', (event) =>
          target = $(event.currentTarget)
          # handle multiple selections
          if event.ctrlKey || event.altKey then target.toggleClass @classstring_selected
          else if event.shiftKey
            if @last_selected.index() < target.index() then elements = @last_selected.nextUntil target
            else elements = @last_selected.prevUntil target
            elements.add(target).addClass @classstring_selected
            document.getSelection().removeAllRanges()
          else # no modifier key at all
            @getSelectedRows().removeClass @classstring_selected
            target.addClass @classstring_selected
          @last_selected = target
          @tree.highlightCategories()

        row_selector.bind 'mousedown', (event) =>
          if @getSelectedRows().length == 0
            target = $(event.currentTarget)
            target.toggleClass @classstring_selected
            @last_selected = target
            @tree.highlightCategories()
            # @prepareHighlighting()

    # do some html-element moving towards the head of the datatable
    $('#no_cats').html($('#no_cats_proto').html())
    $('#no_cats_proto').html ""
    $('#hidden_articles').html($('#hidden_articles_proto').html())
    $('#hidden_articles_proto').html ""
    $('#only_products_from_cat').html($('#only_products_from_cat_proto').html())
    $('#only_products_from_cat').hide()
    $('#only_products_from_cat_proto').html ""

    # hide columns depending on the values read from the cookie
    # if no cookie exists, show all except the category column
    @columns = []
    if $.cookie("category_master_columns")
      try # catch error if the cookie for some strange reason does not contain valid JSON
        @columns = $.parseJSON($.cookie("category_master_columns"))

    # this code will be executed if 1. no cookie was available or 2. the cookie contained corrupt data for some reason
    # this could for example happen if a new column is added in a new version (such that one has now k+1 columns), and the cookie still contains the object with k columns. this mismatch would behave strange behaviour
    if @dom_elem.find("thead th").length isnt @columns.length
      $.cookie("category_master_columns", null) if $.cookie("category_master_columns") # delete the cookie if it contained corrupt data
      counter = 0
      for elem in @dom_elem.find("thead th")
        @columns.push
          title: $(elem).html()
          visible: if $(elem).hasClass "hidden" then false else true
        @columns[counter].visible = false if $.inArray(counter, document.article_table_hidden_columns) isnt -1 # hide columns that should be hidden by default
        counter++
      # make the table responsive, hide columns on small screens / devices
      if $.media({'max-width' : '1280px'})
        @hideColumn 5 # EAN
      if $.media({'max-width' : '1024px'})
        @hideColumn 3 # Manufacturer Art. Num.

    @addListeners()
    @initializeAutoReload()

  # adds bindings
  #
  # adds bindings for:
  #
  # - the both checkboxes (show only products with empty categories, hide inactive arcticles) above the table
  # - the filtering inputs in the head of the table
  # - the drag & drop notification (the small box showing which rows are selected which follows the cursor)
  addListeners: ->
    $("#show_only_empty_categories").bind 'change', (event) => @reloadData() # trigger ajax reload of table data
    $("#hide_inactive_articles").bind 'change', (event) => @reloadData() # trigger ajax reload of table data

    # column-wise search function
    @dom_elem.find('thead input').typeWatch
      callback: (data, el) =>
        @search($("thead input").index(el), data)
      wait: 600
      highlight: true
      captureLength: 0

    # context menu for hiding / showing columns in the table
    $.contextMenu
      selector: "#{@dom_elem.selector} thead th"
      build: (trigger, event) =>
        column_selector_items = []
        counter = 0
        for col in @columns
          continue if counter is @category_column_index
          column_selector_items.push(
            name: col.title
            icon: if col.visible then "ok" else ""
            )
          counter = counter+1
        return {
          callback: (key, options) =>
            @toggleColumn(key, true)
            true # hide the context menu
          items: column_selector_items
          }

    # drag / drop handling
    $(document).bind "drag_start.vakata", (e,data) =>
      $("body").css('cursor', 'move')
      obj = $(data.data.obj) # the element being dragged
      text = "<div id='dragg_notifier'>"
      selected = @getSelectedRows()
      between = selected.first()
      between = between.add(selected.first().nextUntil(selected.last()))
      between = between.add(selected.last())
      for index, elem of between
        if $.inArray(elem,selected) > -1
          title_td = $(elem).children().filter('td').first()
          text += "<div class='row'>"+title_td.html()+"</div>"
      text +="</div>"
      $("#vakata-dragged").html text
      e.preventDefault()

    # this handler is needed for reverting the cursor to normal as soon as the drag and drop action is finished
    # but only if it is not dragged onto a tree element
    $(document).bind "drag_stop.vakata", (e,data) =>
      $("body").css('cursor', 'auto')

    # close the filtering for articles contained in a certain category, from now on show all articles again
    $("#only_products_from_cat").bind 'close', (event) =>
      $(event.currentTarget).hide()
      $("#show_only_empty_categories").removeAttr "disabled"
      @dom_elem.data("show_only_products_in_cat", "")
      @reloadData()
      false

  # perform the automatic reloading of the categories
  # the following functions are needed to set the timeout for callig fetchCategories(), which then does the actual ajax request and updating
  initializeAutoReload: ->
    category_update_timer = false # timer for the automated update of the categories
    category_update_time = false # timestamp when the categories where last updated
    category_update_interval = document.config.category_update_time
    $(document).idleTimer category_update_interval*5
    # start the automatic fetching
    category_update_timer = setInterval =>
      @fetchCategories()
    , category_update_interval

    # when the user becomes idle, reduce the amount of ajax request by a factor 2.5
    $(document).bind "idle.idleTimer", () =>
      # console.log "setting idle"
      if (new Date() - category_update_time)*2.5 > document.category_update_time then @fetchCategories()
      clearInterval(category_update_timer)
      category_update_timer = setInterval =>
        @fetchCategories()
      , category_update_interval*2.5

    # when the user returns, continue ajax-fetching the server in the normal interval
    $(document).bind "active.idleTimer", () =>
      # console.log "setting active"
      if (new Date() - category_update_time) > document.category_update_time then @fetchCategories()
      clearInterval(category_update_timer)
      category_update_timer = setInterval =>
        @fetchCategories()
      , category_update_interval

  # update the category td in the datatable after a successful category assignment
  #
  # @param [Object] data the data used to update the categories
  # @option data [String] id the OXID of the product
  # @option data [Array<String>] the categories the product is assigned to
  updateCategories: (data) ->
    for product in data
      @setCategoryTd(product.id, product.categories)
    @tree.highlightCategories()

  # set the (hidden) td containing the category list in the datatable to the categories given by "categories"
  #
  # @param [jQuery] id DOM id of the row (the <tr> element) where the categories should be updated
  # @param [Array<String>] categories the category ids
  setCategoryTd: (id, categories) ->
    $("##{id}").children().filter("td").eq(@category_column_index).html categories.join(", ")

  # get the category OXIDs of all selected articles
  #
  # The information is taken from the (hidden) category td in the table.
  #
  # @param [Boolean] only_main only get the main categories
  # @return [Array] OXIDs of the categories
  getCategoriesOfSelected: (only_main = false) ->
    ret = []
    for row in @getSelectedRows()
      categories = $(row).children().filter("td").eq(@category_column_index).html().split ", "
      if categories.length> 0 and categories[0] != ""
        if only_main then ret.push categories[0]
        else ret.push cat for cat in categories
    ret

  # get the category OXIDs of all selected articles, but only for the main categories
  #
  # @return [Array] OXIDs of the categories
  getMainCategoriesOfSelected: -> @getCategoriesOfSelected true

  # fetch the categories from the database (via ajax) of all products currently display in the table
  #
  # this is important if more than 1 person is working on the category to keep the webapps in sync
  fetchCategories: ->
    rows = @dom_elem.find('tbody tr')
    ids = ( $(elem).attr('id') for elem in rows )
    return unless ids[0]? # dont perform a request in the unpropable case that there are 0 articles displayed in the table
    $.ajax
      type: "GET"
      url: "ajax/product_categories.php"
      dataType: "json"
      cache: false
      data:
        ids: ids.join(",")
      success: (data) =>
        for product in data
          @setCategoryTd(product.id, product.categories)
          category_update_time = new Date()
        @tree.highlightCategories()

  # search a field in the DataTable
  #
  # @param [Integer] table_column_index the column index of the column that should be searched. If the second table column should be search, pass a 2 here
  # @param [String] string the string to search for
  search: (table_column_index, string) ->
    @oTable.fnFilter(string, table_column_index)

  # perform a reload of data displayed in the DataTable via ajax
  reloadData: ->
    @oTable.fnDraw()

  # hides a column in the table completely
  #
  # hides thead cells as well as tbody cells
  #
  # @param [Integer] index index of the column that should be hidden. Note that counting starts with 0
  # @param [Boolean] setCookie save the column visibility state in a cookie
  hideColumn: (index, setCookie = false) ->
    $(row).children().eq(index).addClass 'hidden' for row in @dom_elem.find('tr')
    @columns[index].visible = false
    @setColumnVisibilityCookie() if setCookie

  # shows a complete column in the table
  #
  # shows thead cells as well as tbody cells
  #
  # @param [Integer] index index of the column that should be shown. Note that counting starts with 0
  # @param [Boolean] setCookie save the column visibility state in a cookie
  showColumn: (index, setCookie = false) ->
    $(row).children().eq(index).removeClass 'hidden' for row in @dom_elem.find('tr')
    @columns[index].visible = true
    @setColumnVisibilityCookie() if setCookie

  # toggle a complete column in the table
  #
  # hides thead cells as well as tbody cells
  #
  # @param [Integer] index index of the column that should be hidden. Note that counting starts with 0
  # @param [Boolean] setCookie save the column visibility state in a cookie
  toggleColumn: (index, setCookie = false) ->
    if @columns[index].visible then @hideColumn(index, setCookie) else @showColumn(index, setCookie)

  # save which columns are shown in the table in a cookie
  #
  # data is saved in JSON format
  setColumnVisibilityCookie: ->
    $.cookie("category_master_columns", JSON.stringify(@columns), { expires: 365 })

  # enable showing only products from a certain category
  #
  # will trigger the reload of the table data and show the breadcrumb indicator showing from which category the articles come from
  #
  # @param [String] cat_id the OXID of the category
  enableCategoryFiltering: (cat_id) ->
    names = @tree.getParentsNames cat_id
    @dom_elem.data("show_only_products_in_cat", cat_id)
    @reloadData()
    # show breadcrumb indicator above the table showing the category from which the articles are shown
    $("#show_only_empty_categories").attr("disabled", "disabled");
    separator = "&nbsp;&nbsp;<i class=\"icon-chevron-right\"></i>&nbsp;&nbsp;"
    html = separator + names.join separator
    $('#only_products_from_cat div span').html html
    $('#only_products_from_cat').show()


  # get the selected rows in the DataTable
  #
  # @return [Array<jQuery>] the DOM ids of the selected rows (<tr> elements)
  getSelectedRows: ->
    @oTable.$('tr.row_selected')
