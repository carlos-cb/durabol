$(function(){
    $("button.addnum").click(function(){
        var t = $(this).parent().parent().find('span#_quantity');
        t.html(parseInt(t.text())+1);
        setHeji();
        setShop();
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
        setShop();
        setTotal();
    });
    function setHeji(){
        var ff = $('table.order-table').find('tr#order-item');
        for(var j=0; j<ff.length; j++){
            var s = parseInt($(ff[j]).find("#_quantity").text())*parseFloat($(ff[j]).find("#priceunit").text())*parseInt($(ff[j]).find("#unit").text());
            $(ff[j]).find("#heji").html(s.toFixed(2));
        }
    }
    function setShop(){
        var sss = $('table.order-table').find('span#cartShopId');
        var tongji = 1;
        for(var k=0; k<sss.length; k++){
            var ssss = $("tbody#shangpin"+parseInt($(sss[k]).text())+"");
            var sssheji = ssss.find('tr#order-item');
            var shopHeji = 0;
            for(var kk=0; kk<sssheji.length; kk++){
                shopHeji += parseFloat($(sssheji[kk]).find('#heji').text());
            }
            ssss.find('span#totalshop').html(shopHeji.toFixed(2));
            if(parseFloat(ssss.find('span#minCoste').text()) >  shopHeji){
                tongji = 0;
                ssss.find('.minCoste').css("color", "red");
            }else{
                tongji = 1;
                ssss.find('.minCoste').css("color", "black");
            }
        }
        if(tongji == 1){
            $('button.yes').show();
            $('button.no').hide();
        }else{
            $('button.yes').hide();
            $('button.no').show();
        }

    }
    function setTotal(){
        var totalprice = 0;
        var tt = $('table.order-table').find('span#totalshop');
        for(var i=0; i<tt.length; i++){
            totalprice += parseFloat($(tt[i]).text());
        }
        var total = totalprice;
        var discount = $('#userdiscount').text();
        $("#totalprice").html((totalprice*100/discount).toFixed(2));
        $("#discount").html((totalprice*100/discount-total).toFixed(2));
        $("#total").html(total.toFixed(2));
    }
    setHeji();
    setShop();
    setTotal();
});
