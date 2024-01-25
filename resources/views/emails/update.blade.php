<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update</title>
    <style>
        /* CSS Reset */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        .invoice {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .header {
            text-align: center;
        }
        .header h1 {
            color: #333;
            margin-bottom: 10px;
        }
        .header p {
            color: #777;
        }
        .content {
            margin-top: 40px;
        }
        .item-table {
            width: 100%;
            border-collapse: collapse;
        }
        .item-table th,
        .item-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ccc;
        }
        .item-table th {
            background-color: #f2f2f2;
        }
        .total-amount {
            margin-top: 20px;
            text-align: right;
        }
        .total-amount p {
            font-weight: bold;
            font-size: 18px;
            margin: 0;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
        }
        .footer p {
            color: #777;
        }
    </style>
</head>
<body>
    <div class="invoice">
        <div class="header">
            <h1>Order Status Update</h1>
            <p>Order ID: {{ $order['order_id'] }}</p>
            <p>Date: {{ $order['date'] }}</p>

            <strong>Your order status now is <span style="color: green">{{ $order['status'] }}</span></strong>
        </div>
        <div class="content">
            <h2>Order Details</h2>
            <p><strong>Customer:</strong> {{ $order['customer'] }}</p>
            <p><strong>store:</strong> {{ $order['storename'] }}</p>



            <table class="item-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Services</th>
                        <th>Quantity</th>
                        {{-- <th>Price</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order['items'] as $item)
                    <tr>
                        <td>{{ $item['product_name'] }}</td>
                        <td>
                            @foreach ($item['services'] as $service)
                                <span>{{ $service }}</span><br>
                            @endforeach
                        </td>
                        <td>{{ $item['quantity'] }}</td>
                        {{-- <td>{{ $item['price'] }}</td> --}}

                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{-- <div class="total-amount">
                <p>Total Amount: {{ $order['total_amount'] }}</p>
                <br>
                <p>VAT: {{ $order['vat'] }}</p>
                <br>
                <p>Grand Total: {{ $order['amount'] }}</p>


            </div> --}}
        </div>
        <div class="footer">
            <p>Thank you for your order!</p>
        </div>
    </div>
</body>
</html>
