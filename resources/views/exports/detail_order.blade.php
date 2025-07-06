<table>
    <thead>
        <tr>
            <th>Order Number</th>
            <th>Agent/Sub Agent Name</th>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Total Price</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->detail as $detail)
        <tr>
            <td>{{ $order->order_number }}</td>
            <td>{{ $detail->subAgent ? $detail->subAgent->name : $order->agent->agentProfile->name }}</td>
            <td>{{ $detail->product->name }}</td>
            <td>{{ $detail->qty }}</td>
            <td>{{ $detail->product->price }}</td>
            <td>{{ $detail->qty * $detail->product->price }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
