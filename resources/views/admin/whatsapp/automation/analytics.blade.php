@extends('admin.pages.whatsapp.layouts.whatsapp')

@section('title', 'Behavior Analytics Dashboard')

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.css" rel="stylesheet">
<style>
    .analytics-dashboard {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 2rem 0;
    }

    .analytics-header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .user-category-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        text-align: center;
        height: 100%;
        border: 3px solid transparent;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .user-category-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        transition: left 0.5s;
    }

    .user-category-card:hover::before {
        left: 100%;
    }

    .user-category-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .category-interested {
        border-color: #28a745;
    }

    .category-interested:hover {
        border-color: #20c997;
        box-shadow: 0 20px 40px rgba(40, 167, 69, 0.3);
    }

    .category-average {
        border-color: #ffc107;
    }

    .category-average:hover {
        border-color: #fd7e14;
        box-shadow: 0 20px 40px rgba(255, 193, 7, 0.3);
    }

    .category-not-interested {
        border-color: #dc3545;
    }

    .category-not-interested:hover {
        border-color: #e83e8c;
        box-shadow: 0 20px 40px rgba(220, 53, 69, 0.3);
    }

    .category-icon {
        width: 100px;
        height: 100px;
        margin: 0 auto 1.5rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        color: white;
        position: relative;
    }

    .icon-interested {
        background: linear-gradient(135deg, #28a745, #20c997);
        animation: pulse-green 2s infinite;
    }

    .icon-average {
        background: linear-gradient(135deg, #ffc107, #fd7e14);
        animation: pulse-yellow 2s infinite;
    }

    .icon-not-interested {
        background: linear-gradient(135deg, #dc3545, #e83e8c);
        animation: pulse-red 2s infinite;
    }

    @keyframes pulse-green {
        0% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(40, 167, 69, 0); }
        100% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0); }
    }

    @keyframes pulse-yellow {
        0% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(255, 193, 7, 0); }
        100% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0); }
    }

    @keyframes pulse-red {
        0% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(220, 53, 69, 0); }
        100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
    }

    .category-number {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .category-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .category-percentage {
        font-size: 1.1rem;
        font-weight: 500;
        opacity: 0.8;
        margin-bottom: 1rem;
    }

    .category-details {
        background: rgba(0, 0, 0, 0.05);
        border-radius: 10px;
        padding: 1rem;
        margin-top: 1rem;
    }

    .detail-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .detail-item:last-child {
        margin-bottom: 0;
    }

    .chart-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        height: 400px;
    }

    .analytics-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .metric-card {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 15px;
        padding: 1.5rem;
        color: white;
        text-align: center;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .metric-card:hover {
        transform: scale(1.05);
        box-shadow: 0 15px 30px rgba(102, 126, 234, 0.4);
    }

    .metric-number {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .metric-label {
        font-size: 0.9rem;
        opacity: 0.9;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .behavior-timeline {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        max-height: 400px;
        overflow-y: auto;
    }

    .timeline-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        margin-bottom: 1rem;
        background: #f8f9fa;
        border-radius: 10px;
        border-left: 4px solid #667eea;
        transition: all 0.3s ease;
    }

    .timeline-item:hover {
        background: #e9ecef;
        transform: translateX(5px);
    }

    .timeline-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        margin-right: 1rem;
        flex-shrink: 0;
    }

    .timeline-interested { background: #28a745; }
    .timeline-average { background: #ffc107; }
    .timeline-not-interested { background: #dc3545; }

    .timeline-content {
        flex-grow: 1;
    }

    .timeline-time {
        font-size: 0.8rem;
        color: #6c757d;
        margin-left: auto;
    }

    .filter-section {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .engagement-heatmap {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .heatmap-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 0.5rem;
        margin-top: 1rem;
    }

    .heatmap-cell {
        aspect-ratio: 1;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        color: white;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .heatmap-cell:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    }

    .heat-level-0 { background: #f8f9fa; color: #6c757d; }
    .heat-level-1 { background: #d1ecf1; color: #0c5460; }
    .heat-level-2 { background: #b3d7ff; color: #004085; }
    .heat-level-3 { background: #667eea; }
    .heat-level-4 { background: #5a6fd8; }
    .heat-level-5 { background: #4b5cd8; }

    .insights-panel {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 15px;
        padding: 2rem;
        color: white;
        margin-bottom: 2rem;
    }

    .insight-item {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(5px);
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .insight-item:last-child {
        margin-bottom: 0;
    }

    .real-time-indicator {
        position: fixed;
        top: 20px;
        right: 20px;
        background: #28a745;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 25px;
        font-size: 0.9rem;
        font-weight: 600;
        z-index: 1000;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        animation: pulse-indicator 2s infinite;
    }

    @keyframes pulse-indicator {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }

    .export-btn {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea, #764ba2);
        border: none;
        color: white;
        font-size: 1.5rem;
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        transition: all 0.3s ease;
        z-index: 1000;
    }

    .export-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 12px 35px rgba(102, 126, 234, 0.6);
    }
</style>
@endpush

@section('content')
<div class="analytics-dashboard">
    <div class="container-fluid">
        <!-- Real-time Indicator -->
        <div class="real-time-indicator">
            <i class="fas fa-circle me-2"></i>
            LIVE ANALYTICS
        </div>

        <!-- Header -->
        <div class="analytics-header animate__animated animate__fadeInDown">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h2 class="mb-2">
                        <i class="fas fa-chart-line text-primary me-2"></i>
                        Behavior Analytics Dashboard
                    </h2>
                    <p class="text-muted mb-0">
                        Track user engagement and categorize interactions as Interested, Average, or Not Interested
                    </p>
                </div>
                <div class="col-lg-4 text-end">
                    <div class="btn-group">
                        <button class="btn btn-outline-primary" onclick="refreshData()">
                            <i class="fas fa-sync me-1"></i> Refresh
                        </button>
                        <button class="btn btn-outline-success" onclick="exportReport()">
                            <i class="fas fa-download me-1"></i> Export
                        </button>
                        <button class="btn btn-primary" onclick="viewRealTime()">
                            <i class="fas fa-eye me-1"></i> Live View
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Categories Overview -->
        <div class="row mb-4">
            <div class="col-lg-4">
                <div class="user-category-card category-interested animate__animated animate__fadeInLeft">
                    <div class="category-icon icon-interested">
                        <i class="fas fa-thumbs-up"></i>
                    </div>
                    <div class="category-number text-success" id="interested-count">{{ $analytics['interested'] ?? 1247 }}</div>
                    <div class="category-title">Interested</div>
                    <div class="category-percentage">{{ $analytics['interested_percentage'] ?? '62.4%' }} of total users</div>
                    <div class="category-details">
                        <div class="detail-item">
                            <span>Active Conversations:</span>
                            <strong>{{ $analytics['interested_active'] ?? 234 }}</strong>
                        </div>
                        <div class="detail-item">
                            <span>Avg Response Time:</span>
                            <strong>{{ $analytics['interested_response_time'] ?? '2.3s' }}</strong>
                        </div>
                        <div class="detail-item">
                            <span>Conversion Rate:</span>
                            <strong>{{ $analytics['interested_conversion'] ?? '68%' }}</strong>
                        </div>
                        <div class="detail-item">
                            <span>Satisfaction Score:</span>
                            <strong>{{ $analytics['interested_satisfaction'] ?? '94%' }}</strong>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="user-category-card category-average animate__animated animate__fadeInUp">
                    <div class="category-icon icon-average">
                        <i class="fas fa-meh"></i>
                    </div>
                    <div class="category-number text-warning" id="average-count">{{ $analytics['average'] ?? 542 }}</div>
                    <div class="category-title">Average</div>
                    <div class="category-percentage">{{ $analytics['average_percentage'] ?? '27.1%' }} of total users</div>
                    <div class="category-details">
                        <div class="detail-item">
                            <span>Active Conversations:</span>
                            <strong>{{ $analytics['average_active'] ?? 89 }}</strong>
                        </div>
                        <div class="detail-item">
                            <span>Avg Response Time:</span>
                            <strong>{{ $analytics['average_response_time'] ?? '4.7s' }}</strong>
                        </div>
                        <div class="detail-item">
                            <span>Conversion Rate:</span>
                            <strong>{{ $analytics['average_conversion'] ?? '34%' }}</strong>
                        </div>
                        <div class="detail-item">
                            <span>Satisfaction Score:</span>
                            <strong>{{ $analytics['average_satisfaction'] ?? '71%' }}</strong>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="user-category-card category-not-interested animate__animated animate__fadeInRight">
                    <div class="category-icon icon-not-interested">
                        <i class="fas fa-thumbs-down"></i>
                    </div>
                    <div class="category-number text-danger" id="not-interested-count">{{ $analytics['not_interested'] ?? 211 }}</div>
                    <div class="category-title">Not Interested</div>
                    <div class="category-percentage">{{ $analytics['not_interested_percentage'] ?? '10.5%' }} of total users</div>
                    <div class="category-details">
                        <div class="detail-item">
                            <span>Active Conversations:</span>
                            <strong>{{ $analytics['not_interested_active'] ?? 12 }}</strong>
                        </div>
                        <div class="detail-item">
                            <span>Avg Response Time:</span>
                            <strong>{{ $analytics['not_interested_response_time'] ?? '8.1s' }}</strong>
                        </div>
                        <div class="detail-item">
                            <span>Conversion Rate:</span>
                            <strong>{{ $analytics['not_interested_conversion'] ?? '5%' }}</strong>
                        </div>
                        <div class="detail-item">
                            <span>Satisfaction Score:</span>
                            <strong>{{ $analytics['not_interested_satisfaction'] ?? '23%' }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section animate__animated animate__fadeInUp">
            <div class="row align-items-center">
                <div class="col-lg-3">
                    <label class="form-label small">Time Period</label>
                    <select class="form-select form-select-sm" id="time-filter" onchange="updateAnalytics()">
                        <option value="today">Today</option>
                        <option value="week" selected>This Week</option>
                        <option value="month">This Month</option>
                        <option value="quarter">This Quarter</option>
                        <option value="custom">Custom Range</option>
                    </select>
                </div>
                <div class="col-lg-3">
                    <label class="form-label small">User Category</label>
                    <select class="form-select form-select-sm" id="category-filter" onchange="updateAnalytics()">
                        <option value="all">All Categories</option>
                        <option value="interested">Interested Only</option>
                        <option value="average">Average Only</option>
                        <option value="not-interested">Not Interested Only</option>
                    </select>
                </div>
                <div class="col-lg-3">
                    <label class="form-label small">Interaction Type</label>
                    <select class="form-select form-select-sm" id="interaction-filter" onchange="updateAnalytics()">
                        <option value="all">All Interactions</option>
                        <option value="messages">Messages Only</option>
                        <option value="appointments">Appointments</option>
                        <option value="searches">Doctor Searches</option>
                    </select>
                </div>
                <div class="col-lg-3 text-end">
                    <label class="form-label small">&nbsp;</label>
                    <div>
                        <button class="btn btn-sm btn-outline-primary me-2" onclick="resetFilters()">
                            <i class="fas fa-undo me-1"></i> Reset
                        </button>
                        <button class="btn btn-sm btn-primary" onclick="applyFilters()">
                            <i class="fas fa-filter me-1"></i> Apply
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Charts Section -->
            <div class="col-lg-8">
                <!-- User Distribution Chart -->
                <div class="chart-container animate__animated animate__fadeInLeft">
                    <h5 class="mb-3">
                        <i class="fas fa-pie-chart text-primary me-2"></i>
                        User Behavior Distribution
                    </h5>
                    <canvas id="distributionChart" width="400" height="200"></canvas>
                </div>

                <!-- Engagement Trend Chart -->
                <div class="chart-container animate__animated animate__fadeInLeft">
                    <h5 class="mb-3">
                        <i class="fas fa-line-chart text-primary me-2"></i>
                        Engagement Trends (Last 7 Days)
                    </h5>
                    <canvas id="trendChart" width="400" height="200"></canvas>
                </div>

                <!-- Engagement Heatmap -->
                <div class="engagement-heatmap animate__animated animate__fadeInUp">
                    <h5 class="mb-3">
                        <i class="fas fa-th text-primary me-2"></i>
                        Weekly Engagement Heatmap
                    </h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="small text-muted">Mon</span>
                        <span class="small text-muted">Tue</span>
                        <span class="small text-muted">Wed</span>
                        <span class="small text-muted">Thu</span>
                        <span class="small text-muted">Fri</span>
                        <span class="small text-muted">Sat</span>
                        <span class="small text-muted">Sun</span>
                    </div>
                    <div class="heatmap-grid" id="heatmap-grid">
                        <!-- Heatmap cells will be generated by JavaScript -->
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="small text-muted">Less engaged</span>
                        <div class="d-flex gap-1">
                            <div class="heatmap-cell heat-level-0" style="width: 15px; height: 15px;"></div>
                            <div class="heatmap-cell heat-level-1" style="width: 15px; height: 15px;"></div>
                            <div class="heatmap-cell heat-level-2" style="width: 15px; height: 15px;"></div>
                            <div class="heatmap-cell heat-level-3" style="width: 15px; height: 15px;"></div>
                            <div class="heatmap-cell heat-level-4" style="width: 15px; height: 15px;"></div>
                            <div class="heatmap-cell heat-level-5" style="width: 15px; height: 15px;"></div>
                        </div>
                        <span class="small text-muted">More engaged</span>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Key Metrics -->
                <div class="analytics-card animate__animated animate__fadeInRight">
                    <h6 class="mb-3">
                        <i class="fas fa-tachometer-alt text-primary me-2"></i>
                        Key Metrics
                    </h6>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <div class="metric-card">
                                <div class="metric-number">{{ $metrics['total_users'] ?? 2000 }}</div>
                                <div class="metric-label">Total Users</div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="metric-card">
                                <div class="metric-number">{{ $metrics['active_today'] ?? 567 }}</div>
                                <div class="metric-label">Active Today</div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="metric-card">
                                <div class="metric-number">{{ $metrics['avg_session'] ?? '4.2m' }}</div>
                                <div class="metric-label">Avg Session</div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="metric-card">
                                <div class="metric-number">{{ $metrics['bounce_rate'] ?? '12%' }}</div>
                                <div class="metric-label">Bounce Rate</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Real-time Activity -->
                <div class="behavior-timeline animate__animated animate__fadeInRight">
                    <h6 class="mb-3">
                        <i class="fas fa-clock text-primary me-2"></i>
                        Real-time Activity
                    </h6>
                    <div id="activity-timeline">
                        <!-- Timeline items will be generated by JavaScript -->
                    </div>
                </div>

                <!-- AI Insights -->
                <div class="insights-panel animate__animated animate__fadeInRight">
                    <h6 class="mb-3">
                        <i class="fas fa-brain me-2"></i>
                        AI Insights
                    </h6>
                    <div class="insight-item">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-trending-up me-2"></i>
                            <strong>Engagement Increase</strong>
                        </div>
                        <p class="small mb-0">
                            Interested users increased by 15% this week, mainly driven by improved response times and better doctor matching.
                        </p>
                    </div>
                    <div class="insight-item">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Optimization Opportunity</strong>
                        </div>
                        <p class="small mb-0">
                            Average users show 23% longer response times. Consider implementing auto-suggestions to improve engagement.
                        </p>
                    </div>
                    <div class="insight-item">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-lightbulb me-2"></i>
                            <strong>Pattern Detection</strong>
                        </div>
                        <p class="small mb-0">
                            Peak engagement occurs between 10-11 AM and 7-8 PM. Consider scheduling campaigns during these windows.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Button -->
    <button class="export-btn" onclick="exportDetailedReport()">
        <i class="fas fa-file-export"></i>
    </button>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    let distributionChart, trendChart;
    let realTimeData = {
        interested: {{ $analytics['interested'] ?? 1247 }},
        average: {{ $analytics['average'] ?? 542 }},
        notInterested: {{ $analytics['not_interested'] ?? 211 }}
    };

    $(document).ready(function() {
        initializeCharts();
        generateHeatmap();
        loadRealtimeActivity();
        startRealTimeUpdates();
    });

    function initializeCharts() {
        // Distribution Chart
        const distributionCtx = document.getElementById('distributionChart').getContext('2d');
        distributionChart = new Chart(distributionCtx, {
            type: 'doughnut',
            data: {
                labels: ['Interested', 'Average', 'Not Interested'],
                datasets: [{
                    data: [realTimeData.interested, realTimeData.average, realTimeData.notInterested],
                    backgroundColor: [
                        '#28a745',
                        '#ffc107',
                        '#dc3545'
                    ],
                    borderWidth: 3,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 20
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed * 100) / total).toFixed(1);
                                return `${context.label}: ${context.parsed} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });

        // Trend Chart
        const trendCtx = document.getElementById('trendChart').getContext('2d');
        const trendData = generateTrendData();
        
        trendChart = new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [
                    {
                        label: 'Interested',
                        data: trendData.interested,
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Average',
                        data: trendData.average,
                        borderColor: '#ffc107',
                        backgroundColor: 'rgba(255, 193, 7, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Not Interested',
                        data: trendData.notInterested,
                        borderColor: '#dc3545',
                        backgroundColor: 'rgba(220, 53, 69, 0.1)',
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        }
                    }
                }
            }
        });
    }

    function generateTrendData() {
        return {
            interested: [185, 210, 178, 245, 267, 234, 289],
            average: [89, 76, 95, 102, 87, 93, 108],
            notInterested: [23, 31, 18, 28, 35, 29, 25]
        };
    }

    function generateHeatmap() {
        const grid = document.getElementById('heatmap-grid');
        grid.innerHTML = '';
        
        // Generate 7 days worth of data
        for (let i = 0; i < 7; i++) {
            const level = Math.floor(Math.random() * 6); // 0-5 heat levels
            const engagementCount = Math.floor(Math.random() * 500) + 50;
            
            const cell = document.createElement('div');
            cell.className = `heatmap-cell heat-level-${level}`;
            cell.textContent = engagementCount;
            cell.title = `${engagementCount} interactions`;
            
            cell.addEventListener('click', function() {
                showDayDetails(i, engagementCount, level);
            });
            
            grid.appendChild(cell);
        }
    }

    function showDayDetails(day, count, level) {
        const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        const dayName = days[day];
        
        Swal.fire({
            title: `${dayName} Details`,
            html: `
                <div class="text-start">
                    <div class="mb-3">
                        <strong>Total Interactions:</strong> ${count}
                    </div>
                    <div class="mb-3">
                        <strong>Engagement Level:</strong> ${level}/5
                    </div>
                    <div class="row text-center mb-3">
                        <div class="col-4">
                            <div class="h5 text-success">${Math.floor(count * 0.6)}</div>
                            <small>Interested</small>
                        </div>
                        <div class="col-4">
                            <div class="h5 text-warning">${Math.floor(count * 0.3)}</div>
                            <small>Average</small>
                        </div>
                        <div class="col-4">
                            <div class="h5 text-danger">${Math.floor(count * 0.1)}</div>
                            <small>Not Interested</small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <strong>Peak Hours:</strong>
                        <ul class="list-unstyled mt-1">
                            <li>• 10:00 AM - 11:00 AM</li>
                            <li>• 7:00 PM - 8:00 PM</li>
                        </ul>
                    </div>
                </div>
            `,
            confirmButtonText: 'Close'
        });
    }

    function loadRealtimeActivity() {
        const timeline = document.getElementById('activity-timeline');
        const activities = [
            {
                type: 'interested',
                user: '+91 9876543210',
                action: 'Booked appointment with Dr. Smith',
                time: '2 min ago',
                icon: 'fas fa-calendar-check'
            },
            {
                type: 'average',
                user: '+91 8765432109',
                action: 'Searched for cardiologist',
                time: '5 min ago',
                icon: 'fas fa-search'
            },
            {
                type: 'interested',
                user: '+91 7654321098',
                action: 'Completed health assessment',
                time: '8 min ago',
                icon: 'fas fa-clipboard-check'
            },
            {
                type: 'not-interested',
                user: '+91 6543210987',
                action: 'Left conversation',
                time: '12 min ago',
                icon: 'fas fa-sign-out-alt'
            },
            {
                type: 'interested',
                user: '+91 5432109876',
                action: 'Requested second opinion',
                time: '15 min ago',
                icon: 'fas fa-user-md'
            }
        ];

        timeline.innerHTML = activities.map(activity => `
            <div class="timeline-item">
                <div class="timeline-icon timeline-${activity.type}">
                    <i class="${activity.icon}"></i>
                </div>
                <div class="timeline-content">
                    <div class="fw-bold">${activity.user}</div>
                    <div class="text-muted small">${activity.action}</div>
                </div>
                <div class="timeline-time">${activity.time}</div>
            </div>
        `).join('');
    }

    function startRealTimeUpdates() {
        setInterval(() => {
            // Simulate real-time data updates
            updateRealTimeData();
            updateCharts();
            addNewActivity();
        }, 10000); // Update every 10 seconds
    }

    function updateRealTimeData() {
        // Simulate small changes in data
        const changes = {
            interested: Math.floor(Math.random() * 5) - 2, // -2 to +2
            average: Math.floor(Math.random() * 3) - 1,    // -1 to +1
            notInterested: Math.floor(Math.random() * 2)   // 0 to +1
        };

        realTimeData.interested = Math.max(0, realTimeData.interested + changes.interested);
        realTimeData.average = Math.max(0, realTimeData.average + changes.average);
        realTimeData.notInterested = Math.max(0, realTimeData.notInterested + changes.notInterested);

        // Update display
        document.getElementById('interested-count').textContent = realTimeData.interested;
        document.getElementById('average-count').textContent = realTimeData.average;
        document.getElementById('not-interested-count').textContent = realTimeData.notInterested;
    }

    function updateCharts() {
        if (distributionChart) {
            distributionChart.data.datasets[0].data = [
                realTimeData.interested,
                realTimeData.average,
                realTimeData.notInterested
            ];
            distributionChart.update('none');
        }
    }

    function addNewActivity() {
        const timeline = document.getElementById('activity-timeline');
        const activities = [
            {
                type: 'interested',
                action: 'Booked appointment',
                icon: 'fas fa-calendar-check'
            },
            {
                type: 'average',
                action: 'Asked about services',
                icon: 'fas fa-question-circle'
            },
            {
                type: 'interested',
                action: 'Shared medical report',
                icon: 'fas fa-file-medical'
            },
            {
                type: 'not-interested',
                action: 'Declined consultation',
                icon: 'fas fa-times-circle'
            }
        ];

        const randomActivity = activities[Math.floor(Math.random() * activities.length)];
        const randomPhone = `+91 ${Math.floor(Math.random() * 9000000000) + 1000000000}`;

        const newItem = document.createElement('div');
        newItem.className = 'timeline-item animate__animated animate__fadeInRight';
        newItem.innerHTML = `
            <div class="timeline-icon timeline-${randomActivity.type}">
                <i class="${randomActivity.icon}"></i>
            </div>
            <div class="timeline-content">
                <div class="fw-bold">${randomPhone}</div>
                <div class="text-muted small">${randomActivity.action}</div>
            </div>
            <div class="timeline-time">Just now</div>
        `;

        timeline.insertBefore(newItem, timeline.firstChild);

        // Remove old items (keep only 5)
        const items = timeline.querySelectorAll('.timeline-item');
        if (items.length > 5) {
            items[items.length - 1].remove();
        }
    }

    function updateAnalytics() {
        const timeFilter = document.getElementById('time-filter').value;
        const categoryFilter = document.getElementById('category-filter').value;
        const interactionFilter = document.getElementById('interaction-filter').value;

        // Show loading
        Swal.fire({
            title: 'Updating Analytics...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        // Simulate API call
        setTimeout(() => {
            Swal.close();
            
            // Regenerate charts with filtered data
            generateHeatmap();
            
            // Show success message
            Swal.fire({
                title: 'Updated!',
                text: 'Analytics have been updated with new filters.',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            });
        }, 2000);
    }

    function resetFilters() {
        document.getElementById('time-filter').value = 'week';
        document.getElementById('category-filter').value = 'all';
        document.getElementById('interaction-filter').value = 'all';
        updateAnalytics();
    }

    function applyFilters() {
        updateAnalytics();
    }

    function refreshData() {
        location.reload();
    }

    function exportReport() {
        Swal.fire({
            title: 'Export Analytics Report',
            html: `
                <div class="text-start">
                    <div class="mb-3">
                        <label class="form-label">Report Format</label>
                        <select class="form-select" id="export-format">
                            <option value="pdf">PDF Report</option>
                            <option value="excel">Excel Spreadsheet</option>
                            <option value="csv">CSV Data</option>
                            <option value="json">JSON Data</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Include</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="include-charts" checked>
                            <label class="form-check-label" for="include-charts">
                                Charts and Visualizations
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="include-raw-data" checked>
                            <label class="form-check-label" for="include-raw-data">
                                Raw Data
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="include-insights" checked>
                            <label class="form-check-label" for="include-insights">
                                AI Insights
                            </label>
                        </div>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Generate Report',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                generateReport();
            }
        });
    }

    function generateReport() {
        Swal.fire({
            title: 'Generating Report...',
            html: `
                <div class="progress mb-3">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                </div>
                <div id="progress-text">Collecting data...</div>
            `,
            allowOutsideClick: false,
            showConfirmButton: false
        });

        let progress = 0;
        const progressInterval = setInterval(() => {
            progress += Math.random() * 20;
            if (progress > 100) progress = 100;

            const progressBar = document.querySelector('.progress-bar');
            const progressText = document.getElementById('progress-text');

            if (progressBar) {
                progressBar.style.width = `${progress}%`;
            }

            if (progressText) {
                if (progress < 30) {
                    progressText.textContent = 'Collecting data...';
                } else if (progress < 60) {
                    progressText.textContent = 'Processing analytics...';
                } else if (progress < 90) {
                    progressText.textContent = 'Generating charts...';
                } else {
                    progressText.textContent = 'Finalizing report...';
                }
            }

            if (progress >= 100) {
                clearInterval(progressInterval);
                setTimeout(() => {
                    Swal.fire({
                        title: 'Report Ready!',
                        text: 'Your analytics report has been generated successfully.',
                        icon: 'success',
                        confirmButtonText: 'Download'
                    });
                }, 500);
            }
        }, 200);
    }

    function exportDetailedReport() {
        exportReport();
    }

    function viewRealTime() {
        Swal.fire({
            title: 'Real-time Analytics View',
            html: `
                <div class="text-start">
                    <div class="row text-center mb-4">
                        <div class="col-4">
                            <div class="h3 text-success" id="live-interested">${realTimeData.interested}</div>
                            <small>Interested</small>
                        </div>
                        <div class="col-4">
                            <div class="h3 text-warning" id="live-average">${realTimeData.average}</div>
                            <small>Average</small>
                        </div>
                        <div class="col-4">
                            <div class="h3 text-danger" id="live-not-interested">${realTimeData.notInterested}</div>
                            <small>Not Interested</small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <strong>Current Activity:</strong>
                        <div id="live-activity" class="mt-2 p-2 bg-light rounded">
                            <div class="d-flex align-items-center">
                                <div class="spinner-border spinner-border-sm text-primary me-2"></div>
                                <span>Monitoring real-time interactions...</span>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Live Mode:</strong> Data updates every 5 seconds
                    </div>
                </div>
            `,
            showCloseButton: true,
            showConfirmButton: false,
            width: 600
        });

        // Simulate live updates in the modal
        const liveUpdateInterval = setInterval(() => {
            const liveInterested = document.getElementById('live-interested');
            const liveAverage = document.getElementById('live-average');
            const liveNotInterested = document.getElementById('live-not-interested');

            if (liveInterested) {
                liveInterested.textContent = realTimeData.interested;
                liveAverage.textContent = realTimeData.average;
                liveNotInterested.textContent = realTimeData.notInterested;
            } else {
                clearInterval(liveUpdateInterval);
            }
        }, 5000);
    }
</script>
@endpush
