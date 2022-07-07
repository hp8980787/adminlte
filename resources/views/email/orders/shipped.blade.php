<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Congratulations, your order has been dispatched</title>
</head>
<body>
<div style="margin: 0 auto;text-align: center;background-color:#f4f6f9">
    <div class="header">
        <h2>Congratulations, your order has been dispatched</h2>
    </div>
    <div class="body" style="padding: 10px 0  10px 0">
        <p>
            Your  transaction id is {{ $trans_id }}.
        </p>
        <p>
            Your order ID is {{ $order_number }}, Your courier number is {{ $ship_no }}
        </p>
        <p>
            Click <a href="{{ $url }}" target="_blank"><button style="width: 50px;background-color: #0c84ff;color: white;border: solid #0c84ff 1px;">here</button></a>for logistics information
        </p>
    </div>

</div>
</body>
</html>
