<?php
require_once __DIR__ . '/../middleware/apply_middleware.php';

// Aplica múltiplos middlewares - primeiro verifica se está autenticado, depois se é anunciante
applyMiddleware(['authMiddleware', 'anuncianteMiddleware']);

// Resto do código da página
?>
