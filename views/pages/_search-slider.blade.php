 @if (($userAuth && $userInfo->extension_downloaded == 0) || $userAuth == FALSE)  
<?php 
@$browser = Session::get('browser'); 

?>
<div class="extension-install-overlay" style="display: none;">
    <div class="extension-install-fade">
        <div class="text {{$browser['name']}}">
            <h3>Klik hier!</h3>
            <p style="text-align: left;">Gebruik alle fantastische functionaliteiten van de uwvoordeelpas.nl
                Spaarhulp!</p>
        </div>
    </div>
</div>
<?php
$compatible_browser_array = array('Chrome', 'Firefox', 'Opera');
?>
 

 <div class="search_slider">
    <div class="black-overlay">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="wow bounceInDown">
                        <h1 align="center">Activeer de spaarhulp en ontvang direct €5.-</h1>
                        <p align="center">Spaar nu automatisch bij wel 2500+ webshops.
                            Deze betalen u tot wel 10% dinertegoed bij iedere aankoop!</p>
                        
                           <?php if(in_array($browser['name'], $compatible_browser_array)):?>
                                    <?php if ($userAuth == FALSE): ?>
                                    <button data-browser="{{$browser['name']}}"
                                            class="detectbrowser login button_action" data-type="login"
                                            data-redirect="{{ URL::full('/').'?extension_download_btn=1' }}">Ja ik wil
                                        ook sparen!
                                    </button>
                                    <?php elseif ($userAuth && $userInfo->extension_downloaded == 0): ?>
                                    <button data-browser="{{$browser['name']}}" id="header_extension_button"
                                            class="detectbrowser install-button-ext button_action">Ja ik wil ook sparen!
                                    </button>
                                    <?php endif; ?>
                                    <?php else:?>
                                    <button data-browser="{{$browser['name']}}"
                                            class="incompatible_browser_ext button_action detectbrowser">Ja ik wil ook
                                        sparen!
                                    </button>
                                    <?php endif; ?>
                            
                    </div>
                    {{--  <div class="drop_icon_show">
                        <a href=""><i class="fa fa-chevron-down"></i></a>
                    </div>  --}}
                </div>

            </div>
        </div>
    </div>
</div>
  @endif  

