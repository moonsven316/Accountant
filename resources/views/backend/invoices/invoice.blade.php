<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{  translate('INVOICE') }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta charset="UTF-8">
	<style media="all">
        @page {
			margin: 0;
			padding:0;
		}
		body{
			font-size: 0.875rem;
            font-family: '<?php echo  $font_family ?>';
            font-weight: normal;
            direction: <?php echo  $direction ?>;
            text-align: <?php echo  $text_align ?>;
			padding:0;
			margin:0; 
		}
		.gry-color *,
		.gry-color{
			color:#000;
		}
		table{
			width: 100%;
		}
		table th{
			font-weight: normal;
		}
		table.padding th{
			padding: .25rem .7rem;
		}
		table.padding td{
			padding: .25rem .7rem;
		}
		table.sm-padding td{
			padding: .1rem .7rem;
		}
		.border-bottom td,
		.border-bottom th{
			border-bottom:1px solid #eceff4;
		}
		.text-left{
			text-align:<?php echo  $text_align ?>;
		}
		.text-right{
			text-align:<?php echo  $not_text_align ?>;
		}
		.text-center {
			text-align: center;
		}
		.btn {
			padding: 0.6rem 1.2rem;
			font-size: 0.875rem;
			color: #2a3242;
			font-weight: inherit;
			border-radius: 25px;
		}
		.btn-primary, .btn-soft-primary:hover, .btn-outline-primary:hover {
			background-color: black;
			border-color: black;
			color: white;
		}
		.btn-primary:hover, .btn-primary:focus, .btn-primary:active, .btn-primary.disabled, .btn-primary:disabled, .btn-primary:not(:disabled):not(.disabled).active, .btn-primary:not(:disabled):not(.disabled):active, .show > .btn-primary.dropdown-toggle, .btn-outline-primary:not(:disabled):not(.disabled).active, .btn-outline-primary:not(:disabled):not(.disabled):active, .show > .btn-outline-primary.dropdown-toggle {
			background-color: #ff0000;
			border-color: #ff0000;
		}
	</style>
	<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
</head>
<body>
	<div class="container mb-4 text-right">
        <button class="btn btn-primary" id="download">
            PDFダウンロード
        </button>
    </div>
	<div id="print">
		<div style="padding: 6rem 3rem 4rem 4rem;">
			<table style="padding-bottom: 2rem;">
				<tr>
					<td style="font-size: 2rem; font-weight:bold;" class="text-center strong">請 求 書</td>
				</tr>
			</table>
			<table style="padding-bottom: 2rem;">
				<tr>
					<td style="font-size: 1.5rem; font-weight:bold; border-bottom: 1px solid #ccc;">フェルグラム株式会社</td>
					<td style="border-bottom: 1px solid #ccc;" class="text-right small">御中</td>
					<td class="text-right"></td>
					<td class="text-right"></td>
					<td class="text-right">請求日: </td>
					<td class="text-right small">{{ date('d-m-Y', $order->date) }}</td>
				</tr>
			</table>
			<table style="padding-bottom: 2rem;">
				@php
					$shipping_address = json_decode($order->shipping_address);
				@endphp
				<tr>
					<td style="font-size: 1rem;" class="strong"></td>
					<td class="text-right"  style="font-size: 1rem; font-weight:bold;">{{ $shipping_address->name }}</td>
				</tr>
				<tr>
					<td style="font-size: 1rem;" class="strong"></td>
					<td class="text-right"  style="font-size: 1rem;">{{ $shipping_address->country }}</td>
				</tr>
				<tr>
					<td style="font-size: 1rem;" class="strong"></td>
					<td class="text-right"  style="font-size: 1rem;">{{ $shipping_address->address }}, {{ $shipping_address->city }},  @if(isset(json_decode($order->shipping_address)->state)) {{ json_decode($order->shipping_address)->state }} - @endif {{ $shipping_address->postal_code }}</td>
				</tr>
				<tr>
					<td style="font-size: 1rem;" class="strong">下記の通り、御請求申し上げます。</td>
					<td class="text-right"  style="font-size: 1rem;">TEL: {{ $shipping_address->phone }}</td>
				</tr>
				<tr>
					<td style="font-size: 1rem;" class="strong"></td>
					<td class="text-right"  style="font-size: 1rem;">FAX: {{ $shipping_address->phone }}</td>
				</tr>
				<tr>
					<td style="font-size: 1rem; font-weight:bold; border-bottom: 1px solid #ccc;" class="strong">合計金額：  <span style="font-size: 2rem; font-weight:bold;">{{ single_price($order->grand_total) }}</span><span>(税込)</span></td>
					<td class="text-right"  style="font-size: 1rem;">登録番号:  {{ $order->code }}</td>
				</tr>
			</table>
			<table class="padding text-left small border-bottom">
				<thead>
	                <tr class="gry-color" style="background: #eceff4;">
	                    <th width="35%" class="text-left">{{ translate('Product Name') }}</th>
						<th width="15%" class="text-left">{{ translate('Delivery Type') }}</th>
	                    <th width="10%" class="text-left">数量 </th>
	                    <th width="15%" class="text-left">単価 </th>
	                    <th width="10%" class="text-left">{{ translate('Tax') }}</th>
	                    <th width="15%" class="text-right">金額</th>
	                </tr>
				</thead>
				<tbody class="strong">
	                @foreach ($order->orderDetails as $key => $orderDetail)
		                @if ($orderDetail->product != null)
							<tr class="">
								<td>
                                    {{ $orderDetail->product->name }} 
                                    @if($orderDetail->variation != null) ({{ $orderDetail->variation }}) @endif
                                    <br>
                                    <small>
                                        @php
                                            $product_stock = json_decode($orderDetail->product->stocks->first(), true);
                                        @endphp
                                        {{translate('SKU')}}: {{ $product_stock['sku'] }}
                                    </small>
                                </td>
								<td>
									@if ($order->shipping_type != null && $order->shipping_type == 'home_delivery')
										{{ translate('Home Delivery') }}
									@elseif ($order->shipping_type == 'pickup_point')
										@if ($order->pickup_point != null)
											{{ $order->pickup_point->getTranslation('name') }} ({{ translate('Pickip Point') }})
										@else
                                            {{ translate('Pickup Point') }}
										@endif
									@elseif ($order->shipping_type == 'carrier')
										@if ($order->carrier != null)
											{{ $order->carrier->name }} ({{ translate('Carrier') }})
											<br>
											{{ translate('Transit Time').' - '.$order->carrier->transit_time }}
										@else
											{{ translate('Carrier') }}
										@endif
									@endif
								</td>
								<td class="">{{ $orderDetail->quantity }}</td>
								<td class="currency">{{ single_price($orderDetail->price/$orderDetail->quantity) }}</td>
								<td class="currency">{{ single_price($orderDetail->tax/$orderDetail->quantity) }}</td>
			                    <td class="text-right currency">{{ single_price($orderDetail->price+$orderDetail->tax) }}</td>
							</tr>
		                @endif
					@endforeach
	            </tbody>
			</table>
		</div>

	    <div style="padding:0 1.5rem;">
	        <table class="text-right sm-padding small strong">
	        	<thead>
	        		<tr>
	        			<th width="60%"></th>
	        			<th width="40%"></th>
	        		</tr>
	        	</thead>
		        <tbody>
			        <tr>
			            <td class="text-left">
			            </td>
			            <td>
					        <table class="text-right sm-padding small strong">
						        <tbody>
							        <tr>
							            <th class="gry-color text-left">{{ translate('Sub Total') }}</th>
							            <td class="currency">{{ single_price($order->orderDetails->sum('price')) }}</td>
							        </tr>
							        <tr>
							            <th class="gry-color text-left">{{ translate('Shipping Cost') }}</th>
							            <td class="currency">{{ single_price($order->orderDetails->sum('shipping_cost')) }}</td>
							        </tr>
							        <tr class="border-bottom">
							            <th class="gry-color text-left">{{ translate('Total Tax') }}</th>
							            <td class="currency">{{ single_price($order->orderDetails->sum('tax')) }}</td>
							        </tr>
				                    <tr class="border-bottom">
							            <th class="gry-color text-left">{{ translate('Coupon Discount') }}</th>
							            <td class="currency">{{ single_price($order->coupon_discount) }}</td>
							        </tr>
							        <tr>
							            <th class="text-left strong">{{ translate('Grand Total') }}</th>
							            <td class="currency">{{ single_price($order->grand_total) }}</td>
							        </tr>
						        </tbody>
						    </table>
			            </td>
			        </tr>
		        </tbody>
		    </table>
	    </div>

	</div>
	<script>
        $(document).ready(function() {

            $('#download').on('click', function (e) {
                $('body').css('visibility', 'hidden');
                $('#print').css('visibility', 'visible');
                print();
                $('body').css('visibility', 'visible');
            });
        });
    </script>
</body>
</html>
