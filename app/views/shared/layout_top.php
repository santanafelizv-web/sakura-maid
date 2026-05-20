<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?=e($pageTitle??'Dashboard')?> — Sakura Maid Services</title>
<link rel="stylesheet" href="/css/sakura.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js" defer></script>
</head>
<body>
<?php $u=authUser(); ?>
<nav class="navbar">
  <div class="nav-inner">
    <a href="/dashboard" class="nav-brand">🌸 <span>Sakura</span> Maid Services</a>
    <div class="nav-links">
      <?php if($u['rol']==='cliente'): ?>
        <a href="/maids"    class="<?=($ap??'')==='maids'?'active':''?>">🔍 Buscar Maids</a>
        <a href="/servicios" class="<?=($ap??'')==='servicios'?'active':''?>">📋 Servicios</a>
        <a href="/facturas"  class="<?=($ap??'')==='facturas'?'active':''?>">🧾 Facturas</a>
      <?php elseif($u['rol']==='maid'): ?>
        <a href="/maids/perfil" class="<?=($ap??'')==='mperfil'?'active':''?>">✏️ Mi Perfil</a>
        <a href="/servicios"    class="<?=($ap??'')==='servicios'?'active':''?>">📋 Trabajos</a>
        <a href="/facturas"     class="<?=($ap??'')==='facturas'?'active':''?>">🧾 Facturas</a>
      <?php else: ?>
        <a href="/maids">👩 Maids</a>
        <a href="/servicios">📋 Servicios</a>
        <a href="/facturas">🧾 Facturas</a>
        <a href="/reportes">📊 Reportes</a>
      <?php endif; ?>
      <a href="/perfil" class="<?=($ap??'')==='perfil'?'active':''?>">👤 <?=e($u['nombre'])?></a>
      <a href="/logout" class="btn-exit">Salir</a>
    </div>
  </div>
</nav>
<div class="page-wrap">
<!-- SIDEBAR -->
<aside class="sidebar">
  <div class="sidebar-user">
    <div class="s-avatar"><?=strtoupper(mb_substr($u['nombre'],0,1))?></div>
    <div><div class="s-name"><?=e($u['nombre'].' '.$u['apellido'])?></div><div class="s-rol"><?=e($u['rol'])?></div></div>
  </div>
  <a href="/dashboard" class="<?=($ap??'')==='dash'?'active':''?>"><span class="s-icon">🏠</span>Dashboard</a>
  <?php if($u['rol']==='cliente'): ?>
    <a href="/maids"     class="<?=($ap??'')==='maids'?'active':''?>"><span class="s-icon">🔍</span>Buscar Maids</a>
    <a href="/servicios" class="<?=($ap??'')==='servicios'?'active':''?>"><span class="s-icon">📋</span>Mis Servicios</a>
    <a href="/facturas"  class="<?=($ap??'')==='facturas'?'active':''?>"><span class="s-icon">🧾</span>Facturas</a>
  <?php elseif($u['rol']==='maid'): ?>
    <a href="/maids/perfil" class="<?=($ap??'')==='mperfil'?'active':''?>"><span class="s-icon">✏️</span>Mi Perfil Maid</a>
    <a href="/servicios"    class="<?=($ap??'')==='servicios'?'active':''?>"><span class="s-icon">📋</span>Mis Trabajos</a>
    <a href="/facturas"     class="<?=($ap??'')==='facturas'?'active':''?>"><span class="s-icon">🧾</span>Facturas</a>
  <?php else: ?>
    <a href="/maids"><span class="s-icon">👩</span>Maids</a>
    <a href="/servicios"><span class="s-icon">📋</span>Servicios</a>
    <a href="/facturas"><span class="s-icon">🧾</span>Facturas</a>
    <a href="/reportes" class="<?=($ap??'')==='reportes'?'active':''?>"><span class="s-icon">📊</span>Reportes</a>
  <?php endif; ?>
  <div class="sidebar-sep"></div>
  <a href="/perfil" class="<?=($ap??'')==='perfil'?'active':''?>"><span class="s-icon">👤</span>Mi Perfil</a>
  <a href="/logout"><span class="s-icon">🚪</span>Salir</a>
</aside>
<main class="main">
