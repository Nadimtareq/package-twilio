@inject('companyReservation', 'App\Models\CompanyReservation')

@if (count($userCompanies) >= 1)
	@foreach($userCompanies as $company)
		<li><a href="{{ url('admin/companies/update/'.$company->id.'/'.$company->slug) }}" class="item"><strong>{{ $company->name }}</strong></a></li>

	    <li><a class="item fixed-row "> Reserveringen</a></li>
		<li><a href="{{ url('admin/reservations/clients/'.$company->id) }}" ><i class="material-icons notranslate">check_box_outline_blank</i> Reserveringen</a></li>
	    <li><a href="{{ url('admin/reservations-options/'.$company->slug) }}" ><i class="material-icons notranslate">check_box_outline_blank</i> Aanbiedingen</a></li>
		<li><a href="{{ url('admin/reservations/saldo/'.$company->slug) }}" ><i class="material-icons notranslate">check_box_outline_blank</i> Financieel</a></li>
		<li><a href="{{ url('admin/reservations/update/'.$company->id) }}" ><i class="material-icons notranslate">check_box_outline_blank</i> Instellingen</a></li>

		<li><a href="{{ url('admin/companies/update/'.$company->id.'/'.$company->slug) }}" ><i class="material-icons notranslate">check_box_outline_blank</i> Bedrijfsgegevens</a></li>
		<li><a href="{{ $userAdmin ? url('admin/users') : url('admin/guests/'.$company->slug) }}" ><i class="material-icons notranslate">check_box_outline_blank</i> Gasten</a></li>

		<li><a href="{{ url('admin/invoices/overview/'.$company->slug) }}" ><i class="material-icons notranslate">check_box_outline_blank</i> Facturen</a></li>
		<li><a href="{{ url('admin/barcodes/'.$company->slug) }}" ><i class="material-icons notranslate">check_box_outline_blank</i> Barcodes</a></li>
		<li><a href="{{ url('admin/giftcards/'.$company->slug) }}" ><i class="material-icons notranslate">check_box_outline_blank</i> Giftcards</a></li>
		<li><a href="{{ url('admin/reviews/'.$company->slug) }}" ><i class="material-icons notranslate">check_box_outline_blank</i> Recensies</a></li>
		<li><a href="{{ url('admin/news/'.$company->slug) }}" ><i class="material-icons notranslate">check_box_outline_blank</i> Nieuwsberichten</a></li>
		<li><a href="{{ url('admin/mailtemplates/'.$company->slug) }}" class="item"><i class="material-icons notranslate">check_box_outline_blank</i> Meldingen</a></li>
		<li><a href="{{ url('admin/widgets/'.$company->slug) }}" ><i class="material-icons notranslate">check_box_outline_blank</i> Widgets</a></li>
		
		<li><a href="{{ url('admin/companies/contract/'.$company->id.'/'.$company->slug) }}" target="_blank" class="item"><i class="material-icons notranslate">check_box_outline_blank</i> Contract</a></li>
	@endforeach
@endif

@if (isset($userCompaniesWaiter) && count($userCompaniesWaiter) >= 1)
	@foreach($userCompaniesWaiter as $company)
		<li><a href="{{ url('admin/companies/update/'.$company->slug) }}" ><strong>{{ $company->name }}</strong></a></li>

		<li><a href="{{ url('admin/reservations/clients/'.$company->id) }}" ><i class="material-icons notranslate">check_box_outline_blank</i> Reserveringen</a></li>
		<li><a href="{{ url('admin/reviews/'.$company->slug) }}" class="item"><i class="material-icons notranslate">check_box_outline_blank</i> Recensies</a></li>
	@endforeach
@endif