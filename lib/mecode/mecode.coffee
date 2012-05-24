$ ->
  $('div.mecode input.code').on 'keydown', ->
    $(@).closest('div.mecode').find('.err').slideUp('fast')