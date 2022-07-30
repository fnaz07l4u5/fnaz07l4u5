<?php

$month_full = $month_name = date("M", mktime(0, 0, 0, $month, 10));
$month_start = strtotime("first day of $month_full $year", time());
$month_end = strtotime("last day of $month_full $year", time());
$start = date('D', $month_start);
$end = date('d', $month_end);

$next_date =  strtotime('+1 month', strtotime($year."-".$month));
$next_month = date("m",$next_date);
$next_year = date("Y",$next_date);

$prev_date =  strtotime('-1 month', strtotime($year."-".$month));
$prev_month = date("m",$prev_date);
$prev_year = date("Y",$prev_date);

$map_day = [
    "Sun" => 0,
    "Mon" => 1,
    "Tue" => 2,
    "Wed" => 3,
    "Thu" => 4,
    "Fri" => 5,
    "Sat" => 6
];
?>
<head>
 
    <style>
        @import url(https://fonts.googleapis.com/css?family=Montserrat:400,700);
 *, *::after, *::before {
	 box-sizing: border-box;
}
 body {
	 background-color: #e8f0ff;
	 color: #4e4f4a;
	 display: flex;
	 align-items: center;
	 justify-content: center;
	 font-family: 'Montserrat';
	 font-weight: 700;
	 min-height: 100vh;
}
 main {
	 background-color: #f6e9dc;
	 box-shadow: 0px 0px 0px 2px #e8f0ff, 10px 10px 20px 10px rgba(78, 79, 74, 0.5);
	 flex-basis: 980px;
}
 .calendar {
	 table-display: fixed;
	 border: 2px solid #4e4f4a;
	 width: 100%;
}
 .calendar__day__header, .calendar__day__cell {
	 border: 2px solid #bebebe;
	 text-align: center;
	 width: 14.2857142857%;
	 vertical-align: middle;
}
 .calendar__day__header:first-child, .calendar__day__cell:first-child {
	 border-left: none;
}
 .calendar__day__header:last-child, .calendar__day__cell:last-child {
	 border-right: none;
}
 .calendar__day__header, .calendar__day__cell {
	 padding: 0.75rem 0 1.5rem;
}
 .calendar__banner--month {
	 border: 2px solid #4e4f4a;
	 border-bottom: none;
	 text-align: center;
	 padding: 0.75rem;
}
 .calendar__banner--month h1 {
	 /*background-color: #4e4f4a;*/
	 color: #f6e9dc;
	 display: inline-block;
	 font-size: 3rem;
	 font-weight: 700;
	 letter-spacing: 0.1em;
	 padding: 0.5rem 2rem;
	 text-transform: uppercase;
}
 .calendar__day__header {
	 font-size: 1rem;
	 letter-spacing: 0.1em;
	 text-transform: uppercase;
             background-color: #8dc6f7;
    color: #f6e9dc;
}
 .calendar__day__cell {
	 font-size: 4rem;
	 position: relative;
}
 
/*tr:nth-child(odd) > .calendar__day__cell:nth-child(odd) {
	 color: #e8f0ff;
}
 tr:nth-child(even) > .calendar__day__cell:nth-child(even) {
	 color: #e8f0ff;
}*/

 .calendar__day__cell[data-moon-phase] {
	 background-color: #e8f0ff;
	 color: #4e4f4a;
}
 .calendar__day__cell[data-moon-phase]:after {
	 content: attr(data-moon-phase);
	 color: #f6e9dc;
	 display: block;
	 font-weight: 400;
	 font-size: 0.75rem;
	 position: absolute;
	 bottom: 0;
	 width: 100%;
	 height: 1rem;
	 text-transform: uppercase;
}
 .calendar__day__cell[data-bank-holiday] {
	 background-color: #4e4f4a;
	 border-color: #4e4f4a;
	 border-bottom: none;
	 color: #f6e9dc;
}
 .calendar__day__cell[data-bank-holiday]:after {
	 content: attr(data-bank-holiday);
	 color: #f6e9dc;
	 display: block;
	 font-size: 0.75rem;
	 font-weight: 400;
	 position: absolute;
	 bottom: 0;
	 width: 100%;
	 height: 1rem;
	 text-transform: uppercase;
}
.calendar__day__cell:hover{
    background-color:#f0fff8;
}

a, a:link, a:visited, a:hover, a:active {
    text-decoration: none;
    color: #f6e9dc;
  }

  .day{
      color: black !important;
      cursor: pointer;
  }

.badge.left{
    left: 0;
    background-color: #4e4f4a;
}

.badge.right{
    right:0;
    background-color: #939eff;
}

.badge{
    position: absolute;
    top: 0;
    /*right: 0;*/
    font-size: 16px;
    width: 50%;
    height: 24px;
    color: #f6e9dc;
}

.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content/Box */
.modal-content {
  background-color: #fefefe;
  margin: 10% auto; /* 15% from the top and centered */
  padding: 20px;
  border: 1px solid #888;
  width: 80%; /* Could be more or less, depending on screen size */
}

/* The Close Button */
.close {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: black;
  text-decoration: none;
  cursor: pointer;
}

        #drawer{
            width: 400px;
            height: 400px;
            position: fixed;
            bottom: -370px;
            left: auto;
            right: auto;
            background-color: lightgrey;
            cursor: pointer;
            border: 1px dotted black;
            z-index: 9;
        }

        #information{
            margin: 12px;
            position: relative;
        }
        .box{
            margin:20px;
            width:20px;
            height:20px;
            
            float:none;
            display: inline-flex;
        }

        </style>
</head>

<div id="drawer">
        <span id="information">Click for Information</span><br>
        <div class="box" style="background-color:#494949;" ></div> <div style="display: inline;">Ημερίσιο</div><br>
        <div class="box"  style="background-color:#87b5df;"></div> <div  style="display: inline;">Συνολικό </div><br>
        <div class="box"  style="background-color:gray;"></div> <div  style="display: inline;">Σήμερα</div>
</div>

<main>
    <table class="calendar">
        <caption class="calendar__banner--month" style="position:relative">
            <div class="logo" style="position: absolute;
    left: 20;
    top: 45;">
                <img width="120px" src="{{asset("images/laundry.png")}}"/></div>
            
        <h1 style="margin-right: 4px;">
            <a href="{{route("calendar",[$prev_year,$prev_month])}}"><img height="50px" src="https://checkin-online.gr/img/l-arrow.png"/></a>
        </h1>
        <h1 style="background-color: #8dc6f7"><a href="{{route("calendar")}}"><?=$month_full." ".$year?> </a></h1> 
        <h1>
            <a href="{{route("calendar",[$next_year,$next_month])}}"><img height="50px" src="https://checkin-online.gr/img/r-arrow.png"/> </a>
        </h1>
        <div class="logo" style="position: absolute;
    right: 20;
    top: 25;">
            
           {{-- <a href="{{route('logout')}}">
                <img width="120px" src="{{Voyager::image($hotel->logo)}}"/>
            </a>--}}
            
        
        </div>
        </caption>
        <thead>
            <tr>
                <th class="calendar__day__header">Sun</th>
                <th class="calendar__day__header">Mon</th>
                <th class="calendar__day__header">Tues</th>
                <th class="calendar__day__header">Wed</th>
                <th class="calendar__day__header">Thu</th>
                <th class="calendar__day__header">Fri</th>
                <th class="calendar__day__header">Sat</th>
            </tr>
        </thead>
        <tbody>


            @for ($i = -$map_day[$start]+1 ; $i <= 42-$map_day[$start]; $i++ )
            @if (($i+$map_day[$start]-1)%7==0) <tr> @endif
                @php $running_date = $year."-".$month."-".str_pad($i, 2, "0", STR_PAD_LEFT) @endphp
                @if ($i <= 0 || $i > $end )
                    <td class='calendar__day__cell'
                        
                    @if( $running_date == date("Y-m-d"))
                        style="background-color:silver;"
                    @endif
                    
                    >
                
                </td>
                @else
                    <td class='calendar__day__cell'
                        @if($running_date == date("Y-m-d"))
                            style="background-color:silver;"
                        @endif
                    >
                        
                     
                        @if ( isset($registry[$running_date]) )
                            <span class="badge left">{{$registry[$running_date]}}</span>
                            <span id="badge_{{$running_date}}" class="badge right">0</span>
                        @else
                            <span class="badge left">0</span>
                            <span id="badge_{{$running_date}}" class="badge right">0</span>
                        @endif 

                       {{-- @if($checkins[$i] == 0)
                            <span class="badge">{{$checkins[$i]}}</span>
                        @elseif($requests[$i] != 0)
                            <span class="badge" style="background-color:#ff7878">{{$checkins[$i]}}</span>
                        @else
                            <span class="badge" style="background-color:#84d084">{{$checkins[$i]}}</span>
                        @endif--}}
                        
                        <a class='day' href="{{route("daily",[$year,$month,$i])}}">{{$i}}</a>
                    </td>
                @endif
            @if ($i+$map_day[$start]%7==0) </tr> @endif
            @endfor
        </tbody>
    </table>
</main>
<script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.0/axios.min.js"></script>

{{--<div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div id="modal-content">
                
            </div>
        </div>
    </div>--}}
    
  
    <script>
        
    var toggle = true;
    function expand(){  $("#drawer").animate({top: "-=120",}, 500) }
    function collapse(){ $("#drawer").animate({top: "+=120",}, 500) }
    $(document).ready(function(){
        
        $("#drawer").click(function(){
            toggle ? expand() : collapse();
            toggle = !toggle
            console.log(toggle)
        })
    })
    
    axios.get(`{{route("getStock")}}`).then(function(res){
        console.log(res)
        totals = res.data.daily_totals
        
        for (const date in totals) {
            $("#badge_"+date).html(totals[date])
        }
    })

   /* $(document).ready(function(){
    // Get the <span> element that closes the modal
            var span = document.getElementsByClassName("close")[0];
    
    // When the user clicks on the button, open the modal
    $(".day").click(function(){
        let href = $(this).data("href");
        axios.get(href).then(function(res){
           $("#modal-content").html(res.data)
           $("#myModal").css("display","block")
        })
       
    })
    
    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        $("#myModal").css("display","none")
    }
    
    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
      if (event.target == $("#myModal")[0]) {
        $("#myModal").css("display","none")
      }
    }
    })
    */

    </script>

<style>
        /* Include the padding and border in an element's total width and height */
        * {
          box-sizing: border-box;
        }
        
        /* Remove margins and padding from the list */
        ul {
          margin: 0;
          padding: 0;
          list-style: none;
        }
        
        /* Style the list items */
        ul li {
          cursor: pointer;
          position: relative;
          padding: 12px 111px 12px 40px;
          background: #eee;
          font-size: 18px;
          transition: 0.2s;
        
          /* make the list items unselectable */
          -webkit-user-select: none;
          -moz-user-select: none;
          -ms-user-select: none;
          user-select: none;
        }
        
        /* Set all odd list items to a different color (zebra-stripes) */
        ul li:nth-child(odd) {
          background: #f9f9f9;
        }
        
        /* Darker background-color on hover */
        ul li:hover {
          background: #ddd;
        }
        
        /* When clicked on, add a background color and strike out text */
        ul li.checked {
          background: #888;
          color: #fff;
          text-decoration: line-through;
        }
        
        /* Add a "checked" mark when clicked on */
        ul li.checked::before {
          content: '';
          position: absolute;
          border-color: #fff;
          border-style: solid;
          border-width: 0 2px 2px 0;
          top: 10px;
          left: 16px;
          transform: rotate(45deg);
          height: 15px;
          width: 7px;
        }
        
        /* Style the close button */
        .close {
          position: absolute;
          right: 0;
          top: 0;
          color: black;
          padding: 12px 16px 12px 16px;
        }
        
        .close:hover {
          background-color: #79cb8a;
          color: white;
        }
        
        /* Style the header */
        .header {
          background-color: #f44336;
          padding: 30px 40px;
          color: white;
          text-align: center;
        }
        
        /* Clear floats after the header */
        .header:after {
          content: "";
          display: table;
          clear: both;
        }
        
        /* Style the input */
        input {
          margin: 0;
          border: none;
          border-radius: 0;
          width: 75%;
          padding: 10px;
          float: left;
          font-size: 16px;
        }
        
        /* Style the "Add" button */
        .addBtn {
          padding: 10px;
          width: 25%;
          background: #d9d9d9;
          color: #555;
          float: left;
          text-align: center;
          font-size: 16px;
          cursor: pointer;
          transition: 0.3s;
          border-radius: 0;
        }
        
        .addBtn:hover {
          background-color: #bbb;
        }
        
            </style>