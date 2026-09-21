<!--Footer Part Start-->
<footer>
    <!--Main Footer Part Start-->
    <div class="main-footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="footer-widget">
                        <div class="footerAboutInfo">
                             <a href="{{route('index')}}" class="footerLogoAria">
                                  <img src="{{asset(general()->logo())}}" alt="{{asset(general()->title)}}" style="width: 100%;" />
                            </a>
                        </div>
                       <br>
                        <div class="footerSocialLink">
                             <ul>
                            
                            @if(general()->facebook_link)
                            <li>
                                <a href="{{general()->facebook_link}}" target="_blank" ><i class="fa-brands fa-square-facebook"></i></a>
                            </li>
                            @endif
                            
                            @if(general()->twitter_link)
                            <li>
                                <a href="{{general()->twitter_link}}" target="_blank" ><i class="fa-brands fa-x-twitter"></i></a>
                            </li>
                            @endif
                            
                            @if(general()->linkedin_link)
                            <li>
                                <a href="{{general()->linkedin_link}} " target="_blank"><i class="fa-brands fa-linkedin"></i></a>
                            </li>
                            @endif
                            
                            @if(general()->instagram_link)
                            <li>
                                <a href="{{general()->instagram_link}}" target="_blank" ><i class="fa-brands fa-instagram"></i></a>
                            </li>
                            @endif 
                            
                            @if(general()->youtube_link)
                            <li>
                                <a href="{{general()->youtube_link}}" target="_blank" ><i class="fa-brands fa-youtube"></i></a>
                            </li>
                            @endif
                            @if(general()->pinterest_link)
                            <li>
                                <a href="{{general()->pinterest_link}}" target="_blank" ><i class="fa-brands fa-tiktok"></i></a>
                            </li>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-1"></div>

                <div class="col-lg-3 col-md-6">
                    <div class="footer-widget">
                        @if($menu =menu('Footer One'))
                        <h3>{{$menu->name}}</h3>
                        <hr style="margin: 10px 0px 22px; width: 15%; border: 1px solid #2a5c46;" />
                        <ul class="footerLink">
                            <li>
                                @foreach($menu->subMenus as $menu)
                                <a href="{{asset($menu->munuLink())}}">{{$menu->munuName()}}</a>
                                @endforeach
                            </li>
                        </ul>
                        @endif
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="footer-widget">
                        <div class="footer-widget">
                            @if($menu =menu('Footer Two'))
                            <h3>{{$menu->name}}</h3>
                            <hr style="margin: 10px 0px 22px; width: 15%; border: 1px solid #2a5c46;" />
                            <ul class="footerLink">
                                <li>
                                    @foreach($menu->subMenus as $menu)
                                    <a href="{{asset($menu->munuLink())}}">{{$menu->munuName()}}</a>
                                    @endforeach
                                </li>
                            </ul>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6">
                    <div class="footer-widget">
                      <h3>Contact Us</h3>
                         <hr style="margin: 10px 0px 22px; width: 15%; border: 1px solid #2a5c46;" />
                      <div class="footerContactUs">
                          <div class="footerCIcon"><i class="fa-solid fa-location-dot"></i></div>
                         <p>{{general()->address_one}}</p>
                      </div>
                      <div class="footerContactUs">
                          <div class="footerCIcon"><i class="fa-solid fa-phone-volume"></i></div>
                         <p>{{general()->mobile}}</p>
                      </div>
                      <div class="footerContactUs">
                          <div class="footerCIcon"><i class="fa-solid fa-envelope"></i></div>
                         <p>{{general()->email}}</p>
                      </div>
                      
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Main Footer Part Start-->

    <!--Bottom Footer Part Start-->
    <div class="bottom-footer">
        <div class="copyright">
            
            <p>Copyright © 2026 {{general()->title}} - All Rights Reserved - Created by<a class="ml-2" href="" target="_blank">croviobd</a></p>
            
        </div>
    </div>
    <!--Bottom Footer Part Start-->
</footer>
<!--Footer Part End-->


