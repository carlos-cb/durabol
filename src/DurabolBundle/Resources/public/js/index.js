$('#myTab a').click(function (e) {
    e.preventDefault();
    $(this).tab('show')
});

$(document).ready(function() {
    $('div.jieshao').height($('div.jieshaoimg').height());
});

$(function(){
    $("#stores .form-control").keyup(function(){
        $("div#stores div.shops")
            .hide()
            .filter(":contains('"+( $(this).val() )+"')")
            .show();
    }).keyup();
});
