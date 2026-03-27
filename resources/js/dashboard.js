import Chart from "chart.js/auto";

document.addEventListener("DOMContentLoaded", () => {
    const data = window.dashboardData;
    if (!data) return;

    const commonOptions = {
        maintainAspectRatio: false,
        plugins: {
            legend: {
                labels: {
                    color: "#374151",
                    font: { size: 12 },
                },
            },
        },
        scales: {
            x: {
                ticks: { color: "#6B7280" },
                grid: { display: false },
            },
            y: {
                ticks: { color: "#6B7280" },
                grid: { color: "#E5E7EB" },
            },
        },
    };

    new Chart(document.getElementById("usageChart"), {
        type: "line",
        data: {
            labels: data.usageLabels,
            datasets: [
                {
                    label: "Pemakaian",
                    data: data.usageData,
                    borderColor: "#3B82F6",
                    backgroundColor: "rgba(59,130,246,0.1)",
                    fill: true,
                    tension: 0.4,
                    pointRadius: 3,
                },
            ],
        },
        options: commonOptions,
    });

    new Chart(document.getElementById("vehicleChart"), {
        type: "bar",
        data: {
            labels: data.vehicleLabels,
            datasets: [
                {
                    label: "Jumlah",
                    data: data.vehicleData,
                    backgroundColor: "#10B981",
                    borderRadius: 8,
                },
            ],
        },
        options: commonOptions,
    });

    new Chart(document.getElementById("statusChart"), {
        type: "doughnut",
        data: {
            labels: data.statusLabels,
            datasets: [
                {
                    data: data.statusData,
                    backgroundColor: [
                        "#3B82F6",
                        "#10B981",
                        "#F59E0B",
                        "#EF4444",
                    ],
                    borderWidth: 0,
                },
            ],
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: "bottom",
                },
            },
        },
    });

    new Chart(document.getElementById("driverChart"), {
        type: "bar",
        data: {
            labels: data.driverLabels,
            datasets: [
                {
                    label: "Driver Usage",
                    data: data.driverData,
                    backgroundColor: "#6366F1",
                    borderRadius: 8,
                },
            ],
        },
        options: commonOptions,
    });

    new Chart(document.getElementById("approvalChart"), {
        type: "doughnut",
        data: {
            labels: data.approvalTimeLabels.map((l) => "Level " + l),
            datasets: [
                {
                    data: data.approvalTimeData,
                    backgroundColor: ["#F59E0B", "#10B981"],
                },
            ],
        },
        options: {
            maintainAspectRatio: false,
        },
    });

    new Chart(document.getElementById("driverKmChart"), {
        type: "bar",
        data: {
            labels: data.driverKmLabels,
            datasets: [
                {
                    label: "KM",
                    data: data.driverKmData,
                    backgroundColor: "#F59E0B",
                },
            ],
        },
        options: {
            indexAxis: "y",
            maintainAspectRatio: false,
        },
    });

    new Chart(document.getElementById("approvalTimeChart"), {
        type: "bar",
        data: {
            labels: data.approvalTimeLabels.map((l) => "Level " + l),
            datasets: [
                {
                    label: "Jam Approval",
                    data: data.approvalTimeData,
                    backgroundColor: "#8B5CF6",
                },
            ],
        },
        options: {
            indexAxis: "y",
            maintainAspectRatio: false,
        },
    });
});
