<div class="min-h-screen bg-gray-900 p-6 rounded-lg">
    <h1 class="text-3xl font-bold mb-8 text-white">
        <i class="fas fa-chart-bar mr-3 text-blue-400"></i> Informes Estadísticos
    </h1>

    <!-- Filtros -->
    <div class="bg-gray-800 rounded-xl shadow-lg p-6 mb-8 border border-gray-700">
        <h2 class="text-xl font-semibold text-white flex items-center mb-6">
            <i class="fas fa-filter mr-2 text-blue-400"></i> Filtros
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Filtro por categoría -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Categoría</label>
                <select wire:model.live.debounce.500ms="selected_category"
                    class="w-full bg-gray-700 text-white rounded-lg border-gray-600 focus:ring-blue-500 focus:border-blue-500 px-4 py-2">
                    <option value="">Todas las categorías</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filtro por subcategoría -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Subcategoría</label>
                <select wire:model.live.debounce.500ms="selected_subcategory"
                    class="w-full bg-gray-700 text-white rounded-lg border-gray-600 focus:ring-blue-500 focus:border-blue-500 px-4 py-2"
                    @disabled(empty($subcategories))>
                    <option value="">Todas las subcategorías</option>
                    @foreach ($subcategories as $subcategory)
                        <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filtro por fecha desde -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Desde</label>
                <input type="date" wire:model.live.debounce.500ms="date_from"
                    class="w-full bg-gray-700 text-white rounded-lg border-gray-600 focus:ring-blue-500 focus:border-blue-500 px-4 py-2">
                @error('date_from')
                    <span class="text-red-400 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Filtro por fecha hasta -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Hasta</label>
                <input type="date" wire:model.live.debounce.500ms="date_to"
                    class="w-full bg-gray-700 text-white rounded-lg border-gray-600 focus:ring-blue-500 focus:border-blue-500 px-4 py-2">
                @error('date_to')
                    <span class="text-red-400 text-xs">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>

    {{-- Gráficos --}}
    <div class="space-y-6">
        <!-- Primera fila de gráficos -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Gráfico de productos -->
            <div class="bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-700">
                <h2 class="text-xl font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-chart-line mr-2 text-blue-400"></i> Top 10 Productos Más Vendidos
                </h2>

                <div wire:loading.class="block" wire:loading.class.remove="hidden" class="hidden text-center py-8">
                    <i class="fas fa-spinner fa-spin text-blue-400 text-2xl"></i>
                    <p class="text-gray-300 mt-2">Cargando datos...</p>
                </div>

                <div wire:loading.class="hidden" class="transition-opacity duration-300">
                    @if (count($topProductsData) > 0)
                        <div wire:ignore>
                            <canvas id="topProductsChart" height="300"></canvas>
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-400">
                            No hay datos disponibles para mostrar con los filtros seleccionados
                        </div>
                    @endif
                </div>
            </div>

            <!-- Gráfico de subcategorías -->
            <div class="bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-700">
                <h2 class="text-xl font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-chart-pie mr-2 text-blue-400"></i> Top 10 Subcategorías Más Vendidas
                </h2>

                <div wire:loading.class="block" wire:loading.class.remove="hidden" class="hidden text-center py-8">
                    <i class="fas fa-spinner fa-spin text-blue-400 text-2xl"></i>
                    <p class="text-gray-300 mt-2">Cargando datos...</p>
                </div>

                <div wire:loading.class="hidden" class="transition-opacity duration-300">
                    @if (count($topSubcategoriesData) > 0)
                        <div wire:ignore>
                            <canvas id="topSubcategoriesChart" height="300"></canvas>
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-400">
                            No hay datos disponibles para mostrar con los filtros seleccionados
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Segunda fila de gráficos -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Gráfico de ventas por categoría -->
            <div class="bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-700">
                <h2 class="text-xl font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-chart-pie mr-2 text-blue-400"></i> Distribución de Ventas por Categoría
                </h2>

                <div wire:loading.class="block" wire:loading.class.remove="hidden" class="hidden text-center py-8">
                    <i class="fas fa-spinner fa-spin text-blue-400 text-2xl"></i>
                    <p class="text-gray-300 mt-2">Cargando datos...</p>
                </div>

                <div wire:loading.class="hidden" class="transition-opacity duration-300">
                    @if (count($categoriesSalesData) > 0)
                        <div wire:ignore>
                            <canvas id="categoriesSalesChart" height="300"></canvas>
                        </div>
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-2">
                            @foreach ($categoriesSalesData as $category)
                                <div class="flex items-center text-sm text-gray-300">
                                    <span class="inline-block w-3 h-3 rounded-full mr-2"></span>
                                    {{ $category['name'] }}: {{ $category['percentage'] }}%
                                    ({{ $category['total_quantity'] }} unidades)
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-400">
                            No hay datos disponibles para mostrar con los filtros seleccionados
                        </div>
                    @endif
                </div>
            </div>

            <!-- Gráfico de nuevos usuarios -->
            <div class="bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-700">
                <h2 class="text-xl font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-users mr-2 text-blue-400"></i> Nuevos Usuarios
                </h2>

                <div wire:loading.class="block" wire:loading.class.remove="hidden" class="hidden text-center py-8">
                    <i class="fas fa-spinner fa-spin text-blue-400 text-2xl"></i>
                    <p class="text-gray-300 mt-2">Cargando datos...</p>
                </div>

                <div wire:loading.class="hidden" class="transition-opacity duration-300">
                    @if (count($newUsersData) > 0)
                        <div wire:ignore>
                            <canvas id="newUsersChart" height="300"></canvas>
                        </div>
                        <div class="mt-4 text-sm text-gray-300 text-center">
                            Total de nuevos usuarios: {{ array_sum(array_column($newUsersData, 'count')) }}
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-400">
                            No hay datos disponibles para mostrar con los filtros seleccionados
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        document.addEventListener('livewire:init', () => {
            // Colores para las categorías (usando colores Tailwind)
            const categoryColors = [
                '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6',
                '#EC4899', '#14B8A6', '#F97316', '#64748B', '#84CC16'
            ];

            // Función para obtener color de categoría
            Livewire.getCategoryColor = function(categoryId) {
                const index = categoryId % categoryColors.length;
                return categoryColors[index];
            };

            // Gráfico de productos
            let productsChart = null;

            function initProductsChart(data) {
                const ctx = document.getElementById('topProductsChart')?.getContext('2d');
                if (!ctx) return;

                if (productsChart) productsChart.destroy();

                productsChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.map(item => item.name),
                        datasets: [{
                            label: 'Cantidad Vendida',
                            data: data.map(item => item.total_quantity),
                            backgroundColor: 'rgba(59, 130, 246, 0.7)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: getBarChartOptions()
                });
            }

            // Gráfico de subcategorías
            let subcategoriesChart = null;

            function initSubcategoriesChart(data) {
                const ctx = document.getElementById('topSubcategoriesChart')?.getContext('2d');
                if (!ctx) return;

                if (subcategoriesChart) subcategoriesChart.destroy();

                subcategoriesChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.map(item => item.name),
                        datasets: [{
                            label: 'Cantidad Vendida',
                            data: data.map(item => item.total_quantity),
                            backgroundColor: 'rgba(16, 185, 129, 0.7)',
                            borderColor: 'rgba(16, 185, 129, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: getBarChartOptions({
                        tooltipCallbacks: {
                            afterLabel: function(context) {
                                const dataItem = data[context.dataIndex];
                                return `Categoría: ${dataItem.category_name}`;
                            }
                        }
                    })
                });
            }

            // Gráfico de ventas por categoría
            let categoriesSalesChart = null;

            function initCategoriesSalesChart(data) {
                const ctx = document.getElementById('categoriesSalesChart')?.getContext('2d');
                if (!ctx) return;

                if (categoriesSalesChart) categoriesSalesChart.destroy();

                categoriesSalesChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: data.map(item => item.name),
                        datasets: [{
                            data: data.map(item => item.total_quantity),
                            backgroundColor: data.map(item => Livewire.getCategoryColor(item.id)),
                            borderColor: 'rgba(31, 41, 55, 0.8)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    color: '#fff',
                                    boxWidth: 12,
                                    padding: 16
                                }
                            },
                            tooltip: {
                                enabled: true,
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.raw || 0;
                                        const percentage = data[context.dataIndex].percentage;
                                        return `${label}: ${value} unidades (${percentage}%)`;
                                    }
                                }
                            }
                        },
                        cutout: '60%'
                    }
                });
            }

            // Gráfico de nuevos usuarios
            let newUsersChart = null;

            function initNewUsersChart(data) {
                const ctx = document.getElementById('newUsersChart')?.getContext('2d');
                if (!ctx) return;

                if (newUsersChart) newUsersChart.destroy();

                const dates = data.map(item => item.date);
                const counts = data.map(item => item.count);

                newUsersChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: dates,
                        datasets: [{
                            label: 'Nuevos Usuarios',
                            data: counts,
                            backgroundColor: 'rgba(139, 92, 246, 0.2)',
                            borderColor: 'rgba(139, 92, 246, 1)',
                            borderWidth: 2,
                            tension: 0.3,
                            fill: true,
                            pointBackgroundColor: 'rgba(139, 92, 246, 1)',
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    color: '#fff'
                                }
                            },
                            tooltip: {
                                enabled: true,
                                mode: 'index',
                                intersect: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    color: '#fff',
                                    precision: 0
                                },
                                grid: {
                                    color: 'rgba(255, 255, 255, 0.1)'
                                }
                            },
                            x: {
                                ticks: {
                                    color: '#fff'
                                },
                                grid: {
                                    color: 'rgba(255, 255, 255, 0.1)'
                                }
                            }
                        }
                    }
                });
            }

            // Opciones comunes para gráficos de barras
            function getBarChartOptions(extras = {}) {
                return {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                color: '#fff'
                            }
                        },
                        tooltip: {
                            enabled: true,
                            mode: 'index',
                            intersect: false,
                            callbacks: extras.tooltipCallbacks || {}
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: '#fff',
                                precision: 0
                            },
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)'
                            }
                        },
                        x: {
                            ticks: {
                                color: '#fff',
                                maxRotation: 45,
                                minRotation: 45
                            },
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)'
                            }
                        }
                    }
                };
            }

            // Escuchar eventos de actualización
            Livewire.on('productsChartUpdated', ({
                data
            }) => initProductsChart(data));
            Livewire.on('subcategoriesChartUpdated', ({
                data
            }) => initSubcategoriesChart(data));
            Livewire.on('categoriesSalesChartUpdated', ({
                data
            }) => initCategoriesSalesChart(data));
            Livewire.on('newUsersChartUpdated', ({
                data
            }) => initNewUsersChart(data));

            // Inicializar gráficos con datos iniciales
            initProductsChart(@json($topProductsData));
            initSubcategoriesChart(@json($topSubcategoriesData));
            initCategoriesSalesChart(@json($categoriesSalesData));
            initNewUsersChart(@json($newUsersData));
        });
    </script>
@endpush
