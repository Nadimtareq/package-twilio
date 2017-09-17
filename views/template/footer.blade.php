<footer id="footer">
		<div class="container">
			<div class="row">
				<div class="col-sm-12 col-footer footer">
					
					<div class="col-sm-3  col-xs-12 col">
						{!! isset($contentBlock[4]) ? $contentBlock[4] : '' !!}
						@if(isset($pageLinks[1]))
						<ul class="ui inverted">
							<li class="title">
								<h3 ><i class="fa fa-caret-down" aria-hidden="true"></i>  Leden</h3>
								<div class="ui divider"></div>								
							</li>
							<div class="content">
							@foreach ($pageLinks[1] as $data)
							    <li><a href="{{ url($data['slug']) }}" id="{{ (isset($data['link_to']) && $data['link_to'] == 'register' ? 'registerButton4' : '') }}" class="{{ isset($data['link_to']) && $data['link_to'] == 'login' ? 'login button' : '' }}" data-type="login">{{ $data['title'] }}</a></li>
							@endforeach
							</div>
						</ul>
						@endif
						<div class="clear"></div>
					</div>
					
					<div class="col-sm-3 col-xs-12  col">
					    {!! isset($contentBlock[5]) ? $contentBlock[5] : '' !!}
					    @if(isset($pageLinks[2]))
						<ul class="ui inverted">
							<li class="title">
								 <h3><i class="fa fa-caret-down" aria-hidden="true"></i>  Bedrijven</h3>
								 <div class="ui divider"></div>
							</li>
							<div class="content">
						    @foreach ($pageLinks[2] as $data)
								<li><a href="{{ url($data['slug']) }}" id="{{ (isset($data['link_to']) && $data['link_to'] == 'register' ? 'registerButton4' : '') }}" class="{{ isset($data['link_to']) && $data['link_to'] == 'login' ? 'login button' : '' }}" data-type="login">{{ $data['title'] }}</a></li>
							@endforeach
							</div>
						</ul>
						@endif
						<div class="clear"></div>
					</div>
					
					<div class="col-sm-3 col-xs-12  col ">
						{!! isset($contentBlock[6]) ? $contentBlock[6] : '' !!}
						@if(isset($pageLinks[3]))
						<ul class="ui inverted">
							<li class="title">
								<h3><i class="fa fa-caret-down" aria-hidden="true"></i>  Algemeen</h3>
								<div class="ui divider"></div>
							</li>
							<div class="content">
							@foreach ($pageLinks[3] as $data)
								<li><a href="{{ url($data['slug']) }}" id="{{ (isset($data['link_to']) && $data['link_to'] == 'register' ? 'registerButton4' : '') }}" class="{{ isset($data['link_to']) && $data['link_to'] == 'login' ? 'login button' : '' }}" data-type="login">{{ $data['title'] }}</a></li>
							@endforeach
							</div>

						</ul>
						@endif
						<div class="clear"></div>
					</div>
					
					<div class="col-sm-3  col-xs-12  col">
						{!! isset($contentBlock[7]) ? $contentBlock[7] : '' !!}
						@if(isset($pageLinks[4]))
						<ul class="ui inverted">
							<li class="title">
								<h3><i class="fa fa-caret-down" aria-hidden="true"></i>  Voorwaarden</h3>
								<div class="ui divider"></div>
							</li>
							<div class="content">
							   @foreach ($pageLinks[4] as $data)
									<li><a href="{{ url($data['slug']) }}" id="{{ (isset($data['link_to']) && $data['link_to'] == 'register' ? 'registerButton4' : '') }}" class="{{ isset($data['link_to']) && $data['link_to'] == 'login' ? 'login button' : '' }}" data-type="login">{{ $data['title'] }}</a></li>
                               @endforeach
							 </div>
						</ul>
						@endif
						<div class="clear"></div>
					</div>
					<div class="clear"></div>
				</div>
			</div>
		</div>
		<!-- ./container end-->
	</footer>
<!--Start of Tawk.to Script-->

<!--<script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
        var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
        s1.async=true;
        s1.src='https://embed.tawk.to/59541d1d50fd5105d0c831cb/default';
        s1.charset='UTF-8';
        s1.setAttribute('crossorigin','*');
        s0.parentNode.insertBefore(s1,s0);
    })();
	
	var open = "<?php echo isset($_GET['n']) ? $_GET['n'] : '';?>";
	if(open == 'open') {
		setTimeout(function(){
			jQuery('a.login')[0].click();
		}, 500);
	}
</script>-->
<!--End of Tawk.to Script-->