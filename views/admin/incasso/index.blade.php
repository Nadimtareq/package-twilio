@extends('template.theme')
@section('content')
<div class="content">
    @include('admin.template.breadcrumb')
    <div class="ui grid">
        <div class="sixteen wide mobile nine wide computer column">
            <?php echo Form::open(array('method' => 'GET', 'class' => 'ui form')); ?>
            <div class="three fields">
                <div class="field">
                    <?php echo Form::select('month', array_unique($months), (Request::has('month') ? Request::has('month') : date('m')), array('class' => 'multipleSelect', 'data-placeholder' => 'Maand')); ?>
                </div>

                <div class="field">
                     <?php echo Form::select('year', array_unique($years), (Request::has('year') ? Request::get('year') : date('Y')), array('class' => 'multipleSelect', 'data-placeholder' => 'Jaar')); ?>
                </div>

                <div class="field">
                    <input type="submit" class="ui blue fluid filter button" value="Filteren" />
                </div>
            </div>
            <?php echo Form::close(); ?>
        </div>

        <div class="sixteen wide mobile seven wide computer column">
            <a href="{{ url('admin/incassos/generate/incasso') }}" class="ui button">
                <i class="refresh icon"></i>
                Incasso genereren
            </a>

            <div class="ui normal selection dropdown">
                <input type="hidden" name="type" value="{{ Request::input('type') }}">
                <i class="dropdown icon"></i>
                
                <div class="default text">Soort incasso</div>

                <div class="menu">
                    <a href="{{ url('admin/incassos?type=debit') }}" class="item" data-value="debit">Incassos</a>
                    <a href="{{ url('admin/incassos?type=payments') }}" class="item" data-value="payments">Uitbetalingen</a>
                </div>
            </div>
        </div>
    </div>

    <?php echo Form::open(array('id' => 'formList', 'method' => 'post')) ?>
    <table class="ui sortable very basic collapsing celled unstackable table" style="width: 100%;">
        <thead>
            <tr>
                <th data-slug="created_at" class="three wide">Datum</th>
                <th data-slug="amount" class="three wide">Totaalbedrag</th>
                <th data-slug="no_of_invoices" class="one wide">Aantal facturen</th>
                <th data-slug="type" class="three wide">Type</th>
                <th data-slug="disabled" class="disabled three wide"></th>
            </tr>
        </thead>
        <tbody class="list search">
            @if(count($incassos) >= 1)
                @foreach($incassos as $incasso)
                    <tr>
                        <td>{{ date('d-m-Y', strtotime($incasso->created_at)) }}</td>
                        <td>&euro;{{ ($incasso->amount * 1) }}</td>
                        <td>{{ $incasso->no_of_invoices }}</td>
                        <td>  
                            @if ($incasso->type == 'debit')
                            Incassos
                            @elseif ($incasso->type == 'payments')
                            Uitbetalingen
                            @endif
                        </td>
                        <td>
                            <a href="{{ url('/admin/incassos/download/'.$incasso->id) }}" class="ui icon button">
                                <i class="download icon"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="2"><div class="ui error message">Er is geen data gevonden.</div></td>
                </tr>
            @endif
            </tbody>
    </table>
    <?php echo Form::close(); ?>

            {!! with(new \App\Presenter\Pagination($incassos->appends($paginationQueryString)))->render() !!}

</div>
@stop