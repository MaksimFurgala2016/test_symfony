/**
 * Created by furga on 03.07.2018.
 */
$(document).ready(function () {
    $('#show-password').click(
        function () {
            if ($('#show-password').is(':checked')){
                $('#form_password').attr('type','text');
            }
            else
            {
                $('#form_password').attr('type','password');
            }
        }
    );
});