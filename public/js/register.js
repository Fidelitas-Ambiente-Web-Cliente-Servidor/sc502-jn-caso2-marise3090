$(function () {
    let formRegister = $("#formRegister");
    const urlBase = "index.php";

    formRegister.on("submit", function (event) {
        event.preventDefault();

        let username = $("#username");
        let password = $("#password");

        if (username.val() === "" || password.val() === "") {
            alert("Debe completar todos los campos");
        } else {
            $.ajax({
                url: urlBase,
                type: "POST",
                data: $(this).serialize() + "&option=register",
                dataType: "json",
                success: function (response) {
                    if (response.response === "00") {
                        window.location.href = "index.php?page=login";
                    } else {
                        alert(response.message);
                    }
                },
                error: function () {
                    alert("Error de conexión");
                }
            });
        }
    });
});