 @extends('template.theme')
 @inject('affiliate', 'App\Models\Affiliate')
 @section('scripts')
@include('admin.template.remove_alert')
@stop

@section('content')
<div class="content">
	
		<div class="ui breadcrumb">
            <a href="{{ url('/') }}" class="section">Home</a>
            <i class="right chevron icon divider"></i>

            <a href="#" class="sidebar open" data-activates="slide-out">Menu</a>
            <i class="right chevron icon divider"></i>

            <div class="active section">Mijn voordeelpas</div>
        </div>
		<div class="ui divider"></div>
		<div class="container">
        
			<div class="up">
				<div class="start">
					<h2>Promoot ons nu! U gaat toch ook liever volledig gratis uiteten?</h2>
					
					<ul class="list">
					
						<li class="item">
							<div class="wrap"><img src="{{asset('images/l1.png')}}" alt="l" /></div>
							<p>1: Klik op de groene knop en plak de link op uw Social-Media, denk aan Facebook of WhatsApp!</p>
						</li>
						
						<li class="item">
							<div class="wrap"><img src="{{asset('images/l2.png')}}" alt="l" /></div>
							<p>2: Klikt er iemand op uw link en hij koopt 2 of meer deals zonder te annuleren, dan ontvangt u €5.- tegoed!</p>
						</li>
						
						<li class="item">
							<div class="wrap"><img src="{{asset('images/l3.png')}}" alt="l" /></div>
							<p>3: Er zit geen limiet aan het aantal nieuwe kopers, €5.- betalen we na 14 dgn. i.v.m. retourrecht! U krijgt eindeloos €5.- tegoed voor iedere nieuwe koper van 2 deals of meer!</p>
						</li>
						
						 
					</ul>
				</div>	
			
			</div>
		</div>
		<div class="ui divider"></div>	
		<div class="ui grid container text-center">           
            <div class="row">
                <div class="col-md-12">
                   {{ucfirst('uw persoonlijke link ')}} 
                    <div class="ui label" style="padding: 15px;">
                        <a href="{{url("source?reference={$reference->reference_code}")}}" id="reference-code" style="opacity: 1;">
                            {{ url("source?reference={$reference->reference_code}") }}
                        </a>                    
                    </div>
                    <a href="javascript:;" class="ui green button large" data-clipboard-target="#reference-code" id="clipboard">
                        <i class="clipboard icon"></i>
                        {{ucfirst('kopieer link') }}
                    </a>
                </div>
                
            
            <div class="col-md-12 text-center">

                <h4>Deel deze link</h4> 			 
                {{--  <span class='addthis_sharing_toolbox'></span>  --}}
                <!-- Go to www.addthis.com/dashboard to customize your tools --> 
                <div class="addthis_inline_share_toolbox"></div>
                <!-- Go to www.addthis.com/dashboard to customize your tools --> <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-59932f986f1f86f2"></script>
    
            </div>
			</div>
                    
        </div>	
		<div class="ui divider"></div>
		<div class="ui grid container">
            <div class="col-md-12">
                <table class="ui very basic sortable collapsing celled table list" style="width: 100%;">
                    <thead>
                        <tr><th>Naam</th><th>Geslacht</th><th>Telefoon</th><th>E-mailadres</th><th>Geregistreerd op</th>
                        <th>Ten gevolge</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($friends as $friend)
                            <tr>
                                <td>{{ @$friend->user->name }}</td>
                                <td>
                                    @if($friend->user->gender == 1)
                                        {{ 'Man' }}
                                    @else
                                        {{ 'Vrouw' }}
                                    @endif
                                </td>
                                <td>{{ @$friend->user->phone }}</td>
                                <td>{{ @$friend->user->email }}</td>
                                <td>{{ @$friend->user->created_at->format('d/m/Y h:i A') }}</td>
                                <td>{{ @$friend->referral() }}</td>
                            </tr>
                        @endforeach
                        @if(count($friends) == 0 )
                            <tr>
                                <td colspan="5">
                                    Helaas er zijn nog geen personen welke via uw link gekocht hebben.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div><br>

</div>
<div class="clear"></div>
@section('scripts')
<script type="text/javascript">
        $(document).ready(function() {
            var clipboard = new Clipboard('#clipboard');
            clipboard.on('success', function(e) {
                e.clearSelection();
                swal({
                    title: "link gekopieerd",
                    text: "Uw link is succesvol gekopieerd.",
                    type: "success",
                    confirmButtonColor: "#6498eb",
                    closeOnConfirm: true
                });
            });
        });
    </script>
@stop
@stop