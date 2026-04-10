$(function () {
    if ($("#solicitudes-body").length > 0) {
        cargarSolicitudes();
    }

    $("#btnLogout").on("click", function () {
        $.post("index.php", { option: "logout" }, function () {
            window.location.href = "index.php?page=login";
        });
    });

    function cargarSolicitudes() {
        $.get("index.php", { option: "solicitudes_json" }, function (data) {
            let solicitudes = data;
            let html = "";

            if (solicitudes.length === 0) {
                html = `<tr><td colspan="5">No hay solicitudes pendientes.</td></tr>`;
            } else {
                $.each(solicitudes, function (index, solicitud) {
                    html += `
                        <tr>
                            <td>${solicitud.id}</td>
                            <td>${solicitud.taller}</td>
                            <td>${solicitud.usuario}</td>
                            <td>${solicitud.fecha_solicitud}</td>
                            <td>
                                <button class="btn btn-success btn-sm btnAprobar" data-id="${solicitud.id}">Aprobar</button>
                                <button class="btn btn-danger btn-sm btnRechazar" data-id="${solicitud.id}">Rechazar</button>
                            </td>
                        </tr>
                    `;
                });
            }

            $("#solicitudes-body").html(html);
        }, "json");
    }

    $(document).on("click", ".btnAprobar", function () {
        let idSolicitud = $(this).data("id");

        $.post("index.php", {
            option: "aprobar",
            id_solicitud: idSolicitud
        }, function (data) {
            let response = data;

            if (response.success) {
                $("#mensaje").html(`<div class="alert alert-success">${response.message}</div>`);
                cargarSolicitudes();
            } else {
                $("#mensaje").html(`<div class="alert alert-danger">${response.error}</div>`);
            }
        }, "json");
    });

    $(document).on("click", ".btnRechazar", function () {
        let idSolicitud = $(this).data("id");

        $.post("index.php", {
            option: "rechazar",
            id_solicitud: idSolicitud
        }, function (data) {
            let response = data;

            if (response.success) {
                $("#mensaje").html(`<div class="alert alert-success">${response.message}</div>`);
                cargarSolicitudes();
            } else {
                $("#mensaje").html(`<div class="alert alert-danger">${response.error}</div>`);
            }
        }, "json");
    });
});