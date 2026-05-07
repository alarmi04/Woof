<script setup>
/**
 * Componente para la página de Adopción.
 * Permite listar y filtrar los perros disponibles en el refugio.
 */
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import Header from '@/Components/Header.vue';
import Footer from '@/Components/Footer.vue';

// Acceso a los datos de la página proporcionados por Inertia
const pagina = usePage();

/**
 * Listado de perros disponibles obtenido de las propiedades de la página.
 */
const perros = computed(() => pagina.props.perros ?? []);

/**
 * Estado actual de los filtros de búsqueda (tamaño, edad, género).
 */
const filtros = computed(() => ({
    tamano: pagina.props.filtros?.tamano ?? 'todos',
    edad: pagina.props.filtros?.edad ?? 'todos',
    genero: pagina.props.filtros?.genero ?? 'todos',
}));

/**
 * Genera el enlace (URL) para aplicar un filtro específico.
 * 
 * @param {string} campo - El nombre del filtro (ej: 'tamano').
 * @param {string} valor - El valor del filtro (ej: 'grande').
 * @returns {string} La URL completa con los parámetros de búsqueda.
 */
const generarEnlaceFiltro = (campo, valor) => {
    const parametros = {
        ...filtros.value,
        [campo]: valor,
    };

    // Uso de Ziggy para generar la ruta si está disponible
    if (typeof route === 'function') {
        return route('adopta', parametros);
    }

    // Fallback manual en caso de que route() no esté definido
    const consulta = new URLSearchParams(parametros).toString();
    return `/adopta?${consulta}`;
};
</script>

<template>
    <Header />

    <section id="adopcion">
        <div class="adopcion-container">
            <h1>Encuentra a tu Compañero Perfecto</h1>
            <p class="adopcion-subtitle">Todos nuestros perritos están esperando un hogar lleno de amor</p>

            <div class="filtros-container">
                <div class="filtro-grupo">
                    <label>Tamaño:</label>
                    <div class="filtro-botones">
                        <Link
                            class="filtro-btn"
                            :class="{ active: filtros.tamano === 'todos' }"
                            :href="generarEnlaceFiltro('tamano', 'todos')"
                        >
                            Todos
                        </Link>
                        <Link
                            class="filtro-btn"
                            :class="{ active: filtros.tamano === 'pequeño' }"
                            :href="generarEnlaceFiltro('tamano', 'pequeño')"
                        >
                            Pequeño
                        </Link>
                        <Link
                            class="filtro-btn"
                            :class="{ active: filtros.tamano === 'mediano' }"
                            :href="generarEnlaceFiltro('tamano', 'mediano')"
                        >
                            Mediano
                        </Link>
                        <Link
                            class="filtro-btn"
                            :class="{ active: filtros.tamano === 'grande' }"
                            :href="generarEnlaceFiltro('tamano', 'grande')"
                        >
                            Grande
                        </Link>
                    </div>
                </div>

                <div class="filtro-grupo">
                    <label>Edad:</label>
                    <div class="filtro-botones">
                        <Link
                            class="filtro-btn"
                            :class="{ active: filtros.edad === 'todos' }"
                            :href="generarEnlaceFiltro('edad', 'todos')"
                        >
                            Todos
                        </Link>
                        <Link
                            class="filtro-btn"
                            :class="{ active: filtros.edad === 'joven' }"
                            :href="generarEnlaceFiltro('edad', 'joven')"
                        >
                            Joven
                        </Link>
                        <Link
                            class="filtro-btn"
                            :class="{ active: filtros.edad === 'adulto' }"
                            :href="generarEnlaceFiltro('edad', 'adulto')"
                        >
                            Adulto
                        </Link>
                        <Link
                            class="filtro-btn"
                            :class="{ active: filtros.edad === 'senior' }"
                            :href="generarEnlaceFiltro('edad', 'senior')"
                        >
                            Senior
                        </Link>
                    </div>
                </div>

                <div class="filtro-grupo">
                    <label>Sexo:</label>
                    <div class="filtro-botones">
                        <Link
                            class="filtro-btn"
                            :class="{ active: filtros.genero === 'todos' }"
                            :href="generarEnlaceFiltro('genero', 'todos')"
                        >
                            Todos
                        </Link>
                        <Link
                            class="filtro-btn"
                            :class="{ active: filtros.genero === 'Macho' }"
                            :href="generarEnlaceFiltro('genero', 'Macho')"
                        >
                            Macho
                        </Link>
                        <Link
                            class="filtro-btn"
                            :class="{ active: filtros.genero === 'Hembra' }"
                            :href="generarEnlaceFiltro('genero', 'Hembra')"
                        >
                            Hembra
                        </Link>
                    </div>
                </div>
            </div>

            <div v-if="perros.length" class="perros-grid">
                <div v-for="perro in perros" :key="perro.id" class="perro-card">
                    <img :src="perro.Imagen || perro.imagen || '/images/default-perro.jpg'" :alt="perro.Nombre || perro.nombre || 'Foto del perro'" class="perro-imagen" />
                    <div class="perro-info">
                        <h2 class="perro-nombre">{{ perro.Nombre || perro.nombre || 'Sin nombre' }}</h2>
                        <div class="perro-detalles">
                            <span class="perro-tag">{{ perro.Tamano || perro.tamano }}</span>
                            <span class="perro-tag">{{ perro.Edad || perro.edad }}</span>
                            <span class="perro-tag">{{ perro.Genero || perro.genero }}</span>
                        </div>
                        <p class="perro-descripcion">{{ perro.Descripcion || perro.descripcion || 'No hay descripción disponible.' }}</p>
                    </div>
                </div>
            </div>

            <div v-else class="no-resultados">
                <p>😔 No encontramos perritos con esos filtros.</p>
            </div>
        </div>
    </section>

    <Footer />
</template>

<style>
    #adopcion,
    #detalle-perro,
    #enviar-perro {
    padding: 4rem 2rem;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    min-height: 100vh;
    }

    .adopcion-container,
    .detalle-container,
    .enviar-container {
    max-width: 1400px;
    margin: 0 auto;
    }

    .adopcion-container h1,
    .enviar-header h1 {
    text-align: center;
    font-size: 3rem;
    color: #d97f11;
    margin-bottom: 1rem;
    font-weight: 700;
    }

    .adopcion-subtitle,
    .enviar-header p {
    text-align: center;
    font-size: 1.2rem;
    color: #666;
    margin-bottom: 3rem;
    line-height: 1.6;
    }

    /* Filtros */
    .filtros-container {
    background: white;
    padding: 2rem;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    margin-bottom: 3rem;
    }

    .filtro-grupo {
    margin-bottom: 1.5rem;
    }

    .filtro-grupo:last-child {
    margin-bottom: 0;
    }

    .filtro-grupo label {
    display: block;
    font-weight: 600;
    color: #333;
    margin-bottom: 0.8rem;
    font-size: 1.1rem;
    }

    .filtro-botones {
    display: flex;
    flex-wrap: wrap;
    gap: 0.8rem;
    }

    .filtro-btn {
    padding: 0.7rem 1.5rem;
    border: 2px solid #e0e0e0;
    background: white;
    color: #666;
    border-radius: 25px;
    cursor: pointer;
    font-size: 1rem;
    font-weight: 500;
    transition: all 0.3s ease;
    }

    .filtro-btn:hover {
    border-color: #f2790f;
    color: #f2790f;
    transform: translateY(-2px);
    }

    .filtro-btn.active {
    background: #f2790f;
    color: white;
    border-color: #f2790f;
    }

    /* Grid de perros */
    .perros-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 2rem;
    }

    .perro-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    }

    .perro-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 40px rgba(242, 121, 15, 0.2);
    }

    .perro-imagen {
    width: 100%;
    height: 300px;
    object-fit: cover;
    }

    .perro-info {
    padding: 1.5rem;
    }

    .perro-nombre {
    font-size: 1.5rem;
    font-weight: 700;
    color: #d97f11;
    margin-bottom: 0.5rem;
    }

    .perro-detalles {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
    flex-wrap: wrap;
    }

    .perro-tag,
    .personalidad-tag {
    background: #f2d888;
    color: #d97f11;
    padding: 0.4rem 0.8rem;
    border-radius: 15px;
    font-size: 0.85rem;
    font-weight: 600;
    }

    .perro-descripcion {
    color: #666;
    line-height: 1.6;
    margin-bottom: 1.5rem;
    font-size: 0.95rem;
    }

    .perro-btn {
    width: 100%;
    padding: 0.9rem;
    background: #f2790f;
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    }

    .perro-btn:hover {
    background: #d97f11;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(242, 121, 15, 0.3);
    }

    .no-resultados {
    text-align: center;
    padding: 3rem;
    background: white;
    border-radius: 20px;
    margin-top: 2rem;
    }

    .no-resultados p {
    font-size: 1.2rem;
    color: #666;
    }

</style>