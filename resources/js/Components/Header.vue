<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth.user);
</script>

<template>
<header id="header">
    <Link href="/home">
        <img
            src="/images/woof-logo-removebg-preview.png"
            alt="Woof Logo"
            class="logo"
        />
    </Link>
    <nav id="menuHeader">
        <Link href="/home" :class="{ 'active': $page.url === '/home' }">Inicio</Link>
        <Link href="/adopta" :class="{ 'active': $page.url === '/adopta' }">Adopta</Link>
        <Link href="/perros-recomendados" :class="{ 'active': $page.url === '/perros-recomendados' }">Perros recomendados</Link>
        <Link href="/donaciones" :class="{ 'active': $page.url === '/donaciones' }">Donar</Link>
        <Link href="/contacto" :class="{ 'active': $page.url === '/contacto' }">Contacto</Link>

        <!-- Opción de Mantenimiento (solo para admins) -->
        <Link 
            v-if="user && user.is_admin" 
            href="/admin/animales" 
            :class="{ 'active': $page.url.startsWith('/admin') }"
            class="admin-link"
        >
            Mantenimiento
        </Link>

        <!-- Sección de Usuario -->
        <div v-if="user" class="user-nav">
            <Link href="/profile" class="profile-link" :class="{ 'active': $page.url === '/profile' }">
                <img
                    :src="user.Foto ? '/storage/' + user.Foto : '/images/default-avatar.png'"
                    alt="Perfil"
                    class="avatar-nav"
                />
                <span>Perfil</span>
            </Link>
            <Link href="/logout" method="post" as="button" class="logout-btn">
                Salir
            </Link>
        </div>
        <div v-else class="auth-nav">
            <Link href="/login" class="login-btn">Entrar</Link>
        </div>
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
  gap: 1.5rem;
  align-items: center;
}

#menuHeader a, .logout-btn {
  text-decoration: none;
  color: #d97f11;
  font-size: 1.1rem;
  font-weight: 500;
  padding: 0.6rem 1.2rem;
  border-radius: 9999px;
  transition: all 0.3s ease;
  border: none;
  background: none;
  cursor: pointer;
}

#menuHeader a:hover, .logout-btn:hover, .login-btn:hover {
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

/* Estilos de usuario en nav */
.user-nav {
    display: flex;
    align-items: center;
    gap: 0.8rem;
    border-left: 2px solid rgba(217, 127, 17, 0.2);
    padding-left: 1.5rem;
    margin-left: 0.5rem;
}

.profile-link {
    display: flex;
    align-items: center;
    gap: 0.7rem;
    background-color: white !important;
    border: 2px solid #f2790f;
    padding: 0.4rem 1rem !important;
}

.profile-link:hover {
    background-color: #fff4ea !important;
    color: #d97f11 !important;
}

.avatar-nav {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
}

.logout-btn {
    font-size: 0.95rem !important;
    padding: 0.5rem 1rem !important;
    background-color: #fce8d5 !important;
    color: #d97f11 !important;
}

.logout-btn:hover {
    background-color: #f8d7b8 !important;
    color: #b86000 !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(242, 121, 15, 0.3);
}

.login-btn {
    background-color: #f2790f !important;
    color: white !important;
}

.admin-link {
    background-color: #d97f11 !important;
    color: white !important;
    font-weight: 600;
}

.admin-link:hover {
    background-color: #b86000 !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(217, 127, 17, 0.3);
}

.admin-link.active {
    background-color: #f2d888 !important;
    color: #d97f11 !important;
}

</style>