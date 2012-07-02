String::format = ->
  s = this
  i = arguments.length
  s = s.replace(new RegExp("\\{" + i + "\\}", "gm"), arguments[i])  while i--
  s
$ ->
  $('div.KV-code input.code').on 'keydown', ->
    $(@).closest('div.KV-code').find('.err').slideUp('fast')

  if window.downloadUrl
    new Boxy "<div>Your download will start in a second, You can use <a href='"+window.downloadUrl+"'> This Link </a> to get it directly.</div>"
      title: "Thank You!"
      modal: true
      closeText: 'x'
      fixed: true

    setTimeout ( () -> window.location = window.downloadUrl ), 3000

  return true