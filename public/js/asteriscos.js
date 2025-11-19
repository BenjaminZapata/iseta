document.addEventListener("DOMContentLoaded", () => {

 // Elementos que pueden contener labels generados con asteriscos
 const selectors = "label, span, p, div";

 document.querySelectorAll(selectors).forEach(el => {

  el.childNodes.forEach(node => {

   // Solo procesamos nodos de texto
   if (node.nodeType === Node.TEXT_NODE && node.textContent.includes('*')) {

    // Convertimos cada * en un span rojo
    const replaced = node.textContent.replace(/\*/g,
     '<span class="asterisco-rojo">*</span>'
    );

    // Convertimos el texto a HTML sin romper otros nodos
    const temp = document.createElement("span");
    temp.innerHTML = replaced;

    el.replaceChild(temp, node);
   }
  });
 });
});
