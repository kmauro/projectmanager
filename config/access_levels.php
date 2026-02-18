<?php

use App\Models\AccessLevel;

// Cómo usar el nuevo helper de access levels (rápido) 💡
// Define los permisos por rol en access_levels.php, por ejemplo:
// 'admin' => [1]
// En una policy puedes usar:
// return $this->hasAccessLevel($user, 'admin');
// o pasar un array: return $this->hasAccessLevel($user, [1,2,3]);
// Esto evita repetir colecciones en cada método de policy.
$accessLevels = AccessLevel::pluck('id', 'name');

return ($accessLevels);
    // Map logical roles to `access_levels.id` arrays.
    // Ajusta los ids aquí según tus datos (en tests se sobreescribe dinámicamente).
    
    // Puedes añadir más roles si los necesitas:
    // 'stock_manager' => [2, 3],

