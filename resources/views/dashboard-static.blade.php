@extends('layouts.app')

@section('title', 'Dashboard Charts')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/chart-styles.css') }}">
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Dashboard Charts</h1>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Room Category Chart -->
        <div>
            @include('components.dashboard.room-category-chart', ['roomCategories' => $roomCategories])
        </div>
        
        <!-- Monthly Trends Chart -->
        <div>
            @include('components.dashboard.monthly-trends-chart', ['monthlyTrends' => $monthlyTrends])
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('js/chart-registry.js') }}"></script>
<script>
    // Global refresh function for all charts
    function refreshChartData() {
        if (typeof refreshRoomCategoryChart === 'function') {
            refreshRoomCategoryChart();
        }
        
        if (typeof refreshMonthlyTrendsChart === 'function') {
            refreshMonthlyTrendsChart();
        }
    }
</script>
@endsection
