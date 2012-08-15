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
    $('div.KV-code input.code').on('keydown', function() {
      return $(this).closest('div.KV-code').find('.err').slideUp('fast');
    });
    if (window.downloadUrl) {
      new Boxy("<div>Your download will start in a second, You can use <a href='" + window.downloadUrl + "'> This Link </a> to get it directly.</div>", {
        title: "Thank You!",
        modal: true,
        closeText: 'x',
        fixed: true
      });
      setTimeout((function() {
        return window.location = window.downloadUrl;
      }), 3000);
    }
    return true;
  });

}).call(this);
