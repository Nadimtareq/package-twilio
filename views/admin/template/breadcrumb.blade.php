<div class="ui padded breadcrumb">
    <a href="#" class="sidebar open" data-activates="slide-out">Menu</a>
    <i class="right chevron icon divider"></i>

    @if(isset($section) && trim($section) != '')
    <a href="{{ url((isset($noAdmin) ? '' : 'admin/').$slugController.(isset($slugParam) ? $slugParam : '')) }}">{{ $section }}</a>

    <i class="right chevron icon divider"></i>
    @endif

    @if(isset($currentPage))
    <div class="active section">{{ $currentPage }}</div>
    @endif
</div>

<div class="ui divider"></div>