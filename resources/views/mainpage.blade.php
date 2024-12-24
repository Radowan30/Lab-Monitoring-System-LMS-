<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Analytics Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f5f5f5;
            display: flex;
            min-height: 100vh;
        }
        @keyframes zoomIn {
            from {
                opacity: 0;
                transform: scale(0.7);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .modal-content {
            animation: zoomIn 0.3s ease-out forwards;
            transform-origin: center center;
        }

        /* Optional: Add a slight backdrop blur for extra depth */
        .modal {
            backdrop-filter: blur(2px);
            background-color: rgba(0, 0, 0, 0.588);
        }
        .filter-actions {
    display: flex;
    justify-content: left;
    gap: 20px; /* Adds space between the buttons */
    margin-bottom: 20px;
}
        .reset-filter-btn {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 150px; /* Smaller width */
    height: 50px; /* Smaller height */
    margin-left: 10px;
    margin-top: 10px; /* Adds some separation */
    padding: 10px; /* Reduced padding */
    border: none;
    border-radius: 12px; /* Slightly smaller corner radius */
    box-shadow: 0 0 6px rgb(207, 207, 207); /* Adjusted shadow size */
    background-color:rgb(87, 157, 249); /* Keeps the reset filter color */
    color: white;
    cursor: pointer;
    font-size: 0.9rem; /* Reduced font size */
    transition: all 1.5s; /* Smooth animations */
    overflow: hidden; /* Ensures child elements don’t overflow */
}

.reset-filter-btn:hover {
    box-shadow: inset 0 0 6px #f9f8fc; /* Adjusted shadow on hover */
    background-color: #fec7d7; /* Hover color similar to animation style */
    color: #0e172c; /* Matches the hover text color */
    border: 2px solid #0e172c; /* Slightly thinner border */
    transform: scale(1.05); /* Slightly enlarges button on hover */
    transition: all 1.5s; /* Smooth transitions */
}

.reset-filter-btn::after {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: #f9f8fc;
    z-index: -1;
    transform: scaleY(0);
    transform-origin: bottom;
    transition: transform 0.5s ease-in-out;
}

.reset-filter-btn:hover::after {
    transform: scaleY(1);
}

.reset-filter-btn a {
    font-size: 16px; /* Reduced font size for smaller button */
    text-decoration: none;
    color: inherit; /* Inherits the button’s text color */
    transition: letter-spacing 1.5s;
}

.reset-filter-btn a:hover {
    letter-spacing: 3px; /* Adjusted for smaller button */
}


        .delete-customer-btn {
    position: relative; /* Required for pseudo-elements */
    display: inline-flex;
    background-color: #000000;
    color: white;
    border: none;
    width: 120px; /* Smaller width */
    height: 40px; /* Smaller height */
    align-items: center;
    justify-content: center;
    padding: 0; /* Padding replaced with fixed dimensions */
    border-radius: 4px; /* Slightly smaller corner radius */
    margin-left: 10px;
    cursor: pointer;
    font-size: 0.7em; /* Smaller text size */
    font-family: 'Montserrat', sans-serif;
    letter-spacing: 1px;
    perspective: 1000px; /* Enables 3D effect */
    transform-style: preserve-3d;
    transform: translateZ(-20px); /* Adjust for smaller depth */
    transition: transform 0.25s;
}

.delete-customer-btn:before,
.delete-customer-btn:after {
    position: absolute;
    content: "Delete"; /* Ensure this matches your button text */
    width: 120px; /* Matches the smaller button width */
    height: 40px; /* Matches the smaller button height */
    display: flex;
    align-items: center;
    justify-content: center;
    border: 4px solid #020202; /* Adjusted border size */
    box-sizing: border-box;
    border-radius: 4px;
}

.delete-customer-btn:before {
    background: #000000;
    color: white;
    transform: rotateY(0deg) translateZ(20px); /* Adjusted depth */
}

.delete-customer-btn:after {
    background: white;
    color: #000000;
    transform: rotateX(90deg) translateZ(20px); /* Adjusted depth */
}

.delete-customer-btn:hover {
    transform: translateZ(-20px) rotateX(-90deg); /* Adjusted depth */
}


        .sidebar {
            width: 250px;
            background-color: white;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            padding: 20px;
        }

        .main-content {
            flex-grow: 1;
            padding: 20px;
        }

     
        .chart-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .chart-card {
            background: rgb(255, 255, 255);
            padding: 20px;
            border-radius: 8px;
            height: 400px;
        }
        .filter-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .filter-group {
            background: rgba(87, 157, 249, 255);
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
        }

        .filter-group select {
    appearance: none; /* Remove default browser styling */
    -webkit-appearance: none;
    -moz-appearance: none;
    width: 100%;
    padding: 12px 15px;
    border: 2px solid rgba(87, 157, 249, 0.5);
    border-radius: 8px;
    background-color: white;
    color: #333;
    font-size: 16px;
    transition: all 0.3s ease;
    outline: none;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%23579DF9' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 15px center;
}

.filter-group select:hover {
    border-color: rgba(87, 157, 249, 0.8);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    transform: translateY(-2px);
}

.filter-group select:focus {
    border-color: rgba(87, 157, 249, 1);
    box-shadow: 0 0 0 3px rgba(87, 157, 249, 0.2);
}

.filter-group label {
    display: block;
    margin-bottom: 8px;
    color: #333;
    font-weight: 600;
    font-size: 14px;
}

        .submissions-table {
            background: white;
            padding: 12px;
            border-radius: 8px;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        .search-bar {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #000000;
            border-radius: 4px;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.588);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border-radius: 8px;
            width: 80%;
            max-width: 800px;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.392);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .close-btn {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close-btn:hover {
            color: #000;
        }

        .modal-body {
            padding: 10px 0;
        }

        .modal-body table {
            width: 100%;
        }

        .modal-body table tr {
            border-bottom: 1px solid #eee;
        }

        .modal-body table td {
            padding: 10px;
            width: 50%;
        }

        .modal-body table td:first-child {
            font-weight: bold;
            width: 30%;
        }

        .customer-name {
            cursor: pointer;
            color: rgb(24, 145, 182);
            text-decoration: underline;
        }

        @media screen and (max-width: 768px) {
            body {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
            }

            .main-content {
                padding: 10px;
            }

            .chart-container {
                grid-template-columns: 1fr;
            }

            .chart-card {
                height: auto;
                min-height: 300px;
            }

            .filter-container {
                grid-template-columns: 1fr;
            }

            .filter-actions {
                flex-direction: column;
                gap: 10px;
            }

            .reset-filter-btn, 
            .view-form-btn, 
            .delete-customer-btn {
                width: 100%;
                margin-left: 0;
            }

            .submissions-table {
                overflow-x: auto;
            }

            table {
                width: 100%;
            }

            .modal-content {
                width: 95%;
                margin: 10% auto;
            }

            .modal-body table {
                width: 100%;
            }

            .modal-body table td {
                display: block;
                width: 100%;
            }

            .modal-body table tr {
                border-bottom: 2px solid #f1f1f1;
                padding: 10px 0;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>Lab Dashboard</h2>
        </div>
    </div>

    <div class="main-content">
       

        <div class="chart-container">
            <div class="chart-card">
                <h3>Equipment Usage</h3>
                <canvas id="equipmentChart"></canvas>
            </div>
            <div class="chart-card">
                <h3>Visitor Distribution</h3>
                <canvas id="institutionChart"></canvas>
            </div>
        </div>
        <form id="filter-form" method="GET" action="{{ route('lab.analytics') }}">
            <div class="filter-container">
                <div class="filter-group">
                    <label for="institution-filter" style="color: white;">Institution</label>
                    <select id="institution-filter" name="institution">
                        <option value="">All Institutions</option>
                        @foreach($institutions as $institution)
                            <option value="{{ $institution }}">{{ $institution }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label for="purpose-filter" style="color: white;">Visit Purpose</label>

                    <select id="purpose-filter" name="purpose">
                        <option value="">All Purposes</option>
                        @foreach($purposes as $purpose)
                            <option value="{{ $purpose }}">{{ $purpose }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label for="equipment-filter" style="color: white;">Equipment</label>
                    <select id="equipment-filter" name="equipment">
                        <option value="">All Equipment</option>
                        @foreach($equipment as $eq)
                            <option value="{{ $eq }}">{{ $eq }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <button type="submit" style="display:none;">Filter</button>
            <div class="filter-actions">
                <button type="button" id="reset-filter-btn" class="reset-filter-btn">Reset Filters</button>
                <button type="button" id="view-form-btn" class="reset-filter-btn" onclick="window.location.href='/'">View Form</button>
            </div>
        </form>

  
        <div class="submissions-table">
            <input type="text" class="search-bar" id="search-input" placeholder="Search...">
            <table>
                <thead>
                    <tr>
                        <th style="text-align: left;">Name</th>
                        <th style="text-align: left;">Institution</th>
                        <th style="text-align: left;">Purpose</th>
                        <th style="text-align: left;">Actions</th>
                        
                    </tr>
                </thead>
                <tbody id="submissions-body">
                    @foreach($submissions as $submission)
                    <tr data-customer-id="{{ $submission->customer_id }}" 
                        data-equipment="{{ $submission->equipment_used }}"
                        data-passport-number="{{ $submission->passport_number }}">
                        <td>
                            <span class="customer-name" data-customer-id="{{ $submission->customer_id }}">
                                {{ $submission->full_name }}
                            </span>
                        </td>
                        <td>{{ $submission->institution }}</td>
                        <td>{{ $submission->purpose_of_usage }}</td>
                        <td>
                            <button class="delete-customer-btn" data-customer-id="{{ $submission->customer_id }}">Delete</button>
                        </td>
                    </tr>
                @endforeach
                    
                </tbody>
            </table>
        </div>
    </div>

    <!-- Customer Details Modal -->
    <div id="customerModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Customer Details</h2>
                <span class="close-btn">&times;</span>
            </div>
            <div class="modal-body" id="customerModalBody">
                <!-- Customer details will be dynamically inserted here -->
            </div>
        </div>
    </div>

    <script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search-input');
    const submissionsBody = document.getElementById('submissions-body');
    const institutionFilter = document.getElementById('institution-filter');
    const purposeFilter = document.getElementById('purpose-filter');
    const equipmentFilter = document.getElementById('equipment-filter');
    const resetFilterBtn = document.getElementById('reset-filter-btn');

    function filterTable() {
        const rows = submissionsBody.querySelectorAll('tr');
        const searchTerm = searchInput.value.toLowerCase();
        const institutionTerm = institutionFilter.value.toLowerCase();
        const purposeTerm = purposeFilter.value.toLowerCase();
        const equipmentTerm = equipmentFilter.value.toLowerCase();

        rows.forEach(row => {
            const nameCell = row.cells[0].textContent.toLowerCase();
            const institutionCell = row.cells[1].textContent.toLowerCase();
            const purposeCell = row.cells[2].textContent.toLowerCase();

            const searchMatch = nameCell.includes(searchTerm) || 
                               institutionCell.includes(searchTerm) || 
                               purposeCell.includes(searchTerm);
            
            const institutionFilter = institutionTerm === '' || institutionCell.includes(institutionTerm);
            const purposeFilter = purposeTerm === '' || purposeCell.includes(purposeTerm);
            const equipmentFilter = equipmentTerm === '' || 
                (row.getAttribute('data-equipment') && 
                 row.getAttribute('data-equipment').toLowerCase().includes(equipmentTerm));

            row.style.display = (searchMatch && institutionFilter && purposeFilter && equipmentFilter) ? '' : 'none';
        });
    }

    // Add event listeners
    searchInput.addEventListener('keyup', filterTable);
    institutionFilter.addEventListener('change', filterTable);
    purposeFilter.addEventListener('change', filterTable);
    equipmentFilter.addEventListener('change', filterTable);

    // Reset button functionality
    resetFilterBtn.addEventListener('click', function() {
        searchInput.value = '';
        institutionFilter.selectedIndex = 0;
        purposeFilter.selectedIndex = 0;
        equipmentFilter.selectedIndex = 0;
        
        const rows = submissionsBody.querySelectorAll('tr');
        rows.forEach(row => {
            row.style.display = '';
        });
    });
});
        
       document.addEventListener('DOMContentLoaded', function () {
    const equipmentUsage = @json($equipmentUsage);
    const institutionDistribution = @json($institutionDistribution);

    // Equipment Usage Chart - Now a Bar Graph
    const equipmentCtx = document.getElementById('equipmentChart').getContext('2d');
new Chart(equipmentCtx, {
    type: 'bar',
    data: {
        labels: Object.keys(equipmentUsage),
        datasets: [{
            label: 'Equipment Usage',
            data: Object.values(equipmentUsage),
            backgroundColor: [
                'rgba(44, 123, 229, 0.7)',   // Professional blue
                'rgba(70, 190, 194, 0.7)',   // Teal blue
                'rgba(102, 126, 234, 0.7)',  // Soft indigo
                'rgba(41, 128, 185, 0.7)',   // Deeper blue
                'rgba(52, 152, 219, 0.7)'    // Bright blue
            ],
            borderColor: [
                'rgba(44, 123, 229, 1)',
                'rgba(70, 190, 194, 1)',
                'rgba(102, 126, 234, 1)',
                'rgba(41, 128, 185, 1)',
                'rgba(52, 152, 219, 1)'
            ],
            borderWidth: 1,
            borderRadius: 5
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        layout: {
            padding: {
                left: 10,
                right: 10,
                top: 10,
                bottom: 10
            }
        },
        plugins: {
            title: {
                display: true,
                text: 'Equipment Usage Analysis',
                font: {
                    size: 16,
                    weight: '600',
                    family: 'Inter, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, "Open Sans", "Helvetica Neue", sans-serif'
                },
                color: '#333'
            },
            tooltip: {
                backgroundColor: 'rgba(0,0,0,0.8)',
                titleFont: {
                    size: 14,
                    weight: 'bold'
                },
                bodyFont: {
                    size: 12
                },
                cornerRadius: 4,
                padding: 8
            },
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Number of Uses',
                    font: {
                        size: 12,
                        weight: '500'
                    },
                    color: '#555'
                },
                grid: {
                    color: 'rgba(0,0,0,0.05)',
                    borderDash: [5, 5]
                },
                ticks: {
                    precision: 0,
                    color: '#666'
                }
            },
            x: {
                grid: {
                    display: false
                },
                ticks: {
                    color: '#666',
                    font: {
                        weight: '500'
                    }
                }
            }
        },
        animation: {
            duration: 1200,
            easing: 'easeInOutQuad'
        }
    }
});
        // Institution Distribution Chart
        const institutionCtx = document.getElementById('institutionChart').getContext('2d');
new Chart(institutionCtx, {
    type: 'bar',
    data: {
        labels: Object.keys(institutionDistribution),
        datasets: [{
            label: 'Visitor Distribution',
            data: Object.values(institutionDistribution),
            backgroundColor: [
                'rgba(41, 128, 185, 0.7)',   // Professional blue
                'rgba(70, 190, 194, 0.7)',   // Teal blue
                'rgba(102, 126, 234, 0.7)',  // Soft indigo
                'rgba(44, 123, 229, 0.7)',   // Bright blue
                'rgba(52, 152, 219, 0.7)'    // Lighter blue
            ],
            borderColor: [
                'rgba(41, 128, 185, 1)',
                'rgba(70, 190, 194, 1)',
                'rgba(102, 126, 234, 1)',
                'rgba(44, 123, 229, 1)',
                'rgba(52, 152, 219, 1)'
            ],
            borderWidth: 1,
            borderRadius: 5
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        layout: {
            padding: {
                left: 10,
                right: 10,
                top: 10,
                bottom: 10
            }
        },
        plugins: {
            title: {
                display: true,
                text: 'Institutional Visitor Analysis',
                font: {
                    size: 16,
                    weight: '600',
                    family: 'Inter, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, "Open Sans", "Helvetica Neue", sans-serif'
                },
                color: '#333'
            },
            tooltip: {
                backgroundColor: 'rgba(0,0,0,0.8)',
                titleFont: {
                    size: 14,
                    weight: 'bold'
                },
                bodyFont: {
                    size: 12
                },
                cornerRadius: 4,
                padding: 8
            },
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Number of Visitors',
                    font: {
                        size: 12,
                        weight: '500'
                    },
                    color: '#555'
                },
                grid: {
                    color: 'rgba(0,0,0,0.05)',
                    borderDash: [5, 5]
                },
                ticks: {
                    precision: 0,
                    color: '#666'
                }
            },
            x: {
                grid: {
                    display: false
                },
                ticks: {
                    color: '#666',
                    font: {
                        weight: '500'
                    }
                }
            }
        },
        animation: {
            duration: 1200,
            easing: 'easeInOutQuad'
        }
    }
});

// Additional Chart Card Styling
const chartCards = document.querySelectorAll('.chart-card');
chartCards.forEach(card => {
    card.style.boxShadow = '0 4px 6px rgba(0,0,0,0.1)';
    card.style.border = '1px solid rgba(0,0,0,0.1)';
    card.style.borderRadius = '8px';
});

            

    // Delete Customer functionality
    const deleteButtons = document.querySelectorAll('.delete-customer-btn');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const customerId = this.getAttribute('data-customer-id');
            
            if (confirm('Are you sure you want to delete this customer record?')) {
                fetch(`/customer/delete/${customerId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove the row from the table
                        const row = document.querySelector(`tr[data-customer-id="${customerId}"]`);
                        if (row) {
                            row.remove();
                        }
                        alert('Customer deleted successfully');
                    } else {
                        alert('Failed to delete customer');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the customer');
                });
            }
        });
    });

    function formatDateTime(dateTimeString) {
    if (!dateTimeString) return 'N/A';
    
    try {
        // Create a Date object from the ISO string
        const date = new Date(dateTimeString);
        
        // Check if the date is valid
        if (isNaN(date.getTime())) return 'N/A';
        
        // Format options for a readable date and time
        const options = {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        };
        
        return date.toLocaleString('en-US', options);
    } catch (error) {
        console.error('Error formatting date:', error);
        return 'N/A';
    }
}

            // Search functionality
const searchInput = document.getElementById('search-input');
const submissionsBody = document.getElementById('submissions-body');

searchInput.addEventListener('keyup', function() {
    const searchValue = this.value.toLowerCase();
    const rows = submissionsBody.querySelectorAll('tr');

    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        const passportNumber = row.getAttribute('data-passport-number') || '';
        
        const match = Array.from(cells).some(cell => 
            cell.textContent.toLowerCase().includes(searchValue)
        ) || passportNumber.toLowerCase().includes(searchValue);
        
        row.style.display = match ? '' : 'none';
    });
});
            // Customer Details Modal Logic
            const customerNames = document.querySelectorAll('.customer-name');
            const modal = document.getElementById('customerModal');
            const modalBody = document.getElementById('customerModalBody');
            const closeBtn = document.querySelector('.close-btn');

            customerNames.forEach(name => {
                name.addEventListener('click', function() {
                    const customerId = this.getAttribute('data-customer-id');
                    
                    // Fetch customer details
                    fetch(`/customer/${customerId}`)
                        .then(response => response.json())
                        .then(customer => {
                            // Create a detailed view of customer information
                            const detailsHTML = `
                                <table>
                                    <tr>
                                        <td>Full Name</td>
                                        <td>${customer.full_name}</td>
                                    </tr>
                                    <tr>
                                        <td>Passport Number</td>
                                        <td>${customer.passport_number}</td>
                                    </tr>
                                    <tr>
                                        <td>Institution</td>
                                        <td>${customer.institution}</td>
                                    </tr>
                                            <tr>
            <td>Specific Institution</td>
            <td>${customer.specific_institution || 'N/A'}</td>
        </tr>
                                    <tr>
                                        <td>Position</td>
                                        <td>${customer.position}</td>
                                    </tr>
                                    <tr>
                                        <td>Phone Number</td>
                                        <td>${customer.phone_number}</td>
                                    </tr>
                                    <tr>
                                        <td>Email</td>
                                        <td>${customer.email}</td>
                                    </tr>
                                     <tr>
            <td>Entry Date/Time</td>
            <td>${formatDateTime(customer.entry_datetime)}</td>
        </tr>
        <tr>
            <td>Exit Date/Time</td>
            <td>${formatDateTime(customer.exit_datetime)}</td>
        </tr>
                                    <tr>
                                        <td>Purpose of Usage</td>
                                        <td>${customer.purpose_of_usage}</td>
                                    </tr>
                                    <tr>
                                        <td>Purpose Description</td>
                                        <td>${customer.purpose_description}</td>
                                    </tr>
                                    <tr>
                                        <td>Equipment Used</td>
                                        <td>${customer.equipment_used}</td>
                                    </tr>
                                    <tr>
                                        <td>Type of Analysis</td>
                                        <td>${customer.type_of_analysis}</td>
                                    </tr>
                                    <tr>
                                        <td>Supervisor Name</td>
                                        <td>${customer.supervisor_name}</td>
                                    </tr>
                                    <tr>
                                        <td>Usage Duration</td>
                                        <td>${customer.usage_duration} hours</td>
                                    </tr>
                                    <tr>
                                        <td>Suggestions</td>
                                        <td>${customer.suggestions || 'N/A'}</td>
                                    </tr>
                                    <tr>
                                        <td>Technical Issues</td>
                                        <td>${customer.technical_issues || 'None'}</td>
                                    </tr>
                                </table>
                            `;
                            
                            modalBody.innerHTML = detailsHTML;
                            modal.style.display = 'block';
                        })
                        .catch(error => {
                            console.error('Error fetching customer details:', error);
                        });
                });
            });

            // Close modal when clicking the close button
            closeBtn.addEventListener('click', function() {
                modal.style.display = 'none';
            });

            // Close modal when clicking outside of it
            window.addEventListener('click', function(event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>