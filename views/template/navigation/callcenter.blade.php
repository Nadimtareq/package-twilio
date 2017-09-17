 @if ($userCallcenter)
    <li class="fixed-row"><a class="item">Callcenter paneel</a></li>
	<li class="fixed-row"><a class="item">Algemeen</a></li>
    <li><a href="{{ url('admin/appointments') }}" ><i class="material-icons notranslate">event_available</i> Afspraken</a></li>
    <li><a href="{{ url('admin/companies/callcenter') }}" ><i class="material-icons notranslate">contact_phone</i> Bellijst</a></li>
    <li><a href="{{ url('admin/reservations/saldo') }}" ><i class="material-icons notranslate">radio_button_unchecked</i> Financieel</a></li>
@endif