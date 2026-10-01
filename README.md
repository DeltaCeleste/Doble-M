# DobleM — MDA & DGP

Repositorio oficial para la documentación del proyecto **DobleM**, desarrollado para las asignaturas de **Metodologías de Desarrollo Ágil (MDA)** y **Dirección y Gestión de Proyectos (DGP)**.

---

## 🎨 Guía de Estilo y Paleta de Colores (LaTeX)

Para mantener la coherencia visual en todos los documentos y plantillas generadas (`cap/personas.tex`, `cap/roles.tex`, etc.), utilizamos una paleta de colores personalizada definida en el archivo principal `main.tex`.

### Paleta Oficial

| Muestra | Nombre en LaTeX | Código HEX | Uso Principal |
| :---: | :--- | :--- | :--- |
| 🟩 | `verdeApagado` | `#A4BEA7` | Destacados y acentos primarios |
| 🟦 | `azulPastelApagado` | `#ABBBCB` | Cabeceras de tablas y secciones principales |
| 🩵 | `azulclaro` | `#D1DBE4` | Fondos de columnas laterales e indicadores |

---

## 📁 Estructura del Proyecto

```mermaid
graph TD
    A[🚀 DobleM] --> B[main.tex]
    A --> C[📁 cap/]
    A --> D[📁 img/]

    C --> C1[portada.tex]
    C --> C2[personas.tex]
    C --> C3[roles.tex]

    D --> D1[logo.png]
    D --> D2[Fondo_Portada.png]
    D --> D3[ugr.png]

    %% Personalización con los colores del proyecto
    style A fill:#A4BEA7,stroke:#333,stroke-width:2px,color:#000
    style C fill:#ABBBCB,stroke:#333,stroke-width:1px,color:#000
    style D fill:#ABBBCB,stroke:#333,stroke-width:1px,color:#000