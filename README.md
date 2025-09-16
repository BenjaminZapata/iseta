# Server PM2025

# Iniciar servidor con ISO

1. Descargar el archivo llamado **"Isetasv_vx.x.x-x.iso"** en la pestaña **Release** de Github. (las x en el nombre de la ISO representan partes que pueden cambiar y hacen referencia a la version)
2. Usar la ISO se puede hacer de varias maneras pero voy a dejar 4:

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

## Opción 2: Para usuarios de Linux con Incus

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

---

## Opción 3: Para usuarios de Windows Home con Oracle VirtualBox

Para quienes usan **Windows Home**, una alternativa sencilla es utilizar **Oracle VirtualBox**, un software gratuito y multiplataforma para crear máquinas virtuales.

1. **Descargar e instalar VirtualBox**:
    * Ve a la página oficial de VirtualBox: [https://www.virtualbox.org/wiki/Downloads](https://www.virtualbox.org/wiki/Downloads)  
    * Descarga la versión para **Windows hosts**.
    * Ejecuta el instalador y sigue los pasos del asistente para completar la instalación.

2. **Crear la máquina virtual**:
    * Abre VirtualBox.
    * Haz clic en el botón **"Nueva"**.
    * Asigna un nombre a la máquina virtual y selecciona el tipo de sistema operativo que coincida con la ISO que vas a instalar (por ejemplo, **Linux** si la ISO es de Linux, o **Other/Unknown** si es un sistema distinto).
    * Configura la memoria RAM y el número de procesadores que quieras asignar.
    * Crea un disco duro virtual siguiendo el asistente (el tamaño recomendado suele ser al menos **20 GB**).

3. **Montar la ISO e iniciar la máquina virtual**:
    * Una vez creada la VM, selecciónala en la lista de VirtualBox y haz clic en **"Configuración"**.
    * En el apartado **Almacenamiento**, selecciona la unidad de CD/DVD y elige **"Elegir un archivo de disco"**.
    * Busca el archivo **Isetasv_vx.x.x-x.iso** que descargaste.
    * Guarda los cambios y haz clic en **"Iniciar"** para arrancar la máquina virtual desde la ISO.

Con esto, la instalación del sistema dentro de la máquina virtual comenzará automáticamente.

---

## Opción 4: Usar un pendrive booteable con Ventoy

Si prefieres instalar el sistema directamente en tu PC (sin usar máquinas virtuales), puedes usar **Ventoy** para bootear la ISO desde un pendrive.

1. **Descargar e instalar Ventoy**:
    * Ve al sitio oficial: [https://www.ventoy.net/en/download.html](https://www.ventoy.net/en/download.html).
    * Descarga la versión para Windows.
    * Extrae el archivo comprimido y ejecuta **Ventoy2Disk.exe** como administrador.

2. **Preparar el pendrive**:
    * Conecta un pendrive USB (mínimo 4 GB, se recomienda 8 GB o más).
    * En la ventana de Ventoy, selecciona la unidad USB.
    * Haz clic en **"Install"** para instalar Ventoy en el pendrive (⚠️ esto borrará todos los datos del USB).
    * Una vez finalizado, tu pendrive estará preparado para recibir ISOs.

3. **Copiar la ISO**:
    * Copia el archivo **Isetasv_vx.x.x-x.iso** directamente al pendrive (no necesitas usar herramientas como Rufus o Etcher).
    * Puedes tener varias ISOs en el mismo USB, Ventoy mostrará un menú para elegir cuál arrancar.

4. **Bootear desde el pendrive**:
    * Reinicia tu PC y entra en el menú de arranque (usualmente presionando **F12**, **ESC**, o **DEL**, dependiendo de tu computadora).
    * Selecciona el pendrive Ventoy como dispositivo de arranque.
    * Aparecerá el menú de Ventoy con las ISOs disponibles.
    * Selecciona **Isetasv_vx.x.x-x.iso** y el sistema bootea directamente desde la ISO.

---

# Dentro del Server

1. Una vez que entramos al servidor tendremos una pantalla la cual nos dara para elegir entre 4 opciones, de esas opciones elegir la opcion que dice **ISETASV live/installation mode**.
2. Donde te encontrarias en este paso seria el *"Live System"*. Tendrias que estar viendo una terminal la cual te pida un usuario y contraseña, en la terminal tendria que aparecerte algo asi: ``isetasv:``, primero se ingresa un usuario el cual es **"root"** y despues la contraseña la cual es **"4123"**.
4. Ya dentro del sistema hay que iniciar sesion como el administrador para que el servidor funcione como se debe, para eso se usa el siguiente comando: ``su admin``, ahora hay que poner el siguiente comando en la terminal ``cd /srv/pm2025``, este comando los va a llevar a la carpeta del servidor, ahora deben escribir el ``frankenphp run``, esto les va a iniciar el servidor completamente configurado.

* si al escribir el comando ``frankenphp run``al final de los mensajes les aparece algo como *"127.0.0.1:2019 bind addres already in use"*, tiren el comando ``sudo systemctl stop frankenphp``, y vuelvan a tirar el comando ``frankenphp run``.
