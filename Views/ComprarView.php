<!DOCTYPE html>
<html lang="es">
<?php include('Head.php'); ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprar</title>

</head>

<body>
    <section id="container" class="">
        <!-- Header -->
        <header class="header dark-bg">
            <div class="toggle-nav">
                <div class="icon-reorder tooltips" data-original-title="Toggle Navigation" data-placement="bottom">
                    <i class="icon_menu"></i>
                </div>
            </div>
            <?php include("Logo.php") ?>
            <div class="nav search-row" id="top_menu">
                <ul class="nav top-menu">
                    <li>
                        <form class="navbar-form">
                            <!-- Busqueda si es necesaria -->
                        </form>
                    </li>
                </ul>
            </div>
            <?php include("DropDown.php"); ?>
        </header>

        <!-- Menú Principal -->
        <?php include("Menu.php") ?>
    </section>

    <section id="main-content">
        <section class="wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <div class="title has-help" data-toggle="modal" data-target="#helpComprar" style="cursor: pointer;">
                        <h3 class="page-header">
                            <i class="icon_wallet"></i>SEGURO QUE TE GUSTA!
                        </h3>
                    </div>


                    <!--FUNCION DE ALERTA DE MENSAJES-->
                    <?php if (isset($_SESSION['mensaje'])): ?>
                        <div class="alert <?= $_SESSION['alerta'] ?? 'alert-info' ?> alert-dismissible fade in" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <strong><?= $_SESSION['mensaje'] ?></strong>
                        </div>
                    <?php
                        unset($_SESSION['mensaje']);
                        unset($_SESSION['alerta']);
                    endif; ?>

                    <ol class="breadcrumb">
                        <li><i class="fa fa-home"></i><a href="PrincipalController.php">Inicio</a></li>
                        <li><i class="icon_creditcard"></i><a href="ComprarController.php">Productos</a></li>
                        <li><i class="icon_cart_alt"></i><a href="CarritoController.php">Carrito</a></li>
                        <li><i class="icon_wallet_alt"></i><a href="PagarController.php">Pagar</a></li>
                    </ol>
                </div>
            </div>

            <!-- Filtro por categorías -->
            <div class="row mb-4">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-filter"></i> FILTRAR POR CATEGORÍA
                        </div>
                        <div class="panel-body">
                            <div class="btn-group flex-wrap">
                                <a href="ComprarController.php" class="btn btn-<?= !isset($_GET['categoria']) ? 'primary' : 'default' ?>">
                                    Todos los productos
                                </a>
                                <?php foreach ($categorias as $categoria): ?>
                                    <a href="ComprarController.php?categoria=<?= $categoria['id_categoria'] ?>"
                                        class="btn btn-<?= (isset($_GET['categoria']) && $_GET['categoria'] == $categoria['id_categoria']) ? 'primary' : 'default' ?>">
                                        <?= htmlspecialchars($categoria['nombre_categoria']) ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Listado de productos en cuadrícula -->
            <div class="row">
                <?php foreach ($productos as $producto): ?>
                    <div class="col-md-6 col-lg-3">
                        <div class="panel panel-default product-card">
                            <div class="panel-body">
                                <div class="text-center">
                                    <img src="<?= URL_VIEWS . htmlspecialchars($producto['imagen']) ?? '' ?>"
                                        class="product-img img-thumbnail"
                                        alt="<?= htmlspecialchars($producto['nombre_producto']) ?>"
                                        onerror="this.src='<?= URL_VIEWS ?>fotoproducto/default.png'">
                                </div>

                                <h4 class="text mt-2" style="max-width: 10ch; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">

                                    <?= htmlspecialchars($producto['nombre_producto']) ?></h4>

                                <div class="mb-2">
                                    <span class="label label-info">
                                        <?= htmlspecialchars($producto['nombre_categoria']) ?>
                                    </span>
                                    <span class="label label-danger pull-right" style="cursor: pointer;" data-usuario-id="<?= $producto['id_usuario'] ?>">
                                        <?= htmlspecialchars($producto['nombre_vendedor']) ?> <i class="icon_info_alt"></i>
                                    </span>
                                </div>

                                <p class="card-text text-muted small">
                                    <?= htmlspecialchars(substr($producto['descripcion'], 0, 20)) ?>...
                                </p>

                                <h4 class="text-success">$<?= number_format($producto['precio_venta'], 2) ?></h4>

                                <div class="btn-group btn-group-justified mt-2">
                                    <a href="#" class="btn btn-sm btn-primary btn-ver-detalle"
                                        data-id="<?= $producto['id_producto'] ?>" style="width:60px;">
                                        <i class="fa fa-eye"></i> Ver
                                    </a>
                                    <button class="btn btn-sm btn-success add-to-cart"
                                        data-id="<?= $producto['id_producto'] ?>" style="width: 80px;">
                                        <i class="fa fa-shopping-cart"></i>
                                        <span class="cart-text">Añadir</span>
                                    </button>

                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <?php if (empty($productos)): ?>
                    <div class="col-lg-12">
                        <div class="alert alert-info text-center">
                            <i class="fa fa-info-circle fa-2x"></i><br>
                            No se encontraron productos disponibles.
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Paginación -->
            <?php if ($total_paginas > 1): ?>
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <ul class="pagination">
                            <?php if ($pagina_actual > 1): ?>
                                <li><a href="ComprarController.php?pagina=<?= $pagina_actual - 1 ?><?= isset($_GET['categoria']) ? '&categoria=' . $_GET['categoria'] : '' ?>">&laquo;</a></li>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                                <li class="<?= $i == $pagina_actual ? 'active' : '' ?>">
                                    <a href="ComprarController.php?pagina=<?= $i ?><?= isset($_GET['categoria']) ? '&categoria=' . $_GET['categoria'] : '' ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($pagina_actual < $total_paginas): ?>
                                <li><a href="ComprarController.php?pagina=<?= $pagina_actual + 1 ?><?= isset($_GET['categoria']) ? '&categoria=' . $_GET['categoria'] : '' ?>">&raquo;</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Modal para Pagina Principal -->
            <div class="modal fade help-modal" id="helpComprar" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h4 class="modal-title" id="helpModalTitle"><i class="icon_wallet" ></i> Informacion sobre la Tienda </h4>

                        </div>
                        <div class="modal-body">
                            <p>¡Bienvenido a la sección de <strong>Productos</strong>
                            ! Aquí puedes explorar todos los artículos que nuestra comunidad UPIICSA ofrece.
                            Estamos comprometidos con una experiencia de compra y venta <strong>justa y segura</strong> para todos los politécnicos.</p>

                            

                            <h4>¿Cómo usar esta sección?</h4>
                            <ul>
                                <li><strong>Añadir al carrito:</strong> ¿Te interesa un producto? Haz clic en el botón  <i class="fa fa-shopping-cart"></i><strong> Añadir</strong> para guardarlo mientras sigues explorando. .</li>
                                <li><strong>Ver comentarios:</strong> Antes de decidirte, puedes leer lo que otros usuarios opinan del producto o del vendedor. Ve más detalles en la sección de <i class="fa fa-eye"></i><strong> Ver</strong> ¡Tu opinión también es valiosa!</li>
                                <li><strong>Comprar:</strong> Cuando estés listo para adquirir un producto, ve a tu carrito y procede a comprarlo o haz clic en el botón <i class="fa fa-money-bill-alt"></i>  <strong> Pagar en Efectivo </strong></li>
                                <li><strong>Reportar:</strong> Si encuentras un producto inapropiado, una descripción engañosa o tienes algún problema con un vendedor, puedes <i class="fa fa-exclamation-triangle"></i><strong> Reportarlo</strong>.  </li>
                            </ul>

                            

                            <p>Recuerda siempre revisar la descripción del producto y contactar al vendedor si tienes alguna pregunta antes de finalizar tu compra.</p>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Entendido</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </section>

    <?php include('../Views/ProductoDetalleModal.php'); ?>
    <?php include('../Views/UsuarioDetalleModal.php'); ?>
    <?php include("LibraryJs.php"); ?>

    <script>
        $(document).ready(function() {
            var URL_VIEWS = '<?= URL_VIEWS ?>';
            // Configuración inicial para capturar IDs correctamente
            $(document).on('click', '.label-danger', function(e) {
                e.preventDefault();
                const idUsuario = $(this).data('usuario-id');
                console.log('ID Usuario capturado:', idUsuario);
                $('#id_usuario_reportado').val(idUsuario);

                // Obtener ID del administrador desde la sesión PHP
                $('#id_administrador').val(<?php echo $_SESSION['usuario']['id_usuario'] ?? 'null'; ?>);

            });

            // Enviar el reporte con validación y manejo de errores mejorado
            $('#enviarReporteBtn').click(function() {
                const $btn = $(this);
                const idUsuario = $('#id_usuario_reportado').val();
                const idAdmin = $('#id_administrador').val();
                const motivo = $('#motivo').val();

                if (!idUsuario || !idAdmin) {
                    console.error('Faltan IDs de usuario o administrador');
                    showMessage('Error: No se pudo identificar al usuario o administrador', 'error');
                    return;
                }

                if (!motivo) {
                    showMessage('Por favor complete el motivo del reporte', 'error');
                    return;
                }

                // Estado de carga
                $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Enviando...');

                $.ajax({
                    url: '../Controller/ReporteController.php?action=reportarUsuario',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        id_usuario_reportado: idUsuario,
                        id_administrador: idAdmin,
                        motivo: motivo,
                        comentarios: $('#comentarios').val(),
                        tipo_reporte: 'VENDEDOR'
                    },
                    success: function(response) {
                        console.log('Respuesta del servidor:', response);

                        // Cerrar modal y resetear siempre
                        $('#reportarUsuarioModal').modal('hide');
                        $('#reportarUsuarioForm')[0].reset();

                        if (response.success) {
                            showMessage('Reporte enviado correctamente', 'success');
                        } else {
                            showMessage(response.message || 'Error al enviar el reporte', 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error en AJAX:', status, error, xhr.responseText);
                        showMessage('Error de conexión: ' + error, 'error');
                    },
                    complete: function() {
                        $btn.prop('disabled', false).html('Enviar Reporte');
                    }
                });
            });

            // Función auxiliar para mostrar mensajes
            function showMessage(message, type) {
                // Intenta usar toastr si está disponible
                if (typeof toastr !== 'undefined' && toastr[type]) {
                    toastr[type](message);
                } else {
                    // Fallback con alert estándar
                    alert(type.toUpperCase() + ': ' + message);
                }
            }





            $(document).on('click', '.label-danger', function(e) {
                e.preventDefault();
                const idUsuario = $(this).data('usuario-id');
                console.log('ID Usuario:', idUsuario); // <--- AGREGAR ESTA LÍNEA
                const modal = $('#usuarioDetalleModal');

                // Mostrar loading
                modal.find('#loadingUsuario').show();
                modal.find('#usuarioContent').hide();
                modal.modal('show');

                // Obtener datos del usuario via AJAX
                $.ajax({
                    url: 'UsuarioController.php?action=detalle&id=' + idUsuario,

                    type: 'GET',
                    success: function(response) {
                        console.log('Respuesta Success:', response); // <-- AGREGAR ESTA LÍNEA

                        if (response.success) {
                            const usuario = response.data.usuario;
                            const redes = response.data.redes;

                            // Actualizar información básica
                            $('#usuarioFoto').attr('src', URL_VIEWS + (usuario.foto_perfil || 'fotoproducto/user.png'));
                            $('#usuarioNombre').text(usuario.nombre + (usuario.apellido ? ' ' + usuario.apellido : ''));
                            $('#usuarioLogin').text('@' + usuario.login);
                            $('#usuarioFechaNacimiento').text(usuario.fecha_nacimiento);
                            const genero = usuario.genero;
                            const mapeoGenero = {
                                'M': 'Masculino',
                                'F': 'Femenino'
                            };

                            $('#usuarioGenero').text(mapeoGenero[genero] || 'No definido');
                            $('#usuarioEmail').text(usuario.email || 'No proporcionado');
                            $('#usuarioTelefono').text(usuario.telefono || 'No proporcionado');
                            $('#usuarioDireccion').text(usuario.direccion || 'No proporcionada');

                            //FUNCION PARA WHASTAPP
                            const telefono = usuario.telefono;
                            const telefonoLimpio = telefono ? telefono.replace(/\D/g, '') : null; // Elimina caracteres no numéricos
                            const enlaceWhatsApp = telefonoLimpio ? `https://wa.me/${telefonoLimpio}` : null;

                            const telefonoElemento = $('#usuarioTelefono');

                            if (enlaceWhatsApp) {
                                telefonoElemento.html(`<a href="${enlaceWhatsApp}" target="_blank">${telefono} <i class="icon_link_alt"></i></a>`);
                            } else {
                                telefonoElemento.text(telefono || 'No proporcionado');
                            }
                            // Actualizar redes sociales
                            let redesHtml = '';
                            if (response.data.redes && response.data.redes.length > 0) {
                                const redesObjeto = response.data.redes[0]; // Accedemos al primer (y único) objeto dentro del array

                                if (redesObjeto.facebook) {
                                    redesHtml += `
                                <a href="${redesObjeto.facebook}" target="_blank" class="btn btn-sm btn-default">
                                    <i class="fa fa-facebook"></i> Facebook
                                </a> `;
                                }
                                if (redesObjeto.instagram) {
                                    redesHtml += `
                                <a href="${redesObjeto.instagram}" target="_blank" class="btn btn-sm btn-default">
                                    <i class="fa fa-instagram"></i> Instagram
                                </a> `;
                                }
                                if (redesObjeto.linkedin) {
                                    redesHtml += `
                                <a href="${redesObjeto.linkedin}" target="_blank" class="btn btn-sm btn-default">
                                    <i class="fa fa-linkedin"></i> LinkedIn
                                </a> `;
                                }
                                if (redesObjeto.twitter) {
                                    redesHtml += `
                                <a href="${redesObjeto.twitter}" target="_blank" class="btn btn-sm btn-default">
                                    <i class="fa fa-twitter"></i> Twitter
                                </a> `;
                                }

                                if (redesHtml === '') {
                                    redesHtml = '<p class="text-muted">El usuario no ha agregado redes sociales</p>';
                                }
                            } else {
                                redesHtml = '<p class="text-muted">El usuario no ha agregado redes sociales</p>';
                            }
                            $('#usuarioRedes').html(redesHtml);

                            // Mostrar contenido
                            $('#loadingUsuario').hide();
                            $('#usuarioContent').show();
                        } else {
                            modal.find('.modal-body').html(`
                        <div class="alert alert-danger">
                            ${response.message || 'Error al cargar la información del usuario'}
                        </div>
                    `);
                        }
                    },
                    error: function(xhr) {
                        console.log('Error AJAX:', xhr); // <-- AGREGAR ESTA LÍNEA
                        modal.find('.modal-body').html(`
                    <div class="alert alert-danger">
                        Error en la conexión: ${xhr.statusText}
                    </div>
                `);
                    }
                });
            });

            // Efecto hover para las tarjetas
            $('.product-card').hover(
                function() {
                    $(this).css('box-shadow', '0 5px 15px rgba(0,0,0,0.1)');
                },
                function() {
                    $(this).css('box-shadow', 'none');
                }
            );
            // Ver detalle del producto
            $(document).on('click', '.btn-ver-detalle', function(e) {
                e.preventDefault();
                const idProducto = $(this).data('id');
                const modal = $('#productoDetalleModal');

                modal.find('.modal-body').html(`
                    <div class="text-center py-5">
                        <i class="fa fa-spinner fa-spin fa-3x"></i>
                        <p>Cargando detalles del producto...</p>
                    </div>
                `);

                modal.modal('show');

                $.ajax({
                    url: 'ComprarController.php?action=detalle&id=' + idProducto,
                    type: 'GET',
                    success: function(data) {
                        modal.find('.modal-body').html(data);
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al cargar los detalles';
                        if (xhr.responseText) {
                            errorMsg += ': ' + xhr.responseText.substring(0, 100);
                        }
                        modal.find('.modal-body').html(`
                        <div class="alert alert-danger">
                            ${errorMsg}
                            <button class="btn btn-sm btn-default" onclick="window.location.reload()">
                                Recargar
                            </button>
                        </div>
                        `);
                    }
                });
            });


        });

        // Función para actualizar el contador del carrito
        function actualizarContadorCarrito() {
            $.ajax({
                url: 'CarritoController.php?action=contador',
                type: 'GET',
                success: function(response) {
                    $('#header-cart-count').text(response.total || 0);
                },
                error: function() {
                    console.error('Error al obtener contador del carrito');
                }
            });
        }

        // Añadir producto al carrito
        $(document).on('click', '.add-to-cart', function(e) {
            e.preventDefault();
            const idProducto = $(this).data('id');
            const boton = $(this);

            // Guardar el estado original del botón
            const originalText = boton.find('.cart-text').text();
            const originalClass = boton.attr('class');

            // Mostrar feedback visual
            boton.prop('disabled', true);
            boton.find('.cart-text').text('Añadiendo...');

            $.ajax({
                url: 'CarritoController.php?action=agregar',
                type: 'POST',
                dataType: 'json', // Asegurarnos de recibir JSON
                data: {
                    id_producto: idProducto
                },
                success: function(response) {
                    if (response.success) {
                        boton.find('.cart-text').text('Añadido');
                        boton.removeClass('btn-success').addClass('btn-info');
                        actualizarContadorCarrito();

                        // Mostrar notificación
                        toastr.success('Producto añadido al carrito');
                    } else {
                        toastr.error(response.message || 'Error al añadir al carrito');
                        boton.find('.cart-text').text('Añadir');
                        boton.prop('disabled', false);
                    }
                },
                error: function(xhr) {
                    toastr.error('Error en la conexión');
                    boton.find('.cart-text').text('Añadir');
                    boton.prop('disabled', false);
                }
            });
        });

        // Actualizar contador al cargar la página
        $(document).ready(function() {
            actualizarContadorCarrito();
        });
    </script>
</body>

</html>