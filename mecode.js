(function() {

  String.prototype.format = function() {
    var i, s;
    s = this;
    i = arguments.length;
    while (i--) {
      s = s.replace(new RegExp("\\{" + i + "\\}", "gm"), arguments[i]);
    }
    return s;
  };

  $(function() {
    $('div.mecode input.code').on('keydown', function() {
      return $(this).closest('div.mecode').find('.err').slideUp('fast');
    });
    $('form.me-product').on('submit', function() {
      $(this).ajaxSubmit({
        success: function(response) {
          new Boxy(response, {
            title: "Pay",
            modal: true,
            closeText: 'x',
            fixed: true
          });
          return $('.topaypal').submit();
        }
      });
      return false;
    });
    return true;
  });

}).call(this);
