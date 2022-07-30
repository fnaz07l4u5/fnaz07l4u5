

<style type="text/css">
    .tg  {border-collapse:collapse;border-spacing:0;margin:0px auto;}
    .tg td{border-color:black;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:14px;
      overflow:hidden;/*padding:15px 13px;*/word-break:normal;}
    .tg th{border-color:black;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:14px;
      font-weight:normal;overflow:hidden;padding:15px 13px;word-break:normal;}
    .tg .tg-j1i3{background-color: #e6e6e6;border-color:inherit;position:-webkit-sticky;position:sticky;text-align:left;top:-1px;vertical-align:top;text-align: center;
      will-change:transform}
    .tg .tg-0pky{border-color:inherit;text-align:center;vertical-align:top}
    .totals{background-color:aquamarine;}
    .inputField{outline: none;border: none;
border-color: transparent;text-align: center}
.inputField:focus{
    background-color:hotpink;
}
    </style>
   

   <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<body style="">
    <div style="display:flex;flex-direction: row;
    justify-content: center;
    align-items: center;">
        <a href="{{route("calendar",["month"=>$month,"year"=>$year])}}" class="btn">Back to month</a>
        <h1 style="margin-left:30px">Laundry Control: {{$day}} {{$month}} {{$year}}</h1>
    </div>
    <table class="tg">
        <thead>
          <tr>
            <th class="tg-j1i3">ΕΙΔΟΣ</th>
            <th class="tg-j1i3">ΔΩΣΑΜΕ</th>
            <th class="tg-j1i3">ΠΗΡΑΜΕ</th>
            <th class="tg-j1i3">ΗΜΕΡΗΣΙO</th>
            <th class="tg-j1i3">ΣΤΟΚ</th>
          </tr>
        </thead>
        <tbody>
            @foreach(\App\Material::orderBy("order")->get() as $material)
          <tr>
            <td class="tg-0pky" style="text-align:left">{{$material->name}}</td>
            <td class="tg-0pky"><input class="inputField input_giv" value="" id="giv_{{$material->id}}" name="giv_{{$material->id}}" type="text"/></td>
            <td class="tg-0pky"><input class="inputField input_rec" value="" id="rec_{{$material->id}}" name="rec_{{$material->id}}" type="text"/></td>
            <td class="tg-0pky" id="diff_{{$material->id}}"></td>
            <td class="tg-0pky" id="diffstock_{{$material->id}}"></td>
          </tr>
          @endforeach
          <tr>
            <td class="tg-0pky totals" style="font-size: 25px;" >ΣΥΝΟΛΑ</td>
            <td class="tg-0pky totals" style="font-size: 25px;" id="giv_total"></td>
            <td class="tg-0pky totals" style="font-size: 25px;" id="rec_total"></td>
            <td class="tg-0pky totals" style="font-size: 25px;" id="diff_total"></td>
            <td class="tg-0pky totals" style="font-size: 25px;" id="diffstock_total"></td>
          </tr>
        </tbody>
        </table>
        <center>
            <input id="submit_btn" style="margin-top:30px;    width: 250px;
            height: 50px;"  type="submit" value="Καταχώρηση" onclick="app.postForm()"/>
        </center>
    </body>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.0/axios.min.js"></script>
    <script>
        

        var registry; // ID -> giv , rec
        var diff = []; // ID -> giv - rec =  diff
        var diffstock = []; // ID -> given - INITIAL STOCK
        var givTotal = 0;
        var recTotal = 0;
        var total = 0;
        var diffstockTotal = 0 ;
        
        var app = {
            init : function(){
                
                axios.get(`{{route("getStock")}}`).then(function(res){
                    console.log(res)
                    diffstock = res.data.per_item["{{$year.'-'.$month.'-'.$day}}"]
                    if (diffstock === undefined){                   
                        diffstock = res.data.per_item[Object.keys(res.data.per_item)[Object.keys(res.data.per_item).length - 1]]
                    }
                    console.log(diffstock)
                }).then(function(){
                    axios.get(`{{route("getResgistry",["year"=>$year,"month"=>$month,"day"=>$day])}}`).then(function(res){
                        registry = res.data[0]
                        console.log(registry)
                        app.calculate_diff();
                        app.updateDOM();
                    })
                });
            },
            calculate_diff : function(){
                diff = []; // ID -> giv - rec =  diff
                //diffstock = [];
                givTotal = 0;
                recTotal = 0;
                total = 0;
                diffstockTotal = 0
               for (const id in registry) {
                    //console.log(id, registry[id]);
                    diff[id] = registry[id].giv - registry[id].rec;
                    givTotal += registry[id].giv
                    recTotal += registry[id].rec
                    total = givTotal - recTotal;
                }
                for (const id in diffstock) {
                    diffstockTotal += diffstock[id]
                }
            },
            userInputUpdate(reff){
                $("#submit_btn").val("Καταχώρηση - 💾");
                let tmp = reff.split("_")
                let type = tmp[0]
                let id = tmp[1]

                console.log(type,id,reff,parseInt($("#"+reff).val()));
                
                if ( type == "rec"){
                    registry[id].rec = parseInt($("#"+reff).val())
                    console.log(registry[id].rec);
                }else if (type == "giv"){
                    registry[id].giv = parseInt($("#"+reff).val())
                }
                app.calculate_diff();
                app.updateDOM();
            },
            updateDOM : function(){
                for (const id in registry) {
                    //console.log(id, registry[id]);
                    $("#giv_"+id).val(registry[id].giv);
                    $("#rec_"+id).val(registry[id].rec);
                    $("#diff_"+id).html(diff[id]);
                    $("#diffstock_"+id).html(diffstock[id]);
                    console.log(id,diffstock[id])
                    $("#giv_total").html(givTotal);
                    $("#rec_total").html(recTotal);
                    $("#diff_total").html(total);
                    $("#diffstock_total").html(diffstockTotal);
                }
            },
            postForm : function(){
                $("#submit_btn").val("Καταχώρηση - ⌛");
                console.log("posting...");

                axios.post(`{{route("updateRegistry")}}`,{
                    _token : `{{csrf_token()}}`,
                    registry : registry,
                    date : "{{$year.'-'.$month.'-'.$day}}"
                }).then(function(res){
                    console.log(res)
                    $("#submit_btn").val("Καταχώρηση - ✔");
                })
            }
        };

        

        $(document).ready(function(){
            $(".input_giv").keyup(function(event) {
                let inputListener = this;
                
                if ( event.which == 13 ) {
                    var nextI = $(".input_giv").index(inputListener)+1;
                    if( $(inputListener).val() == ""){
                        $(inputListener).val(0)
                    }
                    next=$(".input_giv").eq(nextI);
                    next.focus();
                    if (next.val() == 0){
                        next.val("") 
                    }
                    app.userInputUpdate(inputListener.id);
                }
                
                if (event.which != 8 && event.which != 0 && (event.which < 48 || event.which > 57)) {
                        return false;
                }
                
            });
            
            $(".input_giv").focus(function(){
                if( $(this).val() == 0){
                        $(this).val("")
                }
            });

            $(".input_giv").focusout(function(){
                if( $(this).val() == ""){
                        $(this).val(0)
                }
            });

            $(".input_rec").keyup(function(event) {
                let inputListener = this;
               
                if ( event.which == 13 ) {
                    var nextI = $(".input_rec").index(inputListener)+1;
                    if( $(inputListener).val() == ""){
                        $(inputListener).val(0)
                    }
                    next=$(".input_rec").eq(nextI);
                    next.focus();
                    if (next.val() == 0){
                        next.val("") 
                    }
                    app.userInputUpdate(inputListener.id);
                }
                
                if (event.which != 8 && event.which != 0 && (event.which < 48 || event.which > 57)) {
                        return false;
                }
                
            });
            
            $(".input_rec").focus(function(){
                if( $(this).val() == 0){
                        $(this).val("")
                }
            });

            $(".input_rec").focusout(function(){
                if( $(this).val() == ""){
                        $(this).val(0)
                }
            });


            app.init();
        });
    </script>
  {{--  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.0/axios.min.js"></script>
    <script>
    
    // Add a "checked" symbol when clicking on a list item
    var list = document.querySelector('ul');
    list.addEventListener('click', function(ev) {
      if (ev.target.classList.contains('checkins')) {
    
        axios.post(`{{route("printed")}}`,{
            _token : `{{csrf_token()}}`,
            hash : ev.target.id
        }).then(function(res){
            console.log(res)
        })
        ev.target.classList.toggle('checked');
      }
    }, false);
    
    </script>--}}
    