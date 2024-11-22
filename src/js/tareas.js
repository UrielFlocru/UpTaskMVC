(function () {
    
    obtenerTareas();
    let tareas = [];
    
    //Boton para mostrar agregar nueva tarea
    const nuevaTareaBoton = document.querySelector('#agregar-tarea');
    nuevaTareaBoton.addEventListener('click', mostrarFormulario);

    async function obtenerTareas (){
        try {
            const id = obtenerProyecto();
            const url = `/api/tareas?url=${id}`;
            const respuesta = await fetch(url);
            const resultado = await respuesta.json();

            tareas = resultado.tareas;


            mostrarTareas ();

        } catch (error) {
            console.log(error);
        }
    }

    function mostrarTareas (){
        limpiarTareas();
        if (tareas.lenght === 0 ){
            const conetendorTareas = document.querySelector('#listado-tareas');
            const textoNoTareas = document.createElement('LI');
            textoNoTareas.textContent = 'No hay tareas';
            textoNoTareas.classList.add('no-tareas');

            conetendorTareas.appendChild(textoNoTareas);
            return;
        }

        const estados = {
            0 : 'Pendiente',
            1 : 'Completada'
        }

        tareas.forEach(tarea => {
            const contenedorTarea = document.createElement('LI');
            contenedorTarea.dataset.tareaId = tarea.id;
            contenedorTarea.classList.add('tarea');

            const nombreTarea = document.createElement('P');
            nombreTarea.textContent= tarea.nombre;

            const opcionesDiv = document.createElement('DIV');
            opcionesDiv.classList.add('opciones');

            //Botones
            const btnEstadoTarea = document.createElement('BUTTON');
            btnEstadoTarea.classList.add('estado-tarea');
            btnEstadoTarea.classList.add(`${estados[tarea.estado].toLowerCase()}`)
            btnEstadoTarea.textContent = estados[tarea.estado];
            btnEstadoTarea.dataset.estadoTarea = tarea.estado;
            
            const btnEliminarTarea = document.createElement('BUTTON');
            btnEliminarTarea.classList.add('eliminar-tarea');
            btnEliminarTarea.dataset.idTarea = tarea.id;
            btnEliminarTarea.textContent = 'Eliminar';

            opcionesDiv.appendChild(btnEstadoTarea);
            opcionesDiv.appendChild(btnEliminarTarea);

            contenedorTarea.appendChild(nombreTarea);
            contenedorTarea.appendChild(opcionesDiv);

            const listadoTarea = document.querySelector('#listado-tareas');
            listadoTarea.appendChild(contenedorTarea);
        });
    }

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
    }

    function submitNuevaTarea (){
        const tarea = document.querySelector('#tarea').value.trim();

        if (tarea === ''){
            mostrarAlerta('La tarea es obligatoria', 'error', document.querySelector('.formulario legend'));
            return;
        }

        agregarTarea(tarea);

    }


    function obtenerProyecto (){
        const proyectoParams = new URLSearchParams(window.location.search);
        const proyecto = Object.fromEntries(proyectoParams.entries());
        return proyecto.url;

    }

    function limpiarTareas (){
        const listadoTareas = document.querySelector('#listado-tareas');
        while (listadoTareas.firstChild){
            listadoTareas.removeChild(listadoTareas.firstChild);
        }
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

            if (resultado.tipo === 'exito') {
                const modal = document.querySelector('.modal');
                setTimeout(() => {
                    modal.remove();
                }, 1000);

                //Agregar el objeto de tarea al global
                const tareaObj = {
                    id: String(resultado.id),
                    nombre: tarea,
                    estado: "0",
                    proyectoId: resultado.proyectoId
                }

                tareas = [...tareas, tareaObj];
                mostrarTareas();
                
            }


        } catch (error) {
            console.log(error);
        }
    }

})();