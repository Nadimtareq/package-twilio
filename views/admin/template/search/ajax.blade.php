<script type="javascript">
$(document).ready(function() {
    // Toggle sortable
    $('table th.active').click(function() {
        var $this = $(this);
        var orderInputVal = $('.orderInput').val();
        var dataColumn = $(this).data('column');
        var fullOrdername = (orderInputVal == 'desc' ? 'ascending' : 'descending');

        $('.sortInput').val(dataColumn);
        $('.orderInput').val((orderInputVal == 'desc' ? 'asc' : 'desc')); 
       
        $this.siblings().removeClass('sorted ' + fullOrdername);

        if($this.hasClass('sorted descending')) {
            $this.removeClass('sorted descending').addClass('sorted ascending')
        } else {
            $this.removeClass('sorted ascending').addClass('sorted descending');
        }
    });

    // Sortable
    $('table th.active').api({
        url    : '{{ URL::to('admin/'.$slugController.'/search') }}',
        method : 'POST',
        serializeForm: true,
        onComplete: function(response) {
            $('.list.search').html(response);
        }
    });
});
</script>