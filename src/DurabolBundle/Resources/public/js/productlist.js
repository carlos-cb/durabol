$(document).ready(function() {
    $("a#product_image").fancybox({
        closeClick: true,
        helpers : {
            overlay : {
                css : {
                    'background' : 'rgba(40, 33, 27, 0.3)'
                }
            }
        },
        'hideOnContentClick' :   false
    });
    $("button.numcheck").click(function(){
        var productId = parseInt($(this).parent().find('p.productIdHidden').text());
        $("div#product"+productId+"").find("span.nocart").toggle();
        $("div#product"+productId+"").find("span.yescart").toggle();
        $.fancybox.close();
        var path = $(this).attr("data-path");
        var num = $(this).parent().find("span#num").text();
        $.ajax({
            type: 'POST',
            url: path,
            data: {num: num, id: productId},
            error: function(XMLHttpRequest, textStatus, errorThrown)
            {
                alert('Error: ' +  errorThrown);
            }
        });
    });
    var ppy = $("ul.cartItems").find("li.cartItemId");
    var ppn = $("div#product").find("p.productId");
    for(var y=0; y<ppy.length; y++){
        for(var n=0; n<ppn.length; n++){
            if(parseInt($(ppy[y]).text()) == parseInt($(ppn[n]).text())){
                $(ppn[n]).parent().find("span.nocart").toggle();
                $(ppn[n]).parent().find("span.yescart").toggle();
            }
        }
    }
    $('a.nocart').fancybox({
        helpers : {
            overlay : {
                css : {
                    'background' : 'rgba(40, 33, 27, 0.3)'
                }
            }
        }
    });

    $("button.add").click(function(){
        var t = $(this).parent().find('span#num');
        t.text(parseInt(t.text())+1);
        var s =
             parseInt($(this).parent().find("span#num").text())
            * parseFloat($(this).parent().find("span#price").text());
        $(this).parent().find("span#heji").html(s.toFixed(2));
    });
    $("button.min").click(function(){
        var t=$(this).parent().find('span#num');
        t.text(parseInt(t.text())-1);
        if(parseInt(t.text())<1){
            t.text(1);
        }
        var s =
            parseInt($(this).parent().find("span#unit").text())
            * parseInt($(this).parent().find("span#num").text())
            * parseFloat($(this).parent().find("span#price").text());
        $(this).parent().find("span#heji").html(s.toFixed(2));
    });
    var numselect = $("div#product").find("div.numselect");
    for(var i=0; i<numselect.length; i++){
        var s = $(numselect[i]).find("span#unit").text()
            * $(numselect[i]).find("span#num").text()
            * $(numselect[i]).find("span#price").text();
        $(numselect[i]).find("span#heji").html(s.toFixed(2));
    }
});