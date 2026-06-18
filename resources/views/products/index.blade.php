<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario de Productos</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body class="p-4">

<div class="container">

    <h2 class="mb-4">📦 Gestión de Productos</h2>

    <button class="btn btn-primary mb-3" id="btnAdd">
        Nuevo Producto
    </button>

    <a href="{{ route('report.monthly') }}" class="btn btn-success mb-3">
        Descargar Reporte del Mes (PDF)
    </a>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Nombre</th>
                <th>SKU</th>
                <th>Categoría</th>
                <th>Stock</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            @foreach($products as $product)
            <tr id="row-{{ $product->id }}">
                <td>{{ $product->name }}</td>
                <td>{{ $product->sku }}</td>
                <td>{{ $product->category->name }}</td>
                <td>{{ $product->stock }}</td>
                <td>{{ $product->price }}</td>

                <td>
                    <button class="btn btn-warning btnEdit"
                        data-id="{{ $product->id }}">
                        Editar
                    </button>

                    <button class="btn btn-danger btnDelete"
                        data-id="{{ $product->id }}">
                        Eliminar
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>

<!-- ================= MODAL ================= -->
<div class="modal fade" id="productModal">
    <div class="modal-dialog">
        <div class="modal-content p-3">

            <h5>Producto</h5>

            <form id="productForm">

                <input type="hidden" id="product_id">

                <select id="category_id" class="form-control mb-2">
                    <option value="">Categoría</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>

                <input type="text" id="name" class="form-control mb-2" placeholder="Nombre">
                <input type="text" id="sku" class="form-control mb-2" placeholder="SKU">
                <input type="number" id="stock" class="form-control mb-2" placeholder="Stock">
                <input type="number" id="price" class="form-control mb-2" placeholder="Precio">

                <button class="btn btn-success w-100">Guardar</button>

            </form>

        </div>
    </div>
</div>

<script>
$(document).ready(function(){

    // =========================
    // VALIDACIÓN FRONTEND
    // =========================
    function validarFormulario() {
        let errors = [];

        if (!$('#category_id').val()) errors.push('Categoría');
        if (!$('#name').val().trim()) errors.push('Nombre');
        if (!$('#sku').val().trim()) errors.push('SKU');
        if (!$('#stock').val()) errors.push('Stock');
        if (!$('#price').val()) errors.push('Precio');

        if (errors.length > 0) {
            alert('Faltan campos: ' + errors.join(', '));
            return false;
        }

        return true;
    }

    // =========================
    // ABRIR MODAL (CREAR)
    // =========================
    $('#btnAdd').click(function(){
        $('#productForm')[0].reset();
        $('#product_id').val('');

        let modal = new bootstrap.Modal(document.getElementById('productModal'));
        modal.show();
    });

    // =========================
    // GUARDAR / ACTUALIZAR
    // =========================
    $('#productForm').submit(function(e){
        e.preventDefault();

        if (!validarFormulario()) return;

        let id = $('#product_id').val();
        let url = id
            ? '{{ url("/products") }}/' + id
            : '{{ url("/products") }}';

        $.ajax({
            url: url,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                _method: id ? 'POST',
                category_id: $('#category_id').val(),
                name: $('#name').val(),
                sku: $('#sku').val(),
                stock: $('#stock').val(),
                price: $('#price').val()
            },
            success: function(res){
                alert(res.message || 'Operación exitosa');
                location.reload();
            },
            error: function(xhr){
                console.log(xhr.responseJSON);
                alert('Error: revisa los datos o duplicados (SKU)');
            }
        });
    });

    // =========================
    // EDITAR
    // =========================
    $(document).on('click', '.btnEdit', function(){

        let row = $(this).closest('tr');

        $('#product_id').val($(this).data('id'));
        $('#name').val(row.find('td:eq(0)').text());
        $('#sku').val(row.find('td:eq(1)').text());
        $('#stock').val(row.find('td:eq(3)').text());
        $('#price').val(row.find('td:eq(4)').text());

        // seleccionar categoría por texto
        let categoryText = row.find('td:eq(2)').text();

        $('#category_id option').filter(function(){
            return $(this).text() === categoryText;
        }).prop('selected', true);

        let modal = new bootstrap.Modal(document.getElementById('productModal'));
        modal.show();
    });

    // =========================
    // ELIMINAR
    // =========================
    $(document).on('click', '.btnDelete', function(){

        let id = $(this).data('id');

        $.ajax({
            url: '{{ url("/products") }}/' + id,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                _method: 'DELETE'
            },
            success: function(){
                $('#row-' + id).remove();
            }
        });
    });



});
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>