# Server PM2025

1. Descargar el archivo llamado **"Isetasv_vx.x.x-x.iso"** en la pestaña **Release** de Github. (las x en el nombre de la ISO representan partes que pueden cambiar y hacen referencia a la version)
2. Para usar esa ISO, crear una máquina virtual, esto se puede hacer de dos maneras:

---

## Opción 1: Para usuarios de Windows 10/11 Pro con Hyper-V

1. **Habilitar Hyper-V**:
    * Presiona la tecla de **Windows**, busca **"Activar o desactivar las características de Windows"** y haz clic en el resultado.
    * En la ventana de características, marca la casilla de **Hyper-V**.
    * Haz clic en **"Aceptar"** y reinicia tu computadora cuando se te solicite.

2. **Crear la máquina virtual**:
    * Busca **"Administrador de Hyper-V"** en el menú de inicio y ábrelo.
    * En el panel de la derecha, haz clic en **"Nueva"** y luego en **"Máquina virtual"**.
    * Sigue el asistente, dándole un nombre a la máquina virtual, asignándole la memoria RAM y la cantidad de núcleos de CPU que necesites.
    * Cuando te lo pida, selecciona la opción para **"Instalar un sistema operativo desde un archivo de imagen .iso"** y navega hasta el archivo **isetav_vx.x.x-x**.
    * Completa el asistente y la máquina virtual se creará.

---

## Opción 2: Para usuarios de Windows Home y Linux con Incus (reemplazo de LXD)

Para esta opción, se necesita instalar Incus, que es un hipervisor de código abierto.

1. **Instalar Incus**:
    * **En Linux (Ubuntu y derivados)**: Abre una terminal y ejecuta:

        ```bash
        sudo apt update
        sudo apt install -y incus incus-ui-canonical
        sudo usermod -a -G incus $USER
        newgrp incus
        sudo incus admin init
        ```

    * **En Windows (a través de WSL - Subsistema de Windows para Linux)**: Primero, asegúrate de que WSL esté instalado. Luego, instala Incus dentro de tu distribución de Linux usando los mismos comandos anteriores.

2. **Usar la interfaz web (Incus UI)**:
    * Incus incluye una interfaz web llamada **Incus UI** que se instala junto con el paquete `incus-ui-canonical`.  
    * Para saber en qué puerto está disponible por defecto, ejecuta:

        ```bash
        incus config show
        ```

    * Esto mostrará la configuración actual, incluyendo el puerto en el que la UI está corriendo (por lo general suele ser `https://localhost:8443`).  
    * Abre ese puerto en tu navegador y podrás acceder a la interfaz gráfica.

3. **Crear una máquina virtual**:
    * Una vez que Incus esté configurado, puedes usar los siguientes comandos en la terminal para crear una máquina virtual.
    * Copia la ISO a tu entorno Incus (puedes usar el comando `incus storage volume import`).
    * Crea la máquina virtual usando el comando `incus launch images:isetav_vx.x.x-x`.
    * También puedes usar `incus create` para una configuración más detallada. Los comandos exactos pueden variar dependiendo de tu versión de Incus, por lo que es recomendable consultar su documentación oficial.
