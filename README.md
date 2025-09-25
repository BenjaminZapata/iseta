# Server PM2025

## Iniciar servidor con ISO

Descargar el archivo llamado `Isetasv_vx.x.x-x.iso` en la pestaña Release de Github o desde este link a mediafire [https://www.mediafire.com/file/401l1e02aop99hi/isetasv_amd64_2025-09-25_1843.iso/file](https://www.mediafire.com/file/401l1e02aop99hi/isetasv_amd64_2025-09-25_1843.iso/file). (las x en el nombre de la ISO representan partes que pueden cambiar y hacen referencia a la versión).
Usar la ISO se puede hacer de varias maneras, aquí están 4 opciones:

---

## Opción 1: Para usuarios de Windows 10/11 Pro con Hyper-V

### Habilitar Hyper-V:

1. Presiona la tecla de Windows, busca **"Activar o desactivar las características de Windows"** y haz clic en el resultado.
2. En la ventana de características, marca la casilla de **Hyper-V**.
3. Haz clic en **Aceptar** y reinicia tu computadora cuando se te solicite.

### Crear la máquina virtual:

1. Busca **"Administrador de Hyper-V"** en el menú de inicio y ábrelo.
2. En el panel de la derecha, haz clic en **Nueva** y luego en **Máquina virtual**.
3. Sigue el asistente, dándole un nombre a la máquina virtual, asignándole la memoria RAM y la cantidad de núcleos de CPU que necesites.
4. Cuando te lo pida, selecciona la opción para **"Instalar un sistema operativo desde un archivo de imagen .iso"** y navega hasta el archivo `Isetasv_vx.x.x-x.iso`.
5. Completa el asistente y la máquina virtual se creará.

### Configurar adaptador de red para conexión a internet:

1. Selecciona la VM recién creada y haz clic en **Configuración**.
2. En **Adaptador de red**, cambia el tipo de conexión a **Adaptador de red en puente (Bridged)**.
3. Esto permitirá que la VM tenga acceso a internet y se comunique con el host.

---

## Opción 2: Para usuarios de Linux con Incus

### Instalar Incus:

En Linux (Ubuntu y derivados), abre una terminal y ejecuta:

```bash
sudo apt update
sudo apt install -y incus incus-ui-canonical
sudo usermod -a -G incus $USER
newgrp incus
sudo incus admin init
```

En Windows (a través de WSL - Subsistema de Windows para Linux), primero asegúrate de que WSL esté instalado. Luego, instala Incus dentro de tu distribución de Linux usando los mismos comandos anteriores.

### Usar la interfaz web (Incus UI):

1. Incus incluye una interfaz web llamada **Incus UI** que se instala junto con `incus-ui-canonical`.
2. Para saber en qué puerto está disponible por defecto, ejecuta:

```bash
incus config show
```

3. Esto mostrará la configuración actual, incluyendo el puerto en el que la UI está corriendo (por lo general [https://localhost:8443](https://localhost:8443)).
4. Abre ese puerto en tu navegador y podrás acceder a la interfaz gráfica.

### Crear una máquina virtual:

1. Copia la ISO a tu entorno Incus (`incus storage volume import`).
2. Crea la máquina virtual usando:

```bash
incus launch images:isetav_vx.x.x-x
```

También puedes usar `incus create` para configuraciones más detalladas. Los comandos exactos pueden variar según tu versión de Incus.

---

## Opción 3: Para usuarios de Windows Home con Oracle VirtualBox

### Descargar e instalar VirtualBox:

1. Ve a la página oficial de VirtualBox: [https://www.virtualbox.org/wiki/Downloads](https://www.virtualbox.org/wiki/Downloads)
2. Descarga la versión para Windows hosts.
3. Ejecuta el instalador y sigue los pasos del asistente para completar la instalación.

### Crear la máquina virtual:

1. Abre VirtualBox.
2. Haz clic en **Nueva**.
3. Asigna un nombre a la máquina virtual y selecciona el tipo de sistema operativo que coincida con la ISO.
4. Configura la memoria RAM y el número de procesadores.
5. Crea un disco duro virtual (recomendado al menos 20 GB).

### Configurar adaptador de red:

1. Selecciona la VM y haz clic en **Configuración > Red**.
2. Cambia **Conectado a** a **Adaptador puente (Bridged Adapter)**.
3. Esto permitirá que la VM tenga acceso a internet y se comunique con el host.

### Montar la ISO e iniciar la máquina virtual:

1. En la VM, ve a **Configuración > Almacenamiento**, selecciona la unidad CD/DVD y haz clic en **Elegir un archivo de disco**.
2. Busca el archivo `Isetasv_vx.x.x-x.iso`.
3. Guarda los cambios y haz clic en **Iniciar** para arrancar la VM desde la ISO.

---

## Opción 4: Usar un pendrive booteable con Ventoy

### Descargar e instalar Ventoy:

1. Ve al sitio oficial: [https://www.ventoy.net/en/download.html](https://www.ventoy.net/en/download.html)
2. Descarga la versión para Windows.
3. Extrae el archivo comprimido y ejecuta `Ventoy2Disk.exe` como administrador.

### Preparar el pendrive:

1. Conecta un pendrive USB (mínimo 4 GB, recomendado 8 GB o más).
2. En Ventoy, selecciona la unidad USB.
3. Haz clic en **Install** (⚠️ esto borrará todos los datos del USB).

### Copiar la ISO:

1. Copia el archivo `Isetasv_vx.x.x-x.iso` directamente al pendrive.
2. Puedes tener varias ISOs en el mismo USB; Ventoy mostrará un menú para elegir cuál arrancar.

### Bootear desde el pendrive:

1. Reinicia tu PC y entra en el menú de arranque (usualmente F12, ESC o DEL).
2. Selecciona el pendrive Ventoy como dispositivo de arranque.
3. Aparecerá el menú de Ventoy con las ISOs disponibles.
4. Selecciona `Isetasv_vx.x.x-x.iso` y el sistema arrancará directamente desde la ISO.

---

## Dentro del Server

1. Una vez dentro del servidor, selecciona **ISETASV live/installation mode**.
2. Estarás en el **Live System**, con una terminal que pedirá usuario y contraseña:

```
isetasv:
Usuario: root
Contraseña: 4123
```

3. Inicia sesión como administrador:

```bash
su admin
cd /srv/pm2025
frankenphp run
```

4. Si al ejecutar `frankenphp run` aparece:

```
127.0.0.1:2019 bind address already in use
```

Ejecuta:

```bash
sudo systemctl stop frankenphp
frankenphp run
```
