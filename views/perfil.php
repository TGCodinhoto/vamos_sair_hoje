<?php
require_once __DIR__ . '/../middleware/apply_middleware.php';

// Aplica os middlewares necessários
applyMiddleware(['authMiddleware']);

// Resto do código da página
?>
