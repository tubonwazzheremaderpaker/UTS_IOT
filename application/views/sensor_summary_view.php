<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sensor Summary Dashboard</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Font: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <!-- FontAwesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #e9ecef;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1100px;
            margin-top: 40px;
        }

        h2 {
            font-size: 2.2rem;
            color: #495057;
            font-weight: 600;
            text-align: center;
            margin-bottom: 40px;
        }

        /* Card Styling */
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            background-color: #007bff;
            color: #fff;
            font-weight: 600;
            text-transform: uppercase;
        }

        .card-body {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
        }

        .table {
            border-radius: 10px;
            overflow: hidden;
        }

        .table th {
            background-color: #f8f9fa;
            color: #495057;
            text-align: center;
            font-weight: 600;
        }

        .table td {
            text-align: center;
            vertical-align: middle;
        }

        .table-striped tbody tr:nth-child(odd) {
            background-color: #f1f1f1;
        }

        /* Responsive styling for small screens */
        @media (max-width: 768px) {
            h2 {
                font-size: 1.8rem;
            }

            .card-body {
                padding: 20px;
            }
        }

        .loading-spinner {
            font-size: 3rem;
            display: block;
            text-align: center;
            margin: 20px;
            color: #007bff;
        }

        .btn-custom {
            background-color: #28a745;
            color: white;
            font-weight: 600;
            border-radius: 25px;
            padding: 10px 25px;
            transition: background-color 0.3s ease;
        }

        .btn-custom:hover {
            background-color: #218838;
        }

        .icon {
            font-size: 1.2rem;
            margin-right: 10px;
        }

        ul {
            list-style-type: none;
            padding-left: 0;
        }

        ul li {
            background-color: #f1f1f1;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        ul li i {
            color: #007bff;
        }
    </style>
</head>
<body>

<div class="container">

    <h2>Sensor Summary Dashboard</h2>

    <!-- Ringkasan Data -->
    <div class="card shadow-sm">
        <div class="card-header text-center">
            Ringkasan Data Sensor
        </div>
        <div class="card-body" id="summary_data">
            <p><strong>Suhu Max:</strong> <span id="suhumax">Loading...</span></p>
            <p><strong>Suhu Min:</strong> <span id="suhumin">Loading...</span></p>
            <p><strong>Suhu Rata-rata:</strong> <span id="suhurata">Loading...</span></p>
        </div>
    </div>

    <!-- Data Terbaru (Suhu, Kelembaban, Kecerahan, Timestamp) -->
    <div class="card shadow-sm">
        <div class="card-header text-center">
            Data Terbaru Suhu dan Kelembaban
        </div>
        <div class="card-body">
            <table class="table table-striped" id="data_table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Suhu (°C)</th>
                        <th>Kelembaban (%)</th>
                        <th>Kecerahan (lux)</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td colspan="5" class="loading-spinner"><i class="fas fa-spinner fa-spin"></i> Loading...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Data Bulan-Tahun -->
    <div class="card shadow-sm">
        <div class="card-header text-center">
            Data Bulan-Tahun
        </div>
        <div class="card-body">
            <ul id="month_year_list">
                <!-- Bulan-Tahun akan dimuat di sini -->
            </ul>
        </div>
    </div>

</div>

<!-- Bootstrap 5 JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        // URL API
        const apiUrl = 'http://localhost/uts_iot/api/sensor_summary';

        // Ambil data dari API menggunakan AJAX
        $.ajax({
            url: apiUrl,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                // Ringkasan Data
                $('#suhumax').text(response.suhumax + ' °C');
                $('#suhumin').text(response.suhumin + ' °C');
                $('#suhurata').text(response.suhurata + ' °C');

                // Data terbaru (Suhu, Kelembaban, Kecerahan, Timestamp)
                let tableContent = '';
                if (response.nilai_suhu_max_humid_max.length > 0) {
                    response.nilai_suhu_max_humid_max.forEach(function(row, index) {
                        tableContent += `
                            <tr>
                                <td>${row.idx}</td>
                                <td>${row.suhun} °C</td>
                                <td>${row.humid} %</td>
                                <td>${row.kecerahan} lux</td>
                                <td>${row.timestamp}</td>
                            </tr>
                        `;
                    });
                } else {
                    tableContent = `<tr><td colspan="5" class="text-center">Data tidak tersedia</td></tr>`;
                }
                $('#data_table tbody').html(tableContent);

                // Data Bulan-Tahun
                let monthYearContent = '';
                if (response.month_year_max.length > 0) {
                    response.month_year_max.forEach(function(monthYear) {
                        monthYearContent += `<li><i class="fas fa-calendar-alt"></i> ${monthYear.month_year}</li>`;
                    });
                } else {
                    monthYearContent = `<li>Data bulan-tahun tidak tersedia</li>`;
                }
                $('#month_year_list').html(monthYearContent);
            },
            error: function() {
                alert('Error: Gagal mengambil data dari API');
            }
        });
    });
</script>

</body>
</html>
