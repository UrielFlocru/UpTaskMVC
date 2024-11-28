(function () {
    
    obtenerTareas();
    let tareas = [];
    let filtradas =[];
    
    //Boton para mostrar agregar nueva tarea
    const nuevaTareaBoton = document.querySelector('#agregar-tarea');
    nuevaTareaBoton.addEventListener('click', function(){
        mostrarFormulario();
    });

    //Filtros de búsqueda
    const filtros = document.querySelectorAll('#filtros input[type="radio"]');
    filtros.forEach( radio => {
        radio.addEventListener('input', filtrarTareas);
    });

    function filtrarTareas (e){
        const filtro = e.target.value;

        if (filtro !== ''){
            filtradas = tareas.filter(tarea => tarea.estado === filtro);
        } else {
            filtradas = [];
        }
        
        mostrarTareas();
    }
    

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

        const arrayTareas = filtradas.length ? filtradas : tareas;
        const filtroPendientes = document.querySelector('#filtros input[type="radio"]#pendientes');
        const filtroCompletadas = document.querySelector('#filtros input[type="radio"]#completadas');
        const pendientes = tareas.filter(tarea => tarea.estado === "0");
        const completadas = tareas.filter(tarea => tarea.estado === "1");


        
        if (arrayTareas.lenght === 0 ){
            const contendorTareas = document.querySelector('#listado-tareas');
            const textoNoTareas = document.createElement('LI');
            textoNoTareas.textContent = 'No hay tareas';
            textoNoTareas.classList.add('no-tareas');

            contendorTareas.appendChild(textoNoTareas);
            return;
        }

        const estados = {
            0 : 'Pendiente',
            1 : 'Completada'
        }

        if (pendientes.length === 0 & filtroPendientes.checked ){
            const contendorTareas = document.querySelector('#listado-tareas');
            const textoNoTareas = document.createElement('LI');
            textoNoTareas.textContent = 'No tienes tareas pendientes';
            textoNoTareas.classList.add('no-tareas');

            contendorTareas.appendChild(textoNoTareas);
            return;
        } else if (completadas.length === 0 & filtroCompletadas.checked){
            const contendorTareas = document.querySelector('#listado-tareas');
            const textoNoTareas = document.createElement('LI');
            textoNoTareas.textContent = 'No has completado ninguna tarea';
            textoNoTareas.classList.add('no-tareas');

            contendorTareas.appendChild(textoNoTareas);
            return;
        }

        arrayTareas.forEach(tarea => {
            const contenedorTarea = document.createElement('LI');
            contenedorTarea.dataset.tareaId = tarea.id;
            contenedorTarea.classList.add('tarea');

            const nombreTarea = document.createElement('P');
            nombreTarea.textContent= tarea.nombre;
            nombreTarea.ondblclick = function (){
                mostrarFormulario(editar = true, {...tarea});
            }

            const opcionesDiv = document.createElement('DIV');
            opcionesDiv.classList.add('opciones');

            //Botones
            const btnEstadoTarea = document.createElement('BUTTON');
            btnEstadoTarea.classList.add('estado-tarea');
            btnEstadoTarea.classList.add(`${estados[tarea.estado].toLowerCase()}`)
            btnEstadoTarea.textContent = estados[tarea.estado];
            btnEstadoTarea.dataset.estadoTarea = tarea.estado;
            btnEstadoTarea.ondblclick = function (){
                cambiarEstadoTarea({...tarea});
            }
            
            const btnEliminarTarea = document.createElement('BUTTON');
            btnEliminarTarea.classList.add('eliminar-tarea');
            btnEliminarTarea.dataset.idTarea = tarea.id;
            btnEliminarTarea.textContent = 'Eliminar';
            btnEliminarTarea.ondblclick = function () {
                confirmarEliminarTarea({...tarea});
            }

            opcionesDiv.appendChild(btnEstadoTarea);
            opcionesDiv.appendChild(btnEliminarTarea);


            contenedorTarea.appendChild(nombreTarea);
            contenedorTarea.appendChild(opcionesDiv);

            const listadoTarea = document.querySelector('#listado-tareas');
            listadoTarea.appendChild(contenedorTarea);
        });
    }

    function mostrarFormulario ( editar=false, tarea = {}){

        const modal = document.createElement('DIV');
        modal.classList.add('modal');
        modal.innerHTML = `
            <form class="formulario nueva-tarea">
                <legend> ${editar ? 'Editar tarea' : 'Añade una nueva tarea' }  </legend>
                <div class="campo">
                    <label>Tarea</label>
                    <input type="text" 
                    name="tarea" 
                    placeholder="${tarea.nombre ? 'Editar tarea' : 'Añadir tarea al proyecto' }" 
                    id="tarea" 
                    value="${tarea.nombre ? tarea.nombre : ''}" />
                </div>

                <div class="opciones">
                    <input type="submit" class="submit-nueva-tarea" value="${tarea.nombre ? 'Guardar cambios' : 'Añadir tarea' }" />
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
                const nombreTarea = document.querySelector('#tarea').value.trim();

                if (nombreTarea === ''){
                    mostrarAlerta('La tarea es obligatoria', 'error', document.querySelector('.formulario legend'));
                    return;
                }

                if (editar){
                    tarea.nombre = nombreTarea;
                    actualizarTarea(tarea);
                } else {
                    agregarTarea(nombreTarea);
                }

                
            }
        });


        document.querySelector('.dashboard').appendChild(modal);
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

    function confirmarEliminarTarea (tarea){
        
        Swal.fire({
            title: "¿Quieres eliminar la tarea?",
            showCancelButton: true,
            confirmButtonText: "Confirmar",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                eliminarTarea(tarea);
            }
        });
    }

    async function eliminarTarea(tarea){
        const {estado, id, nombre} = tarea;

        const datos = new FormData();
        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('url', obtenerProyecto());
        
        try {
            const url = "http://localhost:3000/api/tarea/eliminar";
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });

            const resultado = await respuesta.json();
            
            if (resultado.resultado){
                // mostrarAlerta(
                //     resultado.mensaje,
                //     resultado.tipo,
                //     document.querySelector('.contenedor-nueva-tarea')
                // );

                Swal.fire('Eliminado!', resultado.mensaje, 'success');

                tareas = tareas.filter( tareaMemoria => tareaMemoria.id !== tarea.id)
                mostrarTareas();

            }
        } catch (error) {
            
        }
    }

    function cambiarEstadoTarea (tarea){
        const nuevoEstado = tarea.estado === "1" ? "0" : "1";
        tarea.estado = nuevoEstado;
        actualizarTarea(tarea);

    }

    async function actualizarTarea (tarea){
        
        const {estado, id, nombre, proyectoId} = tarea;

        const datos = new FormData();
        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('url', obtenerProyecto());

        try {
            const url = "http://localhost:3000/api/tarea/actualizar";
            
            const respuesta = await fetch(url,{
                method: "POST",
                body: datos
            });

            const resultado = await respuesta.json();
            
            if (resultado.respuesta.tipo === 'exito'){
                Swal.fire(
                    resultado.respuesta.mensaje,
                    '',
                    'success'
                );

                const modal = document.querySelector('.modal');
                if (modal){
                    modal.remove();
                }
                

                tareas = tareas.map(tareaMemoria => {
                    if (tareaMemoria.id === id){
                        tareaMemoria.estado = estado;
                        tareaMemoria.nombre = nombre;
                    }
                    return tareaMemoria;
                });
                
                mostrarTareas();
            }

        } catch (error) {
            console.log(error);
        }
        
    }



})();