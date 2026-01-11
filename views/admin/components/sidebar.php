<!-- Sidebar -->
<aside class="admin__sidebar">
    <nav class="admin__nav">
        <a href="/admin?seccion=cotizaciones" class="admin__nav-enlace <?php echo ($seccionActiva === 'cotizaciones') ? 'admin__nav-enlace--activo' : ''; ?>">
            <i class="fa-solid fa-file-invoice-dollar"></i>
            Cotizaciones
        </a>
        <a href="/admin?seccion=proyectos&page=1" class="admin__nav-enlace <?php echo ($seccionActiva === 'proyectos') ? 'admin__nav-enlace--activo' : ''; ?>">
            <i class="fa-solid fa-folder-open"></i>
            Proyectos
        </a>
        <a href="/admin?seccion=mensajes" class="admin__nav-enlace <?php echo ($seccionActiva === 'mensajes') ? 'admin__nav-enlace--activo' : ''; ?>">
            <i class="fa-solid fa-envelope"></i>
            Mensajes
        </a>
    </nav>
</aside>
