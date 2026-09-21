@extends(App\Models\General::first()->theme.'.layouts.app')
@section('title')
<title>Profile Verify | {{App\Models\General::first()->title}} | {{App\Models\General::first()->subtitle}}</title>
@endsection
@section('SEO')
<meta name="description" content="{!!App\Models\General::latest()->first()->meta_dsc!!}">
<meta name="keywords" content="{{App\Models\General::latest()->first()->meta_key}}">
<meta property="og:title" content="{{App\Models\General::latest()->first()->name}}">
<meta property="og:description" content="{!!App\Models\General::latest()->first()->meta_dsc!!}">
<meta property="og:image" content="{!!App\Models\General::latest()->first()->meta_dsc!!}">
<meta property="og:url" content="{{route('index')}}">
@endsection
@push('css')

<style type="text/css">
    
</style>

@endpush
@section('contents')

<div class="userdashboard">
    <div class="container">
    <div class="row" style="margin:0;">
        <div class="col-lg-3 usersidebardiv">
            @include(App\Models\General::first()->theme.'.customer.includes.sidebar')
            
        </div>
        <div class="col-lg-9 usermainbody">
            <div class="usercontent">
                <div class="myrecentorder">
                    @include(App\Models\General::first()->theme.'.alerts')
                    <p style="font-weight: bold;border-bottom: 1px solid #eaeded;padding: 5px 0;">My Profile Update</p>
                    <div>
                       <form method="POST" action="{{route('customer.profileVerifyUpdatePost')}}">
                            @csrf
                          <div class="form-group row">
                            <label  class="col-sm-3">Update For*</label>
                            <div class="col-sm-9">
                              @if ($errors->has('name'))
                                <p style="color: red;margin: 0;">{{ $errors->first('name') }}</p>
                              @endif
                              @if($type=='email')
                              <input type="text" class="form-control" name="name" value="{{Auth::user()->email}}" disabled="" placeholder="Your No Email">
                              @else
                              <input type="text" class="form-control" name="name" value="{{Auth::user()->mobile}}" disabled="" placeholder="Your No Mobile">
                              @endif
                            </div>
                          </div>
                          <div class="form-group row">
                            <label  class="col-sm-3">Get Verify Code By*</label>
                            <div class="col-sm-9">
                              @if ($errors->has('name'))
                                <p style="color: red;margin: 0;">{{ $errors->first('name') }}</p>
                              @endif
                              	<div class="input-group">
	                                <select class="form-control" id="oldData" name="verytype" >
		                              	@if(Auth::user()->email)
		                              	<option value="{{Auth::user()->email}}">{{Auth::user()->email}}</option>
		                              	@endif
		                              	@if(Auth::user()->mobile)
		                              	<option value="{{Auth::user()->mobile}}">{{Auth::user()->mobile}}</option>
		                              	@endif
		                              </select>
	                                <div class="input-group-append">
                                        <span class="getverifycode getverifycodes input-group-text" >Get Verify Code</span>
                                    </div>
	                            </div>

                            </div>
                          </div>

                          <div class="form-group row">
                            <label  class="col-sm-3">Verify Code*</label>
                            <div class="col-sm-9">
                              @if ($errors->has('name'))
                                <p style="color: red;margin: 0;">{{ $errors->first('name') }}</p>
                              @endif
                                <div class="input-group">
                                    <input type="number" name="verifycode" value="{{old('verifycode')}}" class="form-control" placeholder="Enter Verify Code" required="">
                                    <div class="input-group-append">
                                        <span class="input-group-text activeTimeE " id="textE" style="min-width: 60px;"></span>
                                    </div>
                                </div>
                            </div>
                          </div>

                          <div class="form-group row">
                            <label  class="col-sm-3">New Update For*</label>
                            <div class="col-sm-9">
                            	@if($type=='email')
                              @if ($errors->has('newemail'))
                                <p style="color: red;margin: 0;">{{ $errors->first('newemail') }}</p>
                              @endif
                              <div class="form-group">
                                <input type="email" id="email" name="newemail" value="{{old('newemail')}}" class="form-control" placeholder="Enter New Email Address" required="">
                              <div id="emailMsg"></div>
                              </div>
                              
                            	@else
                              @if ($errors->has('newmobile'))
                                <p style="color: red;margin: 0;">{{ $errors->first('newmobile') }}</p>
                              @endif
                                <div class="form-group">
                                    <input type="number" id="mobile" name="newmobile" value="{{old('newmobile')}}" class="form-control" placeholder="Enter New Mobile Number" required="">
                                    <div id="mobileMsg"></div>
                                </div>
                                
                                @endif
                            </div>
                          </div>
                          <div class="form-group row">
                            <label  class="col-sm-3">Action*</label>
                            <div class="col-sm-9">
                                  <button type="submit" class="btn btn-success authBtn btn-block"><span class="bi bi-check2"></span> Update
                                  </button>
                            </div>
                          </div>
                      </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
@endsection
@push('js')
      <script>
    $(document).ready(function(){


        $("#email").keyup(function(){
            if(validateEmail()){
                $("#email").css("border","2px solid green");
                $("#emailMsg").html("<p class='text-success'>Validated</p>");
            }else{
                $("#email").css("border","2px solid red");
                $("#emailMsg").html("<p class='text-danger'>Un-validated Email</p>");
            }

        });

        $("#mobile").keyup(function(){

            if(validateMobile()){
                $("#mobile").css("border","2px solid green");
                $("#mobileMsg").html("<p class='text-success'>Validated</p>");
            }else{
                $("#mobile").css("border","2px solid red");
                $("#mobileMsg").html("<p class='text-danger'>Un-validated Mobile Nember</p>");
            }
        });


        $(".getverifycodes").click(function(){

                
                 var active =$('.activeTimeE').hasClass('active');
                 var reload =$('.activeTimeE').hasClass('reload');

                 if(reload){
                    location.reload();
                 }

                 if(active){
                    //alert('yes');
                    $("#emailMsg").html("<p class='text-success'>Please Wait Verify Code Your Email.</p>");
                    $("#mobileMsg").html("<p class='text-success'>Please Wait Verify Code Your Mobile.</p>");
                 }else{
                    $('#textE').empty().append(120);
                    $("#emailMsg").html("<p class='text-success'>We Send Verify Code Your Email.</p>");
                    $("#mobileMsg").html("<p class='text-success'>We Send Verify Code Your Mobile.</p>");
                    $('.activeTimeE').addClass('active');

                    var time = $('#textE').text();

                    var number = parseInt($('#textE').text(), 10) || 0; 

                        var interval = setInterval(function () {
                           var time = $('#textE').text(number--); 
                           
                            if (number < 0) {
                                clearInterval(interval);
                                $('#textE').empty().append('Re-Send');
                                $('.activeTimeE').addClass('reload');
                            }else{
                                
                            }
                        }, 1000);

                        // Send Mobile SMS Ajax 
                        //
                        //

                        var data =$("#oldData").val();

                        var url='{{url('/customer/profile-update-verify-code')}}' +'/' +data;

                        $.ajax({
                              type: 'GET',
                              url: url,
                              cache: false,
                              dataType: 'json',
                              success:function(data){
                                  if(data.success==true){
                                    alert(data.verifycode);
                                  }else{
                                      $("#emailMsg").html("<p class='text-danger'>You are register Email Are Worng.</p>");
                                      $("#mobileMsg").html("<p class='text-danger'>You are register Mobile Are Worng.</p>");
                                      $('#textE').empty();
                                      clearInterval(interval);
                                      $('.activeTimeE').removeClass('active');
                                  }
                              },error: function(){
                                  alert('Error');
                              }
                            });

                 }

 
        });

        $(".getverifycodeM").click(function(){

            if(validateMobile()){

                 var active =$('.activeTime').hasClass('active');
                 var reload =$('.activeTime').hasClass('reload');

                 if(reload){
                    location.reload();
                 }

                 if(active){
                    //alert('yes');
                    $("#mobileMsg").html("<p class='text-success'>Please Wait Verify Code Your Number.</p>");
                 }else{
                    $('#textM').empty().append(120);
                    $("#mobileMsg").html("<p class='text-success'>We Send Verify Code Your Number.</p>");
                    $('.activeTime').addClass('active');

                    var time = $('#textM').text();

                    var number = parseInt($('#textM').text(), 10) || 0; 

                        var interval = setInterval(function () {
                           var time = $('#textM').text(number--); 
                           
                            if (number < 0) {
                                clearInterval(interval);
                                $('#textM').empty().append('Re-Send');
                                $('.activeTime').addClass('reload');
                            }else{
                                
                            }
                        }, 1000);

                        // Send Mobile SMS Ajax 
                        //
                        //

                        var data =$("#mobile").val();

                        var url='{{url('/customer/profile-update-verify-code')}}' +'/' +data;
                        $.ajax({
                          type: 'GET',
                          url: url,
                          cache: false,
                          dataType: 'json',
                          success:function(data){
                              if(data.success==true){
                                alert(data.verifycode);
                              }else{
                                $("#mobileMsg").html("<p class='text-danger'>You are already register member. Try Login.</p>");
                                $('#textM').empty();
                                clearInterval(interval);
                                $('.activeTime').removeClass('active');
                              }
                          },error: function(){
                              alert('Error');
                          }
                        });
                 }

            }else{
               $("#mobileMsg").html("<p class='text-danger'>Please Input Validated Mobile Number</p>");
            }
 
        });

        function validateEmail(){
            var email=$("#email").val();
             var reg = /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/
             if(reg.test(email)){
                return true;
             }else{
                return false;
             }
        }


        function validateMobile(){
            var mobile=$("#mobile").val();
             if(mobile.length==11){
                return true;
             }else{
                return false;
             }
        }





        
    });
    
</script>
@endpush