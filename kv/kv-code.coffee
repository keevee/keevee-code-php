String::format = ->
  s = this
  i = arguments.length
  s = s.replace(new RegExp("\\{" + i + "\\}", "gm"), arguments[i])  while i--
  s
$ ->
  $('div.KV-code input.code').on 'keydown', ->
    $(@).closest('div.KV-code').find('.err').slideUp('fast')

  if window.downloadUrl
    setTimeout ( () -> window.location = window.downloadUrl ), 3000

  return true