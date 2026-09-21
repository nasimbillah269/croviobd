<label for="delivery_date">Delivery Date*</label>
<input type="taxt" readonly="" class="form-control" name="delivery_date" placeholder="Select Delivery Date" id="datepicker" style="cursor: pointer;" required="">
@if ($errors->has('delivery_date'))
<p style="color: red; margin: 0;">{{ $errors->first('delivery_date') }}</p>
@endif

@if(Session::has('dateError'))
<p style="color: red; margin: 0;">{{Session::get('dateError') }}</p>
@endif



<script>
    $(document).ready(function(){
        
    //     var lastDay ={{$afterDay}};
        
    //     var dates = ["04/01/2023", "03/01/2023"];
    //     function DisableDates(date) {
    //         var string = jQuery.datepicker.formatDate('dd/mm/yy', date);
    //         return [dates.indexOf(string) == -1];
    //     }
        
    //     function disabledays(date) {
    //         var day = date.getDay();
    //         //return [{{general()->weekendHolyday()}}];
    //         return ["20/01/2018", "21/01/2018",];
    //     }
        
    //     $( "#datepicker" ).datepicker({
    //     	minDate: +lastDay,
   	// 		maxDate: "+30D",
   	// 		showTimezone: true, 
   	// 		beforeShowDay: DisableDates,
    //         timezone: "+9" 
            
    //     });
    

        
    var lastDay = {{$afterDay}};
    
    var dates = ["01/01/2025", "02/01/2025","31/03/2025"];
    
    function DisableDates(date) {
        var string = jQuery.datepicker.formatDate('dd/mm/yy', date);
        if (dates.indexOf(string) != -1) {
            return [false];
        }
        return [true];
    }

    function disableWeekends(date) {
        var day = date.getDay();
        // var holidays = "[{{ general()->weekendHolyday() }}]; // Example: [0, 6] (Sunday and Saturday)

        // if (holidays.indexOf(day) !== -1) { // If the day is in the holidays array (e.g., 0 = Sunday, 6 = Saturday)
        //     return [true]; // Disable the holiday
        // }
         var offDay ="{{general()->weekend_holyday}}";
        if (day == offDay) {
            return [false];
        }
        return [true];
    }
    
    function disabledays(date) {
            var day = date.getDay();
            //return [{{general()->weekendHolyday()}}];
            return ["20/01/2018", "21/01/2018",];
    }
    
    
    $( "#datepicker" ).datepicker({
        minDate: +lastDay,
        maxDate: "+30D",
        showTimezone: true,
        beforeShowDay: function(date) {
            var isValid = DisableDates(date)[0];
            var isWeekend = disableWeekends(date)[0];
            return [isValid && isWeekend];
            // return [isWeekend];
        },
        timezone: "+9"
    });


    });
</script>