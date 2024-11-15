(function () {
    //Boton para mostrar agregar nueva tarea
    const nuevaTareaBoton = document.querySelector('#agregar-tarea');
    nuevaTareaBoton.addEventListener('click', mostrarFormulario);


    function mostrarFormulario (){
        const modal = document.createElement('DIV');
        modal.classList.add('modal');
        modal.innerHTML = `
            <form class="formulario nueva-tarea">
                <legend> Añade una nueva tarea </legend>
                <div class="campo">
                    <label>Tarea</label>
                    <input type="text" name="tarea" placeholder="Añadir tarea al proyecto" id="tarea" />
                </div>

                <div class="opciones">
                    <input type="submit" class="submit-nueva-tarea" value="Añadir Tarea" />
                    <button type="button" class="cerrar-modal">Cancelar</button>
                </div>
            </form>
        `;

        setTimeout(() => {
            const formulario = document.querySelector('.formulario');
            formulario.classList.add('animar');

        }, 0);

        modal.addEventListener('click', function(e){
            e.preventDefault();
            if (e.target.classList.contains('cerrar-modal')){
                const formulario = document.querySelector('.formulario');
                formulario.classList.add('cerrar');
                setTimeout(() => {
                    modal.remove();
                }, 300);
            }

            if (e.target.classList.contains('submit-nueva-tarea')){
                submitNuevaTarea();
            }
        });


        document.querySelector('.dashboard').appendChild(modal);

        function submitNuevaTarea (){
            const tarea = document.querySelector('#tarea').value.trim();

            if (tarea === ''){
                mostrarAlerta('La tarea es obligatoria', 'error', document.querySelector('.formulario legend'));
                return;
            }

            agregarTarea(tarea);

        }
        //Funcion para mostrar alertas
        function mostrarAlerta (mensaje, tipo, referencia){
            //Previene la creación de multiples alertas
            alertaPrevia = document.querySelector('.alerta');
            if (alertaPrevia){
                alertaPrevia.remove();
            }


            alerta = document.createElement('DIV');
            alerta.classList.add('alerta', tipo);
            alerta.textContent = mensaje;

            //Inserta la alerta enates del legend
            referencia.parentElement.insertBefore(alerta, referencia.nextElementSibling);


            //Eliminar la alerta despues de 3s
            setTimeout(() => {
                alerta.remove();
            }, 3000);
        }

        //Consultar al servidor para agregar una tarea
        async function agregarTarea (tarea){
            const datos = new FormData();
            datos.append ('nombre', tarea);
            datos.append ('url', obtenerProyecto());

            try {
                const url = 'http://localhost:3000/api/tarea';
                const respuesta = await fetch(url, {
                    method: 'POST',
                    body: datos
                });

                const resultado = await respuesta.json();
                console.log(resultado);

                mostrarAlerta(resultado.mensaje, resultado.tipo, document.querySelector('.formulario legend'));



            } catch (error) {
                console.log(error);
            }
        }

        function obtenerProyecto (){
            const proyectoParams = new URLSearchParams(window.location.search);
            const proyecto = Object.fromEntries(proyectoParams.entries());
            return proyecto.url;

        }
    }
})();