<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
        <script src="http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @if (Auth::check())
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ url('/login') }}">Login</a>
                    @endif
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    Laravel
                    <a href="http://127.0.0.1:8000/customer/register?name=llz&sex=man">rigister</a>
                </div>
                <form action="/validate/test" method="post" id="myForm">
                    {{ csrf_field() }}
                    <input type="text" name="name">
                    <input type="text" name="phone">
                    <input type="text" name="email">
                    <button type="submit">submit</button>
                </form>
                <button type="button" onclick="toCheckdata()">submit</button>
                <div>
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <div class="links">
                    <a href="https://laravel.com/docs">Documentation</a>
                    <a href="https://laracasts.com">Laracasts</a>
                    <a href="https://laravel-news.com">News</a>
                    <a href="https://forge.laravel.com">Forge</a>
                    <a href="https://github.com/laravel/laravel">GitHub</a>
                </div>
            </div>
        </div>
    </body>

<script type="text/javascript">
    $(function() {
    });

    function toCheckdata() {
        alert(21222);
        var param = new Object();
        var name = $('input[name=name]').val();
        var phone = $('input[name=phone]').val();
        var email = $('input[name=email]').val();
        param['name'] = name;
        param['phone'] = phone;
        param['email'] = email;
        param['_token'] = '{{ csrf_token() }}';
        console.log(param);
        // $.post('/validate/test', param, function(data) {
        //     console.log(data);
        // }, 'json');
        $.ajax({
            type: 'POST',
            data: param,
            url: '/validate/test',
            dataType: 'json',
            success: function(data){
                console.log(data);
            },
            error: function(data) {
                console.log(data);
            }
        });
    }
</script>
</html>
