(function() {

    $(function() {
        return $('div.mecode input.code').on('keydown', function() {
            return $(this).closest('div.mecode').find('.err').slideUp('fast');
        });
    });

}).call(this);
