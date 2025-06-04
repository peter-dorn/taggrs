@extends('layouts.app')

@section('content')
    <div class="bg-white shadow rounded-lg p-6">
        <button id="trackButton"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Track me!
        </button>
        <div id="trackingResult" class="mt-4 hidden">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                Tracking successful!
            </div>
        </div>
    </div>
@endsection

@section('sidebar')
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4">System Status</h2>
        <div id="statusChecks">
            <div class="mb-4">
                <h3 class="font-medium mb-2">Redis Status</h3>
                <div class="text-gray-600">
                    <div class="flex items-center space-x-2">
                        <div id="redisStatus">Redis Queue Size: 0</div>
                        <div id="redisSpinner" class="spinner hidden"></div>
                    </div>
                    <button id="processQueue"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mt-4"
                            type="button">
                        Process Queue
                    </button>
                </div>
            </div>
            <div class="mb-4">
                <h3 class="font-medium mb-2">Database Status</h3>
                <div class="text-gray-600">
                    <div class="flex items-center space-x-2">
                        <div id="databaseStatus">Database Tracking Size: 0</div>
                        <div id="databaseSpinner" class="spinner hidden"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
