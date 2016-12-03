$('#myTab a').click(function (e) {
    e.preventDefault();
    $(this).tab('show')
});

$(document).ready(function() {
    $('div.jieshao').height($('div.jieshaoimg').height());
});

