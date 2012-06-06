String::format = ->
  s = this
  i = arguments.length
  s = s.replace(new RegExp("\\{" + i + "\\}", "gm"), arguments[i])  while i--
  s
$ ->
  $('div.mecode input.code').on 'keydown', ->
    $(@).closest('div.mecode').find('.err').slideUp('fast')

  $('form.me-product').on 'submit' , ->
    $(@).ajaxSubmit
      success : (response) ->
        new Boxy response,
          title: "Pay"
          modal: true
          closeText: 'x'
          fixed: true
        $('.topaypal').submit()
    return false

  return true