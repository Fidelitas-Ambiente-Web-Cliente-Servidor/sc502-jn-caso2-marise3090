

$(function () {
    cargarTalleres();

    $("#btnLogout").on("click", function () {
        $.post("index.php", { option: "logout" }, function () {
            window.location.href = "index.php?page=login";
        });
    });

    function cargarTalleres() {
        $.get("index.php", { option: "talleres_json" }, function (data) {
            let talleres = JSON.parse(data);
            let html = "";

            if (talleres.length === 0) {
                html = `<tr><td colspan="5">No hay talleres disponibles.</td></tr>`;
            } else {
                $.each(talleres, function (index, taller) {
                    html += `
                        <tr>
                            <td>${taller.nombre}</td>
                            <td>${taller.descripcion ?? ''}</td>
                            <td>${taller.cupo_maximo}</td>
                            <td>${taller.cupo_disponible}</td>
                            <td>
                                <button class="btn btn-success btn-sm btnSolicitar" data-id="${taller.id}">
                                    Solicitar
                                </button>
                            </td>
                        </tr>
                    `;
                });
            }

            $("#tabla-talleres-body").html(html);
        });
    }

    $(document).on("click", ".btnSolicitar", function () {
        let tallerId = $(this).data("id");

        $.post("index.php", {
            option: "solicitar",
            taller_id: tallerId
        }, function (data) {
            let response = JSON.parse(data);

            if (response.success) {
                $("#mensaje").html(`<div class="alert alert-success">${response.message}</div>`);
                cargarTalleres();
            } else {
                $("#mensaje").html(`<div class="alert alert-danger">${response.error}</div>`);
            }
        });
    });
});
