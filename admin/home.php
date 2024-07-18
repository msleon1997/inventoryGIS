<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas del Sistema</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        h1.titulos {
            color: white !important;
            text-align: center;
        }
        .info-box {
            transition: transform 0.2s;
        }
        .info-box:hover {
            transform: scale(1.05);
        }
        #statsChartContainer {
            position: relative;
            height: 50vh; /* Altura del 50% de la altura de la ventana gráfica */
            width: 100%;
        }
    </style>
</head>
<body>
    <h1 class="titulos">Bienvenid@ <?php echo $_settings->info('name') ?></h1>
    <hr>
    <div class="container">
        <div class="row justify-content-center">
            <?php
            // Obteniendo los datos de las tablas
            $purchase_order_count = $conn->query("SELECT * FROM `ordenes_compra`")->num_rows;
            $back_order_count = $conn->query("SELECT * FROM `tdevoluciones`")->num_rows;
            $sales_count = $conn->query("SELECT * FROM `tventas`")->num_rows;
            $supplier_count = $conn->query("SELECT * FROM `tproveedores` WHERE `estado_proveedor` = 2")->num_rows;
            $product_count = $conn->query("SELECT * FROM `tproducto` WHERE `PRO_Estado` = 1")->num_rows;
            $user_count = $_settings->userdata('type') == 1 ? $conn->query("SELECT * FROM `users` WHERE id != 1")->num_rows : 0;

            $modules = [
                'Órdenes de Compra' => ['icon' => 'fas fa-th-list', 'link' => '?page=ordenes', 'count' => $purchase_order_count],
                'Devoluciones' => ['icon' => 'fas fa-exchange-alt', 'link' => '?page=devoluciones', 'count' => $back_order_count],
                'Ventas' => ['icon' => 'fas fa-file-invoice-dollar', 'link' => '?page=ventas', 'count' => $sales_count],
                'Proveedores' => ['icon' => 'fas fa-truck-loading', 'link' => '?page=proveedores', 'count' => $supplier_count],
                'Productos' => ['icon' => 'fas fa-th-list', 'link' => '?page=productos', 'count' => $product_count],
                'Usuarios' => ['icon' => 'fas fa-users', 'link' => '?page=user', 'count' => $user_count]
            ];

            foreach ($modules as $module_name => $module_info) {
                echo "
                <div class='col-12 col-sm-6 col-md-4'>
                    <a href='{$module_info['link']}' class='text-dark'>
                        <div class='info-box bg-light shadow'>
                            <span class='info-box-icon bg-warning elevation-1'><i class='{$module_info['icon']}'></i></span>
                            <div class='info-box-content'>
                                <span class='info-box-text'>{$module_name}</span>
                                <span class='info-box-number text-right'>{$module_info['count']}</span>
                            </div>
                        </div>
                    </a>
                </div>
                ";
            }
            ?>
        </div>
        <div id="statsChartContainer">
            <canvas id="statsChart"></canvas>
        </div>
    </div>

    <script>
        var ctx = document.getElementById('statsChart').getContext('2d');
        var statsChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Órdenes de Compra', 'Devoluciones', 'Ventas', 'Proveedores', 'Productos', 'Usuarios'],
                datasets: [{
                    label: 'Cantidad de Registros',
                    data: [
                        <?php echo $purchase_order_count; ?>,
                        <?php echo $back_order_count; ?>,
                        <?php echo $sales_count; ?>,
                        <?php echo $supplier_count; ?>,
                        <?php echo $product_count; ?>,
                        <?php echo $user_count; ?>
                    ],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
