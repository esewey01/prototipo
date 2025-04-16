<?php
// helpers.php
function getTipoUsuario($id_tipo) {
    $tipos = [
        1 => [
            'nombre' => 'ADMINISTRADOR', 
            'clase' => 'danger', 
            'icono' => 'fa-shield',
            'badge_class' => 'badge-danger'
        ],
        2 => [
            'nombre' => 'VENDEDOR', 
            'clase' => 'warning', 
            'icono' => 'fa-user-tie',
            'badge_class' => 'badge-warning'
        ],
        3 => [
            'nombre' => 'CLIENTE', 
            'clase' => 'info', 
            'icono' => 'fa-user',
            'badge_class' => 'badge-info'
        ]
    ];
    return $tipos[$id_tipo] ?? [
        'nombre' => 'DESCONOCIDO', 
        'clase' => 'secondary', 
        'icono' => 'fa-question',
        'badge_class' => 'badge-secondary'
    ];
}