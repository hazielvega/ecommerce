<div>
    <h1 class="text-xl font-bold mb-5 text-white">Informes Estadísticos</h1>

    <div class="grid grid-cols-2 gap-6 text-white">
        <!-- Gráfico de Ingresos Mensuales -->
        <div class="mb-10 card">
            <h2 class="text-lg font-semibold mb-4">Ingresos Mensuales</h2>
            <canvas id="revenueChart"></canvas>
        </div>

        <!-- Gráfico de Productos Más Vendidos -->
        <div class="mb-10 card">
            <h2 class="text-lg font-semibold mb-4">Productos Más Vendidos</h2>
            <canvas id="topProductsChart"></canvas>
        </div>

        <!-- Gráfico de Subcategorías Más Vendidas -->
        <div class="mb-10 card">
            <h2 class="text-lg font-semibold mb-4">Subcategorías Más Vendidas</h2>
            <canvas id="topSubcategoriesChart"></canvas>
        </div>

        <!-- Gráfico de Nuevos Clientes -->
        <div class="mb-10 card">
            <h2 class="text-lg font-semibold mb-4">Nuevos clientes</h2>
            <canvas id="newCustomersGrowthChart"></canvas>
        </div>
        
        <div class="mb-10 card">
            <h2 class="text-lg font-semibold mb-4">Ventas por Categoría</h2>
            <canvas id="categorySalesChart"></canvas>
        </div>
    </div>
</div>

<script>
    // Datos para los gráficos
    const revenueLabels = @json($monthlyRevenueLabels);
    const revenueValues = @json($monthlyRevenueValues);

    const topProductsLabels = @json($topProductsLabels);
    const topProductsValues = @json($topProductsValues);

    const subcategoryLabels = @json($topSubcategoriesCombined);
    const subcategoryValues = @json($topSubcategoriesValues);

    const categoryLabels = @json($categoriesLabels);
    const categoryValues = @json($categoriesValues);

    // Gráfico de Ingresos Mensuales
    new Chart(document.getElementById('revenueChart'), {
        type: 'bar',
        data: {
            labels: revenueLabels,
            datasets: [{
                label: 'Ingresos Mensuales',
                data: revenueValues,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                x: {
                    ticks: {
                        color: '#FFFFFF'
                    }
                },
                y: {
                    ticks: {
                        color: '#FFFFFF'
                    },
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        color: 'white'
                    }
                }
            }
        }
    });

    // Gráfico de Productos Más Vendidos
    new Chart(document.getElementById('topProductsChart'), {
        type: 'bar',
        data: {
            labels: topProductsLabels,
            datasets: [{
                label: 'Productos Más Vendidos',
                data: topProductsValues,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                x: {
                    ticks: {
                        color: '#FFFFFF'
                    }
                },
                y: {
                    ticks: {
                        color: '#FFFFFF'
                    },
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        color: 'white'
                    }
                }
            }
        }
    });


    // Gráfico de Subcategorías Más Vendidas
    new Chart(document.getElementById('topSubcategoriesChart'), {
        type: 'bar',
        data: {
            labels: subcategoryLabels,
            datasets: [{
                label: 'Cantidad Vendida',
                data: subcategoryValues,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                x: {
                    ticks: {
                        color: '#FFFFFF'
                    }
                },
                y: {
                    ticks: {
                        color: '#FFFFFF'
                    },
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        title: (tooltipItems) => tooltipItems[0].label
                    }
                }
            }
        }
    });

    // Gráfico de Nuevos Clientes
    new Chart(document.getElementById('newCustomersGrowthChart'), {
        type: 'line',
        data: {
            labels: @json($newCustomersLabels),
            datasets: [{
                label: 'Nuevos Clientes',
                data: @json($newCustomersValues),
                borderColor: '#FF6384',
                fill: false,
                tension: 0.4,
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: true
                }
            }
        }
    });

    new Chart(document.getElementById('categorySalesChart'), {
        type: 'pie',
        data: {
            labels: categoryLabels,
            datasets: [{
                label: 'Ventas por Categoría',
                data: categoryValues,
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
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        color: 'white'
                    }
                }
            }
        }
    });

</script>
</div>
