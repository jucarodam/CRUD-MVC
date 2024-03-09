const formularios_ajax = document.querySelectorAll(".FormularioAjax");

formularios_ajax.forEach((formularios) => {
  formularios.addEventListener("submit", function (e) {
    e.preventDefault();

    Swal.fire({
      title: "Are you sure?",
      text: "You won't be able to revert this!",
      icon: "question",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, continue",
    }).then((result) => {
      if (result.isConfirmed) {
        let data = new FormData(this);
        let method = this.getAttribute("method");
        let action = this.getAttribute("action");

        let encabezados = new Headers();

        let config = {
          method: method,
          headers: encabezados,
          mode: "cors",
          cache: "no-cache",
          body: data,
        };

        fetch(action, config)
          .then((respuesta) => respuesta.json())
          .then((respuesta) => {
            return alertas_ajax(respuesta);
          });
      }
    });
  });
});

function alertas_ajax(alerta) {
  if (alerta.tipo == "simple") {
    Swal.fire({
      icon: alerta.icono,
      title: alerta.titulo,
      text: alerta.texto,
      confirmButtonText: "Ok",
    });
  } else if (alerta.tipo == "recargar") {
    Swal.fire({
      icon: alerta.icono,
      title: alerta.titulo,
      text: alerta.texto,
      confirmButtonText: "Ok",
    }).then((result) => {
      if (result.isConfirmed) {
        location.reload();
      }
    });
  } else if (alerta.tipo == "confirmar") {
    Swal.fire({
      icon: alerta.icono,
      title: alerta.titulo,
      text: alerta.texto,
      confirmButtonText: "Ok",
    }).then((result) => {
      if (result.isConfirmed) {
        document.querySelector(".FormularioAjax").reset();
      }
    });
  } else if (alerta.tipo == "redireccionar") {
    window.location.href = alerta.url;
  }
}
