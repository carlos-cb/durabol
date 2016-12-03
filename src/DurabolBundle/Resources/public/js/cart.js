$(function(){
    $("button.addnum").click(function(){
        var t = $(this).parent().parent().find('span#_quantity');
        t.html(parseInt(t.text())+1);
        setHeji();
        setTotal();
        var isAdd = 1;
        var cartItemId = parseInt($(this).parent().parent().find('#cartItemId').text());
        var path = $(this).attr("name");
        $.ajax({
            type: 'POST',
            url: path,
            data: {val1: isAdd, val2: cartItemId},
            error: function(XMLHttpRequest, textStatus, errorThrown)
            {
                alert('Error: ' +  errorThrown);
            }
        });
    });
    $("button.minnum").click(function(){
        var t=$(this).parent().parent().find('span#_quantity');
        t.html(parseInt(t.text())-1);
        if(parseInt(t.text())<1){
            t.html(1);
        }else{
            var isAdd = -1;
            var cartItemId = parseInt($(this).parent().parent().find('#cartItemId').text());
            var path = $(this).attr("name");
            $.ajax({
                type: 'POST',
                url: path,
                data: {val1: isAdd, val2: cartItemId},
                error: function(XMLHttpRequest, textStatus, errorThrown)
                {
                    alert('Error: ' +  errorThrown);
                }
            });
        }
        setHeji();
        setTotal();
    });
    function setHeji(){
        var ff = $('#shangpin').find('tr#order-item');
        for(var j=0; j<ff.length; j++){
            var s = parseInt($(ff[j]).find("#_quantity").text())*parseFloat($(ff[j]).find("#priceunit").text())*parseInt($(ff[j]).find("#unit").text());
            $(ff[j]).find("#heji").html(s.toFixed(2));
        }
    }
    function setTotal(){
        var totalprice = 0;
        var tt = $('#shangpin').find('tr#order-item');
        for(var i=0; i<tt.length; i++){
            totalprice += parseFloat($(tt[i]).find('#heji').text());
        }
        var total = totalprice;
        var discount = $('#userdiscount').text();
        $("#totalprice").html((totalprice*100/discount).toFixed(2));
        $("#discount").html((totalprice*100/discount-total).toFixed(2));
        $("#total").html(total.toFixed(2));
    }
    setHeji();
    setTotal();
});
