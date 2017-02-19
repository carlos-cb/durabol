$(function(){
    $(".navbar-form .form-control").keyup(function(){
        $("table tbody tr")
            .hide()
            .filter(":contains('"+( $(this).val() )+"')")
            .show();
    }).keyup();
});

$(document).ready(function() {
    shopIdJump();
    function shopIdJump(){
        var label = $('select.routing option:selected').text();
        if( label == '单个店铺' || label == '文章')
        {
            $('label.shopId').show();
            $('input.shopId').show();
        }else{
            $('label.shopId').hide();
            $('input.shopId').hide();
        }
    }

    $("select.routing").change(function(){
        shopIdJump();
    });

    $("button#todo").addClass("active");

    $("button#todo").click(function(){
        $("div#fliter button").removeClass("active");
        $(this).addClass("active");
        var table = $("table tbody").find("tr");
        $("table tbody tr").show();
    });

    $("button#preparando").click(function(){
        $("div#fliter button").removeClass("active");
        $(this).addClass("active");
        var table = $("table tbody").find("tr");
        for(var i=0; i<table.length; i++){
            if($(table[i]).find("button#estado").text() == "已付款"){
                $(table[i]).show();
            }else{
                $(table[i]).hide();
            }
        }
    });

    $("button#entregando").click(function(){
        $("div#fliter button").removeClass("active");
        $(this).addClass("active");
        var table = $("table tbody").find("tr");
        for(var i=0; i<table.length; i++){
            if($(table[i]).find("button#estado").text() == "已发货"){
                $(table[i]).show();
            }else{
                $(table[i]).hide();
            }
        }
    });

    $("button#terminado").click(function(){
        $("div#fliter button").removeClass("active");
        $(this).addClass("active");
        var table = $("table tbody").find("tr");
        for(var i=0; i<table.length; i++){
            if($(table[i]).find("button#estado").text() == "已完成"){
                $(table[i]).show();
            }else{
                $(table[i]).hide();
            }
        }
    });

    $("button#cancelado").click(function(){
        $("div#fliter button").removeClass("active");
        $(this).addClass("active");
        var table = $("table tbody").find("tr");
        for(var i=0; i<table.length; i++){
            if($(table[i]).find("button#estado").text() == "已取消"){
                $(table[i]).show();
            }else{
                $(table[i]).hide();
            }
        }
    });

    $("button#fallado").click(function(){
        $("div#fliter button").removeClass("active");
        $(this).addClass("active");
        var table = $("table tbody").find("tr");
        for(var i=0; i<table.length; i++){
            if($(table[i]).find("button#estado").text() == "未付款"){
                $(table[i]).show();
            }else{
                $(table[i]).hide();
            }
        }
    });
});

var ctx = document.getElementById("myChart");
var day6order = $("div#day6order").text();
var day5order = $("div#day5order").text();
var day4order = $("div#day4order").text();
var day3order = $("div#day3order").text();
var day2order = $("div#day2order").text();
var day1order = $("div#day1order").text();

var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ["Día-6", "Día-5", "Día-4", "Anteayer", "Ayer", "Hoy"],
        datasets: [{
            label: 'CANTIDAD DE PEDIDOS(ÚLTIMA SEMANA)',
            data: [day6order, day5order, day4order, day3order, day2order, day1order],
            backgroundColor: [
                '#ff4a70',
                '#1f97e9',
                '#ffc73d',
                '#3fb3b3',
                '#884dff',
                '#ff9227'
            ],
            borderColor: [
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    stepSize: 1,
                    beginAtZero:true
                }
            }]
        }
    }
});

var ctx1 = document.getElementById("myChart1");
var myChart1 = new Chart(ctx1, {
    type: 'bar',
    data: {
        labels: ["Día-6", "Día-5", "Día-4", "Anteayer", "Ayer", "Hoy"],
        datasets: [{
            label: 'CANTIDAD DE USUARIO ACTIVOS(ÚLTIMA SEMANA)',
            data: [12, 19, 3, 5, 2, 3],
            backgroundColor: [
                '#ff4a70',
                '#1f97e9',
                '#ffc73d',
                '#3fb3b3',
                '#884dff',
                '#ff9227'
            ],
            borderColor: [
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    }
});
