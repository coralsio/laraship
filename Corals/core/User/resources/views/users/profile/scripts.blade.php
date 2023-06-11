@section('js')

    <script type="text/javascript">
        $(document).ready(function () {


            $(document).on('keyup change drop paste', '#two_factor_auth_enabled', function () {
                if ($(this).prop('checked')) {
                    $('#2fa-details').fadeIn();
                } else {
                    $('#2fa-details').fadeOut();
                }
            });

            function refresh_address(data) {
                $('#profile_addresses').html(data.address_list);
                $('#profile_addresses input').val("");
                $('#profile_addresses select').val("");
            }
        });
    </script>
@endsection
