<div align="center">

  <img src="portada/img/logo.png" alt="Logo DobleM" width="160" />

  #  Proyecto DobleM

  **Asignaturas:** Metodologías de Desarrollo Ágil (MDA) & Dirección y Gestión de Proyectos (DGP)  
  **Universidad de Granada (UGR)**

  [![LaTeX](https://img.shields.io/badge/Documentación-LaTeX-008080?style=for-the-badge&logo=LaTeX&logoColor=white)](https://www.latex-project.org/)
  [![GitHub](https://img.shields.io/badge/Repositorio-GitHub-181717?style=for-the-badge&logo=github&logoColor=white)](https://github.com/)
  [![Jira](https://img.shields.io/badge/Gestión-Jira-0052CC?style=for-the-badge&logo=jira&logoColor=white)](https://www.atlassian.com/software/jira)

</div>

---

## 📌 Visión General del Proyecto

**DobleM** es una solución desarrollada de forma colaborativa para aplicar los principios de la gestión de proyectos de software, desarrollo ágil (Scrum/Kanban) y estandarización de procesos de calidad.

---

## 👥 Equipo de Trabajo y Roles

| Miembro | Asignatura | Rol |
| :--- | :---: | :--- |
| **Minerva Cebrián Marín** | MDA & DGP |  **Gestora de Calidad** |
| **Pedro Javier Pérez Jiménez** | MDA & DGP |  **Gestor de Accesibilidad** |
| **Ángela García Olmos** | MDA & DGP | **Moderador** |
| **Daniel Vega Jiménez** | MDA & DGP | **Presentador** |
| **Cristina García Gázquez** | Solo DGP | **Catalogador** |
| **Jorge García Gámiz** | Solo MDA | **Catalogador** |
| **Kirsten Gazy Manalo** | Solo DGP |  |

> 🔄 *Nota:* El rol de **Coordinador** es rotatorio según la fase del proyecto.

---

## 🎨 Guía de Estilo y Paleta de Colores (LaTeX)

Para garantizar la coherencia tipográfica y visual en todas las memorias oficiales, se ha definido una paleta institucional en el archivo `main.tex`:

| Muestra | Nombre en LaTeX | Código HEX | Uso en Documentación |
| :---: | :--- | :--- | :--- |
| 🟩 | `verdeApagado` | `#A4BEA7` | Destacados y acentos primarios |
| 🟦 | `azulPastelApagado` | `#ABBBCB` | Cabeceras de tablas y secciones |
| 🩵 | `azulclaro` | `#D1DBE4` | Fondos de bloques e indicadores |

---
## 📁 Arquitectura del Repositorio

```mermaid
graph TD
    A[DobleM] --> B[portada.tex]
    A --> PDF[📄 portada.pdf]
    A --> C[📁 cap/]
    A --> D[📁 img/]

    %% Carpeta cap y subcarpetas
    C --> C_DGP[📁 dgp/]
    C --> C_MDA[📁 mda/]

    C_DGP --> C_DGP_CLI[📁 cliente/]
    C_DGP --> C_DGP_EQ[📁 equipo/]

    C_DGP_CLI --> C1[jira.tex]
    C_DGP_CLI --> C2[manualdecoordinacion.tex]
    C_DGP_CLI --> C3[propuestadesolucion.tex]
    C_DGP_CLI --> C4[propuestatecnica.tex]

    C_MDA --> C5[backlog.tex]
    C_MDA --> C6[personas.tex]
    C_MDA --> C7[plandeentregas.tex]
    C_MDA --> C8[roles.tex]
    C_MDA --> C9[visiondelproducto.tex]

    %% Carpeta img y subcarpetas
    D --> D_PER[📁 personas/]
    D_PER --> D1[persona1.png]
    D_PER --> D2[persona2.png]
    D_PER --> D3[persona3.png]
    D_PER --> D4[persona4.png]
    D_PER --> D5[persona5.png]

    D --> D6[dia_a_dia.png]
    D --> D7[Fondo_Portada.png]
    D --> D8[github.png]
    D --> D9[grupo_comunicacion.png]
    D --> D10[logo.png]
    D --> D11[pictograma_agenda.png]
    D --> D12[ugr.png]

    %% Estilos institucionales
    style A fill:#A4BEA7,stroke:#333,stroke-width:2px,color:#000
    style PDF fill:#E57373,stroke:#333,stroke-width:1px,color:#fff
    style C fill:#ABBBCB,stroke:#333,stroke-width:1px,color:#000
    style D fill:#ABBBCB,stroke:#333,stroke-width:1px,color:#000
    style C_DGP fill:#d1dbe4,stroke:#333,stroke-width:1px,color:#000
    style C_MDA fill:#d1dbe4,stroke:#333,stroke-width:1px,color:#000
    style C_DGP_CLI fill:#ffffff,stroke:#333,stroke-width:1px,color:#000
    style D_PER fill:#ffffff,stroke:#333,stroke-width:1px,color:#000