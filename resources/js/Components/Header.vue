<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
<header id="header">
    <img
        src="/images/woof-logo-removebg-preview.png"
        alt="Woof Logo"
        class="logo"
    />
    <nav id="menuHeader">
        <Link href="/home">Inicio</Link>
        <Link href="/adopta">Adopta</Link>
        <Link href="/perros-recomendados">Perros recomendados</Link>
        <Link href="/contacto">Contacto</Link>
    </nav>
</header>
</template>

<style>

#header {
  background-color: #d5e5f2;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.5rem 2rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
  height: 20vh;
}

.logo {
  width: 120px;
  height: auto;
  transition: transform 0.3s;
}

.logo:hover {
  transform: scale(1.05);
}

#menuHeader {
  display: flex;
  gap: 2rem;
  align-items: center;
}

#menuHeader a {
  text-decoration: none;
  color: #d97f11;
  font-size: 1.1rem;
  font-weight: 500;
  padding: 0.6rem 1.2rem;
  border-radius: 9999px;
  transition: all 0.3s ease;
}

#menuHeader a:hover {
  background-color: #f2790f;
  color: white;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(242, 121, 15, 0.3);
}

#menuHeader a.active {
  background-color: #f2d888;
  color: #d97f11;
  font-weight: 600;
}

</style>