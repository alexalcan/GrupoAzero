<aside class="Pedido">

            <div class="estatus E{{ $item->status_id }}">{{ $estatuses[$item->status_id] }}</div>
                <div class="Elementos">
                
                
                @if (!empty($item->invoice_number)) 
                <div class="datito" rel="main">{{$item->invoice_number}}<label>Factura</label></div>
                @else
                <div class="datito" rel="main">{{$item->invoice}}<label>Cotización</label></div>
                @endif

                <div class="datito" rel="sec"> {{$item->requisition_code}}<label>Requisición</label></div>

                <div class="datito" rel="sucursal"> {{$item->office}} <label>Sucursal</label></div>
                <div class="datito" rel="cliente">{{$item->client}} <label>Cliente</label></div>
                <div rel="mas"><a href="{{ url('pedidos2/masinfo/'.$item->id) }}" class="masinfo">Más Información</a></div>
                <div class="datito" rel="edit"><a class="editar" href="{{ url('pedidos2/pedido/'.$item->id) }}">Actualizar</a></div>

                <div rel="icons" >
                    <div class='iconSet'>
                        <a class="parciales" title="Parciales" href="{{ url('pedidos2/fragmento/'.$item->id.'/parciales') }}"></a>
                        <a class="ordenf" title="Ordenes de Manufactura" href="{{ url('pedidos2/fragmento/'.$item->id.'/ordenf')}}"></a>
                        <a class="smaterial" title="Salidas de Material" href="{{ url('pedidos2/fragmento/'.$item->id.'/smaterial')}}"></a>
                        <a class="notas" title="Notas" href="{{ url('pedidos2/fragmento/'.$item->id.'/notas')}}"></a>
                    </div>
                </div>

                </div>
            </aside>