<template>
    <div class="container-fluid">
            <div class="row form-group">
                <div class="col-md-4 col-sm-6 box">
                    <div class="card card-pending">
                        <div class="card-body">
                            <p class="card-title">
                                <b><span class="title-icon">P</span>ending Bill(s) in {{ currentMonth }}</b>
                            </p>
                            <p class="card-text">{{ this.pending }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 box">
                    <div class="card card-completed">
                        <div class="card-body">
                            <p class="card-title">
                                <b><span class="title-icon">B</span>ill(s) Received in {{ currentMonth }}</b>
                            </p>
                            <p class="card-text">{{ this.Received }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 box">
                    <div class="card card-process">
                        <div class="card-body">
                            <p class="card-title">
                                <b><span class="title-icon">T</span>otals Bill(s) in year {{ currentYear }}</b>
                            </p>
                            <p class="card-text">{{ this.total_bills }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="isLoading" class="overlay">
                <i class="fas fa-spinner fa-spin"></i>
            </div>
            <div class="row form-group">
                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                    <div class="chart-container cardbox">
                        <canvas id="pie-chart"></canvas>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                    <div class="chart-container cardbox">
                        <canvas id="bar-chart"></canvas>
                    </div>
                </div>
            </div>
    </div>
</template>
<script>
import Chart from 'chart.js';
import { months } from 'moment';

export default {
    data() {
        return {
            currentYear: new Date().getFullYear(),
            pending: '0',
            Received: '0',
            total_bills:'0',
            // under_process: '0',
            pieChartData: null,
            barChartData: null,
            isLoading: false,
        };
    },
    methods: {
        async fetchDashboardDetails() {
            try {
                this.isLoading = true;
                const response = await axios.get('DashboardDetails');
                const data = response.data;
                if(data){
                    this.pending = data.pending;
                    this.Received = data.Received;
                    this.total_bills = data.total_bills;
                // Generate chart data
                    this.pieChartData = {
                        labels: ['Pending Bill(s)', 'Bill(s) Received','Total Bill(s)'],
                        datasets: [
                            {
                                data: [this.pending, this.Received, this.total_bills],
                                backgroundColor: ['#FF4B2B', '#36D1DC', '#8E2DE2'],
                                hoverBackgroundColor: ['#FF4B2B', '#36D1DC', '#8E2DE2']
                            }
                        ]
                    };

                    this.barChartData = {
                        labels: ['Pending Bill(s)', 'Bill(s) Received','Total Bill(s)'],
                        datasets: [
                            {
                                label: 'Bill(s) Count',
                                data: [this.pending, this.Received, this.total_bills],
                                backgroundColor: ['#FF4B2B', '#36D1DC', '#8E2DE2'],
                                hoverBackgroundColor: ['#FF4B2B', '#36D1DC', '#8E2DE2']
                            }
                        ]
                    };

                    // Render charts
                    this.renderPieChart();
                    this.renderBarChart();
                }
            } catch (error) {
                console.error(error)
            }
            this.isLoading = false;
        },
        renderPieChart() {
            const pieChartElement = document.getElementById('pie-chart');
            new Chart(pieChartElement, {
                type: 'pie',
                data: this.pieChartData
            });
        },
        renderBarChart() {
            const barChartElement = document.getElementById('bar-chart');
            new Chart(barChartElement, {
                type: 'bar',
                data: this.barChartData,
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
        },
        
    },
    async mounted() {
        this.fetchDashboardDetails();

    },
    computed: {
        currentMonth() {
            const months = [
                "January", "February", "March", "April",
                "May", "June", "July", "August",
                "September", "October", "November", "December"
            ];

            const currentDate = new Date();
            const monthIndex = currentDate.getMonth();
            return months[monthIndex];
        }
    }
};
</script>

<style>
/* Custom CSS styles */
.overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}
.fa-spinner {
    font-size: 30px;
    color: #fff;
}
.box {
    padding: 10px;
}

.card {
    border-radius: 15px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.card-title {
    color: #333;
    font-size: 24px;
    font-weight: bold;
    text-align: center;
    margin: 0;
    padding: 15px;
    font-family: 'Nunito', sans-serif;
}

.title-icon {
    display: inline-block;
    background-color: #FF4B2B;
    color: #fff;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    text-align: center;
    line-height: 40px;
    margin-right: 10px;
    font-size: 18px;
    font-family: 'Arial', sans-serif;
    font-weight: bold; 
}

.content {
    background-color: #f9f9f9;
    padding: 30px 0;
}

.cardbox {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
}

.card-text {
    background-color: #302b2a;
    color: #fff;
    font-weight: bold;
    text-align: center;
    border-radius: 50%;
    font-size: 18px;
    font-family: 'Nunito', sans-serif;
}

.chart-container {
    position: relative;
    height: 100%;
    width: 100%;
    border-color: #302b2a;
    background: linear-gradient(45deg, #ffffff, #f1f3f3);
    font-family: 'Nunito', sans-serif;
    border-radius: 2%;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    color: #302b2a;
    padding: 10px;
}

/* Optional: Add gradient background to the cards */
.card-pending {
    background: linear-gradient(45deg, hsl(346, 100%, 63%), #FF4B2B);
}

.card-completed {
    background: linear-gradient(45deg, #36D1DC, #5B86E5);
}

.card-process {
    background: linear-gradient(45deg, #8E2DE2, #4A00E0);
}

.card-body {
    background: linear-gradient(45deg, #1892bb, #f1f3f3);
}
</style>
