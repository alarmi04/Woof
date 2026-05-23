<script setup>
/**
 * Componente para la página de Adopción.
 * Permite listar y filtrar los perros disponibles en el refugio.
 */
import { computed, ref, onUnmounted } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import Header from '@/Components/Header.vue';
import Footer from '@/Components/Footer.vue';

// Acceso a los datos de la página proporcionados por Inertia
const pagina = usePage();

const user = computed(() => pagina.props.auth?.user ?? null);
const perfilIncompleto = computed(() => {
    if (!user.value) return true;
    return !user.value.Tipo_vivienda || user.value.Nivel_actividad == null || user.value.Experiencia_mascotas == null || user.value.Tiempo_disponible == null;
});

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
 */
const generarEnlaceFiltro = (campo, valor) => {
    const parametros = {
        ...filtros.value,
        [campo]: valor,
    };

    if (typeof route === 'function') {
        return route('adopta', parametros);
    }

    const consulta = new URLSearchParams(parametros).toString();
    return `/adopta?${consulta}`;
};

// ================= ESTADO Y LÓGICA DEL MODAL DE FICHA =================
const perroSeleccionado = ref(null);
const compatibilidadData = ref({ esCompatible: false, razon: '' });

const calcularCompatibilidad = (usuario, perro) => {
    let puntuacion = 0;
    let razonesPositivas = [];
    let razonesNegativas = [];
    
    // 1. Vivienda
    if (usuario.Tipo_vivienda === 'piso') {
        if (perro.Tamano === 'pequeño') {
            puntuacion += 20;
            razonesPositivas.push("Su tamaño pequeño es ideal para tu piso.");
        } else if (perro.Tamano === 'grande') {
            puntuacion -= 10;
            razonesNegativas.push("Al vivir en un piso, un perro tan grande podría estresarse.");
        }
    } else if (usuario.Tipo_vivienda === 'casa_con_jardin') {
        puntuacion += 15;
        razonesPositivas.push("Tienes el espacio perfecto para que corra.");
    }
    
    // 2. Actividad
    if (usuario.Nivel_actividad == perro.Nivel_actividad) {
        puntuacion += 25;
        razonesPositivas.push("Vuestro nivel de energía es idéntico.");
    } else if (Math.abs((usuario.Nivel_actividad || 0) - (perro.Nivel_actividad || 0)) <= 1) {
        puntuacion += 10;
    } else {
        puntuacion -= 10;
        razonesNegativas.push("Vuestros niveles de actividad son muy distintos.");
    }
    
    // 3. Niños
    if (usuario.Numero_hijos > 0) {
        if (perro.Apto_ninos) {
            puntuacion += 30;
            razonesPositivas.push("Es excelente con niños, perfecto para tu familia.");
        } else {
            puntuacion -= 50;
            razonesNegativas.push("Este perro no es recomendable para convivir con niños.");
        }
    }
    
    // 4. Experiencia
    if (perro.Experiencia_requerida && !usuario.Experiencia_mascotas) {
        puntuacion -= 20;
        razonesNegativas.push("Requiere un adoptante con experiencia previa en perros.");
    } else if (usuario.Experiencia_mascotas >= 1) {
        puntuacion += 10;
    }
    
    // 5. Tiempo
    if ((usuario.Tiempo_disponible || 0) >= (perro.Tiempo_requerido || 0)) {
        puntuacion += 15;
        razonesPositivas.push("Dispones del tiempo que este perro necesita.");
    } else {
        puntuacion -= 15;
        razonesNegativas.push("Puede que no tengas suficiente tiempo libre para dedicarle.");
    }
    
    const esCompatible = puntuacion >= 15;
    
    let razonFinal = '';
    if (esCompatible) {
        razonFinal = razonesPositivas.length > 0 ? razonesPositivas.slice(0, 2).join(" ") : "Un compañero equilibrado para ti.";
    } else {
        razonFinal = razonesNegativas.length > 0 ? razonesNegativas.slice(0, 2).join(" ") : "No encaja del todo con tu estilo de vida actual.";
    }
    
    return {
        esCompatible: esCompatible,
        razon: razonFinal
    };
};

const getImageUrl = (perro) => {
    if (!perro) return '/images/default-perro.jpg';
    const img = perro.Imagen || perro.imagen;
    if (!img) return '/images/default-perro.jpg';
    if (img.startsWith('http')) return img;
    return '/storage/' + img;
};

const abrirFicha = (perro) => {
    perroSeleccionado.value = perro;
    if (user.value && !perfilIncompleto.value) {
        compatibilidadData.value = calcularCompatibilidad(user.value, perro);
    }
    document.body.style.overflow = 'hidden'; // Prevenir scroll
};

const cerrarFicha = () => {
    perroSeleccionado.value = null;
    document.body.style.overflow = '';
};

/**
 * Construye la URL completa de la imagen del perro
 */
const obtenerUrlImagen = (perro) => {
    if (!perro) return '/images/default-perro.jpg';
    const img = perro.Imagen || perro.imagen;
    if (!img) return '/images/default-perro.jpg';
    if (img.startsWith('http')) return img;
    return img.includes('storage') ? img : '/storage/' + img;
};

const generarUrlContactoAdopcion = () => {
    if (!perroSeleccionado.value) return '#';
    
    const nombrePerro = perroSeleccionado.value.Nombre || 'el perro';
    const mensaje = `Hola, me gustaría tener más información para adoptar a ${nombrePerro}.`;
    
    return route('contacto', { 
        asunto: 'adopcion', 
        mensaje: mensaje
    });
};

onUnmounted(() => {
    document.body.style.overflow = '';
});
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
                <div v-for="perro in perros" :key="perro.id" class="perro-card" @click="abrirFicha(perro)" style="cursor: pointer;">
                    <img :src="obtenerUrlImagen(perro)" :alt="perro.Nombre || perro.nombre || 'Foto del perro'" class="perro-imagen" />
                    <div class="perro-info">
                        <h2 class="perro-nombre">{{ perro.Nombre || perro.nombre || 'Sin nombre' }}</h2>
                        <div class="perro-detalles">
                            <span class="perro-tag">{{ perro.Tamano || perro.tamano }}</span>
                            <span class="perro-tag">{{ isNaN(perro.Edad) ? perro.Edad : perro.Edad + ' años' }}</span>
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

    <!-- MODAL DE FICHA DEL PERRO -->
    <div v-if="perroSeleccionado" class="modal-overlay" @click.self="cerrarFicha">
        <div class="modal-content">
            <button class="modal-close" @click="cerrarFicha">×</button>
            <div class="modal-body">
                <div class="modal-img-container">
                    <img :src="obtenerUrlImagen(perroSeleccionado)" :alt="perroSeleccionado.Nombre" />
                </div>
                <div class="modal-info">
                    <h2>{{ perroSeleccionado.Nombre }}</h2>
                    <p class="raza">{{ perroSeleccionado.Raza }}</p>
                    
                    <div class="modal-tags">
                        <span>🐶 {{ perroSeleccionado.Tamano }}</span>
                        <span>🎂 {{ isNaN(perroSeleccionado.Edad) ? perroSeleccionado.Edad : perroSeleccionado.Edad + ' años' }}</span>
                        <span>⚧️ {{ perroSeleccionado.Genero }}</span>
                    </div>

                    <p class="modal-desc">{{ perroSeleccionado.Descripcion }}</p>

                    <!-- SECCIÓN DE COMPATIBILIDAD IA -->
                    <div class="compatibilidad-section">
                        <h3>🤖 Análisis de Compatibilidad IA</h3>
                        <div v-if="!user" class="compat-box warning">
                            <p>Inicia sesión para descubrir si tu estilo de vida es compatible con {{ perroSeleccionado.Nombre }}.</p>
                            <Link href="/login" class="btn-small">Iniciar sesión</Link>
                        </div>
                        <div v-else-if="perfilIncompleto" class="compat-box warning">
                            <p>Completa los datos de tu perfil (vivienda, actividad, etc.) para que nuestra IA calcule vuestra afinidad.</p>
                            <Link href="/profile" class="btn-small">Completar perfil</Link>
                        </div>
                        <div v-else class="compat-box" :class="compatibilidadData.esCompatible ? 'success' : 'alert'">
                            <div class="compat-header">
                                <span class="compat-icon">{{ compatibilidadData.esCompatible ? '✨' : '⚠️' }}</span>
                                <h4>{{ compatibilidadData.esCompatible ? '¡Alta Compatibilidad!' : 'Compatibilidad Baja' }}</h4>
                            </div>
                            <p>{{ compatibilidadData.razon }}</p>
                        </div>
                    </div>

                    <!-- BOTÓN DE ADOPCIÓN -->
                    <div class="modal-actions" style="margin-top: 2rem; text-align: center;">
                        <Link :href="generarUrlContactoAdopcion()" class="btn-adoptar">
                            🐾 ¡Quiero Adoptar a {{ perroSeleccionado.Nombre }}!
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

    /* Modal Ficha Perro */
    .modal-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        padding: 1rem;
        animation: fadeIn 0.3s ease;
    }

    .modal-content {
        background: white;
        border-radius: 20px;
        width: 100%;
        max-width: 800px;
        max-height: 90vh;
        overflow-y: auto;
        position: relative;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        animation: slideUp 0.3s ease;
    }

    .modal-close {
        position: absolute;
        top: 15px;
        right: 20px;
        font-size: 2rem;
        background: none;
        border: none;
        color: #333;
        cursor: pointer;
        z-index: 10;
        transition: color 0.2s;
    }

    .modal-close:hover {
        color: #f2790f;
    }

    .modal-body {
        display: flex;
        flex-direction: column;
    }

    @media (min-width: 768px) {
        .modal-body {
            flex-direction: row;
        }
    }

    .modal-img-container {
        flex: 1;
        min-height: 300px;
    }

    .modal-img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 20px 20px 0 0;
    }

    @media (min-width: 768px) {
        .modal-img-container img {
            border-radius: 20px 0 0 20px;
        }
    }

    .modal-info {
        flex: 1.5;
        padding: 2.5rem;
    }

    .modal-info h2 {
        font-size: 2.2rem;
        color: #d97f11;
        margin-bottom: 0.5rem;
        font-weight: 700;
    }

    .modal-info .raza {
        font-size: 1.2rem;
        color: #666;
        margin-bottom: 1.5rem;
        font-weight: 500;
    }

    .modal-tags {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        margin-bottom: 1.5rem;
    }

    .modal-tags span {
        background: #fef3c7;
        color: #b45309;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.95rem;
    }

    .modal-desc {
        line-height: 1.6;
        color: #555;
        margin-bottom: 2rem;
        font-size: 1.05rem;
    }

    .compatibilidad-section {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 15px;
        border: 1px solid #eee;
    }

    .compatibilidad-section h3 {
        font-size: 1.2rem;
        margin-bottom: 1rem;
        color: #333;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .compat-box {
        padding: 1rem;
        border-radius: 12px;
    }

    .compat-box.warning {
        background: #fff8ee;
        border-left: 4px solid #f2790f;
    }

    .compat-box.success {
        background: #f0fdf4;
        border-left: 4px solid #22c55e;
    }

    .compat-box.alert {
        background: #fef2f2;
        border-left: 4px solid #ef4444;
    }

    .compat-header {
        display: flex;
        align-items: center;
        gap: 0.8rem;
        margin-bottom: 0.5rem;
    }

    .compat-header h4 {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 700;
        color: #1f2937;
    }

    .compat-icon {
        font-size: 1.5rem;
    }

    .compat-box p {
        margin-bottom: 0.8rem;
        color: #4b5563;
        font-size: 0.95rem;
        line-height: 1.5;
    }

    .compat-note {
        font-size: 0.85rem !important;
        color: #6b7280 !important;
        font-style: italic;
        margin-bottom: 0 !important;
    }

    .btn-small {
        display: inline-block;
        padding: 0.5rem 1rem;
        background: #f2790f;
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: background 0.2s;
    }

    .btn-small:hover {
        background: #d97f11;
    }

    .btn-adoptar {
        display: inline-block;
        width: 100%;
        padding: 1.2rem;
        background: linear-gradient(135deg, #f2790f, #d97f11);
        color: white;
        text-decoration: none;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1.2rem;
        box-shadow: 0 10px 20px rgba(242, 121, 15, 0.3);
        transition: all 0.3s ease;
    }

    .btn-adoptar:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 25px rgba(242, 121, 15, 0.4);
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>