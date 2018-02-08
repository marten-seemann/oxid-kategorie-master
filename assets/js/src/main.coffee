$ ->
  "use strict"

  document.sticky_scrolling = true
  document.category_column_index = 11
  document.article_table_hidden_columns = [2, 6, 8, 9, 10] #hide the DISTEAN, OXVENDOR, OXTPRICE, OXBPRICE, OXINSERT by default

  document.notification_handler = new NotificationHandler()
  document.language_handler = new LanguageHandler()
  document.category_tree = new CategoryTree $("#category_tree")
  category_tree = document.category_tree
  document.article_table = new ArticleTable $("#products")
  article_table = document.article_table
  article_table.initialize()
  category_tree.initialize()


  # configure the legend for the jstree and the articles
  $("#articles-info").bind 'click', (event) ->  $('#modal_articles').modal 'toggle'
  $('#help').bind 'click', (event) ->  $('#modal_help').modal 'toggle'
  $('#category-info').bind 'click', (event) ->  $('#modal_categories').modal 'toggle'

  # sticky scrolling for the article datatable
  article_wrapper = $('#article_wrapper')
  top = article_wrapper.offset().top - parseFloat(article_wrapper.css('marginTop').replace(/auto/, 0))
  # for some strange reason the width has to be set manually. we do this by reading the calculated width and setting this value as css width
  article_wrapper.css('width', article_wrapper.width())

  # sticky scrolling
  $(window).scroll (event) ->
    return false unless document.sticky_scrolling
    y = $(this).scrollTop()
    if y >= top then article_wrapper.addClass 'fixed'
    else article_wrapper.removeClass 'fixed'

  $('#sticky_scrolling').bind 'click', () ->
    document.sticky_scrolling = unless $(this).attr('checked') then true else false
    true

  $('#refresh').bind 'click', () -> window.location.href = window.location.href

# count how often a element (search) is contained in in array (array)
$.countOccurences = (array, search) ->
  counter = 0
  counter++ for el in array when el == search
  counter

# escape the . in a DOM id (. is used for classes, but sometimes we have it in the DOM id too, so we need escaping)
$.escapeId = (string) ->
  string.replace(/\./g,"\\.")

# get the value of a GET parameter
$.urlParam = (name) ->
  results = new RegExp('[\\?&]' + name + '=([^&#]*)').exec(window.location.href)
  res = results?[1]
  unless res then res = ""
  decodeURIComponent res

# open a URL in a new tab
$.open_in_new_tab = (url) ->
  window.open url, '_blank'
  window.focus()
