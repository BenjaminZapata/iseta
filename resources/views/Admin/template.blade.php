<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <link rel="stylesheet" href="{{asset('css/Reset/reset.css')}}">
    <link rel="stylesheet" href="{{asset('css/Admin/Edit/edit-page.css')}}">
    <link rel="stylesheet" href="{{asset('css/Admin/main.css')}}">
    <link rel="stylesheet" href="{{asset('css/Admin/aside.css')}}">
    <link rel="stylesheet" href="{{asset('css/global.css')}}">
    <link rel="stylesheet" href="{{asset('css/form.css')}}">


    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
</head>

<body>

    <script src="{{asset('js/libs/ElementEv.js')}}"></script>
    <script src="{{asset('js/libs/ElementList.js')}}"></script>

    @include('Componentes.mensaje')
    @include('Componentes.aside')
    @include('Componentes.confirmacion')

    <div class="admin-main w-80p p-0 pr-6 just-end">
        @yield('content')
    </div>


    <script src="{{asset('js/ocultar-mensaje.js')}}"></script>
    <script src="{{asset('js/confirmacion.js')}}"></script>
    <script src="{{asset('js/filters.js')}}"></script>
    <!-- <script>document.addEventListener('DOMContentLoaded', function () {
    const accordions = document.querySelectorAll('.vanilla-accordion');
    const CSS_TRANSITION_DURATION = 300; // Duración de la transición en ms desde tu CSS

    accordions.forEach(accordion => {
        const headers = accordion.querySelectorAll(':scope > .vanilla-accordion-item > .vanilla-accordion-header');

        headers.forEach(header => {
            header.addEventListener('click', function () {
                const content = this.nextElementSibling;
                const icon = this.querySelector('.vanilla-accordion-icon');
                const isActive = this.classList.contains('active');

                // console.log(`Clic en: ${this.textContent.trim().substring(0,20)}, ID contenido: ${content.id}, Estaba activo: ${isActive}`);

                if (!isActive) { // Si estamos abriendo este panel, cerramos los hermanos
                    const parentAccordionContainer = this.closest('.vanilla-accordion');
                    if (parentAccordionContainer) {
                        const siblingHeaders = parentAccordionContainer.querySelectorAll(':scope > .vanilla-accordion-item > .vanilla-accordion-header');
                        siblingHeaders.forEach(otherHeader => {
                            if (otherHeader !== this) {
                                otherHeader.classList.remove('active');
                                otherHeader.setAttribute('aria-expanded', 'false');
                                const otherContent = otherHeader.nextElementSibling;
                                if (otherContent.style.maxHeight && otherContent.style.maxHeight !== '0px') {
                                    otherContent.style.maxHeight = null; // Colapsar hermano
                                    // console.log(`Colapsando hermano ${otherContent.id}`);
                                }
                                const otherIcon = otherHeader.querySelector('.vanilla-accordion-icon');
                                if (otherIcon) otherIcon.textContent = '+';
                            }
                        });
                    }
                }

                this.classList.toggle('active');
                this.setAttribute('aria-expanded', String(!isActive));

                if (content.style.maxHeight && content.style.maxHeight !== '0px') {
                    content.style.maxHeight = null; // Colapsar el actual
                    if (icon) icon.textContent = '+';
                    // console.log(`Colapsando ${content.id}.`);
                } else {
                    content.offsetHeight; // Forzar reflow
                    const currentScrollHeight = content.scrollHeight;
                    // console.log(`Expandiendo ${content.id}. scrollHeight leído: ${currentScrollHeight}px`);
                    content.style.maxHeight = currentScrollHeight + "px";
                    if (icon) icon.textContent = '−';
                }
                updateParentMaxHeight(this);
            });
        });
    });

    function updateParentMaxHeight(clickedHeader) {
        let currentAccordionItem = clickedHeader.closest('.vanilla-accordion-item');
        const CSS_TRANSITION_DURATION = 300; // Duración de la transición en ms desde tu CSS (repetida aquí para claridad)

        while (currentAccordionItem) {
            const parentAccordionContainer = currentAccordionItem.parentElement;
            if (!parentAccordionContainer || !parentAccordionContainer.classList.contains('vanilla-accordion')) {
                break;
            }

            const grandParentAccordionContent = parentAccordionContainer.parentElement;
            if (!grandParentAccordionContent || !grandParentAccordionContent.classList.contains('vanilla-accordion-content')) {
                break;
            }

            const grandParentHeader = grandParentAccordionContent.previousElementSibling;
            if (grandParentHeader && grandParentHeader.classList.contains('vanilla-accordion-header') && grandParentHeader.classList.contains('active')) {
                // console.log(`Intentando actualizar padre: ${grandParentAccordionContent.id}`);
                // Esperamos a que la transición del hijo (o hijos que se cierran/abren) haya terminado
                setTimeout(() => {
                    grandParentAccordionContent.offsetHeight; // Forzar reflow
                    const newScrollHeight = grandParentAccordionContent.scrollHeight;
                    // console.log(`Actualizando padre ${grandParentAccordionContent.id}. scrollHeight leído: ${newScrollHeight}px. MaxHeight anterior: ${grandParentAccordionContent.style.maxHeight}`);
                    grandParentAccordionContent.style.maxHeight = newScrollHeight + "px";
                }, CSS_TRANSITION_DURATION + 50); // Espera la duración de la transición + un pequeño buffer (50ms)
            }
            currentAccordionItem = grandParentAccordionContent.closest('.vanilla-accordion-item');
        }
    }
})</script> -->

</body>
</html>
