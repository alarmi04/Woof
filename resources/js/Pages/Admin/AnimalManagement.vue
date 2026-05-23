<script setup>
import { computed, ref } from 'vue';
import { Link, usePage, useForm, router } from '@inertiajs/vue3';
import Header from '@/Components/Header.vue';

const page = usePage();
const animales = computed(() => page.props.animales ?? []);
const tabActivo = ref('disponibles');

const animalesFiltrados = computed(() => {
    if (tabActivo.value === 'adoptados') {
        return animales.value.filter(a => a.Estado === 'adoptado');
    }
    return animales.value.filter(a => a.Estado !== 'adoptado');
});

const confirmarEliminar = (id) => {
    if (confirm('¿Marcar este perro como adoptado?')) {
        const form = useForm({
            Estado: 'adoptado'
        });
        
        form.put(route('admin.animales.update', id), {
            onSuccess: () => {
                // Recargar la página después de 500ms para que se procese la petición
                setTimeout(() => {
                    router.visit(route('admin.animales.index'));
                }, 500);
            },
            onError: (errors) => {
                alert('Error: ' + Object.values(errors).join(', '));
            }
        });
    }
};
</script>

<template>
    <Header />
    <div class="admin-container">
        <div class="admin-header">
            <h1>Mantenimiento de Perros</h1>
            <p>Gestiona todas las fichas de los perros disponibles para adopción</p>
        </div>

        <div class="admin-actions">
            <Link href="/admin/animales/create" class="btn-nuevo">
                Agregar Nuevo Perro
            </Link>
        </div>

        <!-- TABS -->
        <div class="tabs-admin">
            <button 
                class="tab-admin-btn" 
                :class="{ active: tabActivo === 'disponibles' }"
                @click="tabActivo = 'disponibles'"
            >
                Disponibles
            </button>
            <button 
                class="tab-admin-btn" 
                :class="{ active: tabActivo === 'adoptados' }"
                @click="tabActivo = 'adoptados'"
            >
                Adoptados
            </button>
        </div>

        <div class="tabla-container">
            <div v-if="animalesFiltrados.length" class="tabla-responsiva">
                <table class="tabla-admin">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Raza</th>
                            <th>Tamaño</th>
                            <th>Edad</th>
                            <th>Género</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="animal in animalesFiltrados" :key="animal.idAnimal" :class="{ 'fila-adoptada': animal.Estado === 'adoptado' }">
                            <td data-label="Nombre">
                                <strong>{{ animal.Nombre }}</strong>
                            </td>
                            <td data-label="Raza">{{ animal.Raza }}</td>
                            <td data-label="Tamaño">{{ animal.Tamano }}</td>
                            <td data-label="Edad">{{ animal.Edad }}</td>
                            <td data-label="Género">{{ animal.Genero }}</td>
                            <td data-label="Estado">
                                <span :class="['estado-badge', `estado-${animal.Estado}`]">
                                    {{ animal.Estado }}
                                </span>
                            </td>
                            <td data-label="Acciones" class="acciones">
                                <Link 
                                    v-if="animal.Estado !== 'adoptado'"
                                    :href="`/admin/animales/${animal.idAnimal}/edit`" 
                                    class="btn-small btn-editar"
                                >
                                    Editar
                                </Link>
                                <div v-else class="btn-small btn-bloqueado">Adoptado</div>
                                <button 
                                    v-if="animal.Estado !== 'adoptado'"
                                    @click="confirmarEliminar(animal.idAnimal)" 
                                    class="btn-small btn-eliminar"
                                >
                                    Adoptado
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div v-else class="sin-datos">
                <p v-if="tabActivo === 'disponibles'">No hay perros disponibles. Agrega uno nuevo</p>
                <p v-else>No hay perros adoptados aún</p>
                <Link v-if="tabActivo === 'disponibles'" href="/admin/animales/create" class="btn-nuevo">
                    Crear Nuevo Perro
                </Link>
            </div>
        </div>
    </div>
</template>

<style scoped>
.admin-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 3rem 2rem;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    min-height: 100vh;
}

.admin-header {
    text-align: center;
    margin-bottom: 3rem;
}

.admin-header h1 {
    font-size: 2.5rem;
    color: #d97f11;
    margin-bottom: 0.5rem;
    font-weight: 700;
}

.admin-header p {
    font-size: 1.1rem;
    color: #666;
}

.admin-actions {
    margin-bottom: 2rem;
    display: flex;
    gap: 1rem;
}

.btn-nuevo {
    display: inline-block;
    padding: 0.8rem 1.5rem;
    background: linear-gradient(to right, #f97316, #ea580c);
    color: white;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-nuevo:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(234, 88, 12, 0.4);
}

/* Tabs */
.tabs-admin {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
    border-bottom: 2px solid #e0e0e0;
}

.tab-admin-btn {
    padding: 1rem 2rem;
    background: none;
    border: none;
    color: #666;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    border-bottom: 3px solid transparent;
    margin-bottom: -2px;
}

.tab-admin-btn:hover {
    color: #f97316;
}

.tab-admin-btn.active {
    color: #d97f11;
    border-bottom-color: #d97f11;
}

.tabla-container {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.tabla-responsiva {
    overflow-x: auto;
}

.tabla-admin {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.95rem;
}

.tabla-admin thead {
    background: linear-gradient(to right, #f97316, #ea580c);
    color: white;
}

.tabla-admin th {
    padding: 1.2rem;
    text-align: left;
    font-weight: 600;
}

.tabla-admin td {
    padding: 1rem 1.2rem;
    border-bottom: 1px solid #e0e0e0;
}

.tabla-admin tbody tr:hover {
    background-color: #f5f5f5;
}

.estado-badge {
    display: inline-block;
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: lowercase;
    background-color: #d1fae5;
    color: #065f46;
}

.estado-disponible {
    background-color: #d1fae5 !important;
    color: #065f46 !important;
}

.estado-adoptado {
    background-color: #fecaca !important;
    color: #7f1d1d !important;
}

.estado-pendiente {
    background-color: #fef3c7 !important;
    color: #92400e !important;
}

.acciones {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.btn-small {
    padding: 0.5rem 0.8rem;
    font-size: 0.85rem;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
    display: inline-block;
    font-weight: 600;
}

.btn-editar {
    background-color: #f97316;
    color: white;
}

.btn-editar:hover {
    background-color: #ea580c;
}

.btn-eliminar {
    background-color: #ef4444;
    color: white;
}

.btn-eliminar:hover {
    background-color: #dc2626;
}

.btn-bloqueado {
    background-color: #d1d5db;
    color: #6b7280;
    cursor: not-allowed;
    opacity: 0.7;
}

.fila-adoptada {
    opacity: 0.6;
    background-color: #f3f4f6;
}

.fila-adoptada td {
    color: #6b7280;
}

.sin-datos {
    padding: 3rem 2rem;
    text-align: center;
}

.sin-datos p {
    font-size: 1.1rem;
    color: #666;
    margin-bottom: 1.5rem;
}

@media (max-width: 768px) {
    .admin-container {
        padding: 1.5rem 1rem;
    }

    .admin-header h1 {
        font-size: 1.8rem;
    }

    .tabla-admin {
        font-size: 0.85rem;
    }

    .tabla-admin th,
    .tabla-admin td {
        padding: 0.8rem;
    }

    .acciones {
        flex-direction: column;
    }

    .btn-small {
        width: 100%;
    }
}
</style>
