<aside class="Pedido">
            <div class="estatus E{{ $item->status_id }}">{{ $estatuses[$item->status_id] }}</div>
                <div class="Elementos">
                <div class="datito" rel="folio"> {{$item->invoice}}<label>Folio</label></div>
                <div class="datito" rel="factura">Factura {{$item->invoice_number}}<label>Factura</label></div>
                <div class="datito" rel="sucursal"> {{$item->office}} <label>Sucursal</label></div>
                <div class="datito" rel="cliente">{{$item->client}} <label>Cliente</label></div>
                <div rel="mas"><a href="{{ url('pedidos2/masinfo/'.$item->id) }}" class="masinfo">Más Información</a></div>
                <div class="datito" rel="edit"><a class="editar" href="{{ url('pedidos2/pedido/'.$item->id) }}">Actualizar</a></div>

                <div rel="icons" >
                    <div class='iconSet'>
                        <a></a>
                        <a></a>
                        <a></a>
                        <a></a>
                    </div>
                </div>
                </div>
            </aside>