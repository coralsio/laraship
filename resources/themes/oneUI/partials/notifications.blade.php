<script type="text/javascript">
    $(document).ready(function () {
        // handle validation messages

        var msg = '';

        @foreach ($errors->all() as $error)
            msg = msg + "- {{ $error }} <br/>";
        @endforeach

        if (msg.length) {
            themeNotify({
                'level': 'error',
                'message': msg
            });
        }

        // handle status messages
        @if(session('status'))
        themeNotify({
            'level': 'info',
            'message': "{{ session('status') }}"
        });
        @endif

        @if($message = session('notification'))
        themeNotify({
            'level': "{{ $message['level'] }}",
            'message': "{!! $message['message'] !!}"
        });
        @endif

        // handle flash messages
        @foreach (session('flash_notification', collect())->toArray() as $message)
        themeNotify({
            'level': "{{ $message['level'] }}",
            'message': "{!! $message['message'] !!}"
        });
        @endforeach

        {{ session()->forget('flash_notification') }}
        {{ session()->forget('notification') }}
    });
</script>