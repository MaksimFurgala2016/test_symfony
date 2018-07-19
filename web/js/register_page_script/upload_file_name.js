/**
 * Created by furga on 30.06.2018.
 */
$(document).ready( function() {
    $("input[type=file]").change(function(){
        var filename = $(this).val().replace(/.*\\/, "");
        if (filename != "")
        {
            $(".custom-file-label").text(filename);
        }
        else $(".custom-file-label").text("Выберите файл");
    });
});