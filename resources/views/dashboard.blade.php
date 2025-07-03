<x-layouts.app :title="'Dashboard'">
    <div class="flex w-full flex-1 flex-col gap-4 p-4 md:p-6">
        <!-- Filters -->
        <div class="mb-4 flex flex-wrap items-center justify-between gap-4 bg-white p-4 rounded-xl border border-neutral-200 shadow-sm">
        Selamat datang di Dashboard, {{ auth()->user()->name }}!
        </div>
        @if(auth()->user()->role === "role_admin"):
        <div class="mb-4 flex flex-wrap items-center justify-between gap-4 bg-white p-4 rounded-xl border border-neutral-200 shadow-sm">
            <h2 class="text-lg font-semibold text-gray-700">Filter Data</h2>
            <form method="GET" action="{{ route('dashboard') }}" class="flex flex-wrap gap-4">
                <select name="month" onchange="this.form.submit()" class="rounded-md border border-gray-300 px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                    @endfor
                </select>

                <select name="year" onchange="this.form.submit()" class="rounded-md border border-gray-300 px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    @for ($y = date('Y') - 2; $y <= date('Y') + 2; $y++)
                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </form>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white p-4 rounded-xl border border-neutral-200 shadow-sm">
                <h3 class="text-sm font-medium text-gray-500">Pendapatan Bulan Ini</h3>
                <p class="mt-2 text-2xl font-bold text-gray-800">Rp{{ number_format($monthlyIncome ?? 0) }}</p>
            </div>
            <div class="bg-white p-4 rounded-xl border border-neutral-200 shadow-sm">
                <h3 class="text-sm font-medium text-gray-500">Jumlah Pesanan</h3>
                <p class="mt-2 text-2xl font-bold text-gray-800">{{ $totalOrders ?? 0 }}</p>
            </div>
            <div class="bg-white p-4 rounded-xl border border-neutral-200 shadow-sm">
                <h3 class="text-sm font-medium text-gray-500">Meja Terbanyak Digunakan</h3>
                <p class="mt-2 text-2xl font-bold text-gray-800">{{ optional($mostOccupiedTable)->nama ?? '-' }}</p>
            </div>
        </div>

        <!-- Line Chart -->
        <div class="bg-white p-6 rounded-xl border border-neutral-200 shadow-sm mt-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Pendapatan Harian Bulan Ini</h3>
            <div class="w-full" style="height: 350px;">
                <canvas id="incomeLineChart"></canvas>
            </div>
        </div>

        <!-- Other Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
            <div class="bg-white p-6 rounded-xl border border-neutral-200 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Menu Terlaris</h3>
                <div class="w-full" style="height: 300px;">
                    <canvas id="bestSellingMenuChart"></canvas>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl border border-neutral-200 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Meja Paling Sering Digunakan</h3>
                <div class="w-full" style="height: 300px;">
                    <canvas id="mostOccupiedTablesChart"></canvas>
                </div>
            </div>
        </div>
        @endif
    </div>


    <script>
        function initiateCharts() {
            const incomeLineCtx = document.getElementById('incomeLineChart').getContext('2d');
            const bestSellingMenuCtx = document.getElementById('bestSellingMenuChart').getContext('2d');
            const mostOccupiedTablesCtx = document.getElementById('mostOccupiedTablesChart').getContext('2d');

            // Prepare daily income data with proper day labels
            const dailyIncomeData = @json($dailyIncome ?? []);
            const selectedMonth = {{ $month }};
            const selectedYear = {{ $year }};
            const daysInMonth = new Date(selectedYear, selectedMonth, 0).getDate();
            
            // Create arrays for all days of the month
            const dayLabels = [];
            const incomeValues = [];
            
            for (let day = 1; day <= daysInMonth; day++) {
                dayLabels.push(day.toString());
                
                // Find income for this day
                const dayData = dailyIncomeData.find(item => {
                    const itemDate = new Date(item.date);
                    return itemDate.getDate() === day;
                });
                
                incomeValues.push(dayData ? parseFloat(dayData.total) : 0);
            }

            // Income Line Chart
            new Chart(incomeLineCtx, {
                type: 'line',
                data: {
                    labels: dayLabels,
                    datasets: [{
                        label: 'Pendapatan Harian',
                        data: incomeValues,
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { 
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: '#3B82F6',
                            borderWidth: 1,
                            callbacks: {
                                title: function(context) {
                                    return `Tanggal ${context[0].label}`;
                                },
                                label: function(context) {
                                    return 'Pendapatan: Rp' + parseInt(context.raw).toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Tanggal',
                                font: {
                                    size: 12,
                                    weight: 'bold'
                                }
                            },
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Pendapatan (Rp)',
                                font: {
                                    size: 12,
                                    weight: 'bold'
                                }
                            },
                            ticks: {
                                callback: function(value) {
                                    return 'Rp' + value.toLocaleString('id-ID');
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    }
                }
            });

            // Best Selling Menu Chart
            new Chart(bestSellingMenuCtx, {
                type: 'bar',
                data: {
                    labels: @json(array_column($bestSellingMenus ?? [], 'name')),
                    datasets: [{
                        label: 'Terjual (Net)',
                        data: @json(array_column($bestSellingMenus ?? [], 'net_qty')),
                        backgroundColor: '#10B981',
                        borderRadius: 6,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { 
                            display: false 
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: '#10B981',
                            borderWidth: 1,
                            callbacks: {
                                label: function(context) {
                                    return 'Terjual: ' + context.raw + ' porsi';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                callback: function(value) {
                                    return Number.isInteger(value) ? value : '';
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        }
                    }
                }
            });

            // Most Occupied Tables Chart
            new Chart(mostOccupiedTablesCtx, {
                type: 'pie',
                data: {
                    labels: @json(array_column($tableUsage ?? [], 'meja')),
                    datasets: [{
                        label: 'Penggunaan Meja',
                        data: @json(array_column($tableUsage ?? [], 'count')),
                        backgroundColor: [
                            '#10B981',
                            '#F59E0B', 
                            '#EF4444', 
                            '#8B5CF6', 
                            '#EC4899',
                            '#06B6D4',
                            '#84CC16',
                            '#F97316'
                        ],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                usePointStyle: true,
                                padding: 15
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((context.raw / total) * 100).toFixed(1);
                                    return `${context.label}: ${context.raw} kali (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }
        
        if (document.readyState === 'complete') {
            initiateCharts();
        } else {
            document.addEventListener('DOMContentLoaded', initiateCharts);
        }
        document.addEventListener('turbo:load', initiateCharts);
    </script>
</x-layouts.app>