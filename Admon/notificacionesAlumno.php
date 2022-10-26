<?php if (isset($_GET['action']) && $_GET['action'] == 'created_success') { ?>
    <script>
        Swal.fire({
            icon: "success",
            text: "Alumno creado"
        }).then((resultado) => {
            var url = document.location.href;
            window.history.pushState({}, "", url.split("?")[0]);
        });
    </script>
<?php } ?>

<?php if (isset($_GET['action']) && $_GET['action'] == 'created_error') { ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Alumno no fue creado',
            text: 'Verifique que los campos sean correctos y no vacíos.\n' +
                'Vuelve a intentarlo.'
        }).then((resultado) => {
            var url = document.location.href;
            window.history.pushState({}, "", url.split("?")[0]);
        });
    </script>
<?php } ?>

<?php if (isset($_GET['action']) && $_GET['action'] == 'created_exist') { ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Alumno no fue creado',
            text: 'El nombre de usuario ya está registrado. \n' +
                'Vuelve a intentarlo.'
        }).then((resultado) => {
            var url = document.location.href;
            window.history.pushState({}, "", url.split("?")[0]);
        });
    </script>
<?php } ?>

<?php if (isset($_GET['action']) && $_GET['action'] == 'updated_success') { ?>
    <script>
        Swal.fire({
            icon: "success",
            text: "Datos actualizados"
        }).then((resultado) => {
            var url = document.location.href;
            window.history.pushState({}, "", url.split("?")[0]);
        });
    </script>
<?php } ?>

<?php if (isset($_GET['action']) && $_GET['action'] == 'updated_error') { ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Datos no actualizado.',
            text: 'Verifique que los campos sean correctos y no vacíos.\n' +
                'Vuelve a intentarlo.'
        }).then((resultado) => {
            var url = document.location.href;
            window.history.pushState({}, "", url.split("?")[0]);
        });
    </script>
<?php } ?>

<?php if (isset($_GET['action']) && $_GET['action'] == 'updated_exist') { ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Alumno no fue actualizado',
            text: 'El nombre de usuario ya está registrado. \n' +
                'Vuelve a intentarlo.'
        }).then((resultado) => {
            var url = document.location.href;
            window.history.pushState({}, "", url.split("?")[0]);
        });
    </script>
<?php } ?>

<?php if (isset($_GET['action']) && $_GET['action'] == 'delete') { ?>
    <script>
        Swal.fire({
            title: 'Confirmar',
            text: "¿Seguro que desear eliminar este alumno?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si'
        }).then((result) => {

            var url = document.location.href;
            window.history.pushState({}, "", url.split("?")[0]);

            if (result.isConfirmed) {
                $.ajax({
                    url: "./FncDatabase/AlumnoEliminar.php?id=<?php echo $_GET['IdUsuario']; ?>"
                });
                Swal.fire(
                    'Eliminado!',
                    'Alumno fue eliminado.',
                    'success'
                ).then((r) => {
                    window.location.reload();
                });
            }

        })
    </script>
<?php } ?>