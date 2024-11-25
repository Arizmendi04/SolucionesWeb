# 🌐 Sitio Web - Soluciones para Plagas

Bienvenido al proyecto del **Sitio Web** desarrollado para la empresa **Soluciones para Plagas**. Este proyecto incluye un backend con base de datos en **MySQL** y un frontend integrado. 

---

## 🛠️ Requisitos previos

Antes de empezar, asegúrate de tener instalados los siguientes componentes en tu máquina:

1. **Visual Studio** (versión recomendada: 2022 o superior)
2. **MySQL** (instancia configurada en el puerto predeterminado 3306)
3. **Git** (para clonar el repositorio)

---

## ⚙️ Instalación y configuración

Sigue estos pasos para configurar y ejecutar el proyecto en tu entorno local:

### 📁 1. Clonar el repositorio

1. Abre una terminal.
  
2. Ejecuta el siguiente comando para clonar el proyecto en tu máquina local:
   git clone https://github.com/JesusA2004/SolucionesWeb.git

### 📁 2. Configurar la base de datos

El proyecto incluye dos scripts necesarios para configurar la base de datos. Estos se encuentran en la carpeta database dentro del repositorio clonado:

* EsquemaSoluPlagas.sql: Contiene el esquema de la base de datos (tablas, relaciones, etc.).
* RegistrosBD.sql: incluye registros iniciales para poblar la base de datos.

Opciones para ejecutar los scripts:

📌 Opción 1: Usando un plugin de MySQL en Visual Studio
Abre Visual Studio.
Configura la conexión con tu instancia de MySQL.
Ejecuta primero el script EsquemaSoluPlagas.sql y luego RegistrosBD.sql.

📌 Opción 2: Usando la consola de MySQL desde CMD
Abre una terminal y accede al cliente MySQL:

mysql -u root -p
Ingresa tu contraseña de MySQL cuando se te solicite. (Contraseña: cadena vacia)


