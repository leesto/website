@section('signoff', 'Regards')
@section('from', 'Backstage Technical Services')

<!DOCTYPE html>
<html>
    <body style="background: #FFF;  font: 15px 'Helvetica Neue',Helvetica,Arial,sans-serif; font-weight: 300; padding: 2em 0;">
        <div style="max-width: 600px;">
            <div style="background: #F6F6F6;border: 1px solid #777;border-radius: 3px;padding: 1em;">
                <h1 style="color: #333;font-size: 19px;margin: 0 0 1em 0;">@yield('title')</h1>
                @yield('content')
                <div style="color: #333;margin-top: 1.5em;">
                    <p style="margin: 0.3em 0 0 0;">@yield('signoff'),</p>
                    <p style="margin: 0.3em 0 0 0;">@yield('from')</p>
                </div>
            </div>
            <p style="color: #777;font-size: 13px;margin: 0.5em 0 0 0;text-align: right;">&copy; Backstage Technical Services {{ date('Y') }}</p>
        </div>
    </body>
</html>