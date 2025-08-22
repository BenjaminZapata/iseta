import os
import platform
import subprocess
from pathlib import Path
import getpass

def crear_backup_script(mysql_user, mysql_pass, mysql_db, backup_dir):
    sistema = platform.system()
    Path(backup_dir).mkdir(parents=True, exist_ok=True)

    if sistema == "Linux":
        ruta = Path.home() / "mysql_backup.sh"
        contenido = f"""#!/bin/sh
fecha=$(date +%F_%H-%M-%S)
mysqldump -u {mysql_user} -p'{mysql_pass}' {mysql_db} > {backup_dir}/backup-$fecha.sql
"""
        ruta.write_text(contenido)
        os.chmod(ruta, 0o755)
        return str(ruta)

    elif sistema == "Windows":
        ruta = Path.home() / "mysql_backup.bat"
        contenido = f"""@echo off
set fecha=%date:~6,4%-%date:~3,2%-%date:~0,2%_%time:~0,2%-%time:~3,2%-%time:~6,2%
mysqldump -u {mysql_user} -p{mysql_pass} {mysql_db} > "{backup_dir}\\backup-%fecha%.sql"
"""
        ruta.write_text(contenido)
        return str(ruta)

    else:
        raise NotImplementedError("SO no soportado")

def registrar_tarea(script_path):
    sistema = platform.system()

    if sistema == "Linux":
        cron_line = f"0 2 * * * {script_path}\n"  # diario a las 2 AM
        subprocess.run(f'(crontab -l; echo "{cron_line}") | crontab -', shell=True, check=True)

    elif sistema == "Windows":
        comando = [
            "schtasks",
            "/Create",
            "/SC", "DAILY",
            "/TN", "BackupMySQL",
            "/TR", f'"{script_path}"',
            "/ST", "02:00"
        ]
        subprocess.run(comando, check=True)

if __name__ == "__main__":
    print("=== Configuración de Backup MySQL ===")
    mysql_user = input("Usuario MySQL: ")
    mysql_pass = getpass.getpass("Contraseña MySQL: ")
    mysql_db   = input("Nombre de la base de datos: ")
    backup_dir = input("Directorio donde guardar backups (default ~/mysql_backups): ") or str(Path.home() / "mysql_backups")

    script = crear_backup_script(mysql_user, mysql_pass, mysql_db, backup_dir)
    registrar_tarea(script)

    print(f"✅ Backup de '{mysql_db}' programado. Los archivos estarán en: {backup_dir}")
