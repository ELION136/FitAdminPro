@extends('layouts.admin')

@section('content')
    <h1 class="text-center mb-4">Historial de Asistencias</h1>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <table id="calendar" class="table table-bordered table-hover text-center">
                    <caption></caption>
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>Lun</th>
                            <th>Mar</th>
                            <th>Mie</th>
                            <th>Jue</th>
                            <th>Vie</th>
                            <th>Sab</th>
                            <th>Dom</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        var actual = new Date(); // Definimos la fecha actual

        function mostrarCalendario(year, month, asistencias) {
            var now = new Date(year, month - 1, 1);
            var last = new Date(year, month, 0);
            var primerDiaSemana = (now.getDay() == 0) ? 7 : now.getDay();
            var ultimoDiaMes = last.getDate();
            var dia = 0;
            var resultado = "<tr>";
            var diaActual = 0;

            var last_cell = primerDiaSemana + ultimoDiaMes;

            for (var i = 1; i <= 42; i++) {
                if (i == primerDiaSemana) {
                    dia = 1;
                }

                if (i < primerDiaSemana || i >= last_cell) {
                    resultado += "<td>&nbsp;</td>";
                } else {
                    var fechaDia = `${year}-${('0' + month).slice(-2)}-${('0' + dia).slice(-2)}`;
                    if (asistencias.includes(fechaDia)) {
                        resultado += "<td class='asistio bg-success text-white fw-bold'>" + dia + "</td>";
                    } else if (dia == actual.getDate() && month == actual.getMonth() + 1 && year == actual.getFullYear()) {
                        resultado += "<td class='bg-info text-white fw-bold'>" + dia + "</td>";
                    } else {
                        resultado += "<td>" + dia + "</td>";
                    }
                    dia++;
                }

                if (i % 7 == 0) {
                    if (dia > ultimoDiaMes) break;
                    resultado += "</tr><tr>\n";
                }
            }
            resultado += "</tr>";

            var meses = Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre",
                "Octubre", "Noviembre", "Diciembre");

            nextMonth = month + 1;
            nextYear = year;
            if (month + 1 > 12) {
                nextMonth = 1;
                nextYear = year + 1;
            }

            prevMonth = month - 1;
            prevYear = year;
            if (month - 1 < 1) {
                prevMonth = 12;
                prevYear = year - 1;
            }

            document.getElementById("calendar").getElementsByTagName("caption")[0].innerHTML =
                "<div class='d-flex justify-content-between'><a class='btn btn-sm btn-secondary' onclick='mostrarCalendario(" + prevYear + "," +
                prevMonth + ", asistencias)'>&lt; Mes anterior</a> <span class='fw-bold'>" + meses[month - 1] + " / " + year + "</span><a class='btn btn-sm btn-secondary' onclick='mostrarCalendario(" + nextYear + "," + nextMonth +
                ", asistencias)'>Mes siguiente &gt;</a></div>";
            document.getElementById("calendar").getElementsByTagName("tbody")[0].innerHTML = resultado;
        }

        var asistencias = @json($asistencias); // Esto debe venir desde tu controlador
        mostrarCalendario(actual.getFullYear(), actual.getMonth() + 1, asistencias);
    </script>

    <style>
        #calendar {
            font-family: Arial, sans-serif;
        }

        #calendar caption {
            background-color: #007bff;
            color: white;
            padding: 10px;
            font-weight: bold;
            border-radius: 4px;
        }

        #calendar th {
            background-color: #007bff;
            color: white;
            padding: 10px;
        }

        #calendar td {
            height: 80px;
            vertical-align: middle;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        #calendar td:hover {
            background-color: #f0f0f0;
            cursor: pointer;
        }

        .asistio {
            background-color: #28a745 !important;
            color: white !important;
        }

        .hoy {
            background-color: #007bff;
            color: white;
        }
    </style>
@endsection
