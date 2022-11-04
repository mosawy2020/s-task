<!doctype html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laravel 8 Load More Data using Ajax jQuery </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .wrapper > ul#results li {
            margin-bottom: 20px;
            background: #e2e2e2;
            padding: 20px;
            list-style: none;
        }

        .ajax-loading {
            text-align: center;
        }
    </style>
</head>
<body>
<h2 style="text-align: center;margin: 30px 0px"></h2>
<div class="container">

    <div class="row">
        <div class="col-sm ">
            <div style="position: fixed">
                <form>
                    <div class="form-group">
                        <label for="">search</label>
                        <input type="text" class="form-control" id="like" aria-describedby="emailHelp"
                               placeholder="search">
                    </div>
                    <label for="">categories</label>
                    @foreach($categories as $category )
                        <div class="form-check">
                            <label><input type="checkbox" name="categories[]" value="{{$category}}">{{$category}}</label>
                         </div>
                    @endforeach
                    <label for="">brand</label>
                    @foreach($brands as $brand )
                        <div class="checkbox">
                            <label><input type="checkbox" name="brands[]" value="{{$brand}}">{{$brand}}</label>
                         </div>
                    @endforeach
                </form>
                <canvas id="myChart">
                 </canvas>
            </div>

        </div>
        <div class="col-sm wrapper">
            <ul id="results">
                @foreach($products as $product)
                    <li>{{$product->product}}</li>
                @endforeach
            </ul>


        </div>
        @if(count($products))
            <button type="button" id="morebtn" class="btn btn-secondary btn-lg btn-block"
                    onclick=" page++ ; load_more(page)">load more
            </button>
        @endif

    </div>
</div>
<script>
    var arr = [];

    function preparedata(labels) {

    }

    var labels =<?php echo json_encode($products); ?>;
    labels = labels.data;
    labels = labels.map(e => e.product);
    var arr = labels;
    var freqMap = arr.reduce(
        (map, item) => map.set(item, (map.get(item) || 0) + 1),
        new Map
    );
    var xAxisArr = Array.from(freqMap.keys()); // array of unique items
    var yAxisArr = Array.from(freqMap.values());
     var data = {
        labels: xAxisArr,
        datasets: [{
            label: 'My First dataset',
            backgroundColor: 'rgb(255, 99, 132)',
            borderColor: 'rgb(255, 99, 132)',
            data: yAxisArr,
        }]
    };

    var config = {
        type: 'line',
        data: data,
        options: {}
    };
</script>
<script>
    myChart = new Chart(
        document.getElementById('myChart'),
        config
    );

    function addData(arr) {
        labels = arr;
        freqMap = arr.reduce(
            (map, year) => map.set(year, (map.get(year) || 0) + 1),
            new Map
        );
        xAxisArr = Array.from(freqMap.keys()); // array of unique years
        yAxisArr = Array.from(freqMap.values());
        data = {
            labels: xAxisArr,
            datasets: [{
                label: 'My First dataset',
                backgroundColor: 'rgb(255, 99, 132)',
                borderColor: 'rgb(255, 99, 132)',
                data: yAxisArr,
            }]
        };
        myChart.data = data;
        myChart.update();
    }

</script>
</body>
</html>
<script>
    $(function () {
        $("#like").on('keyup', function () {
            $("#results").html("");
            load_more();

        });
        $('input[name="brands[]"]').change(
            function(){
                 $("#results").html("");
                 load_more();

            });
        $('input[name="categories[]"]').change(
            function(){
                 $("#results").html("");

                load_more();
            });
    });
    var page = 1;

    function load_more(page = 1) {
        let  brands =[]; let categories = [] ;
        $.each($("input[name='brands[]']:checked"), function(){
            brands.push($(this).val());
        });
        $.each($("input[name='categories[]']:checked"), function(){
            categories.push($(this).val());
        });
        $.ajax({
            url: "{{route("products.index")}}" + "?page=" + page,
            data: {
                like: $('#like').val(),
                brand:brands,
                category:categories,
            },
            processData: true,
            type: "get",
            async: true,
            dataType: 'json',
        })
            .done(function (data) {
                if (data.data.length == 0) {
                    $("#morebtn").attr("hidden",true);
                    return;
                }
                else {
                     $("#morebtn").attr("hidden",false);
                    app = data.data.map(function (item) {
                        arr.push(item);
                        return '<li>' + item + '</li>'
                    })
                    addData(arr);
                     $("#results").append(app);}

            })
            .fail(function (jqXHR, ajaxOptions, thrownError) {
                // alert('No response from server');
            });
        page++;
    }
</script>
