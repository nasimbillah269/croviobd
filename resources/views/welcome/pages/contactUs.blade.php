@extends(general()->theme.'.layouts.app') @section('title')
<title>{{general()->title}} | {{general()->subtitle}}</title>
@endsection @section('SEO')
<meta name="description" content="{!!general()->meta_description!!}" />
<meta name="keywords" content="{{general()->meta_keyword}}" />
<meta property="og:title" content="{{general()->meta_title}}" />
<meta property="og:description" content="{!!general()->meta_description!!}" />
<meta property="og:image" content="{!!general()->meta_description!!}" />
<meta property="og:url" content="{{route('index')}}" />
@endsection @push('css')
@endpush 

@section('contents')

<div class="contact-page">
	<div class="container">
	    
	    <div class="contact-info">
			
			<div class="row">
			    
			    <div class="col-md-6" style="padding: 15px;">
			        <div class="contactLeft">
			        <h3>Contact Information</h3>
			        <p>Find Us Here </p>
			        <div class="media">
        			  <i class="fa fa-map-signs" aria-hidden="true"></i>
        			  <div class="media-body">
        			    <h5 class="mt-0">Office Address</h5>
        			    <p>
        			    	{!!general()->address_one!!}
        			    </p>
        			  </div>
        			</div>
        			<div class="media">
        			  <i class="fa fa-phone" aria-hidden="true"></i>
        			  <div class="media-body">
        			    <h5 class="mt-0">Phone Number</h5>
        			    <p>
        			    	{{general()->mobile}}
        			    </p>
        			  </div>
        			</div>
        			<div class="media">
        			  <i class="fa fa-phone" aria-hidden="true"></i>
        			  <div class="media-body">
        			    <h5 class="mt-0">Email</h5>
        			    <p>
        			    	{{general()->email}}
        			    </p>
        			  </div>
        			</div>
        			 </div>
			    </div>
			    
			    <div class="col-md-6" style="padding: 15px;">
			       <div class="form-info">
                        <h3>Send Messege</h3>
                        <p>Feel Free To Contact Us</p>
                        @if(Session::has('success'))
                        <div class="alert alert-success alert-dismissable">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            <strong>Success! </strong> {{Session::get('success')}}.
                        </div>
                        @endif
                        <form action="{{route('contactMail')}}" id="contactForm" method="post">
                            @csrf
                            <div class="form-group form-group-section">
                                @if ($errors->has('name'))
                                <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('name') }}</p>
                                @endif
                                <input type="name" name="name" value="" class="form-control control-section" placeholder="Enter Name" required="" />
                            </div>
                            <!-- <span class="required">This field is required</span> -->
                            <div class="form-group form-group-section">
                                @if ($errors->has('email'))
                                <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('email') }}</p>
                                @endif
                                <input type="email" name="email" value="" class="form-control control-section" placeholder="Email Address" required="" />
                            </div>
                            <div class="form-group form-group-section">
                                @if ($errors->has('phone'))
                                <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('phone') }}</p>
                                @endif
                                <input type="phone" name="phone" value="" class="form-control control-section" placeholder="Phone Number" required="" />
                            </div>
                            <div class="form-group form-group-section">
                                @if ($errors->has('subject'))
                                <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('subject') }}</p>
                                @endif
                                <input type="subject" name="subject" value="" class="form-control control-section" placeholder="Subject" required="" />
                            </div>
                            <div class="form-group form-group-section">
                                @if ($errors->has('message'))
                                <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('message') }}</p>
                                @endif
                                <textarea name="message" rows="5" value="" class="form-control control-section" placeholder="Write Your Massege" required=""></textarea>
                            </div>
                            <div>

                                <button class="g-recaptcha btn submitbutton" 
                                data-sitekey="6LcAkugrAAAAAMhXP1QjRyVYBRU3KHlVmhmtykvV" 
                                data-callback='onSubmit' 
                                data-action='submit'>Submit</button>
                            </div>
                            <!--<div>-->

                            <!--    <button class=" btn submitbutton" -->
                            <!--    type="submit"-->
                            <!--    >-->
                            <!--        Submit-->
                            <!--    </button>-->
                            <!--</div>-->
                        </form>
                    </div>
			    </div>
			    
			</div>
		</div>
	    
	

    <div class="contact-form-section">
    	<div class="row">
    		<div class="col-md-8">
    			<div class="contact-form">
					<div class="container">
						
					</div>
				</div>
    		</div>

    		<div class="col-md-4">
    			
    		</div>

    	</div>
    </div></div>
</div>

@endsection @push('js')

 <script src="https://www.google.com/recaptcha/api.js"></script>
 <script>
   function onSubmit(token) {
     document.getElementById("contactForm").submit();
   }
 </script>

@endpush