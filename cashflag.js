         function refresca(id,enlace) {
            document.getElementById("inicio").className = "";
            document.getElementById("ventajas").className = "";
            document.getElementById("blockchain").className = "";
            document.getElementById("productos").className = "";
            document.getElementById("beneficios").className = "";
            document.getElementById("contacto").className = "";

            id.className = "active";

            document.getElementById("marco").src = "html/"+enlace+".html";
            // document.getElementById("marco").reload();
         }
