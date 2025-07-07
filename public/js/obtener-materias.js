
const carreraSelect = _find('#carrera_select')
const asignaturaSelect = _find('#asignatura_select')

if(carreraSelect.element.value != 0){
    var url = new URL(window.location.href);
    var parametros = new URLSearchParams(url.search);
    var valorParametro1 = parametros.get('filter_asignatura_id');

    callback(valorParametro1)
}

carreraSelect.when('change', function(){
    callback(0)
})

function callback(asigSelected){
    asignaturaSelect.clear()
    if(carreraSelect.valueIs('any')) return
    
    fetch(`/api/a/${carreraSelect.value()}`)
    .then(asig => asig.json())
    .then(asig => {
        asignaturaSelect.createChild('<option>')
                .withText('Cualquiera')
                .withAttrs({value: 0})
        asig.forEach(asignatura => {
            asignaturaSelect.createChild('<option>')
                .withText(asignatura.nombre)
                .withAttrs({value: asignatura.id})
            if(asigSelected == asignatura.id){
                asignaturaSelect.withAttrs({selected:true})
            }
            });
            
            asignaturaSelect.insert()
    })
    .catch(e=>console.log(e))
}


