jQuery(document).ready(function() {


    $('button[data-loading-text]')
            .on('click', function() {
                var btn = $(this);
                btn.button('loading');
                setTimeout(function() {
                    btn.button('reset');
                }, 3000);
            });


    $('#contactform').submit(function() {

        var action = $(this).attr('action');

        $("#message").slideUp(750, function() {
            $('#message').hide();
            $.post(action, {
                name: $('#name').val(),
                email: $('#email').val(),
                subject: $('#subject').val(),
                msg: $('#msg').val()
            },
                    function(data) {
                        document.getElementById('message').innerHTML = data;
                        $('#message').slideDown('slow');
                        $('#submit').removeAttr('disabled');
                        if (data.match('success') != null)
                            $('#contactform').slideUp('slow');

                    }
            );

        });

        return false;

    });
   
    $('#callback').submit(function() {

        var action = $(this).attr('action');

        $("#error").slideUp(750, function() {
            $('#error').hide();
            $.post(action, {
                names: $('#names').val(),
                emails: $('#emails').val(),
                numbers: $('#numbers').val()
            },
                    function(data) {
                        document.getElementById('error').innerHTML = data;
                        $('#error').slideDown('slow');
                        $('#submit').removeAttr('disabled');
                        if (data.match('success') != null)
                            $('#callback').slideUp('slow');

                    }
            );

        });

        return false;

    });

});